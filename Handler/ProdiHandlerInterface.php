<?php

namespace Ais\ProdiBundle\Handler;

use Ais\ProdiBundle\Model\ProdiInterface;

interface ProdiHandlerInterface
{
    /**
     * Get a Prodi given the identifier
     *
     * @api
     *
     * @param mixed $id
     *
     * @return ProdiInterface
     */
    public function get($id);

    /**
     * Get a list of Prodis.
     *
     * @param int $limit  the limit of the result
     * @param int $offset starting from the offset
     *
     * @return array
     */
    public function all($limit = 5, $offset = 0);

    /**
     * Post Prodi, creates a new Prodi.
     *
     * @api
     *
     * @param array $parameters
     *
     * @return ProdiInterface
     */
    public function post(array $parameters);

    /**
     * Edit a Prodi.
     *
     * @api
     *
     * @param ProdiInterface   $prodi
     * @param array           $parameters
     *
     * @return ProdiInterface
     */
    public function put(ProdiInterface $prodi, array $parameters);

    /**
     * Partially update a Prodi.
     *
     * @api
     *
     * @param ProdiInterface   $prodi
     * @param array           $parameters
     *
     * @return ProdiInterface
     */
    public function patch(ProdiInterface $prodi, array $parameters);
}
