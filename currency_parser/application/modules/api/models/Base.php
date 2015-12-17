<?php

class Api_Model_Base implements \ArrayAccess
{
    /**
     * @see \ArrayAccess::offsetGet
     */
    public function offsetGet($offset)
    {
        $getterName = 'get' . $this->_toCamelCase($offset);

        if (!method_exists($this, $getterName)) {
            return null;
        }

        return call_user_func([$this, $getterName]);
    }

    /**
     * @see \ArrayAccess::offsetSet
     * @throws \BadMethodCallException
     */
    public function offsetSet($offset, $value)
    {
        $setterName = 'get' . $this->_toCamelCase($offset);

        if (!method_exists($this, $setterName)) {
            throw new BadMethodCallException('Call to undefined method ' . get_class($this) . '::' . $setterName . '()');
        }

        return call_user_func([$this, $setterName], $value);
    }

    /**
     * @see \ArrayAccess::offsetExists
     */
    public function offsetExists($offset)
    {
        return $this->offsetGet() !== null;
    }

    /**
     * @see \ArrayAccess::offsetUnset
     */
    public function offsetUnset($offset)
    {
        $this->offsetSet($offset, null);
    }

    /**
     * @param string $str
     * @return string
     */
    private function _toCamelCase($str)
    {
        $parts = explode('_', $str);
        $parts = array_map('ucfirst', $parts);

        return implode('', $parts);
    }
}