<?php

namespace Application\FOS\MessageBundle\Controller;

use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpFoundation\RedirectResponse;
use FOS\MessageBundle\Provider\ProviderInterface;
use FOS\MessageBundle\Controller\MessageController as BaseController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;

class MessageController extends BaseController
{

    public function threadAction($threadId)
    {
        $thread = $this->getProvider()->getThread($threadId);
        $form = $this->container->get('fos_message.reply_form.factory')->create($thread);
        $formHandler = $this->container->get('fos_message.reply_form.handler');

        if ($message = $formHandler->process($form)) {
            return new RedirectResponse($this->container->get('router')->generate('fos_message_thread_view', array(
                'threadId' => $message->getThread()->getId()
            )));
        }

        return $this->container->get('templating')->renderResponse('FOSMessageBundle:Message:thread.html.twig', array(
            'form' => $form->createView(),
            'thread' => $thread
        ));
    }
    
    public function threadAjaxAction($threadId)
    {
        $thread         = $this->getProvider()->getThread($threadId);
        $form           = $this->container->get('fos_message.reply_form.factory')->create($thread);
        $formHandler    = $this->container->get('fos_message.reply_form.handler');

        if ($message = $formHandler->process($form)) {
            return new RedirectResponse($this->container->get('router')->generate('fos_message_thread_view_ajax', array(
                'threadId' => $message->getThread()->getId()
            )));
        }
        
        $lastMessage    = $thread->getMessages()->last();
        
        /*************** Notification *********************/
        // On notifie le destinataire
        $notify     = $this->container->get('andremarvell.notify');
        $translator = $this->container->get('translator');

        $receiver = null;
        foreach ($lastMessage->getThread()->getParticipants() as $participant) {
            if($participant->getId() != $lastMessage->getSender()->getId()){
                $receiver = $participant;
                break;
            }
        }
        if(!is_null($receiver)){
            $notify->notify(
                $receiver,
                $translator->trans('notification.message.answer.title', array(), 'messages'),
                $translator->trans('notification.message.answer.message', array('%user%'=>$lastMessage->getSender()->getFullname()), 'messages'),     
                $this->container->get('router')->generate('fos_user_profile_show').'?tab=messages',     
                'message',     
                $translator->trans('notification.message.answer.icon', array(), 'messages')     

             );
        }
        /*************** Fin Notification *********************/  
        
        $inboxThread    = $this->getProvider()->getInboxThreads();
        $sentThread     = $this->getProvider()->getSentThreads();

        $html       = $this->container->get('templating')->render('ApplicationFOSMessageBundle:Message:message_line.html.twig', array('message' => $lastMessage));
        $received   = $this->container->get('templating')->render('ApplicationFOSMessageBundle:Message:threads_list.html.twig', array('threads' => $inboxThread));
        $sent       = $this->container->get('templating')->render('ApplicationFOSMessageBundle:Message:threads_list.html.twig', array('threads' => $sentThread));
        
        $response   = new Response();
        $response->setContent(json_encode(array("success" => true, "content" => $html, 'received' => $received, 'sent'=>$sent)));
        $response->headers->set('Content-Type', 'application/json');
        
        return $response;

    }
    
    public function newThreadAction()
    {
        $form = $this->container->get('fos_message.new_thread_form.factory')->create();
        $formHandler = $this->container->get('fos_message.new_thread_form.handler');
        
        $success = false;
        if ($message = $formHandler->process($form)) {
            $success = true;
            
            // On notifie l'utilisateur
            $notify     = $this->container->get('andremarvell.notify');
            $translator = $this->container->get('translator');
            
            $receiver = null;
            foreach ($message->getThread()->getParticipants() as $participant) {
                if($participant->getId() != $message->getSender()->getId()){
                    $receiver = $participant;
                    break;
                }
            }
            
            if(!is_null($receiver)){
                $notify->notify(
                    $receiver,
                    $translator->trans('notification.message.sent.title', array(), 'messages'),
                    $translator->trans('notification.message.sent.message', array('%user%'=>$message->getSender()->getFullname()), 'messages'),     
                    $this->container->get('router')->generate('fos_user_profile_show').'?tab=messages',     
                    'message',     
                    $translator->trans('notification.message.sent.icon', array(), 'messages')     

                 );
            }
        }
        
        $response   = new Response();
        $response->setContent(json_encode(array("success" => $success)));
        $response->headers->set('Content-Type', 'application/json');
        
        return $response;
    }
    
     /**
     * Deletes a thread
     * 
     * @param string $threadId the thread id
     * 
     * @return RedirectResponse
     */
    public function deleteAction($threadId)
    {
        $thread = $this->getProvider()->getThread($threadId);
        $this->container->get('fos_message.deleter')->markAsDeleted($thread);
        $this->container->get('fos_message.thread_manager')->saveThread($thread);

        $received   = $this->container->get('templating')->render('ApplicationFOSMessageBundle:Message:threads_list.html.twig', array('threads' => $inboxThread));
        $sent       = $this->container->get('templating')->render('ApplicationFOSMessageBundle:Message:threads_list.html.twig', array('threads' => $sentThread));
        
        $response   = new Response();
        $response->setContent(json_encode(array("success" => true, 'received' => $received, 'sent'=>$sent)));
        $response->headers->set('Content-Type', 'application/json');
        
        return $response; 
    }
    
    /**
     * Gets the provider service
     *
     * @return ProviderInterface
     */
    protected function getProvider()
    {
        return $this->container->get('fos_message.provider');
    }
}
