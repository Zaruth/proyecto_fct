<?php

namespace FctBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\OneToMany;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Empresa
 *
 * @ORM\Table(name="empresa")
 * @ORM\Entity(repositoryClass="FctBundle\Repository\EmpresaRepository")
 */
class Empresa
{
    /**
     * One Empresa has Many Fct.
     * @OneToMany(targetEntity="Fct", mappedBy="empresa")
     */
    private $fcts;
    public function __construct() {
        $this->fcts = new ArrayCollection();
    }
    
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="cif", type="string", length=9, unique=true)
     */
    private $cif;

    /**
     * @var string
     *
     * @ORM\Column(name="nombre", type="string", length=30)
     */
    private $nombre;

    /**
     * @var string
     *
     * @ORM\Column(name="tutor_laboral", type="string", length=90, nullable=true, unique=true)
     */
    private $tutorLaboral;

    /**
     * @var string
     *
     * @ORM\Column(name="direccion", type="string", length=30)
     */
    private $direccion;

    /**
     * @var string
     *
     * @ORM\Column(name="poblacion", type="string", length=30)
     */
    private $poblacion;

    /**
     * @var int
     *
     * @ORM\Column(name="cp", type="integer")
     */
    private $cp;

    /**
     * @var string
     *
     * @ORM\Column(name="provincia", type="string", length=30)
     */
    private $provincia;

    /**
     * @var string
     *
     * @ORM\Column(name="tlf", type="string", length=9)
     */
    private $tlf;

    /**
     * @var string
     *
     * @ORM\Column(name="mail", type="string", length=50, unique=true)
     */
    private $mail;


    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set cif
     *
     * @param string $cif
     *
     * @return Empresa
     */
    public function setCif($cif)
    {
        $this->cif = $cif;

        return $this;
    }

    /**
     * Get cif
     *
     * @return string
     */
    public function getCif()
    {
        return $this->cif;
    }

    /**
     * Set nombre
     *
     * @param string $nombre
     *
     * @return Empresa
     */
    public function setNombre($nombre)
    {
        $this->nombre = $nombre;

        return $this;
    }

    /**
     * Get nombre
     *
     * @return string
     */
    public function getNombre()
    {
        return $this->nombre;
    }

    /**
     * Set tutorLaboral
     *
     * @param string $tutorLaboral
     *
     * @return Empresa
     */
    public function setTutorLaboral($tutorLaboral)
    {
        $this->tutorLaboral = $tutorLaboral;

        return $this;
    }

    /**
     * Get tutorLaboral
     *
     * @return string
     */
    public function getTutorLaboral()
    {
        return $this->tutorLaboral;
    }

    /**
     * Set direccion
     *
     * @param string $direccion
     *
     * @return Empresa
     */
    public function setDireccion($direccion)
    {
        $this->direccion = $direccion;

        return $this;
    }

    /**
     * Get direccion
     *
     * @return string
     */
    public function getDireccion()
    {
        return $this->direccion;
    }

    /**
     * Set poblacion
     *
     * @param string $poblacion
     *
     * @return Empresa
     */
    public function setPoblacion($poblacion)
    {
        $this->poblacion = $poblacion;

        return $this;
    }

    /**
     * Get poblacion
     *
     * @return string
     */
    public function getPoblacion()
    {
        return $this->poblacion;
    }

    /**
     * Set cp
     *
     * @param integer $cp
     *
     * @return Empresa
     */
    public function setCp($cp)
    {
        $this->cp = $cp;

        return $this;
    }

    /**
     * Get cp
     *
     * @return int
     */
    public function getCp()
    {
        return $this->cp;
    }

    /**
     * Set provincia
     *
     * @param string $provincia
     *
     * @return Empresa
     */
    public function setProvincia($provincia)
    {
        $this->provincia = $provincia;

        return $this;
    }

    /**
     * Get provincia
     *
     * @return string
     */
    public function getProvincia()
    {
        return $this->provincia;
    }

    /**
     * Set tlf
     *
     * @param string $tlf
     *
     * @return Empresa
     */
    public function setTlf($tlf)
    {
        $this->tlf = $tlf;

        return $this;
    }

    /**
     * Get tlf
     *
     * @return string
     */
    public function getTlf()
    {
        return $this->tlf;
    }

    /**
     * Set mail
     *
     * @param string $mail
     *
     * @return Empresa
     */
    public function setMail($mail)
    {
        $this->mail = $mail;

        return $this;
    }

    /**
     * Get mail
     *
     * @return string
     */
    public function getMail()
    {
        return $this->mail;
    }
}

