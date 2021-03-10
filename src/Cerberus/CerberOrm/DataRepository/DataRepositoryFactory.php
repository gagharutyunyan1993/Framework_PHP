<?php

declare(strict_types=1);

namespace Cerberus\CerberOrm\DataRepository;

use Cerberus\CerberOrm\DataRepository\Exception\DataRepositoryException;

class DataRepositoryFactory
{
    /**
     * @var string
     */
    protected string $tableSchema;

    /**
     * @var string
     */
    protected string $tableSchemaID;

    /**
     * @var string
     */
    protected string $crudIdentifier;

    /**
     * DataRepositoryFactory constructor.
     * @param string $crudIdentifier
     * @param string $tableSchema
     * @param string $tableSchemaID
     */
    public function __construct(string $crudIdentifier, string $tableSchema, string $tableSchemaID)
    {
        $this->tableSchema = $tableSchema;
        $this->tableSchemaID = $tableSchemaID;
        $this->crudIdentifier = $crudIdentifier;
    }

    public function create(string $dataRepositoryString)
    {
        $dataRepositoryObject = new $dataRepositoryString();
        if(!$dataRepositoryObject instanceof DataRepositoryInterface)
        {
            throw new DataRepositoryException($dataRepositoryString . " is not a valid repository object.");
        }
        return $dataRepositoryObject;
    }


}