<?php

namespace TeaCampus\CommonBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

/**
 * Description of BookingController
 *
 * @author ikounga_marvel
 */
class ContactController  extends Controller{
    
    public function indexAction()
    {
        
        $em = $this->getDoctrine()->getManager();
        
        $data = array();
        $form = $this->createFormBuilder($data)
            ->add('subject', 'text')
            ->add('name', 'text')
            ->add('email', 'email')
            ->add('message', 'textarea')
            ->getForm();
        
        $current_url = $this->get('router')
                                ->generate($this->container->get('request')->attributes->get('_route'),
                                            array(),true
                                          );
            
            $seoPage = $this->container->get('sonata.seo.page');
            
            $seo_desc  = "Contactez nous et transmettez-nous toutes vos demandes - TeaCampus";
            $seo_title = "Contactez nous | TEA";

            $seoPage
                ->setTitle($seo_title)
                ->addMeta('name', 'description', $seo_desc)
                ->addMeta('name', 'keywords',"entrepreneuriat, contact, formation à la création d'entreprise")
                ->addMeta('name', 'robots', "index, follow")
                ->addMeta('property', 'og:title', $seo_title)
                ->addMeta('property', 'og:type', "website")
                ->addMeta('property', 'og:url',  $current_url)
                ->addMeta('property', 'og:description', $seo_desc)
            ;
            
        return $this->render('TeaCampusCommonBundle:Contact:index.html.twig', array(
                'form' => $form->createView(),
              ));        
        
    }
    
    public function sendAction()
    {
        $request = $this->get('request');
        
        
        if($request->isXmlHttpRequest()){
            
            
            $mail = $request->request->get('email');
            $name = $request->request->get('name');
            $message = $request->request->get('message');
            $subject = $request->request->get('subject');
            $errors = $this->validateEmails($mail);
            
            if( empty($errors) && 
                !empty($subject) &&
                !empty($name) &&
                !empty($message)
            ){
               $headers  = 'MIME-Version: 1.0' . "\r\n";
               $headers .= 'Content-type: text/html; charset=UTF-8' . "\r\n";
               $headers .= 'From: '.$name.' <'.$mail.'>' . "\r\n";
               $sent = mail(
                       "contact@todaysentrepreneursforafrica.com",
                       $subject,
                       $this->renderView('TeaCampusCommonBundle:Contact:email.html.twig', array(
                                            'name' => $name,
                                            'message' => $message,
                                            'text/html')),
                       $headers);
     
                if($sent){
                    $html = $this->container->get('templating')->render('TeaCampusCommonBundle:Contact:success.html.twig');
                    $response = new Response(json_encode( array("success"=>true,"message"=>$html) ));
                }else{
                    $response = new Response(json_encode( array("success"=>false,"message"=>"Désolé un problème est survenu lors de l'envoi du message. Veuillez réessayer ulterieurement") ));
                } 
            }else{
                $response = new Response(json_encode( array("success"=>false,"message"=>"Erreur d'envoi du message. Vérifiez que tous les champs sont correctement remplis") ));
            }
            
            
            $response->headers->set('Content-Type', 'application/json');
            return $response;
        }
        
        
        
        
    }
  
    
    /**
     * Validates single email (or an array of email addresses
     *
     * @param array|string $emails
     *
     * @return array
     */
    public function validateEmails($emails){

        $errors = array();
        $emails = is_array($emails) ? $emails : array($emails);

        $validator = $this->container->get('validator');

        $constraints = array(
            new \Symfony\Component\Validator\Constraints\Email(),
            new \Symfony\Component\Validator\Constraints\NotBlank()
        );

        foreach ($emails as $email) {

            $error = $validator->validateValue($email, $constraints);

            if (count($error) > 0) {
                $errors[] = $error;
            }
        }

        return $errors;
    }
}

?>