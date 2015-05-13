<?php

namespace Diside\GeneratorBundle\Twig;

use Diside\GeneratorBundle\Helper\Inflect;
use Symfony\Component\Security\Core\SecurityContextInterface;
use Twig_Extension;

class InflectExtension extends Twig_Extension
{

    public function getFilters()
    {
        return array(
            new \Twig_SimpleFilter('pluralize', array($this, 'pluralize')),
            new \Twig_SimpleFilter('singularize', array($this, 'singularize'))
        );
    }

    public function pluralize($string)
    {
        return Inflect::pluralize($string);
    }

    public function singularize($string)
    {
        return Inflect::singularize($string);
    }

    public function getName()
    {
        return 'inflect_extension';
    }

}
