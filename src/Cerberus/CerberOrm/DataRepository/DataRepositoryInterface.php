<?php

declare(strict_types=1);

namespace Cerberus\CerberOrm\DataRepository;

interface DataRepositoryInterface
{
    /**
     * @todo
     *
     * @param int $id
     * @return array
     */
    public function find(int $id): array;

    /**
     * @todo
     *
     * @return array
     */
    public function findAll(): array;

    /**
     * @todo
     *
     * @param array $selectors
     * @param array $conditions
     * @param array $parameters
     * @param array $optional
     * @return array
     */
    public function findBy(array $selectors = [], array $conditions = [], array $parameters = [], array $optional = []): array;

    /**
     * @todo
     *
     * @param array $conditions
     * @return array
     */
    public function findOneBy(array $conditions): array;

    /**
     * @todo
     *
     * @param array $conditions
     * @param array $selectors
     * @return object
     */
    public function findObjectBy(array $conditions = [], array $selectors = []): object;

    /**
     * @todo
     *
     * @param array $selectors
     * @param array $conditions
     * @param array $parameters
     * @param array $optional
     * @return array
     */
    public function findBySearch(array $selectors = [], array $conditions = [], array $parameters = [], array $optional = []): array;

    /**
     * @todo
     *
     * @param array $conditions
     * @param int $id
     * @return bool
     */
    public function findByIdAndDelete(array $conditions, int $id): bool;

    /**
     * @todo
     *
     * @param array $fields
     * @param int $id
     * @return bool
     */
    public function findByIdAndUpdate(int $id, array $fields = []): bool;

    /**
     * @todo
     *
     * @param array $args
     * @param Object $request
     * @return array
     */
    public function findWithSearchAndPaging(array $args, Object $request): array;

    /**
     * @todo
     *
     * @param int $id
     * @param array $selectors
     * @return $this
     */
    public function findAndReturn(int $id, array $selectors = []): self;
}