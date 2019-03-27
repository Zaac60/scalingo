<?php

namespace Biopen\CoreBundle\Document\Configuration;

use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;

/** @MongoDB\EmbeddedDocument */
class ConfigurationMarker
{
    /** @MongoDB\Field(type="bool") */
    public $displayPopup = true;

    /** @MongoDB\Field(type="bool") */
    public $popupAlwaysVisible = false;

    /** @MongoDB\Field(type="string") */
    public $popupTemplate = "{{ name }}";

    /** @MongoDB\Field(type="bool") */
    public $popupTemplateUseMarkDown = false;

    /** @MongoDB\Field(type="hash") */
    public $fieldsUsedByTemplate = ['name'];

    // Those fields we be used in element compact Json
    public function updateFieldsUsedByTemplate()
    {
        $matches = [];
        preg_match_all('/({{\s*[\w_|]*\s*}})/',$this->popupTemplate, $matches);
        $newFields = array_map(function($match) { 
            $fieldName = explode('|', preg_replace('/[{}\s]/', '', $match))[0];
            if ($fieldName == "image") $fieldName = "images";
            return $fieldName;
        }, $matches[0]);
        $oldFields = $this->fieldsUsedByTemplate;

        // if new fields different from old fields (order si not important)
        if (count(array_diff(array_merge($newFields, $oldFields), array_intersect($newFields, $oldFields))) != 0) {
            $this->setFieldsUsedByTemplate($newFields);
        }        
    }

    /**
     * Set displayPopup
     *
     * @param bool $displayPopup
     * @return $this
     */
    public function setDisplayPopup($displayPopup)
    {
        $this->displayPopup = $displayPopup;
        return $this;
    }

    /**
     * Get displayPopup
     *
     * @return bool $displayPopup
     */
    public function getDisplayPopup()
    {
        return $this->displayPopup;
    }

    /**
     * Set popupAlwaysVisible
     *
     * @param bool $popupAlwaysVisible
     * @return $this
     */
    public function setPopupAlwaysVisible($popupAlwaysVisible)
    {
        $this->popupAlwaysVisible = $popupAlwaysVisible;
        return $this;
    }

    /**
     * Get popupAlwaysVisible
     *
     * @return bool $popupAlwaysVisible
     */
    public function getPopupAlwaysVisible()
    {
        return $this->popupAlwaysVisible;
    }

    /**
     * Set popupTemplate
     *
     * @param string $popupTemplate
     * @return $this
     */
    public function setPopupTemplate($popupTemplate)
    {
        $this->popupTemplate = $popupTemplate;
        $this->updateFieldsUsedByTemplate();        
        return $this;
    }

    /**
     * Get popupTemplate
     *
     * @return string $popupTemplate
     */
    public function getPopupTemplate()
    {
        return $this->popupTemplate;
    }

    /**
     * Set popupTemplateUseMarkDown
     *
     * @param bool $popupTemplateUseMarkDown
     * @return $this
     */
    public function setPopupTemplateUseMarkDown($popupTemplateUseMarkDown)
    {
        $this->popupTemplateUseMarkDown = $popupTemplateUseMarkDown;
        return $this;
    }

    /**
     * Get popupTemplateUseMarkDown
     *
     * @return bool $popupTemplateUseMarkDown
     */
    public function getPopupTemplateUseMarkDown()
    {
        return $this->popupTemplateUseMarkDown;
    }

    /**
     * Set fieldsUsedByTemplate
     *
     * @param hash $fieldsUsedByTemplate
     * @return $this
     */
    public function setFieldsUsedByTemplate($fieldsUsedByTemplate)
    {
        $this->fieldsUsedByTemplate = $fieldsUsedByTemplate;
        return $this;
    }

    /**
     * Get fieldsUsedByTemplate
     *
     * @return hash $fieldsUsedByTemplate
     */
    public function getFieldsUsedByTemplate()
    {
        return $this->displayPopup ? $this->fieldsUsedByTemplate : [];
    }
}
