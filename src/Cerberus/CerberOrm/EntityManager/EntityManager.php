<?php

declare(strict_types=1);

namespace Cerberus\CerberOrm\EntityManager;

class EntityManager implements EntityManagerInterface
{
    /**
     * @var CrudInterface
     */
    protected CrudInterface $crud;

    /**
     * Main constructor class
     * @param CrudInterface $crud
     */
    public function __construct(CrudInterface $crud)
    {
        $this->crud = $crud;
    }

    /**
     * @inheritDoc
     */
    public function getCrud(): object
    {
        return $this->crud;
    }


}