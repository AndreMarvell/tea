<?php

namespace TeaCampus\CommonBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use TeaCampus\CommonBundle\Entity\Videos;
use TeaCampus\CommonBundle\Form\ProjetType;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;

class FormationController extends Controller
{
    public function indexAction()
    {
        $em             = $this->getDoctrine()->getManager();
        $locale         = $this->get('request')->getLocale();
        $categories     = null;//$em->getRepository('ApplicationSonataClassificationBundle:Category')->findCategories($locale);
        $popularPosts       = $em->getRepository('ApplicationSonataNewsBundle:Post')->findPopular();
        $popularProjects    = $em->getRepository('TeaCampusCommonBundle:Projet')->findPopular();
        $popularVideos      = $em->getRepository('TeaCampusCommonBundle:Video')->findPopular();
        $tags               = $em->getRepository('ApplicationSonataClassificationBundle:Tag')->findBy(array('context'=>'news'),null,25);
        
        return $this->render('TeaCampusCommonBundle:Formation:index.html.twig', array(
            'categories' => $categories,
            'popularPosts'     => $popularPosts,
            'popularProjects'  => $popularProjects,
            'popularVideos'    => $popularVideos,
            'tags'             => $tags,
        ));
        
       
    }
    
    public function subjectAction($subject)
    {
        
    }
    
    public function chapterAction($subject, $chapter)
    {
        
        
    }
    
   public function videoAction($subject, $chapter, $id)
    {
        
         
    }
   
}
