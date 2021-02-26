<?php

declare(strict_types=1);

namespace Cerberus\CerberOrm\QueryBuilder;

interface QueryBuilderInterface
{
    /**
     * Insert query string
     * Function return false or string
     *
     * @return mixed
     * @throws QueryBuilderException
     */
    public function insertQuery();

    /**
     * Select query string
     * Function return false or string
     *
     * @return mixed
     * @throws QueryBuilderException
     */
    public function selectQuery();

    /**
     * Update query string
     * Function return false or string
     *
     * @return mixed
     * @throws QueryBuilderException
     */
    public function updateQuery();

    /**
     * Delete query string
     * Function return false or string
     *
     * @return mixed
     * @throws QueryBuilderException
     */
    public function deleteQuery();

    /**
     * Search|Select query string
     * Function return false or string
     *
     * @return mixed
     * @throws QueryBuilderException
     */
    public function searchQuery();

    /**
     * Raw query string
     * Function return false or string
     *
     * @return mixed
     * @throws QueryBuilderException
     */
    public function rawQuery();
}