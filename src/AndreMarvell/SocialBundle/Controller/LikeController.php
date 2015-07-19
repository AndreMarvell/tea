<?php

namespace AndreMarvell\SocialBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use FOS\UserBundle\Model\UserInterface;
use AndreMarvell\SocialBundle\Entity\Like;
use AndreMarvell\SocialBundle\Entity\LikeThread;

class LikeController extends Controller
{
    public function indexAction($id)
    {
        $em         = $this->getDoctrine()->getManager();
        $Thread     = $em->getRepository('AndreMarvellSocialBundle:LikeThread')->findOneById($id);
        $isLiker    = false;
        
        if(is_null($Thread)){
            $Thread = new LikeThread($id);
            $em->persist($Thread);
            $em->flush($Thread);
            
        }else{
            
            $user   = $this->get('security.context')->getToken()->getUser();
            $like   = $em->getRepository('AndreMarvellSocialBundle:Like')
                         ->findOneBy(array('thread' => $Thread, 'liker'=>  $user));
        
            $isLiker = (!is_null($like))?true:false;
 
        }
        
        return $this->render('AndreMarvellSocialBundle:Like:like.html.twig', 
                    array(
                        'Thread' => $Thread,
                        'isLiker' => $isLiker
                    ));
        
    }
    
    public function likeAction()
    {
        $request    = $this->get('request');
        $em         = $this->getDoctrine()->getEntityManager();
        $thread_id  = $request->request->get('id');
        $user       = $this->get('security.context')->getToken()->getUser();
        
        $like       = $em->getRepository('AndreMarvellSocialBundle:Like')
                         ->findOneBy(array('thread' => $thread_id, 'liker'=>  $user));
        
        $success    = true;
        $message    = "error";
        if(is_null($like)){
            if(!is_object($user) || !$user instanceof UserInterface){
                $success = false;
                $message = "not_connected";
            }else{
                $like = new Like($em->getRepository('AndreMarvellSocialBundle:LikeThread')->find($thread_id),$user);
                $em->persist($like);
                $em->flush($like);
            }
            
        }
        else{
            $em->remove($like);
            $em->flush($like);
        }
        
        $response = new Response();
        
        if(!is_null($like)){
            $thread = $like->getThread();
            $em->flush($thread);
            $response ->setContent(json_encode(array("success" => $success,'likes'=>$thread->getNumLikes(),'message'=>$message)));
        }else{
            $response ->setContent(json_encode(array("success" => $success,'message'=>$message)));
        }
        
        $response ->headers->set('Content-Type', 'application/json');
        
        return $response;
        
        
    } 
}
