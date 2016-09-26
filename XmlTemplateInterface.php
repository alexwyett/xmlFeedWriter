<?php

interface XmlTemplateInterface
{
    /**
     * To Array function - used in the xml template render function to pass
     * variables into the twig template.
     * 
     * @return array
     */
    public function toArray();
    
    /**
     * Returns the filename of the file requested
     * 
     * @return string
     */
    public function getFilename();
}
