<?php


namespace Diside\GeneratorBundle\Form\Processor;

use Diside\GeneratorBundle\Entity\Repository\AbstractRepository;
use Symfony\Component\Form\Form;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\SecurityContext;
use Symfony\Component\Security\Core\SecurityContextInterface;

abstract class AbstractFormProcessor
{
    const REDIRECT_TO_LIST = 'redirect_to_list';

    /** @var Form */
    private $form;

    /** @var SecurityContextInterface */
    private $securityContext;

    /** @var FormFactoryInterface */
    private $formFactory;

    /** @var string */
    private $redirectTo;

    /** @var bool */
    private $isNew;

    /** @var AbstractRepository */
    private $repository;

    private $data;

    public function __construct(
        AbstractRepository $repository,
        FormFactoryInterface $formFactory,
        SecurityContextInterface $securityContext
    ) {
        $this->securityContext = $securityContext;
        $this->formFactory = $formFactory;
        $this->repository = $repository;
    }

    protected abstract function buildForm();

    public function getForm()
    {
        return $this->form;
    }

    public function process(Request $request, $object = null)
    {
        $this->isNew = is_null($object) ? true : ($object->getId() == null);

        $this->form = $this->formFactory->create($this->buildForm(), $object);

        $this->handleRequest($request);
    }

    protected function handleRequest(Request $request)
    {
        if ($request->isMethod('POST')) {
            $this->form->handleRequest($request);

            if ($this->form->isValid()) {
                $this->data = $this->form->getData();

                $this->repository->save($this->data);

                $this->evaluateRedirect();
            }
        }
    }

    public function isValid()
    {
        return $this->form->isValid();
    }

    public function isNew()
    {
        return $this->isNew;
    }

    public function isRedirectingTo($to)
    {
        return $this->redirectTo == $to;
    }

    protected function getAuthenticatedUser()
    {
        $token = $this->securityContext->getToken();
        $user = $token->getUser();

        return $user;
    }

    protected function evaluateRedirect()
    {
        $this->setRedirectTo(
            $this->isButtonClicked(
                'saveAndClose'
            ) ? self::REDIRECT_TO_LIST : null
        );
    }

    protected function setRedirectTo($to)
    {
        $this->redirectTo = $to;
    }

    protected function isButtonClicked($buttonName)
    {
        if (!$this->form->has($buttonName)) {
            return false;
        }

        return $this->form->get($buttonName)->isClicked();
    }

    protected function getFormData()
    {
        return $this->form->getData();
    }

    protected function getRepository()
    {
        return $this->repository;
    }

    public function getData()
    {
        return $this->data;
    }

    /** @return SecurityContextInterface */
    public function getSecurityContext()
    {
        return $this->securityContext;
    }

}
