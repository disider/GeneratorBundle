<?php

namespace Diside\GeneratorBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class BaseController extends Controller
{
    protected function translate($id, $params = array())
    {
        $translator = $this->get('translator');

        /** @Ignore */
        return $translator->trans($id, $params);
    }

    protected function addFlash($type, $id, array $params = array())
    {
        $flashBag = $this->get('session')->getFlashBag();

        $flashBag->add(
            $type,
            $this->translate($id, $params)
        );
    }

}
