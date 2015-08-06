<?php

namespace Diside\GeneratorBundle\Controller;

use Knp\Component\Pager\Paginator;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\HttpFoundation\Request;

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

    protected function buildPaginationWithFilter(Request $request, AbstractType $type, $queryBuilder, $parameter)
    {
        $form = $this->get('form.factory')->create($type);
        if ($this->get('request')->query->has($form->getName())) {
            $form->submit($this->get('request')->query->get($form->getName()));
        }

        $lexik = $this->get('lexik_form_filter.query_builder_updater');
        $lexik->addFilterConditions($form, $queryBuilder);

        return array_merge(
            array('form' => $form->createView()),
            $this->buildPagination($request, $queryBuilder, $parameter)
        );
    }

    protected function buildPagination (Request $request, $queryBuilder, $parameter)
    {
        $page = $request->query->get('page', 1);
        /** @var Paginator $paginator */
        $paginator = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            $queryBuilder,
            $page,
            $parameter
        );

        return array(
            'pagination' => $pagination
        );
    }
}
