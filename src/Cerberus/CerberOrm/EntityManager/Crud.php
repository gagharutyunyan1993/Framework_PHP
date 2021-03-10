<?php

declare(strict_types=1);

namespace Cerberus\CerberOrm\EntityManager;

use Cerberus\CerberOrm\DataMapper\DataMapper;
use Cerberus\CerberOrm\QueryBuilder\QueryBuilder;
use Throwable;

class Crud implements CrudInterface
{
    /**
     * @var DataMapper
     */
    protected DataMapper $dataMapper;

    /**
     * @var QueryBuilder
     */
    protected QueryBuilder $queryBuilder;

    /**
     * @var string
     */
    protected string $tableSchema;

    /**
     * @var string
     */
    protected string $tableSchemaID;

    public function __construct(DataMapper $dataMapper, QueryBuilder $queryBuilder, string $tableSchema, string $tableSchemaID)
    {
        $this->dataMapper = $dataMapper;
        $this->queryBuilder = $queryBuilder;
        $this->tableSchema = $tableSchema;
        $this->tableSchemaID = $tableSchemaID;
    }

    /**
     * @inheritDoc
     */
    public function getSchema(): string
    {
        return (string)$this->tableSchema;
    }

    /**
     * @inheritDoc
     */
    public function getSchemaID(): string
    {
        return (string)$this->tableSchemaID;
    }

    /**
     * @inheritDoc
     */
    public function lastID(): int
    {
        return $this->dataMapper->getLastId();
    }

    /**
     * @inheritDoc
     */
    public function create(array $fields = []): bool
    {
        try {
            $args = [
                'table' => $this->getSchema(),
                'type' => 'insert',
                'fields' => 'fields'
            ];
            $query = $this->queryBuilder->buildQuery($args)->insertQuery();
            $this->dataMapper->persist($query, $this->dataMapper->buildQueryParameters($fields));
            if ($this->dataMapper->numRows() == 1) {
                return true;
            }
        } catch (Throwable $throwable) {
            throw $throwable;
        }
    }

    /**
     * @inheritDoc
     */
    public function read(array $selectors = [], array $conditions = [], array $parameters = [], array $optional = []): array
    {
        try {
            $args = ['table' => $this->getSchema(), 'type' => 'select', 'selectors' => $selectors, 'conditions' => $conditions, 'params' => $parameters, 'extras' => $optional];
            $query = $this->queryBuilder->buildQuery($args)->selectQuery();
            $this->dataMapper->persist($query, $this->dataMapper->buildQueryParameters($conditions, $parameters));
            if ($this->dataMapper->numRows() > 0) {
                return $this->dataMapper->results();
            }
        } catch (Throwable $throwable) {
            throw $http_response_header;
        }
    }

    /**
     * @inheritDoc
     */
    public function update(array $fields, string $primaryKey): bool
    {
        try {
            $args = ['table' => $this->getSchema(), 'type' => 'update', 'fields' => $fields, 'primary_key' => $primaryKey];
            $query = $this->queryBuilder->buildQuery($args)->updateQuery();
            $this->dataMapper->persist($query, $this->dataMapper->buildQueryParameters($fields));
            if ($this->dataMapper->numRows() == 1) {
                return true;
            }
        } catch (Throwable $throwable) {
            throw $throwable;
        }
    }

    /**
     * @inheritDoc
     */
    public function delete(array $conditions = []): bool
    {
        try {
            $args = ['table' => $this->getSchema(), 'type' => 'delete', 'conditions' => $conditions];
            $query = $this->queryBuilder->buildQuery($args)->deleteQuery();
            $this->dataMapper->persist($query, $this->dataMapper->buildQueryParameters($conditions));
            if ($this->dataMapper->numRows() === 1) {
                return true;
            }
        } catch (Throwable $throwable) {
            throw $throwable;
        }
    }

    /**
     * @inheritDoc
     */
    public function search(array $selectors = [], array $conditions = []): ?array
    {
        try {
            $args = ['table' => $this->getSchema(), 'type' => 'search', 'selectors' => $selectors, 'conditions' => $conditions];
            $query = $this->queryBuilder->buildQuery($args)->searchQuery();
            $this->dataMapper->persist($query, $this->dataMapper->buildQueryParameters($conditions));
            if ($this->dataMapper->numRows() === 1) {
                return $this->dataMapper->results();
            }
        } catch (Throwable $throwable) {
            throw $throwable;
        }
    }

    /**
     * @inheritDoc
     * @throws Throwable
     */
    public function rawQuery(string $rawQuery, ?array $conditions = [], string $resultType = 'column')
    {
        try {
            $argc = [
                'table' => $this->getSchema(),
                'type' => 'raw',
                'raw' => $rawQuery,
                'conditions' => $conditions
            ];
            $query = $this->queryBuilder->buildQuery($argc)->rawQuery();
            $this->dataMapper->persist($query, $this->dataMapper->buildQueryParameters($conditions));
            if ($this->dataMapper->numRows()) {
                dd('testing');
            }
        } catch (Throwable $throwable) {
            throw $throwable;
        }
    }

    /**
     * @inheritDoc
     */
    public function get(array $selectors = [], array $conditions = []): ?object
    {
        // TODO: Implement get() method.
    }

    /**
     * @inheritDoc
     */
    public function aggregate(string $type, ?string $field = 'id', array $conditions = [])
    {
        // TODO: Implement aggregate() method.
    }

    /**
     * @inheritDoc
     */
    public function countRecords(array $conditions = [], ?string $field = 'id'): int
    {
        // TODO: Implement countRecords() method.
    }
}