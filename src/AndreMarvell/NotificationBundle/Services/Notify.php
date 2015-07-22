<?php

namespace AndreMarvell\NotificationBundle\Services;

use Symfony\Component\DependencyInjection\ContainerInterface;
use AndreMarvell\NotificationBundle\Entity\Notification;
use FOS\UserBundle\Model\UserInterface;

class Notify
{
    private $container;
    
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }
    
    public function notify ($receiver, $title, $description, $permalink, $type, $icon) {
        
        $notification = new Notification();
        $notification ->setDescription($description)
                      ->setTitle($title)
                      ->setType($type)
                      ->setPermalink($permalink)
                      ->setIcon($icon)
                      ->setReceiver($receiver);
        
        $user = $this->container->get('security.context')->getToken()->getUser();
        
        if (is_object($user) && $user instanceof UserInterface) {
            $notification->setSender($user);
        }
        
        $em = $this->container->get('doctrine.orm.entity_manager');
        $em->persist($notification);
        $em->flush();

        
        return true;
    }
    
    public function hasNotifications() {
        
        $user = $this->container->get('security.context')->getToken()->getUser();
        
        if (!is_object($user) || !$user instanceof UserInterface) {
            return false;
        }
        
        $em             = $this->container->get('doctrine.orm.entity_manager');
        $notifications  = $em->getRepository('AndreMarvellNotificationBundle:Notification')->findBy(array("open"=>false, "receiver"=>$user));
        
        if(is_null($notifications) || count($notifications)==0)
            return false;
        else
            return true;
    }
    
    public function countUnreadNotifications() {
        
        $user = $this->container->get('security.context')->getToken()->getUser();
        
        if (!is_object($user) || !$user instanceof UserInterface) {
            return 0;
        }
        
        $em             = $this->container->get('doctrine.orm.entity_manager');
        $notifications  = $em->getRepository('AndreMarvellNotificationBundle:Notification')->findBy(array("open"=>false, "receiver"=>$user));
        
        return count($notifications);
    }

}