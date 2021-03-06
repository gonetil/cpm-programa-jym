<?php
//LOS TEMAS SON LOS EJES TEMATICOS... y LOS EJES SON EN REALIDAD LAS AREAS DE REFERENCIA. Genial!
namespace Cpm\JovenesBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Cpm\JovenesBundle\Entity\Tema
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Cpm\JovenesBundle\Entity\TemaRepository")
 */
class Tema
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
     * @var string $nombre
     *
     * @ORM\Column(name="nombre", type="string")
     */
    private $nombre;

    /**
     * @var boolean $anulado
     *
     * @ORM\Column(name="anulado", type="boolean")
     */
    private $anulado;



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
     * Set nombre
     *
     * @param string $nombre
     */
    public function setNombre($nombre)
    {
        $this->nombre = $nombre;
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
     * Set anulado
     *
     * @param boolean $anulado
     */
    public function setAnulado($anulado)
    {
        $this->anulado = $anulado;
    }


    /**
     * Get anulado
     *
     * @return boolean 
     */
    public function getAnulado()
    {
        return $this->anulado;
    }
    
    public function __toString()
    {
    	return $this->nombre;
    }
    
    public function toArray($recursive_depth) 
    {
		if ($recursive_depth == 0)
    		return $this->getId();
    	
    	return array(
			 		'id' => $this->id ,
			    	'nombre' => $this->nombre,
			  );
    }
    
    public function equals($other)
    {
    	if ($other instanceof Tema)
        	return $this->getId() == $other->getId();
        else
        	return false;
    }    
    
}