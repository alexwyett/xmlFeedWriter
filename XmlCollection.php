<?php

/**
 * Description of XmlCollection
 *
 * @author alex
 */
class XmlCollection extends XmlTemplate
{
    /**
     * Collection of rendered template objects
     * 
     * @var array
     */
    protected $collection = array();
    
    /**
     * Add an item to the collection
     * 
     * @param XmlTemplate $object Object to add to collection
     * 
     * @return \XmlCollection
     */
    public function addToCollection(&$object)
    {
        // Add any template vars there are to the object
        foreach ($this->getTemplateVars() as $key => $val) {
            $object->addTemplateVar($key, $val);
        }
        
        // Add to collection
        $this->collection[] = $object;
        
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function getFilename()
    {
        return 'feed.xml';
    }
    
    /**
     * @inheritDoc
     */
    public function toArray()
    {
        return array(
            'collection' => $this->collection
        );
    }
}