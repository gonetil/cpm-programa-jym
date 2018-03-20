<?php

namespace Cpm\UserBundle\Controller;

use Symfony\Component\DependencyInjection\ContainerAware;
use Symfony\Component\Security\Core\SecurityContext;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

use FOS\UserBundle\Controller\ResettingController as BaseController;

class ResettingController extends BaseController
{

  /**
   * Reset user password
   */
  public function resetAction($token)
  {

      $user = $this->container->get('fos_user.user_manager')->findUserByConfirmationToken($token);

      if (null === $user) {
          throw new NotFoundHttpException(sprintf('The user with "confirmation token" does not exist for value "%s"', $token));
      }

      if (!$user->isPasswordRequestNonExpired($this->container->getParameter('fos_user.resetting.token_ttl'))) {
          return new RedirectResponse($this->container->get('router')->generate('fos_user_resetting_request'));
      }

      $form = $this->container->get('fos_user.resetting.form');
      $formHandler = $this->container->get('fos_user.resetting.form.handler');
      $process = $formHandler->process($user);

      if ($process) {
          $this->authenticateUser($user);

          $this->setFlash('success', 'La nueva contraseña fue registrada satisfactoriamente ');

          return new RedirectResponse($this->container->get('router')->generate('home_usuario'));
      }

      return $this->container->get('templating')->renderResponse('FOSUserBundle:Resetting:reset.html.'.$this->getEngine(), array(
          'token' => $token,
          'form' => $form->createView(),
          'theme' => $this->container->getParameter('fos_user.template.theme'),
      ));
  }
}
