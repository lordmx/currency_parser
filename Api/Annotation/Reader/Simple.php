<?php

class Api_Annotation_Reader_Simple implements Api_Annotation_Reader_Interface
{
    /**
     * @param string $phpdoc
     * @return array
     */
    public function read($phpdoc)
    {
        $regexp = '#@([a-z0-9_\\\]+)([ ].*+|[ ]?\(.*?\))?#i';
        $matches = [];

        preg_match_all($regexp, $phpdoc, $matches);

        if (empty($matches[1][0])) {
            return [];
        }

        foreach ($matches[1] as $i => $name) {
            $value = $matches[2][$i];
            $value = trim($value);
            $value = str_replace(["\r", "\n"], '', $value);

            if ($value && $value[0] == '(') {
                $value = $this->_extractValue($value);
                $value = $this->_separateValue($value);
            }

            $result[] = $this->_createAnnotation($name, $value);
        }

        return $result;
    }

    /**
     * @param string $name
     * @param array $value
     * @return Api_Annotation_Interface
     * @throws Api_Exception_InternalError
     */
    private function _createAnnotation($name, $value)
    {
        $className = 'Api_Annotation_' .  ucfirst($name);

        if (!class_exists($className)) {
            throw new Api_Exception_InternalError('Wrong annotation class: ' . $className);
        }

        /**
         * @var Annotation $annotation
         */
        $annotation = new $className();
        $annotation->deserialize($value);

        return $annotation;
    }
    /**
     * @param string $value
     * @return array
     */
    private function _separateValue($value)
    {
        if (!$value) {
            if (is_array($value)) {
                return $value;
            }

            return [$value];
        }

        $regexp = '#([a-z0-9"]+)[\= ]+([a-z0-9{"]+)#i';
        $value = preg_replace($regexp, '$1: $2', $value);

        if ($value[0] != '{' && preg_match('#[a-z0-9]"[\: ]+([a-z0-9{"]+)#i', $value)) {
            $value = preg_replace('#([a-z0-9]+)[\: ]#', '"$1":', $value);
            return json_decode('{' . $value . '}', true);
        } else {
            return json_decode('[' . $value. ']', true);
        }
    }
    /**
     * @param string $value
     * @return string
     */
    private function _extractValue($value)
    {
        if ($value && $value[0] == '(' && $value[strlen($value) - 1] == ')') {
            $value = substr($value, 1, -1);
        }

        return $value;
    }
}
