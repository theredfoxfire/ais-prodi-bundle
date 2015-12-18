<?php

namespace Ais\ProdiBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Ais\ProdiBundle\Model\ProdiInterface;

/**
 * Prodi
 */
class Prodi implements ProdiInterface
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var string
     */
    private $nama;

    /**
     * @var integer
     */
    private $fakultas_id;

    /**
     * @var boolean
     */
    private $is_active;

    /**
     * @var boolean
     */
    private $is_delete;


    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set nama
     *
     * @param string $nama
     *
     * @return Prodi
     */
    public function setNama($nama)
    {
        $this->nama = $nama;

        return $this;
    }

    /**
     * Get nama
     *
     * @return string
     */
    public function getNama()
    {
        return $this->nama;
    }

    /**
     * Set fakultasId
     *
     * @param integer $fakultasId
     *
     * @return Prodi
     */
    public function setFakultasId($fakultasId)
    {
        $this->fakultas_id = $fakultasId;

        return $this;
    }

    /**
     * Get fakultasId
     *
     * @return integer
     */
    public function getFakultasId()
    {
        return $this->fakultas_id;
    }

    /**
     * Set isActive
     *
     * @param boolean $isActive
     *
     * @return Prodi
     */
    public function setIsActive($isActive)
    {
        $this->is_active = $isActive;

        return $this;
    }

    /**
     * Get isActive
     *
     * @return boolean
     */
    public function getIsActive()
    {
        return $this->is_active;
    }

    /**
     * Set isDelete
     *
     * @param boolean $isDelete
     *
     * @return Prodi
     */
    public function setIsDelete($isDelete)
    {
        $this->is_delete = $isDelete;

        return $this;
    }

    /**
     * Get isDelete
     *
     * @return boolean
     */
    public function getIsDelete()
    {
        return $this->is_delete;
    }
}
