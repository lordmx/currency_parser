<?php

interface Api_Annotation_Reader_Interface
{
    /**
     * Get annotations from phpdoc
     *
     * @param string $phpdoc
     * @return Api_Annotation_Collection
     */
    public function read($phpdoc);
}