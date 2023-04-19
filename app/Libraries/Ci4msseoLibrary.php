<?php

namespace ci4seo\Libraries;

use Melbahja\Seo\MetaTags;
use Melbahja\Seo\Schema;
use Melbahja\Seo\Schema\Thing;

class Ci4msseoLibrary
{
    /**
     * @param $title
     * @param $description
     * @param string $url
     * @param array $metatags
     * @param string $coverImage
     * @return MetaTags
     */
    public function metaTags($title, $description, string $url, array $metatagsArray = [], string $coverImage = '')
    {
        $metatags = new MetaTags();
        $metatags->title($title);
        $metatags->description($description);
        if (!empty($coverImage)) $metatags->image($coverImage);
        if (is_array($metatagsArray['keywords']) && !empty($metatagsArray['keywords'])) {
            $keywords = '';
            foreach ($metatagsArray['keywords'] as $tag) {
                $keywords .= $tag . ', ';
            }
            $metatags->meta('keywords', substr($keywords, 0, -2));
        }
        if (!empty($metatagsArray['author'])) $metatags->meta('author', $metatagsArray['author']);
        $metatags->canonical(\App\Libraries\site_url($url));
        return $metatags;
    }


    /**
     * @param string $type
     * @param array $data
     * [
     *      'url'=>'https://codeigniterformanagementsystems.com',
     *      'logo'=>'http://codeigniterformanagementsystems.com/logo.png',
     *      'name'=>'Site Name',
     *      'sameAs'=>['https://twitter.com/bertugfahriozer','https://instagram.com/bertugfahriozerofficial',...],
     *      'children'=>[
     *          [
     *              'ContactPoint'=>[ //$type attribute
     *                  'telephone'=>'+90xxxxxx',
     *                  'contactType'=>'customer service',
     *                  ...
     *              ],
     *      ],
     *      ...
     * ]
     * @return Schema
     *
     *
     * new Thing('Organization', [
     * 'url'          => site_url(),
     * 'logo'         => $this->defData['settings']->logo,
     * 'name'=>$this->defData['settings']->siteName,
     * 'contactPoint' => new Thing('ContactPoint', [
     * 'telephone' => $this->defData['settings']->companyPhone,
     * 'contactType' => 'customer support'
     * ]),
     * 'sameAs'=>$socialNetwork
     * ])
     */
    public function ldPlusJson(string $type, array $data)
    {
        if (!empty($data['children'])) {
            $children = $data['children'];
            unset($data['children']);
            foreach ($children as $key => $child) {
                $data[lcfirst($key)] = new Thing($key, $child);
            }
        }
        return new Schema(new Thing($type, $data));
    }
}