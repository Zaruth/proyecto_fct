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
}

