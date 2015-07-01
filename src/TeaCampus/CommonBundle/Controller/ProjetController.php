<?php

namespace TeaCampus\CommonBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use TeaCampus\CommonBundle\Entity\Projet;
use TeaCampus\CommonBundle\Form\ProjetType;
use Symfony\Component\HttpFoundation\Response;

class ProjetController extends Controller
{
    public function addAction()
    {
        
        $em         = $this->getDoctrine()->getManager();
        $context    = $em->getRepository('ApplicationSonataClassificationBundle:Context')->find('projet');
        $form       = $this->createForm(new ProjetType($em, $context), new Projet());
        
        $form->handleRequest($this->getRequest());        
        if ($form->isValid()) {
            
            $projet     = $form->getData();
            // On recupere le current user
            $usr        = $this->get('security.context')->getToken()->getUser();
            $projet->setAuthor($usr);
            
            $em->persist($projet);
            $em->flush();  
            
            $projet->createThread();
                        
            $em->persist($projet);
            $em->flush();
            
//            \Doctrine\Common\Util\Debug::dump($projet->getMedia());
//            exit;
            
            
            $html = $this->container->get('templating')->render('TeaCampusCommonBundle:Projet:profile_list_item.html.twig', array('projet' => $projet));
            $response = new Response();
            $response->setContent(json_encode(array("success"=>true,"content"=>$html)));
            $response -> headers -> set('Content-Type', 'application/json');
            
            return $response;
            
            
        }
        
        return $this->render('TeaCampusCommonBundle:Projet:add.html.twig', array('form' => $form->createView()));
        
    }
    
    public function deleteAction()
    {
        $request    = $this->get('request');
        $em         = $this->getDoctrine()->getManager();
        $id         = $request->request->get('id');
        $repository = $em->getRepository("TeaCampusCommonBundle:Projet");
        $projet     = $repository->find($id);
        
        $user= $this->get('security.context')->getToken()->getUser();
        
        $success = false;
        if(is_null($projet) || $user->getId()!==$projet->getAuthor()->getId()){
                throw new NotFoundHttpException("Désolé, la page que vous avez demandée semble introuvable !");
        }else{
            $success = true;
            $em->remove($projet);
            $em->flush();
            
        }
        
        $response = new Response();
        $response->setContent(json_encode(array("success"=>$success)));
        $response -> headers -> set('Content-Type', 'application/json');

        return $response;
        
    }
}
