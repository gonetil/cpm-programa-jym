<?php

namespace Cpm\JovenesBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

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
     * @Assert\Email(message = "La dirección de correo no es válida.", checkMX = false)
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
     *  @ORM\ManyToOne(targetEntity="Usuario" )
     *  @ORM\JoinColumn(name="destinatario_id", referencedColumnName="id", nullable=true, onDelete="SET NULL")
     */
    private $destinatario;
    
    /**
     *  @ORM\ManyToOne(targetEntity="Usuario")
     *  @ORM\JoinColumn(name="emisor_id", referencedColumnName="id", nullable=true, onDelete="SET NULL")
     */
    private $emisor;

    
    /**
    *  @ORM\ManyToOne(targetEntity="Proyecto" )
    *  @ORM\JoinColumn(name="proyecto_id", referencedColumnName="id", nullable=true, onDelete="SET NULL")
    */
    private $proyecto;
 


	/**
	 * @var Cpm\JovenesBundle\Entity\Archivo $archivos
	 * @ORM\OneToMany(targetEntity="Archivo", mappedBy="correo",cascade={"persist"})
	 */
     private $archivos;
    

    
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
        $this->email = $destinatario->getEmail();
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
    
    public function getProyecto() { 
    	return $this->proyecto;
    }
    
    public function setProyecto($proyecto) {
    	$this->proyecto = $proyecto;
    }
    
 	
 	public function clonar($isFullClone){
 		$copia = new Correo();
 		$copia->asunto=$this->asunto;
	    $copia->cuerpo=$this->cuerpo;
	    $copia->archivos = $this->archivos;
	    if ($isFullClone){
		//$copia->emisor=$this->emisor; el emisor depende de la sesión
		   // $copia->fecha=$this->fecha;
		    $copia->email=$this->email;
		    $copia->destinatario=$this->destinatario;
			$copia->proyecto=$this->proyecto;
		}
		return $copia;
	 }
 
 
    public function __toString() 
    { 
    	return "Correo {$this->id} {$this->email}";
    }   

    public function __toString2(){
    	return "{$this->fecha} Destinatario {$this->email} Asunto {$this->asunto}";
    }
    
    public function getArchivos() {
    	return $this->archivos;
    }
    
    public function setArchivos($archivos) {
    	$this->archivos = $archivos;
    }
    
    public function addArchivo(\Cpm\JovenesBundle\Entity\Archivo $archivo) {
    	$this->archivos[] = $archivo;
    }
}