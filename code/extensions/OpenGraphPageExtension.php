<?php

/**
 * An extension of the data extension class to add open graph metatags to pages.
 */
class OpenGraphPageExtension extends DataExtension
{
    /**
     * Answers the open graph type for the page (override type in extended object).
     *
     * @return string
     */
    public function OGType()
    {
        return OpenGraphTypes::DEFAULT_TYPE;
    }
    
    /**
     * Answers the open graph type namespace for the page.
     *
     * @return string
     */
    public function OGTypeNamespace()
    {
        if ($type = $this->owner->OGType()) {
            
            if (strpos($type, '.') !== false) {
                return array_shift(explode('.', $type));
            }
            
            return $type;
            
        }
    }

    /**
     * Answers the open graph prefix for the page.
     *
     * @return string
     */
    public function OGPrefix()
    {
        // Add Default Name Space:
        
        $nspace = array('og' => OpenGraphTypes::get_namespace_uri('og'));
        
        // Add Name Space for Page:
        
        if ($ns_uri = OpenGraphTypes::get_namespace_uri($this->owner->OGTypeNamespace())) {
            $nspace[$this->owner->OGTypeNamespace()] = $ns_uri;
        }
        
        // Build Prefix Array:
        
        $prefix = array();
        
        foreach ($nspace as $name => $uri) {
            $prefix[] = sprintf('%s: %s#', $name, $uri);
        }
        
        // Answer Prefix Attribute:
        
        return sprintf(' prefix="%s"', implode(' ', $prefix));
    }

    /**
     * Answers the URL value for the open graph tag.
     *
     * @return string
     */
    public function OGURL()
    {
        if ($this->owner->hasMethod('MetaAbsoluteLink')) {
            return $this->owner->MetaAbsoluteLink();
        }
        
        return $this->owner->AbsoluteLink();
    }

    /**
     * Answers the title value for the open graph tag.
     *
     * @return string
     */
    public function OGTitle()
    {
        if ($this->owner->hasMethod('MetaTitle')) {
            return $this->owner->MetaTitle();
        }
        
        return $this->owner->Title;
    }

    /**
     * Answers the image value for the open graph tag.
     *
     * @return string
     */
    public function OGImage()
    {
        // Obtain Site Config:
        
        $Config = SiteConfig::current_site_config();
        
        // Answer Meta Image (if it exists):
        
        if ($this->owner->hasMethod('MetaImage')) {
            
            if ($Image = $this->owner->MetaImage()) {
                
                if ($Image->exists()) {
                    return $Image->AbsoluteURL;
                }
                
            }
            
        }
        
        // Answer Site Icon (if it exists):
        
        if ($Config->SiteIconLargeExists()) {
            return $Config->SiteIconLargeResized(500, 500)->AbsoluteURL;
        }
    }

    /**
     * Answers the locale for the open graph tag.
     *
     * @return string
     */
    public function OGLocale()
    {
        return ModelAsController::controller_for($this->owner)->ContentLocale();
    }

    /**
     * Answers the site name value for the open graph tag.
     *
     * @return string
     */
    public function OGSiteName()
    {
        return SiteConfig::current_site_config()->Title;
    }
    
    /**
     * Answers the description value for the open graph tag.
     *
     * @return string
     */
    public function OGDescription()
    {
        if ($this->owner->hasMethod('MetaSummary')) {
            return $this->owner->MetaSummary();
        }
    }

    /**
     * Adds additional open graph meta tags to the extended page markup.
     *
     * @param string $tags
     */
    public function MetaTags(&$tags)
    {
        $this->addMetaTag($tags, 'og:title', $this->owner->OGTitle());
        $this->addMetaTag($tags, 'og:type', $this->owner->OGType());
        $this->addMetaTag($tags, 'og:url', $this->owner->OGURL());
        $this->addMetaTag($tags, 'og:image', $this->owner->OGImage());
        $this->addMetaTag($tags, 'og:description', $this->owner->OGDescription());
        $this->addMetaTag($tags, 'og:site_name', $this->owner->OGSiteName());
        $this->addMetaTag($tags, 'og:locale', $this->owner->OGLocale());
    }

    /**
     * Appends a meta tag with the given property name and content value to the given tags variable.
     *
     * @param string $tags
     * @param string $property
     * @param string $content
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
}
