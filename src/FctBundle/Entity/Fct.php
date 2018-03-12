<?php

namespace FctBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\JoinColumn;

/**
 * Fct
 *
 * @ORM\Table(name="fct")
 * @ORM\Entity(repositoryClass="FctBundle\Repository\FctRepository")
 */
class Fct
{
    /**
     * Many Fct have One alumno.
     * @ManyToOne(targetEntity="Alumno", inversedBy="fcts")
     * @JoinColumn(name="alumno_id", referencedColumnName="id")
     */
    private $alumno;
    
    /**
     * Many Fct have One profesor.
     * @ManyToOne(targetEntity="Profesor", inversedBy="fcts")
     * @JoinColumn(name="profesor_id", referencedColumnName="id")
     */
    private $profesor;
    
    /**
     * Many Fct have One empresa.
     * @ManyToOne(targetEntity="Empresa", inversedBy="fcts")
     * @JoinColumn(name="empresa_id", referencedColumnName="id")
     */
    private $empresa;
    
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="anyo", type="date")
     */
    private $anyo;
    
    /**
     * @var string
     *
     * @ORM\Column(name="periodo", type="string", length=30)
     */
    private $periodo;


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
     * Set anyo
     *
     * @param \DateTime $anyo
     *
     * @return Fct
     */
    public function setAnyo($anyo)
    {
        $this->anyo = $anyo;

        return $this;
    }

    /**
     * Get anyo
     *
     * @return \DateTime
     */
    public function getAnyo()
    {
        return $this->anyo;
    }
    
    /**
     * Set alumno
     *
     * @param Alumno $alumno
     *
     * @return Fct
     */
    public function setAlumno($alumno)
    {
        $this->alumno = $alumno;

        return $this;
    }

    /**
     * Get alumno
     *
     * @return Alumno
     */
    public function getAlumno()
    {
        return $this->alumno;
    }
    
    /**
     * Set profesor
     *
     * @param Profesor $profesor
     *
     * @return Fct
     */
    public function setProfesor($profesor)
    {
        $this->profesor = $profesor;

        return $this;
    }

    /**
     * Get profesor
     *
     * @return Profesor
     */
    public function getProfesor()
    {
        return $this->profesor;
    }
    
    /**
     * Set empresa
     *
     * @param Empresa $empresa
     *
     * @return Fct
     */
    public function setEmpresa($empresa)
    {
        $this->empresa = $empresa;

        return $this;
    }

    /**
     * Get empresa
     *
     * @return Empresa
     */
    public function getEmpresa()
    {
        return $this->empresa;
    }
    
    /**
     * Set periodo
     *
     * @param string $periodo
     *
     * @return Fct
     */
    public function setPeriodo($periodo)
    {
        $this->periodo = $periodo;

        return $this;
    }

    /**
     * Get periodo
     *
     * @return string
     */
    public function getPeriodo()
    {
        return $this->periodo;
    }
}

