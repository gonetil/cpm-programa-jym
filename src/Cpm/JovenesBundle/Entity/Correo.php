<?php

namespace Cpm\JovenesBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Cpm\JovenesBundle\Entity\Correo
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Cpm\JovenesBundle\Entity\CorreoRepository")
 */
class Correo
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
     * @var datetime $fecha
     *
     * @ORM\Column(name="fecha", type="datetime")
     */
    private $fecha;

    /**
     * @var string $email
     *
     * @ORM\Column(name="email", type="string")
     */
    private $email;
    
    /**
     * @var string $asunto
     *
     * @ORM\Column(name="asunto", type="string")
     */
    private $asunto;

    /**
     * @var text $cuerpo
     *
     * @ORM\Column(name="cuerpo", type="text")
     */
    private $cuerpo;

    /**
     *  @ORM\ManyToOne(targetEntity="Usuario")
     */
    private $destinatario;
    
    /**
     *  @ORM\ManyToOne(targetEntity="Usuario")
     */
    private $emisor;
    

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
     * Set fecha
     *
     * @param datetime $fecha
     */
    public function setFecha(\datetime $fecha)
    {
        $this->fecha = $fecha;
    }

    /**
     * Get fecha
     *
     * @return datetime 
     */
    public function getFecha()
    {
        return $this->fecha;
    }

    /**
     * Set asunto
     *
     * @param string $asunto
     */
    public function setAsunto($asunto)
    {
        $this->asunto = $asunto;
    }

    /**
     * Get asunto
     *
     * @return string 
     */
    public function getAsunto()
    {
        return $this->asunto;
    }

    /**
     * Set cuerpo
     *
     * @param text $cuerpo
     */
    public function setCuerpo($cuerpo)
    {
        $this->cuerpo = $cuerpo;
    }

    /**
     * Get cuerpo
     *
     * @return text 
     */
    public function getCuerpo()
    {
        return $this->cuerpo;
    }

    /**
     * Set destinatario
     *
     * @param Cpm\JovenesBundle\Entity\Usuario $destinatario
     */
    public function setDestinatario(\Cpm\JovenesBundle\Entity\Usuario $destinatario)
    {
        $this->destinatario = $destinatario;
    }

    /**
     * Get destinatario
     *
     * @return Cpm\JovenesBundle\Entity\Usuario 
     */
    public function getDestinatario()
    {
        return $this->destinatario;
    }

    /**
     * Set emisor
     *
     * @param Cpm\JovenesBundle\Entity\Usuario $emisor
     */
    public function setEmisor(\Cpm\JovenesBundle\Entity\Usuario $emisor)
    {
        $this->emisor = $emisor;
    }

    /**
     * Get emisor
     *
     * @return Cpm\JovenesBundle\Entity\Usuario 
     */
    public function getEmisor()
    {
        return $this->emisor;
    }

    /**
     * Set email
     *
     * @param string $email
     */
    public function setEmail($email)
    {
        $this->email = $email;
    }

    /**
     * Get email
     *
     * @return string 
     */
    public function getEmail()
    {
        return $this->email;
    }
    
    public function __toString(){
    	return "{$this->fecha} Destinatario {$this->email} Asunto {$this->asunto}";
    }
    
}