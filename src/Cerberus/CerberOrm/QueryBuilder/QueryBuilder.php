<?php

declare(strict_types=1);

namespace Cerberus\CerberOrm\QueryBuilder;

use Cerberus\CerberOrm\QueryBuilder\Exception\QueryBuilderInvalidArgumentException;

class QueryBuilder implements QueryBuilderInterface
{
    /**
     * @var array
     */
    protected array $key;

    /**
     * @var string
     */
    protected string $sqlQuery;

    protected const SQL_DEFAULT = [
        'conditions' => [],
        'selectors' => [],
        'replace' => false,
        'distinct' => false,
        'from' => [],
        'where' => null,
        'and' => [],
        'or' => [],
        'orderBy' => [],
        'fields' => [],
        'primary_key' => '',
        'table' => '',
        'type' => '',
        'raw' => ''
    ];

    protected const QUERY_TYPES = [
        'insert',
        'select',
        'update',
        'delete',
        'raw'
    ];

    /**
     * Main constructor class
     *
     * @return void
     */
    public function __construct()
    {

    }

    /**
     * TODO
     *
     * @param array $args
     * @return $this
     */
    public function buildQuery(array $args = []): self
    {
        if (count($args) < 0) {
            throw new QueryBuilderInvalidArgumentException();
        }

        $args = array_merge(self::SQL_DEFAULT, $args);
        $this->key = $args;
        return $this;
    }

    /**
     * TODO
     *
     * @param string $type
     * @return bool
     */
    private function isQueryTypeValid(string $type): bool
    {
        if (in_array($type, self::QUERY_TYPES)) {
            return true;
        }
        return false;
    }

    /**
     * @inheritDoc
     */
    public function insertQuery()
    {
        if ($this->isQueryTypeValid('insert')) {
            if (is_array($this->key['fields']) && count($this->key['fields']) > 0) {
                $index = array_keys($this->key['fields']);
                $value = array(implode(', ', $index), ":" . implode(", :", $index));
                $this->sqlQuery = "INSERT INTO {$this->key['table']} ({$value[0]}) VALUES ({$value[1]})";
                return $this->sqlQuery;
            }
        }
        return false;
    }

    /**
     * @inheritDoc
     */
    public function selectQuery()
    {
        if ($this->isQueryTypeValid('select')) {
            $selectors = (!empty($this->key['selectors'])) ? implode(", ", $this->key['selectors']) : '*';
            $this->sqlQuery = "SELECT {$selectors} FROM {$this->key['table']}";
            $this->sqlQuery = $this->hasConditions();
            return $this->sqlQuery;
        }
        return false;
    }

    /**
     * @inheritDoc
     */
    public function updateQuery()
    {
        if ($this->isQueryTypeValid('update')) {
            if (is_array($this->key['fields']) && count($this->key['fields']) > 0) {
                foreach ($this->key['fields'] as $field) {
                    if ($field !== $this->key['primary_key']) {
                        $values .= $field . " = :" . $field . ", ";
                    }
                }
                $values = substr_replace($values, '', -2);
                if (count($this->key['fields']) > 0) {
                    $this->sqlQuery = "UPDATE {$this->key['table']} SET {$values} WHERE {$this->key['primary_key']} = :{$this->key['primary_key']} LIMIT 1";
                    if (isset($this->key['primary_key']) && $this->key['primary_key'] === '0') {
                        unset($this->key['primary_key']);
                        $this->sqlQuery = "UPDATE {$this->key['table']} SET {$values}";
                    }
                }
                return $this->sqlQuery;
            }
        }
        return false;
    }

    /**
     * @inheritDoc
     */
    public function deleteQuery()
    {
        if ($this->isQueryTypeValid('delete')) {
            $index = array_keys($this->key['conditions']);
            $this->sqlQuery = "DELETE {$this->key['table']} WHERE {$index[0]} = :{$index[0]} LIMIT 1";
            $bulkDelete = array_values($this->key['fields']);
            if (is_array($bulkDelete) && count($bulkDelete) > 1) {
                for ($i = 0; $i < count($bulkDelete); $i++) {
                    $this->sqlQuery = "DELETE FROM {$this->key['table']} WHERE {$index[0]} = :{$index[0]}";
                }
            }
            return $this->sqlQuery;
        }
        return false;
    }

    /**
     * @inheritDoc
     */
    public function searchQuery()
    {
        // TODO: Implement searchQuery() method.
    }

    /**
     * @inheritDoc
     */
    public function rawQuery()
    {
        // TODO: Implement rawQuery() method.
    }

    private function hasConditions()
    {
        if (isset($this->key['conditions']) && $this->key['conditions'] != '') {
            if (is_array($this->key['conditions'])) {
                $sort = [];
                foreach (array_keys($this->key['conditions']) as $where) {
                    if (isset($where) && $where != '') {
                        $sort[] = $where . " = :" . $where;
                    }
                }
                if (count($this->key['conditions']) > 0) {
                    $this->sqlQuery .= " WHERE " . implode(" AND ", $sort);
                }
            }
        } else if (empty($this->key['conditions'])) {
            $this->sqlQuery = " WHERE 1";
        }
//        $this->sqlQuery .= $this->orderByQuery();
//        $this->sqlQuery .= $this->queryOffset();
        if(isset($this->key['orderBy']) && $this->key['orderBy'] != "")
        {
            $this->sqlQuery .= " ORDER BY " . $this->key['orderBy'] . " ";
        }
        if(isset($this->key['limit']) && $this->key['offset'] != -1){
            $this->sqlQuery .= " LIMIT :offset, :limit";
        }

        return $this->sqlQuery;
    }
}