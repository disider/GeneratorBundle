<?php

namespace Diside\GeneratorBundle\Entity\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;

abstract class AbstractRepository extends EntityRepository
{
    abstract protected function getRootAlias();

    public function findLast()
    {
        return $this->createQueryBuilder($this->getRootAlias())
            ->orderBy($this->getRootAlias().'.id', 'DESC')
            ->setMaxResults(1)
            ->getQuery()->getOneOrNullResult();
    }

    /** @return QueryBuilder */
    public function findAllQuery()
    {
        return $this->createQueryBuilder($this->getRootAlias());
    }

    public function countAll()
    {
        $qb = $this->findAllQuery();

        return $qb->select(sprintf('COUNT(%s.id)', $this->getRootAlias()))
            ->getQuery()->getSingleScalarResult();
    }

    public function save($object)
    {
        $em = $this->getEntityManager();
        $em->persist($object);
        $em->flush();

        return $object;
    }

    public function delete($object)
    {
        $em = $this->getEntityManager();
        $em->remove($object);
        $em->flush();
    }

}
