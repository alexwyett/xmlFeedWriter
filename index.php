<?php

// Include dependancies and connect to the api
require_once 'connect.php';

$xmlCollection = new XmlCollection();
$xmlCollection->setTemplateName($feed['templates']['collection'])->setUp();

$propertyCollection = new tabs\api\property\PropertyCollection();
$propertyCollection->setFields(
        array(
            'id',
            'propertyRef',
            'brandCode',
            'url',
            'accountingBrand',
            'slug',
            'name',
            'address',
            'coordinates',
            'area',
            'location',
            'brands',
            'accommodates',
            'accommodationDescription',
            'bedrooms',
            'changeOverDay',
            'rating',
            'pets',
            'promote',
            'images',
            'specialOffers',
            'attributes',
            'calendar'
        )
    )->setAdditionalParam('orderBy', 'propref_asc');
$propertyCollection->findAll();

if ($propertyCollection->getTotal() > 0) {
    
    $total = $propertyCollection->getTotal();
    $xmlCollection->addTemplateVar('total', $total);
    $xmlCollection->addTemplateVar('apiInfo', $apiInfo);
    $xmlCollection->addTemplateVar('brand', $brand);
    $xmlCollection->addTemplateVar('feed', $feed);
    
    foreach ($propertyCollection->getProperties() as $property) {
        $propXml = new PropertyXml();
        $propXml->getTwig()->addFilter('boolToStr',
            new Twig_Filter_Function('boolToStr')
        );

        $propXml->getTwig()->addFilter('lessThanOne',
            new Twig_Filter_Function('lessThanOne')
        );
        
        $propXml->setTemplateCache('cache/' . $property->getId())
            ->setUp()
            ->setFilename($feedname)
            ->setTemplateName($feed['templates']['property'])
            ->addTemplateVar('brand', $brand)
            ->addTemplateVar('feed', $feed)
            ->setProperty($property)
            ->generateFromCache(
                strtotime(CACHETIME, time())
            );
        $xmlCollection->addToCollection($propXml);
    }
    
    $end = memory_get_usage(true) - $start;
    $xmlCollection->addTemplateVar('memory', $end);
    
    header("HTTP/1.1 200 OK");
    header("Cache-Control: no-cache, must-revalidate");
    header("content-type: text/xml");
    header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
    echo (string) $xmlCollection->render();
    die();
    
}

do404();