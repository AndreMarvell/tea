<?php

namespace TeaCampus\CommonBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use TeaCampus\CommonBundle\Entity\Projet;
use TeaCampus\CommonBundle\Form\ProjetType;
use Symfony\Component\HttpFoundation\Response;

class ProjetController extends Controller
{
    public function indexAction()
    {
        $em                  = $this->getDoctrine()->getManager();
        $projetRepo          = $em->getRepository('TeaCampusCommonBundle:Projet');
        $idselectedProjet    = $projetRepo->getSelectedProjet();
        $idProjectOfTheMonth = $projetRepo->getProjectOfTheMonth();
        $idProjectOfTheWeek  = $projetRepo->getProjectOfTheWeek();
        $tags                = $em->getRepository('ApplicationSonataClassificationBundle:Tag')->findByContext('projet');
        $mostRead       = $em->getRepository('TeaCampusCommonBundle:Projet')->findMostRead();
        
        $selectedProjet      = $projetRepo->findOneById($idselectedProjet);
        $projetofthemonth    = $projetRepo->findOneById($idProjectOfTheMonth);
        $projetoftheweek     = $projetRepo->findOneById($idProjectOfTheWeek);
        
        $query               = $em->createQuery(
                                    'SELECT p FROM TeaCampusCommonBundle:Projet p
                                    WHERE p.private = false
                                    ORDER BY p.date DESC'
                                    );
        $query->setMaxResults(4);
        $latestProject = $query->getResult();
        
        
        
        return $this->render('TeaCampusCommonBundle:Projet:index.html.twig',
                            array('selectionOftea'=>$selectedProjet,
                                  'projectofthemonth'=>$projetofthemonth,
                                  'latestproject' =>$latestProject,
                                  'tags' =>$tags,
                                  'mostRead' => $mostRead,
                                  'projectoftheweek'=>$projetoftheweek));
        
        
    }
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
    
    public function listAction()
    {
        return $this->render('TeaCampusCommonBundle:Projet:list.html.twig');
         
    }
    
    public function searchAction()
    {
        return $this->render('TeaCampusCommonBundle:Projet:list.html.twig');
         
    }
    
     public function showAction($id)
    {
        $em              = $this->getDoctrine()->getManager();
        $projetRepo      = $em->getRepository('TeaCampusCommonBundle:Projet');
        $projet          = $projetRepo->findOneById($id);
        
        $query               = $em->createQuery(
                                   'SELECT p FROM TeaCampusCommonBundle:Projet p
                                    WHERE p.private = false
                                    ORDER BY p.date DESC'
                                    );
            
        $randomProject = $query->getResult();
        shuffle($randomProject);
        
        $newArray = array_splice($randomProject, 0, 6);
        
        return $this->render('TeaCampusCommonBundle:Projet:show.html.twig',
                            array('project'=>$projet,
                                  'randomproject'=>$newArray));
         
    }
}
