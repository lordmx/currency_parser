<?php

class Api_Annotation_Request implements Api_Annotation_Interface
{
    /**
     * @var string
     */
    private $_field;

    /**
     * @return string
     */
    public function getField()
    {
        return $this->_field;
    }

    /**
     * @param string $field
     */
    public function setField($field)
    {
        $this->_field = $field;
    }

    /**
     * @inheritdoc
     */
    public function serialize()
    {
        return [$this->getField()];
    }

    /**
     * @inheritdoc
     */
    public function deserialize(array $data = [])
    {
        $this->setField(reset($data));
    }

    /**
     * @inheritdoc
     */
    public function getName()
    {
        return 'request';
    }
}
