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


//    /**
//     * @return Response
//     *
//     * @throws AccessDeniedException
//     */
//    public function editProfileAction()
//    {
//        $user = $this->container->get('security.context')->getToken()->getUser();
//        if (!is_object($user) || !$user instanceof UserInterface) {
//            throw new AccessDeniedException('This user does not have access to this section.');
//        }
//
//        $form = $this->container->get('sonata.user.profile.form');
//        $formHandler = $this->container->get('sonata.user.profile.form.handler');
//
//        $process = $formHandler->process($user);
//        if ($process) {
//            $this->setFlash('sonata_user_success', 'profile.flash.updated');
//
//            return new RedirectResponse($this->generateUrl('sonata_user_profile_show'));
//        }
//
//        return $this->render('SonataUserBundle:Profile:edit_profile.html.twig', array(
//            'form'               => $form->createView(),
//            'breadcrumb_context' => 'user_profile',
//        ));
//    }

}