<?php

class Api_Annotation_Required implements Api_Annotation_Interface
{
    /**
     * @inheritdoc
     */
    public function serialize()
    {
        return [true];
    }

    /**
     * @inheritdoc
     */
    public function deserialize(array $data = [])
    {

    }

    /**
     * @inheritdoc
     */
    public function getName()
    {
        return 'required';
    }
}
