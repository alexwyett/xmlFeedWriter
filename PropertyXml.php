<?php

/**
 * Description of PropertyXml
 *
 * @author alex
 */
class PropertyXml extends XmlTemplate
{
    /**
     * Filename
     * 
     * @var string
     */
    protected $filename = '';
    
    /**
     * Property object
     * 
     * @var \tabs\api\property\Property
     */
    protected $property;
    
    /**
     * Set the property object
     * 
     * @param \tabs\api\property\Property $property Api property object
     * 
     * @return \PropertyXml
     */
    public function setProperty($property)
    {
        $this->property = $property;
        
        return $this;
    }
    
    /**
     * Set the filename
     * 
     * @param string $filename Filename
     * 
     * @return \PropertyXml
     */
    public function setFilename($filename)
    {
        $this->filename = $filename;
        
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function getFilename()
    {
        return $this->filename . '.xml';
    }
    
    /**
     * @inheritDoc
     */
    public function toArray()
    {
        return array(
            'property' => $this->property,
            'availableperiods' => $this->getAvailablePeriods()
        );
    }
    
    /**
     * Get an array of available periods
     *
     * @return array
     */
    public function getAvailablePeriods()
    {
        $availablePeriods = array();
        $lastAvailableDay = false;
        foreach ($this->property->getAvailabilityFull() as $avp) {
            if ($avp->available && !$lastAvailableDay) {
                //$avp->from = strtotime('+1 day', $avp->date);
                $avp->from = $avp->date;
                $avp->till = null;
                $availablePeriods[$avp->date] = $avp;
                $lastAvailableDay = $avp;
            } else {
                if (!$avp->available && $lastAvailableDay) {
                    $availablePeriods[$lastAvailableDay->date]->till = $avp->date;
                    $lastAvailableDay = false;
                }
            }
        }
        $availablePeriods[$lastAvailableDay->date]->till = $avp->date;
        return $availablePeriods;
    }
}
