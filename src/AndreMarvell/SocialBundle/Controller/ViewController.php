<?php

namespace AndreMarvell\SocialBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use AndreMarvell\SocialBundle\Entity\View;
use AndreMarvell\SocialBundle\Entity\ViewThread;
use FOS\UserBundle\Model\UserInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ViewController extends Controller
{
    
    
    public function indexAction($id)
    {
        $request    = $this->get('request');
        $user       = $this->get('security.context')->getToken()->getUser();
        $em         = $this->getDoctrine()->getEntityManager();
        $ip         = $request->getClientIp();
        
        $thread     = $em->getRepository('AndreMarvellSocialBundle:ViewThread')->findOneById($id);
        
        if(is_null($thread)){
            $thread = new ViewThread($id);
            $em->persist($thread);
            $em->flush($thread);
        }
        
        if(!is_object($user) || !$user instanceof UserInterface){
            // Le user  n'est pas connecté, on verifie donc par IP
            $view       = $em  ->getRepository('AndreMarvellSocialBundle:View')
                               ->findOneBy(array('thread' => $thread, 'ip'=>  $ip));
            $user       = null;
        }else{
            // On teste si le user a deja consulté
            $view       = $em->getRepository('AndreMarvellSocialBundle:View')
                             ->findOneBy(array('thread' => $thread, 'viewer'=>  $user));
            
            if(is_null($view)){
               // Dans ce cas le user n'est n'a jamais consulté mais on teste encore l'IP
                $view   = $em->getRepository('AndreMarvellSocialBundle:View')
                             ->findOneBy(array('thread' => $thread, 'ip'=>  $ip)); 
            }
        }
        
        
        if(is_null($view)){
        
            $view = new View($thread, $ip ,$user);
            $em->persist($view);
            $em->flush($view);
        }
        
        $thread = $view->getThread();
        $em->flush($thread);
        
        return $this->render('AndreMarvellSocialBundle:View:view.html.twig', 
                    array(
                        'Thread' => $thread,
                    ));
        
        
    } 
}
