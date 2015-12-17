<?php

class Api_Annotation_Response implements Api_Annotation_Interface
{
    /**
     * @var string
     */
    private $_sourceField;

    /**
     * @var string
     */
    private $_targetField;

    /**
     * @return string
     */
    public function getTargetField()
    {
        return $this->_targetField ?: $this->_sourceField;
    }

    /**
     * @param string $targetField
     */
    public function setTargetField($targetField)
    {
        $this->_targetField = $targetField;
    }

    /**
     * @return string
     */
    public function getSourceField()
    {
        return $this->_sourceField ?: $this->_targetField;
    }

    /**
     * @param string $sourceField
     */
    public function setSourceField($sourceField)
    {
        $this->_sourceField = $sourceField;
    }

    /**
     * @inheritdoc
     */
    public function serialize()
    {
        $result = [];

        if ($this->getSourceField()) {
            $result['source'] = $this->getSourceField();
        }

        if ($this->getTargetField()) {
            $result['target'] = $this->getTargetField();
        }

        return $result;
    }

    /**
     * @inheritdoc
     */
    public function deserialize(array $data = [])
    {
        if ($data) {
            if (isset($data['source'])) {
                $this->setSourceField($data['source']);
            }

            if (isset($data['target'])) {
                $this->setTargetField($data['target']);
            }

            if (!$this->getSourceField()) {
                $this->setSourceField(reset($data));
            }
        }

        if (!$this->getTargetField()) {
            $this->setTargetField($this->getSourceField());
        }
    }

    /**
     * @inheritdoc
     */
    public function getName()
    {
        return 'response';
    }
}
