# xmlFeedWriter

Creates an xml feed from the tabs api which external affiliates can use.

## Prerequisites
* You will need access to the [tabs-api-client](https://github.com/CarltonSoftware/tabs-api-client).
* The directory which this script runs will need to be writable by the web server.

## Setup
* Create a new config.php file based on the contents of config_sample.php.
* Add you api key and secret into the config.php.
* Download composer: ```curl -sS https://getcomposer.org/installer | php```
* Install the composer dependencies: ```php composer.phar update```
* Run the php script index.php with the following query parameters:
    * feed: this can be either affiliatewindow, tabsxml or eurorelais.
    * brandcode: This should be your two letter brandcode.
    * For example: http://localhost/index.php?feed=affiliatewindow&brandcode=ZZ
        * Note: You may need to run this a couple of times depending on your server timeout and memory settings.
* Add in a webhook to the property.php file.  This [guide](https://github.com/CarltonSoftware/api-documentation/wiki/Web-Hooks) will demonstrate how to do this.
