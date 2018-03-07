<?php

namespace Cpm\UserBundle\Controller;

use Symfony\Component\DependencyInjection\ContainerAware;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use FOS\UserBundle\Model\UserInterface;

class ProfileController extends ContainerAware
{
  /**
   * Edit the user . Este action lo ejecuta siempre un humano.
   */
  public function editAction()
  {

      $user = $this->container->get('security.context')->getToken()->getUser();
      if (!is_object($user) || !$user instanceof UserInterface) {
          throw new AccessDeniedException('This user does not have access to this section.');
      }
      $jym = $this->container->get('cpm_jovenes_bundle.application');
      $form = $this->container->get('fos_user.profile.form');
      $formHandler = $this->container->get('fos_user.profile.form.handler');

      $this->updateUserTimestamp($user);
      $process = $formHandler->process($user);
      if ($process) {
          $jym = $this->container->get('cpm_jovenes_bundle.application');
          if ($jym->getEtapaActual()->getNumero() == 2) {
            $this->setFlash('success', 'Sus datos ya fueron actualizados. Para continuar el proceso seleccione NUEVA INSCRIPCION');
            return new RedirectResponse($this->container->get('router')->generate('home_usuario'));
          }
          else
             $this->setFlash('fos_user_success', 'profile.flash.updated');


          return new RedirectResponse($this->container->get('router')->generate('fos_user_profile_show'));
      }

      return $this->container->get('templating')->renderResponse(
          'FOSUserBundle:Profile:edit.html.'.$this->container->getParameter('fos_user.template.engine'),
          array('form' => $form->createView(), 'theme' => $this->container->getParameter('fos_user.template.theme'))
      );
  }

 private function updateUserTimestamp($user) {

   $referer = $this->container->get('request')
                ->headers
                ->get('referer');
    if (strpos('login',$referer) === FALSE)  //no viene desde el login
      $user->setHumanUpdatedAt(new \DateTime());

 }
  /**
   * Show the user
   */
  public function showAction()
  {
      $user = $this->container->get('security.context')->getToken()->getUser();
      if (!is_object($user) || !$user instanceof UserInterface) {
          throw new AccessDeniedException('This user does not have access to this section.');
      }

      return $this->container->get('templating')->renderResponse('FOSUserBundle:Profile:show.html.'.$this->container->getParameter('fos_user.template.engine'), array('user' => $user));
  }

  protected function setFlash($action, $value)
  {
      $this->container->get('session')->setFlash($action, $value);
  }
}
