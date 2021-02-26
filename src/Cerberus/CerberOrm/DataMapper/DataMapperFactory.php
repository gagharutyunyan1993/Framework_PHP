<?php

declare(strict_types=1);

namespace Cerberus\CerberOrm\DataMapper;

use Cerberus\CerberOrm\DataMapper\Exception\DataMapperException;
use Cerberus\DatabaseConnection\DatabaseConnectionInterface;

class DataMapperFactory
{
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
     * @param string $databaseConnectionString
     * @param string $dataMapperEnvironmentConfiguration
     * @return DataMapperInterface
     * @throws DataMapperException
     */
    public function __create(string $databaseConnectionString, string $dataMapperEnvironmentConfiguration) : DataMapperInterface
    {
        $credentials = (new $dataMapperEnvironmentConfiguration([]))->getDatabaseCredentials('mysql');
        $databaseConnectionObject = new $databaseConnectionString($credentials);
        if (!$databaseConnectionObject instanceof DatabaseConnectionInterface) {
            throw new DataMapperException($databaseConnectionString . ' is not a valid database connection object');
        }
        return new DataMapper($databaseConnectionObject);
    }
}