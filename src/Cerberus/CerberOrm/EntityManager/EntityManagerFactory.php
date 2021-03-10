<?php


namespace Cerberus\CerberOrm\EntityManager;

use Cerberus\CerberOrm\EntityManager\Exception\CrudException;
use Cerberus\CerberOrm\DataMapper\DataMapperInterface;
use Cerberus\CerberOrm\QueryBuilder\QueryBuilderInterface;

class EntityManagerFactory
{
    protected DataMapperInterface $dataMapper;

    protected QueryBuilderInterface $queryBuilder;

    public function __construct(DataMapperInterface $dataMapper, QueryBuilderInterface $queryBuilder)
    {
        $this->dataMapper = $dataMapper;
        $this->queryBuilder = $queryBuilder;
    }

    /**
     * TODO: write doc ...
     *
     * @param string $crudString
     * @param string $tableSchema
     * @param string $tableSchemaId
     * @param array $options
     * @return EntityManager
     * @throws CrudException
     */
    public function create(string $crudString, string $tableSchema, string $tableSchemaId, array $options = [])
    {
        $crudObject = new $crudString($this->dataMapper,$this->queryBuilder,$tableSchema,$tableSchemaId);
        if(!$crudObject instanceof CrudInterface){
            throw new CrudException($crudString . ' is not a valid crud object.');
        }
        return new EntityManager($crudObject);
    }
}