<?php
/**
* Este listener se utiliza para capturar el LoginSuccess de los usuarios.
* Durante la etapa 2 (Inscripcion de propuestas) los usuarios deben ir a la opcion de
* editar perfil una vez que iniciaron sesion
*
*/

namespace Cpm\JovenesBundle\EventListener;

use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationSuccessHandlerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Security\Http\Event\InteractiveLoginEvent;
use Symfony\Component\Routing\Router;
use Symfony\Component\Security\Core\SecurityContext;
use Cpm\JovenesBundle\Service\JYM;

class MyAuthenticationSuccessHandler implements AuthenticationSuccessHandlerInterface
{
    private $router;
    private $security;
    private $jym;
    public function __construct(Router $router, SecurityContext $security, JYM $jym)
  	{
  		$this->router = $router;
  		$this->security = $security;
      $this->jym = $jym;
  	}
    public function onSecurityInteractiveLogin(InteractiveLoginEvent $event)
    {

      $token = $event->getAuthenticationToken();
      $request = $event->getRequest();

      $this->onAuthenticationSuccess($request, $token);
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token)
    {
        $route = '';
        $etapa = $this->jym->getEtapaActual();
        if ( $this->security->isGranted('ROLE_ADMIN') || $this->security->isGranted('ROLE_SUPER_ADMIN') )
        {
          $route = $this->router->generate('dashboard');
        } elseif ($this->security->isGranted('ROLE_USER'))
        {
              if ( $etapa->getNumero() == 2 ) {
                  $request->getSession()->setFlash('info', "Durante la etapa de inscripción de propuestas del programa Jóvenes y Memoria, solicitamos a los usuarios que actualicen los datos de sus perfiles personales");
                  $route= $this->router->generate('fos_user_profile_edit');
                }
               else
                $route = $this->router->generate('home_usuario');
        }
        return new RedirectResponse($route);
    }
}
