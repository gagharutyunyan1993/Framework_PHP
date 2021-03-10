<?php

declare(strict_types=1);

namespace Cerberus\CerberOrm\DataRepository;

use Cerberus\CerberOrm\DataRepository\Exception\DataRepositoryInvalidArgumentException;
use Cerberus\CerberOrm\EntityManager\EntityManager;
use Cerberus\CerberOrm\EntityManager\EntityManagerInterface;
use Throwable;

class DataRepository implements DataRepositoryInterface
{
    /**
     * @var EntityManagerInterface
     */
    protected EntityManagerInterface $em;

    /**
     * DataRepository constructor.
     * @param EntityManagerInterface $em
     */
    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    /**
     * @param array $conditions
     * @todo
     *
     */
    private function isArray(array $conditions): void
    {
        if (!is_array($conditions)) {
            throw new DataRepositoryInvalidArgumentException("The argument supplied is not an argument.");
        }
    }

    private function isEmpty(int $id): void
    {
        if (empty($id)) {
            throw new DataRepositoryInvalidArgumentException("Argument should not be empty");
        }
    }

    /**
     * @inheritDoc
     */
    public function find(int $id): array
    {
        $this->isEmpty($id);

        try {
            return $this->findOneBy(['id' => $id]);
        } catch (Throwable $throwable) {
            throw $throwable;
        }
    }

    /**
     * @inheritDoc
     */
    public function findAll(): array
    {
        try {
            return $this->em->getCrud()->read();
        } catch (Throwable $throwable) {
            throw $throwable;
        }
    }

    /**
     * @inheritDoc
     */
    public function findBy(array $selectors = [], array $conditions = [], array $parameters = [], array $optional = []): array
    {
        try {
            return $this->em->getCrud()->read($selectors, $conditions, $parameters, $optional);
        } catch (Throwable $throwable) {
            throw $throwable;
        }
    }

    /**
     * @inheritDoc
     */
    public function findOneBy(array $conditions): array
    {
        $this->isArray($conditions);
        try {
            return $this->em->getCrud()->read([], $conditions);
        } catch (Throwable $throwable) {
            throw $throwable;
        }
    }

    /**
     * @inheritDoc
     */
    public function findObjectBy(array $conditions = [], array $selectors = []): object
    {
        try {
            // TODO
        } catch (Throwable $throwable) {
            throw $throwable;
        }
    }

    /**
     * @inheritDoc
     */
    public function findBySearch(array $selectors = [], array $conditions = [], array $parameters = [], array $optional = []): array
    {
        $this->isArray($conditions);

        try {
            return $this->em->getCrud()->search($selectors, $conditions, $parameters, $optional);
        } catch (Throwable $throwable) {
            throw $throwable;
        }
    }

    /**
     * @inheritDoc
     */
    public function findByIdAndDelete(array $conditions, int $id): bool
    {
        $this->isArray($conditions);

        try {
            $result = $this->findOneBy($conditions);
            if($result != null && count($result) > 0)
            {
                $delete = $this->em->getCrud()->delete($conditions);
                if($delete){
                    return true;
                }
            }
        } catch (Throwable $throwable) {
            throw $throwable;
        }
    }

    /**
     * @inheritDoc
     */
    public function findByIdAndUpdate(int $id, array $fields = []): bool
    {
        $this->isArray($fields);

        try {
            $result = $this->findOneBy([$this->em->getCrud()->getSchemaID() => $id]);
            if($result != null && count($result) > 0){
                $params = (!empty($fields)) ? array_merge([$this->em->getCrud()->getSchemaID() => $id],$fields) : $fields;
                $update = $this->em->getCrud()->update($params, $this->em->getCrud()->getSchemaID());
                if($update){
                    return true;
                }

            }
        } catch (Throwable $throwable) {
            throw $throwable;
        }
    }

    /**
     * @inheritDoc
     */
    public function findWithSearchAndPaging(array $args, Object $request): array
    {
        return [];
    }

    /**
     * @inheritDoc
     */
    public function findAndReturn(int $id, array $selectors = []): DataRepositoryInterface
    {
        // TODO
    }
}