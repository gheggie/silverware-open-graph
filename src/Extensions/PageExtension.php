<?php

/**
 * This file is part of SilverWare.
 *
 * PHP version >=5.6.0
 *
 * For full copyright and license information, please view the
 * LICENSE.md file that was distributed with this source code.
 *
 * @package SilverWare\OpenGraph\Extensions
 * @author Colin Tucker <colin@praxis.net.au>
 * @copyright 2017 Praxis Interactive
 * @license https://opensource.org/licenses/BSD-3-Clause BSD-3-Clause
 * @link https://github.com/praxisnetau/silverware-open-graph
 */

namespace SilverWare\OpenGraph\Extensions;

use SilverStripe\CMS\Controllers\ModelAsController;
use SilverStripe\Core\Convert;
use SilverStripe\Core\Extension;
use SilverStripe\SiteConfig\SiteConfig;

/**
 * An extension which adds open graph metadata to pages.
 *
 * @package SilverWare\OpenGraph\Extensions
 * @author Colin Tucker <colin@praxis.net.au>
 * @copyright 2017 Praxis Interactive
 * @license https://opensource.org/licenses/BSD-3-Clause BSD-3-Clause
 * @link https://github.com/praxisnetau/silverware-open-graph
 */
class PageExtension extends Extension
{
    /**
     * Maps field and method names to the class names of casting objects.
     *
     * @var array
     * @config
     */
    private static $casting = [
        'OGPrefix' => 'HTMLFragment'
    ];
    
    /**
     * Answers the open graph prefix for the <head> tag.
     *
     * @return string
     */
    public function getOGPrefix()
    {
        // Add Default Namespace URI:
        
        $ns = ['og' => $this->getNamespaceURI('og')];
        
        // Add Page Namespace URI:
        
        if ($type = $this->owner->getOGTypeNamespace()) {
            $ns[$type] = $this->getNamespaceURI($type);
        }
        
        // Build Prefix Array:
        
        $prefix = [];
        
        foreach (array_filter($ns) as $name => $uri) {
            $prefix[] = sprintf('%s: %s#', $name, $uri);
        }
        
        // Answer Prefix Attribute:
        
        return sprintf(' prefix="%s"', implode(' ', $prefix));
    }
    
    /**
     * Answers the open graph type for the page (override in extended object).
     *
     * @return string
     */
    public function getOGType()
    {
        return $this->owner->getOGTypeMapping('default');
    }
    
    /**
     * Answers the open graph type for the mapping with the given name and optional sub-name.
     *
     * @param string $name
     * @param string $subname
     *
     * @return string
     */
    public function getOGTypeMapping($name, $subname = null)
    {
        if ($types = $this->owner->config()->open_graph_types) {
            
            if (isset($types[$name])) {
                
                if (is_array($types[$name]) && $subname) {
                    return $types[$name][$subname];
                }
                
                if (!is_array($types[$name])) {
                    return $types[$name];
                }
                
            }
            
        }
    }
    
    /**
     * Answers the open graph type namespace for the page.
     *
     * @return string
     */
    public function getOGTypeNamespace()
    {
        if ($type = $this->owner->OGType) {
            return (strpos($type, '.') !== false) ? explode('.', $type)[0] : $type;
        }
    }
    
    /**
     * Answers the URL for the open graph tag.
     *
     * @return string
     */
    public function getOGURL()
    {
        if ($url = $this->owner->MetaAbsoluteLink) {
            return $url;
        }
        
        return $this->owner->AbsoluteLink();
    }
    
    /**
     * Answers the title for the open graph tag.
     *
     * @return string
     */
    public function getOGTitle()
    {
        if ($title = $this->owner->MetaTitle) {
            return $title;
        }
        
        return $this->owner->Title;
    }
    
    /**
     * Answers the image for the open graph tag.
     *
     * @return string
     */
    public function getOGImage()
    {
        $config = $this->getSiteConfig();
        
        if (($image = $this->owner->MetaImage) && $image->exists()) {
            return $image->AbsoluteURL;
        }
        
        if ($config->AppIconLargeID && $config->AppIconLarge()->exists()) {
            return $config->AppIconLarge()->AbsoluteURL;
        }
    }
    
    /**
     * Answers the locale for the open graph tag.
     *
     * @return string
     */
    public function getOGLocale()
    {
        return ModelAsController::controller_for($this->owner)->ContentLocale();
    }
    
    /**
     * Answers the site name for the open graph tag.
     *
     * @return string
     */
    public function getOGSiteName()
    {
        return $this->getSiteConfig()->Title;
    }
    
    /**
     * Answers the description for the open graph tag.
     *
     * @return string
     */
    public function getOGDescription()
    {
        if ($desc = $this->owner->MetaDescription) {
            return $desc;
        }
        
        return $this->owner->MetaSummaryLimited;
    }
    
    /**
     * Appends the additional open graph tags to the provided meta tags.
     *
     * @param string $tags
     *
     * @return void
     */
    public function MetaTags(&$tags)
    {
        // Add New Line (if does not exist):
        
        if (!preg_match('/[\n]$/', $tags)) {
            $tags .= "\n";
        }
        
        // Iterate Open Graph Metadata:
        
        foreach ($this->owner->config()->open_graph_metadata as $tag => $property) {
            $this->addMetaTag($tags, "og:{$tag}", $this->owner->$property);
        }
    }
    
    /**
     * Appends a meta tag with the given property name and content value to the provided tags variable.
     *
     * @param string $tags
     * @param string $property
     * @param string $content
     *
     * @return void
     */
    protected function addMetaTag(&$tags, $property = null, $content = null)
    {
        if (is_array($content)) {
            
            foreach ($content as $value) {
                $this->addMetaTag($tags, $property, $value);
            }
            
        } else {
            
            if ($content) {
                $tags .= $this->getMetaTag($property, $content);
            }
            
        }
    }
    
    /**
     * Answers a meta tag with the given property name and content value.
     *
     * @param string $property
     * @param string $content
     *
     * @return string
     */
    protected function getMetaTag($property = null, $content = null)
    {
        return sprintf(
            "<meta property=\"%s\" content=\"%s\" />\n",
            Convert::raw2att($property),
            Convert::raw2att($content)
        );
    }
    
    /**
     * Answers the namespace URI for the specified prefix.
     *
     * @param string $prefix
     *
     * @return string
     */
    protected function getNamespaceURI($prefix)
    {
        $namespaces = $this->owner->config()->open_graph_namespaces;
        
        if (isset($namespaces[$prefix])) {
            return $namespaces[$prefix];
        }
    }
    
    /**
     * Answers the current site config object.
     *
     * @return SiteConfig
     */
    protected function getSiteConfig()
    {
        return SiteConfig::current_site_config();
    }
}
