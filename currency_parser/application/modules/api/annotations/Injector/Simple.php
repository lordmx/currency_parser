<?php

class Api_Annotation_Injector_Simple implements Api_Annotation_Injector_Interface
{
    /**
     * @inheritdoc
     * @throws Api_Exception_NoArgument
     */
    public function injectToPropertyFromArray(
        $dto,
        \ReflectionProperty $reflection,
        Api_Annotation_Collection $annotations,
        array $data = []
    ) {
        $propertyName = $reflection->getName();
        $annotation = $annotations->filterByName('request')->first();

        if (!$annotation) {
            $argumentName = $propertyName;
        } else {
            $argumentName = $annotation->getField() ?: $propertyName;
        }

        $value = isset($data[$argumentName]) ? $data[$argumentName] : null;

        $reflection->setValue($dto, $value);
    }

    /**
     * @inheritdoc
     */
    public function injectToPropertyFromModel(
        $dto,
        \ReflectionProperty $reflection,
        Api_Annotation_Collection $annotations,
        \ArrayAccess $model
    ) {
        $propertyName = $reflection->getName();
        $annotation = $annotations->filterByName('response')->first();

        if (!$annotation) {
            $sourceField = $propertyName;
        } else {
            $sourceField = $annotation->getSourceField() ?: $propertyName;
        }

        $reflection->setValue($dto, $model[$sourceField]);
    }

    /**
     * @inheritdoc
     */
    public function injectToArrayFromDto(
        $dto,
        \ReflectionProperty $reflection,
        Api_Annotation_Collection $annotations
    ) {
        $propertyName = $reflection->getName();
        $annotation = $annotations->filterByName('response')->first();

        if (!$annotation) {
            $targetField = $propertyName;
        } else {
            $targetField = $annotation->getTargetField() ?: $propertyName;
        }

        $value = $reflection->getValue($dto);

        if ($value instanceof \DateTime) {
            $value = $value->format(DATE_W3C);
        }

        return [$targetField => $value];
    }
}
