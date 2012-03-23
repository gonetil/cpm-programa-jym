<?php

namespace Cpm\JovenesBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\AdvancedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;

use FOS\UserBundle\Entity\User as BaseUser;
/**
 * Cpm\JovenesBundle\Entity\Usuario
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Cpm\JovenesBundle\Entity\UsuarioRepository")
 */
class Usuario extends BaseUser //implements AdvancedUserInterface, \Serializable
{
	const ROL_USUARIO = 'ROLE_USER';
	const ROL_ADMIN = 'ROLE_ADMIN';
	
    /**
     * @var integer $id
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var string $dni
     *
     * @ORM\Column(name="dni", type="string", nullable=true)
     * @Assert\Regex(pattern="/^[0-9]*$/", message="El dni solo puede contener números")
     * @Assert\NotBlank(message="Debe completar el DNI (solo con números)", groups={"Registration", "Profile", "Administracion"})
     * @Assert\Min(limit="10000", message="El dni ingresado no es válido", groups={"Registration", "Profile", "Administracion"})
     */
    private $dni;

    /**
    * @var string $apellido
    *
    * @ORM\Column(name="apellido", type="string")
    * @Assert\NotBlank(message="Debe completar el apellido")
    * @Assert\MinLength(limit="2", message="El nombre es muy corto, es un apellido de verdad?")
    * @Assert\MaxLength(limit="255", message="El apellido es muy largo.")
    */
    private $apellido;
    
    /**
     * @var string $nombre
     *
     * @ORM\Column(type="string", length="255")
     *
     * @Assert\NotBlank(message="Falta el campo nombre")
     * @Assert\MinLength(limit="3", message="El nombre es muy corto, es un nombre de verdad?")
     * @Assert\MaxLength(limit="255", message="El nombre es muy largo.")
     */
   private $nombre;
   
    /**
     * @var string $telefono
     *
     * @ORM\Column(name="telefono", type="string", nullable=true)
     * @Assert\Regex(pattern="/^[\s0-9]+$/", message="El teléfono solo puede contener números y espacios")
     */
    private $telefono;

    /**
     * @var string $telefonoCelular
     *
     * @ORM\Column(name="telefonoCelular", type="string", nullable=true)
     * @Assert\Regex(pattern="/^[\s0-9]*$/", message="El teléfono celular solo puede contener números y espacios")
     * @Assert\NotBlank(message="Ingrese su teléfono celular", groups={"Registration", "Profile"})
     */
    private $telefonoCelular;

    /**
     * @var string $codigoPostal
     * @ORM\Column(name="codigoPostal", type="string", nullable=true)
     * @Assert\NotBlank(message="Ingrese el código postal", groups={"Registration", "Profile"})
     */
    private $codigoPostal;


   /**
    * 
    * @ORM\ManyToOne(targetEntity="Localidad")
    *  @ORM\JoinColumns({
    *   @ORM\JoinColumn(name="localidad_id", referencedColumnName="id", nullable = "true")
    * })
    */
    private $localidad;
        
    private $distrito;
    /**
    *  @ORM\OneToMany(targetEntity="Correo",mappedBy="destinatario")
    *  @ORM\JoinColumns({
    *   @ORM\JoinColumn(name="correo_id", referencedColumnName="id", nullable=true)
    * })
    */
    private $correosRecibidos;
    
    /**
     * @ORM\ManyToMany(targetEntity="Proyecto", mappedBy="colaboradores")
     **/
    private $proyectosColaborados;

	/**
     * @ORM\OneToMany(targetEntity="Proyecto", mappedBy="coordinador")
     **/
    private $proyectosCoordinados;
    
    public function __construct()
    {
    	parent::__construct();
        $this->correosRecibidos = new \Doctrine\Common\Collections\ArrayCollection();
        $this->proyectosCoordinados = new \Doctrine\Common\Collections\ArrayCollection();
        $this->proyectosColaborados = new \Doctrine\Common\Collections\ArrayCollection();
    }
    
    public function getNombreComleto(){
    	return $this->apellido.", ".$this->nombre;
    }
    /**
     * Get id
     *$email
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    public function setId($id) 
    {
    	$this->id = $id;
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

	public function setEmail($email)
    {
        parent::setEmail($email);
        parent::setUsername($email);
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
    * Set apellido
    *
    * @param string $apellido
    */
    public function setApellido($apellido)
    {
    	$this->apellido = ucwords(strtolower($apellido)) ;
    }
    
    /**
     * Get apellido
     *
     * @return string
     */
    public function getApellido()
    {
    	return $this->apellido;
    }
    
    
    /**
    * Set nombre
    *
    * @param string $nombre
    */
    public function setNombre($nombre)
    {
    	$this->nombre = ucwords(strtolower($nombre));
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
	
    public function getDistrito() {
	 if ($this->localidad)
    	return $this->localidad->getDistrito();
	 else return "";
    }
    /**
     * esta funcion no hace nada
     */
    public function setDistrito($distrito) {
    	$this->distrito = $distrito;
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
     * Get proyectosColaborados
     *
     * @return Doctrine\Common\Collections\Collection 
     */
    public function getProyectosColaborados()
    {
        return $this->proyectosColaborados;
    }
    
    public function __toString() {
    	return "{$this->nombre} {$this->apellido} <{$this->email}>";
    }

    /**
     * Add proyectosColaborados
     *
     * @param Cpm\JovenesBundle\Entity\Proyecto $proyectosColaborados
     */
    public function addProyecto(\Cpm\JovenesBundle\Entity\Proyecto $proyectosColaborados)
    {
        $this->proyectosColaborados[] = $proyectosColaborados;
    }

    /**
     * Get proyectosCoordinados
     *
     * @return Doctrine\Common\Collections\Collection 
     */
    public function getProyectosCoordinados()
    {
        return $this->proyectosCoordinados;
    }
}