<?php

declare(strict_types=1);

namespace Cerberus\CerberOrm\DataMapper;

use Cerberus\CerberOrm\DataMapper\Exception\DataMapperException;
use Cerberus\DatabaseConnection\DatabaseConnectionInterface;
use PDO;
use Throwable;
use PDOStatement;

class DataMapper implements DataMapperInterface
{
    /**
     * @var DatabaseConnectionInterface
     */
    private DatabaseConnectionInterface $dbh;

    /**
     * @var PDOStatement
     */
    private PDOStatement $stmt;

    /**
     * Main constructor class
     *
     * @param DatabaseConnectionInterface $dbh
     */
    public function __construct(DatabaseConnectionInterface $dbh)
    {
        $this->dbh = $dbh;
    }

    /**
     * TODO
     *
     * @param $value
     * @param string|null $errorMessage
     * @throws DataMapperException
     */
    private function isEmpty($value, string $errorMessage = null)
    {
        if (empty($value)) {
            throw new DataMapperException($errorMessage);
        }
    }

    /**
     * TODO
     *
     * @param $value
     * @param string|null $errorMessage
     * @throws DataMapperException
     */
    private function isArray($value, string $errorMessage = null)
    {
        if (is_array($value)) {
            throw new DataMapperException('Your arguments needs to be an array.');
        }
    }

    /**
     * @inheritDoc
     */
    public function prepare(string $sqlQuery): DataMapperInterface
    {
        $this->stmt = $this->dbh->open()->prepare($sqlQuery);
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function bind($value)
    {
        try {
            switch ($value) {
                case is_bool($value):
                    $dataType = PDO::PARAM_BOOL;
                    break;
                case intval($value):
                    $dataType = PDO::PARAM_INT;
                    break;
                case is_null($value):
                    $dataType = PDO::PARAM_NULL;
                    break;
                default:
                    $dataType = PDO::PARAM_STR;
                    break;
            }
            return $dataType;
        } catch (DataMapperException $exception) {
            throw $exception;
        }
    }

    /**
     * Binds a value to a corresponding name or question mark placeholder
     * statement that was used to prepare the statement
     *
     * @param array $fields
     * @return PDOStatement
     * @throws DataMapperException
     */
    protected function bindValues(array $fields): PDOStatement
    {
        $this->isArray($fields);
        foreach ($fields as $key => $value) {
            $this->stmt->bindValue(':' . $key, $value, $this->bind($value));
        }

        return $this->stmt;
    }

    /**
     * @inheritDoc
     *
     * @param array $fields
     * @param boolean $isSearch
     * @return self
     * @throws DataMapperException
     */
    public function bindParameters(array $field, bool $isSearch = false): self
    {
        if (is_array($field)) {
            $type = ($isSearch === false) ? $this->bindValues($field) : $this->bindSearchValues($field);
            if ($type) {
                return $this;
            }
        }
        return false;
    }

    protected function bindSearchValues($fields)
    {
        $this->isArray($fields);
        foreach ($fields as $key => $value) {
            $this->stmt->bindValue(':' . $key, '%' . $value . '%', $this->bind($value));
        }

        return $this->stmt;
    }

    /**
     * @inheritDoc
     */
    public function numRows(): int
    {
        if($this->stmt)
        {
            return $this->stmt->rowCount();
        }
    }

    /**
     * @inheritDoc
     */
    public function execute(): bool
    {
        if ($this->stmt) {
            return $this->stmt->execute();
        }
    }

    /**
     * @inheritDoc
     */
    public function result(): object
    {
        if($this->stmt)
        {
            return $this->stmt->fetch(PDO::FETCH_OBJ);
        }
    }

    /**
     * @inheritDoc
     */
    public function results(): array
    {
        if($this->stmt)
        {
            return $this->stmt->fetchAll();
        }
    }

    /**
     * @inheritDoc
     * @return int
     * @throws Throwable
     */
    public function getLastId(): int
    {
        try {
            if($this->dbh->open()){
                $lastID = $this->dbh->open()->lastInsertId();
                if(!empty($lastID)){
                    return intval($lastID);
                }
            }
        }catch (Throwable $throwable){
            throw $throwable;
        }
    }

    /**
     * @inheritDoc
     */
    public function column()
    {
        if ($this->stmt) return $this->stmt->fetchColumn();
    }
}