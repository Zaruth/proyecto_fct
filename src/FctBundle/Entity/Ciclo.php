<?php

namespace FctBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\OneToMany;
use Doctrine\ORM\Mapping\ManyToMany;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Ciclo
 *
 * @ORM\Table(name="ciclo")
 * @ORM\Entity(repositoryClass="FctBundle\Repository\CicloRepository")
 */
class Ciclo
{
    /**
     * One ciclo has Many alumnos.
     * @OneToMany(targetEntity="Alumno", mappedBy="ciclo")
     */
    private $alumnos;
    
    /**
     * Many Ciclo have Many Profesor.
     * @ManyToMany(targetEntity="Profesor", mappedBy="ciclos")
     */
    private $profesores;
    
    /**
     * Get profesores
     *
     * @return array
     */
    public function getProfesores()
    {
        return $this->profesores;
    }

    /**
     * Set profesores
     *
     * @param array $profesores
     *
     * @return Ciclo
     */
    public function setProfesores($profesores)
    {
        $this->profesores = $profesores;

        return $this;
    }
    
    public function __construct() {
        $this->alumnos = new ArrayCollection();
        $this->profesores = new \Doctrine\Common\Collections\ArrayCollection();
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
     * @ORM\Column(name="abr", type="string", length=10)
     */
    private $abr;

    /**
     * @var string
     *
     * @ORM\Column(name="nombre", type="string", length=50)
     */
    private $nombre;

    /**
     * @var string
     *
     * @ORM\Column(name="grado", type="string", length=10)
     */
    private $grado;


    /**
     * @var int
     *
     * @ORM\Column(name="horas", type="integer")
     */
    private $horas;


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
     * Set abr
     *
     * @param string $abr
     *
     * @return Ciclo
     */
    public function setAbr($abr)
    {
        $this->abr = $abr;

        return $this;
    }

    /**
     * Get abr
     *
     * @return string
     */
    public function getAbr()
    {
        return $this->abr;
    }

    /**
     * Set nombre
     *
     * @param string $nombre
     *
     * @return Ciclo
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
     * Set grado
     *
     * @param string $grado
     *
     * @return Ciclo
     */
    public function setGrado($grado)
    {
        $this->grado = $grado;

        return $this;
    }

    /**
     * Get grado
     *
     * @return string
     */
    public function getGrado()
    {
        return $this->grado;
    }

    /**
     * Set horas
     *
     * @param integer $horas
     *
     * @return Ciclo
     */
    public function setHoras($horas)
    {
        $this->horas = $horas;

        return $this;
    }

    /**
     * Get horas
     *
     * @return int
     */
    public function getHoras()
    {
        return $this->horas;
    }
}

