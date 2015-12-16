<?php

class Api_Annotation_Injector_Simple implements Api_Annotation_Injector_Interface
{
    /**
     * @inheritdoc
     * @throws Api_Exception_NoArgument
     */
    public function injectFromArray(
        $dto,
        \ReflectionProperty $reflection,
        Api_Annotation_Collection $annotations,
        array $data = []
    ) {
        $propertyName = $reflection->getName();
        $argumentName = $annotations->filterByName('request')->first();

        if (!$argumentName) {
            $argumentName = $propertyName;
        }

        $value = isset($data[$argumentName]) ? $data[$argumentName] : null;

        if ($annotations->filterByName('required')->count() > 0 && is_null($value)) {
            throw (new Api_Exception_NoArgument())->setName($propertyName);
        }

        $reflection->setValue($dto, $value);
    }

    /**
     * @inheritdoc
     */
    public function injectFromModel(
        $dto,
        \ReflectionProperty $reflection,
        Api_Annotation_Collection $annotations,
        \ArrayAccess $model
    ) {
        $propertyName = $reflection->getName();
        $fieldName = $annotations->filterByName('response')->first();

        if (!$fieldName) {
            $fieldName = $propertyName;
        }

        $reflection->setValue($dto, $model[$fieldName]);
    }
}
