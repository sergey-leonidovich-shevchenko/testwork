<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\EntityInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class BaseRepository extends ServiceEntityRepository
{
    /**
     * @template T of object
     * @param ManagerRegistry $registry
     * @param class-string<T> $entityString
     */
    public function __construct(ManagerRegistry $registry, string $entityString)
    {
        parent::__construct($registry, $entityString);
    }

    final public function save(EntityInterface $entity): void
    {
        $em = $this->getEntityManager();
        $em->persist($entity);
        $em->flush();
    }
}
