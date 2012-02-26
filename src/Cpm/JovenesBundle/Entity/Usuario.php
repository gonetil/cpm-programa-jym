<?php

namespace Cpm\JovenesBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\AdvancedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * Cpm\JovenesBundle\Entity\Usuario
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Cpm\JovenesBundle\Entity\UsuarioRepository")
 */
class Usuario implements AdvancedUserInterface, \Serializable
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
    private $id;

    /**
     * @var string $clave
     *
     * @ORM\Column(name="clave", type="string", nullable=true)
     */
    private $clave;

    /**
     * @var string $salt
     *
     * @ORM\Column(name="salt", type="string", nullable=true)
     */
    private $salt;
    
    /**
     * @var datetime $ultimoAcceso
     *
     * @ORM\Column(name="ultimoAcceso", type="datetime", nullable=true)
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
     * @ORM\Column(name="telefono", type="string", nullable=true)
     */
    private $telefono;

    /**
     * @var string $telefonoCelular
     *
     * @ORM\Column(name="telefonoCelular", type="string", nullable=true)
     */
    private $telefonoCelular;

    /**
     * @var string $email
     *
     * @ORM\Column(name="email", type="string", unique=true)
     */
    private $email;

    /**
     * @var string $codigoPostal
     *
     * @ORM\Column(name="codigoPostal", type="string", nullable=true)
     */
    private $codigoPostal;


   /**
    * 
    * @ORM\ManyToOne(targetEntity="Localidad")
    *  @ORM\JoinColumns({
    *   @ORM\JoinColumn(name="localidad_id", referencedColumnName="id")
    * })
    */
    private $localidad;
    
    /**
    * @ORM\ManyToOne(targetEntity="Distrito")
    *  @ORM\JoinColumns({
    *   @ORM\JoinColumn(name="distrito_id", referencedColumnName="id")
    * })
    */
    private $distrito;

    /**
    * @ORM\ManyToOne(targetEntity="RegionEducativa")
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
    
    /*
    * @ORM\ManyToMany(targetEntity="Usuario", inversedBy="colaboradores")
     * @ORM\JoinTable(name="colaboradores_proyectos",
    * joinColumns={@ORM\JoinColumn(name="usuario_id", referencedColumnName="id")},
    * inverseJoinColumns={@ORM\JoinColumn(name="proyecto_id", referencedColumnName="id")}
    * )
    
    private $categories;
*/

    /**
     * @var bool $estaHabilitado
     *
     * @ORM\Column(name="esta_habilitado", type="boolean" )
     */
    private $estaHabilitado;

    /**
     * @var bool $esAdmin
     *
     * @ORM\Column(name="es_admin", type="boolean" )
     */
    private $esAdmin;
    
    
    public function __construct()
    {
        $this->esAdmin = false;
        $this->estaHabilitado = true;
        $this->salt = base_convert(sha1(uniqid(mt_rand(), true)), 16, 36);
        $this->correosRecibidos = new \Doctrine\Common\Collections\ArrayCollection();
		//$this->categories = new \Doctrine\Common\Collections\ArrayCollection();
        
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
     * Set estaHabilitado
     *
     * @param boolean $estaHabilitado
     */
    public function setEstaHabilitado($estaHabilitado)
    {
        $this->estaHabilitado = $estaHabilitado;
    }

    /**
     * Get estaHabilitado
     *
     * @return boolean 
     */
    public function getEstaHabilitado()
    {
        return $this->estaHabilitado;
    }
    
    /**
     * Set esAdmin
     *
     * @param boolean $esAdmin
     */
    public function setEsAdmin($esAdmin)
    {
        $this->esAdmin = $esAdmin;
    }

    /**
     * Get esAdmin
     *
     * @return boolean 
     */
    public function getEsAdmin()
    {
        return $this->esAdmin;
    }
        /********************************************************************************/
    /********************* Metodos de userInterface     *****************************/
    /********************************************************************************/
    
     public function getRoles()
    {
    	$roles = array(Usuario::ROL_USUARIO);
    	if ($this->getEsAdmin())
    		$roles[]=Usuario::ROL_ADMIN;
        return $roles;
    }

    public function equals(UserInterface $user)
    {
        return $user->getUsername() === $this->email;
    }

    public function eraseCredentials()
    {
    	//$this->clave ='';
    }

    public function getUsername()
    {
        return $this->email;
    }

    public function getSalt()
    {
        return $this->salt;
    }

    public function getPassword()
    {
        return $this->getClave();
    }
    
    public function isAccountNonExpired()
    {
        return true;
    }

    public function isAccountNonLocked()
    {
        return !empty($this->clave);;
    }

    public function isCredentialsNonExpired()
    {
        return true;
    }

    public function isEnabled()
    {
        return $this->getEstaHabilitado();
    }

    /**
     * Set salt
     *
     * @param string $salt
     */
    public function setSalt($salt)
    {
        $this->salt = $salt;
    }


  /**
     * Serializes the user.
     * The serialized data have to contain the fields used byt the equals method.
     * @return string
     */
    public function serialize()
    {
        return serialize(array(
            $this->id,
            $this->clave,
            $this->salt,
            $this->email,
            $this->estaHabilitado,
        ));
    }

    /**
     * Unserializes the user.
     *
     * @return string
     */
    public function unserialize($serialized)
    {
        list(
            $this->id,
            $this->clave,
            $this->salt,
            $this->email,
            $this->estaHabilitado
        ) = unserialize($serialized);
    }
}