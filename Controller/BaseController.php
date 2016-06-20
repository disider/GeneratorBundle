<?php

namespace Diside\GeneratorBundle\Controller;

use Doctrine\ORM\QueryBuilder;
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

    protected function buildFilterForm(Request $request, QueryBuilder $queryBuilder, AbstractType $filterForm)
    {
        $filterForm = $this->createForm($filterForm);

        if ($request->query->has($filterForm->getName())) {
            $filterForm->submit($request->query->get($filterForm->getName()));

            $lexik = $this->get('lexik_form_filter.query_builder_updater');
            $lexik->addFilterConditions($filterForm, $queryBuilder);
        }

        return $filterForm;
    }

    protected function paginate($query, $page, $pageSize, $sortField = '', $sortDirection = 'asc')
    {
        $paginator = $this->get('knp_paginator');

        if (in_array($this->getParameter('kernel.environment'), array('prod', 'dev'))) {
            $options = array(
                'defaultSortFieldName' => $sortField,
                'defaultSortDirection' => $sortDirection
            );
        } else {
            $options = array();
        }

        return $paginator->paginate(
            $query,
            $page,
            $pageSize,
            $options
        );
    }
}
