<?php

namespace Ais\ProdiBundle\Model;

Interface ProdiInterface
{
    /**
     * Get id
     *
     * @return integer
     */
    public function getId();

    /**
     * Set nama
     *
     * @param string $nama
     *
     * @return Prodi
     */
    public function setNama($nama);

    /**
     * Get nama
     *
     * @return string
     */
    public function getNama();

    /**
     * Set fakultasId
     *
     * @param integer $fakultasId
     *
     * @return Prodi
     */
    public function setFakultasId($fakultasId);

    /**
     * Get fakultasId
     *
     * @return integer
     */
    public function getFakultasId();

    /**
     * Set isActive
     *
     * @param boolean $isActive
     *
     * @return Prodi
     */
    public function setIsActive($isActive);

    /**
     * Get isActive
     *
     * @return boolean
     */
    public function getIsActive();

    /**
     * Set isDelete
     *
     * @param boolean $isDelete
     *
     * @return Prodi
     */
    public function setIsDelete($isDelete);

    /**
     * Get isDelete
     *
     * @return boolean
     */
    public function getIsDelete();
}
