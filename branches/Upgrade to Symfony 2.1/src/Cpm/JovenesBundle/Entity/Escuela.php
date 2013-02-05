<?php

namespace Cpm\JovenesBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Cpm\JovenesBundle\Entity\Escuela
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Cpm\JovenesBundle\Entity\EscuelaRepository")
 */
class Escuela
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
    *  @ORM\ManyToOne(targetEntity="TipoInstitucion")
    *  @ORM\JoinColumns({
    *   @ORM\JoinColumn(name="tipoInstitucion_id", referencedColumnName="id", nullable="true")
    * })
    */
    private $tipoInstitucion;

    /**
    * @var string $otroTipoInstitucion
    *
    * @ORM\Column(name="otroTipoInstitucion", type="string", nullable="true")
    *  
    */
    private $otroTipoInstitucion;
    
    
    
    /**
    *  @ORM\ManyToOne(targetEntity="TipoEscuela")
    *  @ORM\JoinColumns({
    * 	  @ORM\JoinColumn(name="tipoEscuela_id", referencedColumnName="id", nullable="true")
    *  })

    */
        private $tipoEscuela;

        /**
        * @var string $nombre
        *
        * @ORM\Column(name="nombre", type="string", nullable="true")
        */
        private $nombre;
        
        /**
         * @var integer $numero
         * 
         * @ORM\Column (name="numero", type="integer", nullable="true")
         * @Assert\Regex(pattern="/^[\s0-9]*$/", message="El número de escuela solo puede contener números y espacios")
         */
        private $numero;
        

    /**
     * @var string $email
     * @Assert\Email(message = "La dirección de correo de la escuela no es válida.", checkMX = false)
     * @ORM\Column(name="email", type="string", nullable="true")
     */
    private $email;

    /**
     * @var string $telefono
     *
     * @ORM\Column(name="telefono", type="string", nullable="true")
     * @Assert\Regex(pattern="/^[\s0-9]*$/", message="El telefono solo puede contener números y espacios")
     */
    private $telefono;

    /**
     * @var string $domicilio
     *
     * @ORM\Column(name="domicilio", type="string")
     */
    private $domicilio;

    /**
     * @var string $codigoPostal
     *
     * @ORM\Column(name="codigoPostal", type="string", length=15)
     */
    private $codigoPostal;

    /**
     * @var string $director
     *
     * @ORM\Column(name="director", type="string")
     */
    private $director;

    
    
    /**
    *  @ORM\ManyToOne(targetEntity="Localidad")
    *  @ORM\JoinColumns({
    *   @ORM\JoinColumn(name="localidad_id", referencedColumnName="id")
    * })
    */
    private $localidad;
    
    
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
     * Set otroTipoInstitucion
     *
     * @param string $otroTipoInstitucion
     */
    public function setOtroTipoInstitucion($otroTipoInstitucion)
    {
        $this->otroTipoInstitucion = $otroTipoInstitucion;
    }

    /**
     * Get otroTipoInstitucion
     *
     * @return string 
     */
    public function getOtroTipoInstitucion()
    {
        return $this->otroTipoInstitucion;
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

    /**
     * Set telefono
     *
     * @param string $telefono
     */
    public function setTelefono($telefono)
    {
        $this->telefono = $telefono;
    }

    /**
     * Get telefono
     *
     * @return string 
     */
    public function getTelefono()
    {
        return $this->telefono;
    }

    /**
     * Set domicilio
     *
     * @param string $domicilio
     */
    public function setDomicilio($domicilio)
    {
        $this->domicilio = $domicilio;
    }

    /**
     * Get domicilio
     *
     * @return string 
     */
    public function getDomicilio()
    {
        return $this->domicilio;
    }

    /**
     * Set codigoPostal
     *
     * @param string $codigoPostal
     */
    public function setCodigoPostal($codigoPostal)
    {
        $this->codigoPostal = $codigoPostal;
    }

    /**
     * Get codigoPostal
     *
     * @return string 
     */
    public function getCodigoPostal()
    {
        return $this->codigoPostal;
    }

    /**
     * Set director
     *
     * @param string $director
     */
    public function setDirector($director)
    {
        $this->director = $director;
    }

    /**
     * Get director
     *
     * @return string 
     */
    public function getDirector()
    {
        return $this->director;
    }


    /**
     * Set tipoInstitucion
     *
     * @param Cpm\JovenesBundle\Entity\TipoInstitucion $tipoInstitucion
     */
    public function setTipoInstitucion($tipoInstitucion)
    {
        $this->tipoInstitucion = $tipoInstitucion;
    }

    /**
     * Get tipoInstitucion
     *
     * @return Cpm\JovenesBundle\Entity\TipoInstitucion 
     */
    public function getTipoInstitucion()
    {
        return $this->tipoInstitucion;
    }

    /**
     * Set tipoEscuela
     *
     * @param Cpm\JovenesBundle\Entity\TipoEscuela $tipoEscuela
     */
    public function setTipoEscuela($tipoEscuela)
    {
        $this->tipoEscuela = $tipoEscuela;
    }

    /**
     * Get tipoEscuela
     *
     * @return Cpm\JovenesBundle\Entity\TipoEscuela 
     */
    public function getTipoEscuela()
    {
        return $this->tipoEscuela;
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
     * Set temaPrincipal
     *
     * @param Cpm\JovenesBundle\Entity\Tema $temaPrincipal
     */
    public function setTemaPrincipal($temaPrincipal)
    {
        $this->temaPrincipal = $temaPrincipal;
    }

    /**
     * Get temaPrincipal
     *
     * @return Cpm\JovenesBundle\Entity\Tema 
     */
    public function getTemaPrincipal()
    {
        return $this->temaPrincipal;
    }

    /**
     * Set produccionFinal
     *
     * @param Cpm\JovenesBundle\Entity\Produccion $produccionFinal
     */
    public function setProduccionFinal($produccionFinal)
    {
        $this->produccionFinal = $produccionFinal;
    }

    /**
     * Get produccionFinal
     *
     * @return Cpm\JovenesBundle\Entity\Produccion 
     */
    public function getProduccionFinal()
    {
        return $this->produccionFinal;
    }

    /**
     * Set localidad
     *
     * @param Cpm\JovenesBundle\Entity\Localidad $localidad
     */
    public function setLocalidad(\Cpm\JovenesBundle\Entity\Localidad $localidad)
    {
        $this->localidad = $localidad;
    }

    /**
     * Get localidad
     *
     * @return Cpm\JovenesBundle\Entity\Localidad 
     */
    public function getLocalidad()
    {
        return $this->localidad;
    }

    /**
     * Set distrito
     *
     * @param Cpm\JovenesBundle\Entity\Distrito $distrito
   */
    public function setDistrito($distrito)
    {
    }  

    /**
     * Get distrito
     *
     * @return Cpm\JovenesBundle\Entity\Distrito 
     */
    public function getDistrito()
    {
    	return $this->localidad->getDistrito();

    }

    /**
     * Set region
     *
     * @param Cpm\JovenesBundle\Entity\RegionEducativa $region
  */  
    public function setRegion($region)
    {
    }
 
    /**
     * Get region
     *
     * @return Cpm\JovenesBundle\Entity\RegionEducativa 
     */
    public function getRegion()
    {
        return $this->getDistrito()->getRegion();
    }
    
    public function __toString() { 
 		return "{$this->tipoEscuela} {$this->nombre}";   	
    }

    /**
     * Set numero
     *
     * @param integer $numero
     */
    public function setNumero($numero)
    {
        $this->numero = $numero;
    }

    /**
     * Get numero
     *
     * @return integer 
     */
    public function getNumero()
    {
        return $this->numero;
    }
    

}