<?php

namespace Cpm\JovenesBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Cpm\JovenesBundle\Entity\Proyecto
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Cpm\JovenesBundle\Entity\ProyectoRepository")
 */
class Proyecto
{
    /**
     * @var integer $id
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string $titulo
     *
     * @ORM\Column(name="titulo", type="string")
     */
    private $titulo;

    /**
     * @var integer $nroAlumnos
     *
     * @ORM\Column(name="nroAlumnos", type="integer")
     */
    private $nroAlumnos;

    /**
     * @var boolean $esPrimeraVezDocente
     *
     * @ORM\Column(name="esPrimeraVezDocente", type="boolean")
     */
    private $esPrimeraVezDocente;

    /**
     * @var boolean $esPrimeraVezEscuela
     *
     * @ORM\Column(name="esPrimeraVezEscuela", type="boolean")
     */
    private $esPrimeraVezEscuela;

    /**
     * @var boolean $esPrimeraVezAlumnos
     *
     * @ORM\Column(name="esPrimeraVezAlumnos", type="boolean")
     */
    private $esPrimeraVezAlumnos;


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
     * Set titulo
     *
     * @param string $titulo
     */
    public function setTitulo(\string $titulo)
    {
        $this->titulo = $titulo;
    }

    /**
     * Get titulo
     *
     * @return string 
     */
    public function getTitulo()
    {
        return $this->titulo;
    }

    /**
     * Set nroAlumnos
     *
     * @param integer $nroAlumnos
     */
    public function setNroAlumnos($nroAlumnos)
    {
        $this->nroAlumnos = $nroAlumnos;
    }

    /**
     * Get nroAlumnos
     *
     * @return integer 
     */
    public function getNroAlumnos()
    {
        return $this->nroAlumnos;
    }

    /**
     * Set esPrimeraVezDocente
     *
     * @param boolean $esPrimeraVezDocente
     */
    public function setEsPrimeraVezDocente($esPrimeraVezDocente)
    {
        $this->esPrimeraVezDocente = $esPrimeraVezDocente;
    }

    /**
     * Get esPrimeraVezDocente
     *
     * @return boolean 
     */
    public function getEsPrimeraVezDocente()
    {
        return $this->esPrimeraVezDocente;
    }

    /**
     * Set esPrimeraVezEscuela
     *
     * @param boolean $esPrimeraVezEscuela
     */
    public function setEsPrimeraVezEscuela($esPrimeraVezEscuela)
    {
        $this->esPrimeraVezEscuela = $esPrimeraVezEscuela;
    }

    /**
     * Get esPrimeraVezEscuela
     *
     * @return boolean 
     */
    public function getEsPrimeraVezEscuela()
    {
        return $this->esPrimeraVezEscuela;
    }

    /**
     * Set esPrimeraVezAlumnos
     *
     * @param boolean $esPrimeraVezAlumnos
     */
    public function setEsPrimeraVezAlumnos($esPrimeraVezAlumnos)
    {
        $this->esPrimeraVezAlumnos = $esPrimeraVezAlumnos;
    }

    /**
     * Get esPrimeraVezAlumnos
     *
     * @return boolean 
     */
    public function getEsPrimeraVezAlumnos()
    {
        return $this->esPrimeraVezAlumnos;
    }
}