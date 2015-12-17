<?php

interface Api_Repository_Interface
{
    /**
     * Get models via DTO criteria
     *
     * @param object $dto
     * @return object[]
     */
    public function findByDto($dto);

    /**
     * Get models count via DTO criteria
     *
     * @param object $dto
     * @return int
     */
    public function countByDto($dto);

    /**
     * Find a model by ID
     *
     * @param int $id
     * @return object|null
     */
    public function findById($id);

    /**
     * Get all models from data source
     *
     * @return object[]
     */
    public function findAll();

    /**
     * Persist the model
     *
     * @param object $model
     * @return object
     */
    public function persist($model);

    /**
     * Delete the model
     *
     * @param object $model
     * @return bool
     */
    public function delete($model);
}
