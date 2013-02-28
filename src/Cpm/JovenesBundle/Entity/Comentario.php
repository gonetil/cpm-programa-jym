<?php
namespace Cpm\JovenesBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Cpm\JovenesBundle\Entity\Comentario
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Cpm\JovenesBundle\Entity\ComentarioRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class Comentario
{
   
    const COMENTARIO    = 'comentario';
    const TAREA 		= 'tarea';
    const POSTIT   		= 'postit';
   
    /**
     * @var integer $id
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var datetime $fecha_creado
     *
     * @ORM\Column(name="fecha_creado", type="datetime", nullable="true")
     */
    private $fecha_creado;

    /**
     * @var datetime $fecha_modificado
     *
     * @ORM\Column(name="fecha_modificado", type="datetime", nullable="true")
     */
    private $fecha_modificado;
    
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
     *  @ORM\JoinColumn(name="autor_id", referencedColumnName="id", onDelete="CASCADE")
     */
    private $autor;

    
    /**
    *  @ORM\ManyToOne(targetEntity="Proyecto" )
    *  @ORM\JoinColumn(name="proyecto_id", referencedColumnName="id", nullable="false")
    */
    private $proyecto;
    
    
    /**
     * @var boolean $estado
     *
     * @ORM\Column(name="estado", type="boolean")
     */
    private $estado;  //post-it leido/no leido ; tarea realizada/sin realizar
    
    /**
     * @var string $tipo
     *
     * @ORM\Column(name="tipo", type="string")
     */
	private $tipo;
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
     * Set fecha_creado
     *
     * @param datetime $fecha_creado
     */
    public function setFechaCreado(\datetime $fecha)
    {
        $this->fecha_creado = $fecha;
    }

    /**
     * Get fecha_creado
     *
     * @return datetime 
     */
    public function getFechaCreado()
    {
        return $this->fecha_creado;
    }

    /**
     * Set fecha_modificado
     *
     * @param datetime $fecha_modificado
     */
    public function setFechaModificado(\datetime $fecha)
    {
        $this->fecha_modificado = $fecha;
    }

    /**
     * Get fecha_modificado
     *
     * @return datetime 
     */
    public function getFechaModificado()
    {
        return $this->fecha_modificado;
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
     * Set autor
     *
     * @param Cpm\JovenesBundle\Entity\Usuario $autor
     */
    public function setAutor(\Cpm\JovenesBundle\Entity\Usuario $autor)
    {
        $this->autor = $autor;
    }

    /**
     * Get autor
     *
     * @return Cpm\JovenesBundle\Entity\Usuario 
     */
    public function getAutor()
    {
        return $this->autor;
    }
    
    public function getProyecto() { 
    	return $this->proyecto;
    }
    
    public function setProyecto($proyecto) {
    	$this->proyecto = $proyecto;
    }
    
    public function getTipo()
    {
    	return $this->tipo;
    }
    
    public function setTipo($tipo)
    {
    	$this->tipo = $tipo;
    }

	public function getEstado()
	{
		return $this->estado;
	}
	
	public function setEstado($estado)
	{
		$this->estado = $estado;
	}
	
    public function __toString() 
    { 
    	return "{$this->asunto}";
    }   

	 /**
     *
     * @ORM\PrePersist
     * @ORM\PreUpdate
     */
    public function updatedTimestamps()
    {
        $this->setFechaModificado(new \DateTime(date('Y-m-d H:i:s')));

        if($this->getFechaCreado() == null)
        {
            $this->setFechaCreado(new \DateTime(date('Y-m-d H:i:s')));
        }
    }
    
    public function __construct($asunto,$cuerpo,$autor,$proyecto,$tipo,$estado) {
    	$this->estado = $estado;
    	$this->asunto = $asunto;
    	$this->cuerpo = $cuerpo;
    	$this->autor = $autor;
    	$this->proyecto = $proyecto;
    	$this->tipo = $tipo;
    }

}