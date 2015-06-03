<?php

namespace AndreMarvell\SocialBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use AndreMarvell\SocialBundle\Entity\View;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ViewController extends Controller
{
    public function indexAction($id)
    {
        $em     = $this->getDoctrine()->getManager();
        $Thread = $em->getRepository('AndreMarvellSocialBundle:ViewThread')->findOneById($id);
        
        if(is_null($Thread) || $Thread->getNumViews()==0){
            $isViewer= false;
        }else{
            
            $user   = $this->get('security.context')->getToken()->getUser();
            $view   = $em->getRepository('AndreMarvellSocialBundle:View')
                         ->findOneBy(array('thread' => $Thread, 'viewer'=>  $user));
            
            // Si on a pas le user en base, on check l'adresse ip
            if(is_null($view)){
                $ip         = $this->get('request')->getClientIp();
                $view       = $em  ->getRepository('AndreMarvellSocialBundle:View')
                                   ->findOneBy(array('thread' => $Thread, 'ip'=>  $ip));
            }
            $isViewer   = (!is_null($view))?true:false;
        }
        
        return $this->render('AndreMarvellSocialBundle:View:view.html.twig', 
                    array(
                        'Thread' => $Thread,
                        'isViewer' => $isViewer
                    ));
        
    }
    
    public function viewAction()
    {
        $request    = $this->get('request');
        $user       = $this->get('security.context')->getToken()->getUser();
        $em         = $this->getDoctrine()->getEntityManager();
        $ip         = $request->getClientIp();
        $thread_id  = $request->request->get('id');
        
        if(is_null($user) || !($user instanceof Application\Sonata\UserBundle\Entity\User)){
            // Le user  n'est pas connecté, on verifie donc par IP
            $view       = $em  ->getRepository('AndreMarvellSocialBundle:View')
                               ->findOneBy(array('thread' => $thread_id, 'ip'=>  $ip));
        }else{
            // On teste si le user a deja consulté
            $view       = $em->getRepository('AndreMarvellSocialBundle:View')
                             ->findOneBy(array('thread' => $thread_id, 'viewer'=>  $user));
            
            if(is_null($view)){
               // Dans ce cas le user n'est n'a jamais consulté mais on teste encore l'IP
                $view   = $em->getRepository('AndreMarvellSocialBundle:View')
                             ->findOneBy(array('thread' => $thread_id, 'ip'=>  $ip)); 
            }
        }
        
        
        if(is_null($view)){
        
            $view = new View($em->getRepository('AndreMarvellSocialBundle:ViewThread')->find($thread_id), $ip ,$user);
            $em->persist($view);
            $em->flush($view);
        }
        
        $thread = $view->getThread();
        $em->flush($thread);
        
        $response = new Response();
        $response ->setContent(json_encode($thread->getNumViews()));
        $response ->headers->set('Content-Type', 'application/json');
        
        return $response;
        
        
    } 
}
