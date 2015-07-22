<?php

namespace AndreMarvell\NotificationBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use FOS\UserBundle\Model\UserInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;

class NotificationController extends Controller
{
    public function indexAction()
    {
        $user = $this->container->get('security.context')->getToken()->getUser();
        if (!is_object($user) || !$user instanceof UserInterface) {
            throw new AccessDeniedException('This user does not have access to this section.');
        }else{
            
            $em             = $this->getDoctrine()->getManager();
            $notifications  = $em->getRepository('AndreMarvellNotificationBundle:Notification')->findUnread($user->getId());

            return $this->render('AndreMarvellNotificationBundle:Notification:index.html.twig', array('notifications' => $notifications));
    
        }
    }
    
    public function showAction($id)
    {
        $user = $this->container->get('security.context')->getToken()->getUser();
        if (!is_object($user) || !$user instanceof UserInterface) {
            throw new AccessDeniedException('This user does not have access to this section.');
        }else{
            
            $em             = $this->getDoctrine()->getManager();
            $notification   = $em->getRepository('AndreMarvellNotificationBundle:Notification')->find($id);
            
            if (is_null($notification) || $notification->getReceiver()->getId() != $user->getId()) {
                throw new NotFoundHttpException("Désolé, la page que vous avez demandée semble introuvable !");
            } else {
                $notification->setOpen(true);
                $em->persist($notification);
                $em->flush();
                
                return new RedirectResponse($notification->getPermalink());
            }
        }
    }
}
