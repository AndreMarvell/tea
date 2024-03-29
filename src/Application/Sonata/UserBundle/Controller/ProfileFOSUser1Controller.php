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
use Symfony\Component\HttpFoundation\Request;
use Application\Sonata\MediaBundle\Entity\Media;

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
        
        $videos                 = $this->getDoctrine()->getRepository("TeaCampusCommonBundle:Video")->findBy(array('author'=>$user),array('date' => 'DESC'));;
        $projects               = $this->getDoctrine()->getRepository("TeaCampusCommonBundle:Projet")->findBy(array('author'=>$user),array('date' => 'DESC'));;
        $teaandmes              = $this->getDoctrine()->getRepository("TeaCampusCommonBundle:TeaAndMe")->findBy(array('author'=>$user),array('date' => 'DESC'));;
        $provider               = $this->container->get('fos_message.provider');
        $threads                = $provider->getInboxThreads();
        $threads_send           = $provider->getSentThreads();
        $tutoringRequestExist   = (is_null($this->getDoctrine()->getRepository("TeaCampusCommonBundle:TutoringRequest")->findOneByAuthor($user)))? false : true;

        return $this->render('SonataUserBundle:Profile:show.html.twig', array(
            'user'                  => $user,
            'threads'               => $threads,
            'threads_send'          => $threads_send,
            'projects'              => $projects,
            'teaandmes'             => $teaandmes,
            'tutoringRequestExist'  => $tutoringRequestExist,
            'videos'                => $videos,
            'blocks'                => $this->container->getParameter('sonata.user.configuration.profile_blocks')
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
    public function avatarAction(Request $request){
        
        $em = $this->getDoctrine()->getEntityManager();
        $user= $this->get('security.context')->getToken()->getUser();
        
        if (!is_object($user) || !$user instanceof UserInterface) {
            throw new AccessDeniedException('This user does not have access to this section.');
        }else{
            
            if (!is_null($request->files->get('file'))) {
                // Getting sonata media manager service
                $mediaManager = $this->container->get('sonata.media.manager.media');

                // Getting sonata media object and saving media
                $media = new Media;
                $media->setBinaryContent($request->files->get('file'));
                $media->setContext('avatar');
                $media->setName($user->getFullname());
                $media->setProviderName('sonata.media.provider.image');
                $mediaManager->save($media);
                
                $previousAvatar = $user->getAvatar();
                $user->setAvatar($media);
                $em->persist($user);
                if(!is_null($previousAvatar)){
                    $em->remove($previousAvatar);
                }
                $em->flush();  
                
                $response = new Response();
                $response->setContent(json_encode(array("success" => true)));
                $response->headers->set('Content-Type', 'application/json');

                return $response;
            }
            return $this->render('ApplicationSonataUserBundle:Profile:avatar.html.twig');
        }
        
        
        
    }

    /**
     * @return Response
     *
     * @throws AccessDeniedException
     */
    public function cvAction(Request $request){

        $em = $this->getDoctrine()->getEntityManager();
        $user= $this->get('security.context')->getToken()->getUser();

        if (!is_object($user) || !$user instanceof UserInterface) {
            throw new AccessDeniedException('This user does not have access to this section.');
        }else{

            if (!is_null($request->files->get('file'))) {
                // Getting sonata media manager service
                $mediaManager = $this->container->get('sonata.media.manager.media');

                // Getting sonata media object and saving media
                $media = new Media;
                $media->setBinaryContent($request->files->get('file'));
                $media->setContext('cv');
                $media->setName($user->getFullname());
                $media->setProviderName('sonata.media.provider.file');
                $mediaManager->save($media);

                $previousCV = $user->getCv();
                $user->setCv($media);
                $em->persist($user);
                if(!is_null($previousCV)){
                    $em->remove($previousCV);
                }
                $em->flush();

                $response = new Response();
                $response->setContent(json_encode(array("success" => true)));
                $response->headers->set('Content-Type', 'application/json');

                return $response;
            }
            return $this->render('ApplicationSonataUserBundle:Profile:cv.html.twig');
        }



    }


}