<?php

interface Api_Annotation_Interface
{
    /**
     * Get annotation tag (phpdoc)
     *
     * @return string
     */
    public function getName();

    /**
     * Serializes annotations data
     *
     * @return array
     */
    public function serialize();

    /**
     * Get annotation object from array
     *
     * @param array $data
     * @return Annotation
     */
    public function deserialize(array $data = []);
}