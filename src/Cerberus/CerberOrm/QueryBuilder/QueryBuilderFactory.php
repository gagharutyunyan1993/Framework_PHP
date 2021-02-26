<?php

declare(strict_types=1);

namespace Cerberus\CerberOrm\QueryBuilder;

use Cerberus\CerberOrm\QueryBuilder\Exception\QueryBuilderException;

class QueryBuilderFactory
{
    /**
     * Main constructor method
     *
     * @return void
     */
    public function __construct()
    {

    }

    /**
     * TODO
     *
     * @param string $queryBuilderString
     * @return QueryBuilderInterface
     */
    public function create(string $queryBuilderString): QueryBuilderInterface
    {
           $queryBuilderObject = new $queryBuilderString();
           if($queryBuilderString instanceof QueryBuilderInterface)
           {
               throw new QueryBuilderException($queryBuilderString . " is not a valid Query");
           }
           return new QueryBuilder();
    }
}