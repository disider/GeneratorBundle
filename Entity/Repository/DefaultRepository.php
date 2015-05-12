<?php

namespace Diside\GeneratorBundle\Entity\Repository;

class DefaultRepository extends AbstractRepository
{
    const ROOT_ALIAS = 'entity';

    protected function getRootAlias()
    {
        return self::ROOT_ALIAS;
    }

}