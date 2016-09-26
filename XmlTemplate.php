<?php

use Symfony\Component\Filesystem\Filesystem;

/**
 * Description of XmlTemplate
 *
 * @author alex
 */
abstract class XmlTemplate implements XmlTemplateInterface
{
    /**
     * Path to template dir
     * 
     * @var string
     */
    protected $twigTemplateDir = 'templates';
    
    /**
     * Template name
     * 
     * @var string
     */
    protected $templateName = 'property.html.twig';
    
    /**
     * Twig cache
     * 
     * @var string
     */
    protected $twigCache = 'cache/twig';
    
    /**
     * Template cache
     * 
     * @var string
     */
    protected $templateCache = 'cache';
    
    /**
     * Rendered twig template
     * 
     * @var string
     */
    protected $render = '';
    
    /**
     * Symfony filesystem object
     * 
     * @var Symfony\Component\Filesystem\Filesystem
     */
    protected $fs;
    
    /**
     * Additional template variables
     * 
     * @var array
     */
    protected $templateVars = array();
    
    /**
     * Twig Env
     * 
     * @var Twig_Environment
     */
    protected $env;

    /**
     * Constructor
     * 
     * @return void
     */
    public function __construct()
    {
        $this->fs = new Filesystem();
        
        $loader = new Twig_Loader_Filesystem($this->twigTemplateDir);
        $this->env = new Twig_Environment($loader, array(
            'cache' => $this->twigCache
        ));
    }
    
    /**
     * Return the twig environment
     * 
     * @return Twig_Environment
     */
    public function getTwig()
    {
        return $this->env;
    }
    
    /**
     * Initialise the cache
     * 
     * @return \PropertyXml
     */
    public function setUp()
    {
        if (!$this->fs->exists($this->templateCache)) {
            $this->fs->mkdir($this->templateCache);
        }
        if (!$this->fs->exists($this->twigCache)) {
            $this->fs->mkdir($this->twigCache);
        }
        
        return $this;
    }
    
    /**
     * Set the template cache
     * 
     * @param string $cache Path to cache
     * 
     * @return \XmlTemplate
     */
    public function setTemplateCache($cache)
    {
        $this->templateCache = $cache;
        
        return $this;
    }
    
    /**
     * Obliterate the cache
     * 
     * @return \PropertyXml
     */
    public function cacheBust()
    {
        if ($this->fs->exists($this->templateCache)) {
            $this->fs->remove($this->templateCache);
        }
        if ($this->fs->exists($this->twigCache)) {
            $this->fs->remove($this->twigCache);
        }
        
        return $this;
    }

    /**
     * Render function, returns templated text from twig
     * 
     * @return \PropertyXml
     */
    public function render()
    {
        $this->getTwig()->addFilter(
            'var_dump',
            new Twig_Filter_Function('var_dump')
        );
        $this->getTwig()->addExtension(new Twig_Extension_StringLoader());
        
        // Add global vars
        foreach ($this->getTemplateVars() as $key => $val) {
            $this->getTwig()->addGlobal($key, $val);
        }
        
        $this->render = $this->getTwig()->render(
            $this->templateName,
            $this->toArray()
        );
        
        return $this;
    }
    
    /**
     * Save the rendered file to the filesystem
     * 
     * @throws Exception
     * 
     * @return \PropertyXml
     */
    public function save()
    {
        if (strlen($this->render) == 0) {
            throw new Exception('Template has not been rendered', -1);
        }
        
        if (!$this->fs->exists($this->templateCache)) {
            throw new Exception(
                'Cache directory not setup.',
                -1
            );
        }
        
        $this->fs->dumpFile(
            $this->_getFiledir(),
            $this->render
        );
        
        return $this;
    }
    
    /**
     * Set the twig template name
     * 
     * @param string $name Template name
     * 
     * @return \XmlTemplate
     */
    public function setTemplateName($name)
    {
        $this->templateName = $name;
        
        return $this;
    }
    
    /**
     * Load the contents of a previous render
     * 
     * @todo Look at cache time expiration
     * 
     * @return \PropertyXml
     */
    public function load()
    {
        if ($this->fs->exists($this->_getFiledir())) {
            $this->render = file_get_contents($this->_getFiledir());
        }
        
        return $this;
    }
    
    /**
     * Generate render string from cache or regen if it doesn't exist or is
     * older than the expiry timestamp
     * 
     * @param integer $expiry Timestamp
     * 
     * @return \XmlTemplate
     */
    public function generateFromCache($expiry = null)
    {
        $this->load();
        if (strlen($this->render) == 0 
            || $expiry === null
            || filemtime($this->_getFiledir()) < $expiry
        ) {
            $this->render()->save();
        }
        
        return $this;
    }
    
    /**
     * Clear the file cache
     * 
     * @return \PropertyXml
     */
    public function clear()
    {
        $this->fs->remove($this->_getFiledir());
        
        return $this;
    }
    
    /**
     * Set a template variable
     * 
     * @param string $variable Template variable
     * 
     * @return \XmlTemplate
     */
    public function addTemplateVar($key, &$variable)
    {
        $this->templateVars[$key] = $variable;
        
        return $this;
    }
    
    /**
     * Return the template vars
     * 
     * @return array
     */
    public function getTemplateVars()
    {
        return $this->templateVars;
    }
    
    /**
     * ToString magic goodness
     * 
     * @return string
     */
    public function __toString()
    {
        return $this->render;
    }
    
    /**
     * Return the relative path to the file cache
     * 
     * @return string
     */
    private function _getFiledir()
    {
        return $this->templateCache . '/' . $this->getFilename();
    }
}
