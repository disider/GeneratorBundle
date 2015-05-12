<?php

namespace Diside\GeneratorBundle\Form;

use Symfony\Component\Form\AbstractType as BaseAbstractType;

abstract class AbstractType extends BaseAbstractType
{

    protected function buildChoices($values, $prefix = null)
    {
        $list = array();

        foreach ($values as $value) {
            $list[$value] = $prefix ? ($prefix.'.'.$value) : $value;
        }

        return $list;
    }
}
