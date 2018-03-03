<?php

namespace FctBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\OneToMany;
use Doctrine\ORM\Mapping\ManyToMany;
use Doctrine\ORM\Mapping\JoinTable;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * Profesor
 *
 * @ORM\Table(name="profesor")
 * @ORM\Entity(repositoryClass="FctBundle\Repository\ProfesorRepository")
 */
class Profesor implements UserInterface
{
    /**
     * One Profesor has Many Fct.
     * @OneToMany(targetEntity="Fct", mappedBy="profesor")
     */
    private $fcts;
    
    /**
     * Many Profesor have Many Ciclo.
     * @ManyToMany(targetEntity="Ciclo", inversedBy="profesores")
     * @JoinTable(name="ciclos")
     */
    private $ciclos;
    
    public function __construct() {
        $this->fcts = new ArrayCollection();
        $this->ciclos = new ArrayCollection();
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
     * @ORM\Column(name="nif", type="string", length=9, unique=true)
     */
    private $nif;

    /**
     * @var string
     *
     * @ORM\Column(name="nombre", type="string", length=30)
     */
    private $nombre;

    /**
     * @var string
     *
     * @ORM\Column(name="ape1", type="string", length=30)
     */
    private $ape1;

    /**
     * @var string
     *
     * @ORM\Column(name="ape2", type="string", length=30)
     */
    private $ape2;

    /**
     * @var string
     *
     * @ORM\Column(name="img", type="string", length=9, nullable=true, unique=true)
     */
    private $img;

    /**
     * @var string
     *
     * @ORM\Column(name="nuser", type="string", length=20, unique=true)
     */
    private $nuser;

    /**
     * @var string
     *
     * @ORM\Column(name="pass", type="string", length=60)
     */
    private $pass;

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
     * @var string
     *
     * @ORM\Column(name="role", type="string", length=10)
     */
    private $role;

    //AUTH
    
    public function getUsername(){
        return $this->nuser;
    }
    
    public function getSalt(){
        return null;
    }
    
    public function getPassword()
    {
        return $this->pass;
    }
    
    public function getRoles(){
        return array($this->getRole());
    }
    
    public function eraseCredentials(){
    }
    
    //END AUTH

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
     * Set nif
     *
     * @param string $nif
     *
     * @return Profesor
     */
    public function setNif($nif)
    {
        $this->nif = $nif;

        return $this;
    }

    /**
     * Get nif
     *
     * @return string
     */
    public function getNif()
    {
        return $this->nif;
    }

    /**
     * Set nombre
     *
     * @param string $nombre
     *
     * @return Profesor
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
     * Set ape1
     *
     * @param string $ape1
     *
     * @return Profesor
     */
    public function setApe1($ape1)
    {
        $this->ape1 = $ape1;

        return $this;
    }

    /**
     * Get ape1
     *
     * @return string
     */
    public function getApe1()
    {
        return $this->ape1;
    }

    /**
     * Set ape2
     *
     * @param string $ape2
     *
     * @return Profesor
     */
    public function setApe2($ape2)
    {
        $this->ape2 = $ape2;

        return $this;
    }

    /**
     * Get ape2
     *
     * @return string
     */
    public function getApe2()
    {
        return $this->ape2;
    }

    /**
     * Set img
     *
     * @param string $img
     *
     * @return Profesor
     */
    public function setImg($img)
    {
        $this->img = $img;

        return $this;
    }

    /**
     * Get img
     *
     * @return string
     */
    public function getImg()
    {
        return $this->img;
    }

    /**
     * Set nuser
     *
     * @param string $nuser
     *
     * @return Profesor
     */
    public function setNuser($nuser)
    {
        $this->nuser = $nuser;

        return $this;
    }

    /**
     * Get nuser
     *
     * @return string
     */
    public function getNuser()
    {
        return $this->nuser;
    }

    /**
     * Set pass
     *
     * @param string $pass
     *
     * @return Profesor
     */
    public function setPass($pass)
    {
        $this->pass = $pass;

        return $this;
    }

    /**
     * Get pass
     *
     * @return string
     */
    public function getPass()
    {
        return $this->pass;
    }

    /**
     * Set tlf
     *
     * @param string $tlf
     *
     * @return Profesor
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
     * @return Profesor
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

    /**
     * Set role
     *
     * @param string $role
     *
     * @return Profesor
     */
    public function setRole($role)
    {
        $this->role = $role;

        return $this;
    }

    /**
     * Get role
     *
     * @return string
     */
    public function getRole()
    {
        return $this->role;
    }
}

