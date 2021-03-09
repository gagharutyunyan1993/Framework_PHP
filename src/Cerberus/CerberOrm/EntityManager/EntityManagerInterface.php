<?php

declare(strict_types=1);

namespace Cerberus\CerberOrm\EntityManager;

interface EntityManagerInterface
{
    /**
     * @return object
     */
    public function getCrud(): object;
}