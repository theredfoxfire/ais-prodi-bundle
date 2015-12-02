<?php

namespace Ais\ProdiBundle\Handler;

use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Form\FormFactoryInterface;
use Ais\ProdiBundle\Model\ProdiInterface;
use Ais\ProdiBundle\Form\ProdiType;
use Ais\ProdiBundle\Exception\InvalidFormException;

class ProdiHandler implements ProdiHandlerInterface
{
    private $om;
    private $entityClass;
    private $repository;
    private $formFactory;

    public function __construct(ObjectManager $om, $entityClass, FormFactoryInterface $formFactory)
    {
        $this->om = $om;
        $this->entityClass = $entityClass;
        $this->repository = $this->om->getRepository($this->entityClass);
        $this->formFactory = $formFactory;
    }

    /**
     * Get a Prodi.
     *
     * @param mixed $id
     *
     * @return ProdiInterface
     */
    public function get($id)
    {
        return $this->repository->find($id);
    }

    /**
     * Get a list of Prodis.
     *
     * @param int $limit  the limit of the result
     * @param int $offset starting from the offset
     *
     * @return array
     */
    public function all($limit = 5, $offset = 0)
    {
        return $this->repository->findBy(array(), null, $limit, $offset);
    }

    /**
     * Create a new Prodi.
     *
     * @param array $parameters
     *
     * @return ProdiInterface
     */
    public function post(array $parameters)
    {
        $prodi = $this->createProdi();

        return $this->processForm($prodi, $parameters, 'POST');
    }

    /**
     * Edit a Prodi.
     *
     * @param ProdiInterface $prodi
     * @param array         $parameters
     *
     * @return ProdiInterface
     */
    public function put(ProdiInterface $prodi, array $parameters)
    {
        return $this->processForm($prodi, $parameters, 'PUT');
    }

    /**
     * Partially update a Prodi.
     *
     * @param ProdiInterface $prodi
     * @param array         $parameters
     *
     * @return ProdiInterface
     */
    public function patch(ProdiInterface $prodi, array $parameters)
    {
        return $this->processForm($prodi, $parameters, 'PATCH');
    }

    /**
     * Processes the form.
     *
     * @param ProdiInterface $prodi
     * @param array         $parameters
     * @param String        $method
     *
     * @return ProdiInterface
     *
     * @throws \Ais\ProdiBundle\Exception\InvalidFormException
     */
    private function processForm(ProdiInterface $prodi, array $parameters, $method = "PUT")
    {
        $form = $this->formFactory->create(new ProdiType(), $prodi, array('method' => $method));
        $form->submit($parameters, 'PATCH' !== $method);
        if ($form->isValid()) {

            $prodi = $form->getData();
            $this->om->persist($prodi);
            $this->om->flush($prodi);

            return $prodi;
        }

        throw new InvalidFormException('Invalid submitted data', $form);
    }

    private function createProdi()
    {
        return new $this->entityClass();
    }

}
