<?php

namespace Diside\GeneratorBundle\Twig;

class GlobalExtension extends \Twig_Extension
{
    /**
     * @var bool
     */
    private $debug;

    public function __construct($debug)
    {
        $this->debug = $debug;
    }

    public function getGlobals()
    {
        return array('novalidate' => $this->debug ? 'novalidate="novalidate"' : '');
    }

    public function getFilters()
    {
        return array(
            'to_underscore' => new \Twig_Filter_Method($this, 'toUnderscore'),
        );
    }

    public function getName()
    {
        return 'globals';
    }

    public function toUnderscore($value)
    {
        if(!is_string($value)) {
            return $value;
        }

        $value = preg_replace('/(?<=\\w)(?=[A-Z])/',"_$1", $value);

        return strtolower($value);
    }
}
