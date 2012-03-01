<?php

namespace Cpm\JovenesBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Cpm\JovenesBundle\Entity\Plantilla
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Cpm\JovenesBundle\Entity\PlantillaRepository")
 */
class Plantilla
{
	
	const REGISTRO_USUARIO = "registro_usuario";
	
    /**
     * @var integer $id
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string $codigo
     *
     * @ORM\Column(name="codigo", type="string")
     */
    private $codigo;

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
     * @var boolean $puedeBorrarse
     *
     * @ORM\Column(name="puedeBorrarse", type="boolean")
     */
    private $puedeBorrarse;


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
     * Set codigo
     *
     * @param string $codigo
     */
    public function setCodigo($codigo)
    {
        $this->codigo = $codigo;
    }

    /**
     * Get codigo
     *
     * @return string 
     */
    public function getCodigo()
    {
        return $this->codigo;
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
     * Set puedeBorrarse
     *
     * @param boolean $puedeBorrarse
     */
    public function setPuedeBorrarse($puedeBorrarse)
    {
        $this->puedeBorrarse = $puedeBorrarse;
    }

    /**
     * Get puedeBorrarse
     *
     * @return boolean 
     */
    public function getPuedeBorrarse()
    {
        return $this->puedeBorrarse;
    }
    
    public function __toString(){
    	return $this->codigo;
    }
    
}