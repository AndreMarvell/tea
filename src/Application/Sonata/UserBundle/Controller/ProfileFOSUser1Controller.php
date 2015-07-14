<?php

/*
 * This file is part of the FOSUserBundle package.
 *
 * (c) FriendsOfSymfony <http://friendsofsymfony.github.com/>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Application\Sonata\UserBundle\Controller;

use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\HttpFoundation\Response;
use FOS\UserBundle\Model\UserInterface;
use Sonata\UserBundle\Controller\ProfileFOSUser1Controller as BaseController;
use Symfony\Component\DependencyInjection\ContainerAware;
use Symfony\Component\HttpFoundation\RedirectResponse;
use FOS\MessageBundle\Provider\ProviderInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * This class is inspired from the FOS Profile Controller, except :
 *   - only twig is supported
 *   - separation of the user authentication form with the profile form
 */
class ProfileFOSUser1Controller extends BaseController
{
    /**
     * @return Response
     *
     * @throws AccessDeniedException
     */
    public function showAction()
    {
        $user = $this->container->get('security.context')->getToken()->getUser();
        if (!is_object($user) || !$user instanceof UserInterface) {
            throw new AccessDeniedException('This user does not have access to this section.');
        }
        
        $videos         = $this->getDoctrine()->getRepository("TeaCampusCommonBundle:Video")->findBy(array('author'=>$user),array('date' => 'DESC'));;
        $projects       = $this->getDoctrine()->getRepository("TeaCampusCommonBundle:Projet")->findBy(array('author'=>$user),array('date' => 'DESC'));;
        $provider       = $this->container->get('fos_message.provider');
        $threads        = $provider->getInboxThreads();
        $threads_send   = $provider->getSentThreads();
        
        $form = $this->container->get('fos_message.new_thread_form.factory')->create();
        $formHandler = $this->container->get('fos_message.new_thread_form.handler');

        if ($message = $formHandler->process($form)) {
            return new RedirectResponse($this->container->get('router')->generate('fos_message_thread_view', array(
                'threadId' => $message->getThread()->getId()
            )));
        }
        
        return $this->render('SonataUserBundle:Profile:show.html.twig', array(
            'user'   => $user,
            'threads' => $threads,
            'threads_send' => $threads_send,
            'form' => $form->createView(),
            'data' => $form->getData(),
            'projects' => $projects,
            'videos' => $videos,
            'blocks' => $this->container->getParameter('sonata.user.configuration.profile_blocks')
        ));
    }
    
    /**
     * @return Response
     *
     * @throws AccessDeniedException
     */
    public function otherAction($id)
    {
        $currentUser = $this->container->get('security.context')->getToken()->getUser();
        $user = $this->getDoctrine()->getRepository("ApplicationSonataUserBundle:User")->find($id);
        if (!is_object($user) || !$user instanceof UserInterface) {
            throw new NotFoundHttpException('This user does not have access to this section.');
        }elseif (is_object($currentUser) && $currentUser instanceof UserInterface && $user->getId()==$currentUser->getId()) {
            return new RedirectResponse($this->container->get('router')->generate('fos_user_profile_show')); 
        }elseif (!is_object($currentUser)){
            return new RedirectResponse($this->container->get('router')->generate('fos_user_security_login')); 
        
        }
        $form = $this->container->get('fos_message.new_thread_form.factory')->create();
        $formHandler = $this->container->get('fos_message.new_thread_form.handler');       
        $videos         = $this->getDoctrine()->getRepository("TeaCampusCommonBundle:Video")->findBy(array('author'=>$user, 'enabled'=>true),array('date' => 'DESC'));;
        $projects       = $this->getDoctrine()->getRepository("TeaCampusCommonBundle:Projet")->findBy(array('author'=>$user, 'enabled'=>true, 'private'=>false),array('date' => 'DESC'));;
        
        
        return $this->render('SonataUserBundle:Other:show.html.twig', array(
            'user'   => $user,
            'form' => $form->createView(),
+           'data' => $form->getData(),
            'projects' => $projects,
            'videos' => $videos,
            'blocks' => $this->container->getParameter('sonata.user.configuration.profile_blocks')
        ));
    }
    
    


}