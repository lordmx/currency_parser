<?php

interface Api_Annotation_Cache_Interface
{
    /**
     * Restore annotations of the property from cache
     *
     * @param object $object
     * @param string $propertyName
     * @return Api_Annotation_Collection|null
     */
    public function get($object, $propertyName);

    /**
     * Store annotations of the property
     *
     * @param object $object
     * @param string $propertyName
     * @param Api_Annotation_Collection $annotations
     */
    public function set($object, $propertyName, Api_Annotation_Collection $annotations);
}
