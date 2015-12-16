<?php

class Api_Annotation_Collection implements \IteratorAggregate, \Countable
{
    /**
     * @var Api_Annotation_Interface[]
     */
    protected $_annotations;

    /**
     * @param Api_Annotation_Interface[] $annotations
     */
    public function __construct(array $annotations = [])
    {
        $this->_annotations = $annotations;
    }

    /**
     * Add an object to the collection
     *
     * @param Api_Annotation_Interface $annotation
     */
    public function add(Api_Annotation_Interface $annotation)
    {
        $this->_annotations[] = $annotation;
    }

    /**
     * Get objects from collection as an list
     *
     * @return Api_Annotation_Interface[]
     */
    public function toArray()
    {
        return $this->_annotations;
    }

    /**
     * @see IteratorAggregate::getIterator
     */
    public function getIterator()
    {
        return $this->toArray();
    }

    /**
     * @see Countable::count
     */
    public function count()
    {
        return count($this->_annotations);
    }

    /**
     * Filter collection by the tag (name) of annotation
     *
     * @param string $name
     * @return Api_Annotation_Collection
     */
    public function filterByName($name)
    {
        $collection = new self();

        foreach ($this->_annotations as $annotation) {
            if ($annotation->getName() != $name) {
                continue;
            }

            $collection->add($annotation);
        }

        return $collection;
    }

    /**
     * Get first object
     *
     * @return Api_Annotation_Interface|null
     */
    public function first()
    {
        return $this->_annotations ? reset($this->_annotations) : null;
    }
}
