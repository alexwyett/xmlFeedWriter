<?php

define('APIKEY', '');
define('APISECRET', '');
define('CACHETIME', '-5 minutes');

// Brands using the feed system
$brands = array(
    'CD' => array(
        'weburl' => 'https://www.coastandcountry.co.uk/cottage-details/',
        'name' => 'Coast and Country Devon'
    )
);

// Feeds array will define the templates
$feeds = array(
    'affiliatewindow' => array(
        'templates' => array(
            'collection' => 'aw_feed.html.twig',
            'property' => 'aw_property.html.twig'
        ),
        'descriptiontype' => 'TABSLONG',
        'brands' => array(
            'CD'
        )
    ),
    'tabsxml' => array(
        'templates' => array(
            'collection' => 'tabsxml_feed.html.twig',
            'property' => 'tabsxml_property.html.twig'
        ),
        'descriptiontype' => 'TABSLONG',
        'brands' => array(
            'CD'
        ),
        'attributes' => array(
            'bathrooms' => array(
                'name' => 'No.of bathrooms'
            ),
            'closetocoast' => array(
                'name' => 'Coastal Cottages',
                'func' => 'boolToStr'
            )
        )
    ),
    'eurorelais' => array(
        'templates' => array(
            'collection' => 'eurorelais_feed.html.twig',
            'property' => 'eurorelais_property.html.twig'
        ),
        'descriptiontype' => 'TABSLONG',
        'brands' => array(
            'CD'
        )
    )
);


$feedname = filter_input(INPUT_GET, 'feed');
if ($feedname && array_key_exists($feedname, $feeds)) {
    $feed = $feeds[$feedname];
} else {
    throw new Exception('Feed not defined');
}