<?php

namespace Cpm\JovenesBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Cpm\JovenesBundle\Entity\Usuario
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Cpm\JovenesBundle\Entity\UsuarioRepository")
 */
class Usuario
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
     * @var string $usuario
     *
     * @ORM\Column(name="usuario", type="string")
     */
    private $usuario;

    /**
     * @var string $clave
     *
     * @ORM\Column(name="clave", type="string")
     */
    private $clave;

    /**
     * @var datetime $ultimoAcceso
     *
     * @ORM\Column(name="ultimoAcceso", type="datetime")
     */
    private $ultimoAcceso;

    /**
     * @var string $dni
     *
     * @ORM\Column(name="dni", type="string")
     */
    private $dni;

    /**
     * @var string $telefono
     *
     * @ORM\Column(name="telefono", type="string")
     */
    private $telefono;

    /**
     * @var string $telefonoCelular
     *
     * @ORM\Column(name="telefonoCelular", type="string")
     */
    private $telefonoCelular;

    /**
     * @var string $email
     *
     * @ORM\Column(name="email", type="string")
     */
    private $email;

    /**
     * @var string $codigoPostal
     *
     * @ORM\Column(name="codigoPostal", type="string")
     */
    private $codigoPostal;


   /**
    *  @ORM\ManyToOne(targetEntity="Localidad")
    *  @ORM\JoinColumns({
    *   @ORM\JoinColumn(name="localidad_id", referencedColumnName="id")
    * })
    */
    private $localidad;
    
    /**
    *  @ORM\ManyToOne(targetEntity="Distrito")
    *  @ORM\JoinColumns({
    *   @ORM\JoinColumn(name="distrito_id", referencedColumnName="id")
    * })
    */
    private $distrito;

    /**
    *  @ORM\ManyToOne(targetEntity="RegionEducativa")
    *  @ORM\JoinColumns({
    *   @ORM\JoinColumn(name="region_educativa_id", referencedColumnName="id")
    * })
    */
    private $region;
    
    
    
    /**
    *  @ORM\OneToMany(targetEntity="Correo",mappedBy="destinatario")
    *  @ORM\JoinColumns({
    *   @ORM\JoinColumn(name="correo_id", referencedColumnName="id")
    * })
    */
    private $correosRecibidos;
    
    /**
    * @ORM\ManyToMany(targetEntity="Usuario", inversedBy="colaboradores")
     * @ORM\JoinTable(name="colaboradores_proyectos",
    * joinColumns={@ORM\JoinColumn(name="usuario_id", referencedColumnName="id")},
    * inverseJoinColumns={@ORM\JoinColumn(name="proyecto_id", referencedColumnName="id")}
    * )
    */
    private $categories;
    
    
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
     * Set usuario
     *
     * @param string $usuario
     */
    public function setUsuario($usuario)
    {
        $this->usuario = $usuario;
    }

    /**
     * Get usuario
     *
     * @return string 
     */
    public function getUsuario()
    {
        return $this->usuario;
    }

    /**
     * Set clave
     *
     * @param string $clave
     */
    public function setClave($clave)
    {
        $this->clave = $clave;
    }

    /**
     * Get clave
     *
     * @return string 
     */
    public function getClave()
    {
        return $this->clave;
    }

    /**
     * Set ultimoAcceso
     *
     * @param datetime $ultimoAcceso
     */
    public function setUltimoAcceso($ultimoAcceso)
    {
        $this->ultimoAcceso = $ultimoAcceso;
    }

    /**
     * Get ultimoAcceso
     *
     * @return datetime 
     */
    public function getUltimoAcceso()
    {
        return $this->ultimoAcceso;
    }

    /**
     * Set dni
     *
     * @param string $dni
     */
    public function setDni($dni)
    {
        $this->dni = $dni;
    }

    /**
     * Get dni
     *
     * @return string 
     */
    public function getDni()
    {
        return $this->dni;
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
     * Set telefonoCelular
     *
     * @param string $telefonoCelular
     */
    public function setTelefonoCelular($telefonoCelular)
    {
        $this->telefonoCelular = $telefonoCelular;
    }

    /**
     * Get telefonoCelular
     *
     * @return string 
     */
    public function getTelefonoCelular()
    {
        return $this->telefonoCelular;
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
    public function __construct()
    {
        $this->correosRecibidos = new \Doctrine\Common\Collections\ArrayCollection();
    $this->categories = new \Doctrine\Common\Collections\ArrayCollection();
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

    /**
     * Add correosRecibidos
     *
     * @param Cpm\JovenesBundle\Entity\Correo $correosRecibidos
     */
    public function addCorreo(\Cpm\JovenesBundle\Entity\Correo $correosRecibidos)
    {
        $this->correosRecibidos[] = $correosRecibidos;
    }

    /**
     * Get correosRecibidos
     *
     * @return Doctrine\Common\Collections\Collection 
     */
    public function getCorreosRecibidos()
    {
        return $this->correosRecibidos;
    }

    /**
     * Add categories
     *
     * @param Cpm\JovenesBundle\Entity\Usuario $categories
     */
    public function addUsuario(\Cpm\JovenesBundle\Entity\Usuario $categories)
    {
        $this->categories[] = $categories;
    }

    /**
     * Get categories
     *
     * @return Doctrine\Common\Collections\Collection 
     */
    public function getCategories()
    {
        return $this->categories;
    }
}