<?php

namespace SellNow\Contracts;

use SellNow\Models\Model;

interface RepositoryInterface
{

    /**
     * @param int|string $id
     * @return Model|null
     */
    public function findById(int|string $id): ?array;

    /**
     * @param array $params
     * @param array $relations
     * @return Model|null
     */
    public function getFirstWhere(array $params, array $relations = []): ?Model;


    /**
     * @param array $orderBy
     * @param string|null $searchValue
     * @param array $filters
     * @param array $relations
     * @return Model|null
     */
    public function getListWhere(array $orderBy = [], string $searchValue = null, array $filters = [], array $relations =[]):?Model;

    /**
     * @param array $data
     * @return string|object
     */
    public function add(array $data): string|object;

    /**
     * @param int $id
     * @param array $data
     * @return bool
     */
    public function update(int $id, array $data): bool;

    /**
     * @param int $id
     * @return bool
     */
    public function delete(int $id): bool;
}