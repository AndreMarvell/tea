<?php

namespace AndreMarvell\SocialBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use AndreMarvell\SocialBundle\Entity\Like;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class LikeController extends Controller
{
    public function indexAction($id)
    {
        $em     = $this->getDoctrine()->getManager();
        $Thread = $em->getRepository('AndreMarvellSocialBundle:LikeThread')->findOneById($id);
        
        if(is_null($Thread) || $Thread->getNumLikes()==0){
            $isLiker= false;
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
        
        if(is_null($like)){
            if(is_null($user) || !($user instanceof Application\Sonata\UserBundle\Entity\User)){
                throw new \Symfony\Component\Security\Core\Exception\AccessDeniedException("Must login to like!");
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
        
        $thread = $like->getThread();
        $em->flush($thread);
        
        $response = new Response();
        $response ->setContent(json_encode($thread->getNumLikes()));
        $response ->headers->set('Content-Type', 'application/json');
        
        return $response;
        
        
    } 
}
