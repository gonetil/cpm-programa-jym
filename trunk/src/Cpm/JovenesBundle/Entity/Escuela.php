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
    */
    private $tipoInstitucion;

    /**
    * @var string $otroTipoInstitucion
    *
    * @ORM\Column(name="otroTipoInstitucion", type="string")
    *  
    */
    private $otroTipoInstitucion;
    
    
    
    /**
    *  @ORM\ManyToOne(targetEntity="TipoEscuela")
    */
        private $tipoEscuela;

        /**
        * @var string $nombre
        *
        * @ORM\Column(name="nombre", type="string")
        */
        
        private $nombre;
        
    /**
    * @var string $deQueSeTrata
    *
    * @ORM\Column(name="de_que_se_trata", type="text")
    */
        
    private $deQueSeTrata;
    
    /**
    * @var string $motivoRealizacion
    *
    * @ORM\Column(name="motivo_realizacion", type="text")
    */
    
    private $motivoRealizacion;
    
    /**
    * @var string $impactoBuscado
    *
    * @ORM\Column(name="impacto_buscado", type="text")
    */
    
    private $impactoBuscado;

    /**
     * @var string $email
     *
     * @ORM\Column(name="email", type="string")
     */
    private $email;

    /**
     * @var string $telefono
     *
     * @ORM\Column(name="telefono", type="string")
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
     * Set deQueSeTrata
     *
     * @param text $deQueSeTrata
     */
    public function setDeQueSeTrata($deQueSeTrata)
    {
        $this->deQueSeTrata = $deQueSeTrata;
    }

    /**
     * Get deQueSeTrata
     *
     * @return text 
     */
    public function getDeQueSeTrata()
    {
        return $this->deQueSeTrata;
    }

    /**
     * Set motivoRealizacion
     *
     * @param text $motivoRealizacion
     */
    public function setMotivoRealizacion($motivoRealizacion)
    {
        $this->motivoRealizacion = $motivoRealizacion;
    }

    /**
     * Get motivoRealizacion
     *
     * @return text 
     */
    public function getMotivoRealizacion()
    {
        return $this->motivoRealizacion;
    }

    /**
     * Set impactoBuscado
     *
     * @param text $impactoBuscado
     */
    public function setImpactoBuscado($impactoBuscado)
    {
        $this->impactoBuscado = $impactoBuscado;
    }

    /**
     * Get impactoBuscado
     *
     * @return text 
     */
    public function getImpactoBuscado()
    {
        return $this->impactoBuscado;
    }

    /**
     * Set tipoInstitucion
     *
     * @param Cpm\JovenesBundle\Entity\TipoInstitucion $tipoInstitucion
     */
    public function setTipoInstitucion(\Cpm\JovenesBundle\Entity\TipoInstitucion $tipoInstitucion)
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
    public function setTipoEscuela(\Cpm\JovenesBundle\Entity\TipoEscuela $tipoEscuela)
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
     * Set temaPrincipal
     *
     * @param Cpm\JovenesBundle\Entity\Tema $temaPrincipal
     */
    public function setTemaPrincipal(\Cpm\JovenesBundle\Entity\Tema $temaPrincipal)
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
    public function setProduccionFinal(\Cpm\JovenesBundle\Entity\Produccion $produccionFinal)
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
    public function setDistrito(\Cpm\JovenesBundle\Entity\Distrito $distrito)
    {
        $this->distrito = $distrito;
    }

    /**
     * Get distrito
     *
     * @return Cpm\JovenesBundle\Entity\Distrito 
     */
    public function getDistrito()
    {
        return $this->distrito;
    }

    /**
     * Set region
     *
     * @param Cpm\JovenesBundle\Entity\RegionEducativa $region
     */
    public function setRegion(\Cpm\JovenesBundle\Entity\RegionEducativa $region)
    {
        $this->region = $region;
    }

    /**
     * Get region
     *
     * @return Cpm\JovenesBundle\Entity\RegionEducativa 
     */
    public function getRegion()
    {
        return $this->region;
    }
    
    public function __toString() { 
 		return "{$this->tipoEscuela} {$this->nombre}";   	
    }
}