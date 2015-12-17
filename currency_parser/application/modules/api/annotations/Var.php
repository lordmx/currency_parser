<?php

class Api_Annotation_Var implements Api_Annotation_Interface
{
    /**
     * @var string
     */
    private $_type;

    /**
     * @return string
     */
    public function getType()
    {
        return $this->_type;
    }

    /**
     * @param string $type
     */
    public function setType($type)
    {
        $this->_type = $type;
    }

    /**
     * @inheritdoc
     */
    public function serialize()
    {
        return [$this->getType()];
    }

    /**
     * @inheritdoc
     */
    public function deserialize(array $data = [])
    {
        $this->setType(reset($data));
    }

    /**
     * @inheritdoc
     */
    public function getName()
    {
        return 'var';
    }
}
