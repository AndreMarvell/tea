<?php

namespace TeaCampus\CommonBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
        $em             = $this->getDoctrine()->getManager();
        $lastNews       = $em->getRepository('ApplicationSonataNewsBundle:Post')->findLastNews(3);
        $lastVideos     = $em->getRepository('TeaCampusCommonBundle:Video')->findLast(4);
        $lastProjects   = $em->getRepository('TeaCampusCommonBundle:Projet')->findLast(4);

        return $this->render('TeaCampusCommonBundle:Home:index.html.twig', array(
            'lastNews' => $lastNews,
            'lastVideos' => $lastVideos,
            'lastProjects' => $lastProjects
        ));
    }
    
    
    public function aboutAction()
    {
        $em             = $this->getDoctrine()->getManager();
        $locale         = $this->get('request')->getLocale();
        $histories      = $em->getRepository('TeaCampusCommonBundle:History')->findBy(array('locale'=>$locale), array('date' => 'ASC'));
        $partners       = $em->getRepository('TeaCampusCommonBundle:Partner')->findBy(array(), array('position' => 'ASC'));
        $services       = $em->getRepository('TeaCampusCommonBundle:Service')->findBy(array('locale'=>$locale), array('position' => 'ASC'));
        
        return $this->render('TeaCampusCommonBundle:Home:about.html.twig', array(
            'histories' => $histories,
            'partners' => $partners,
            'services' => $services,
        ));
    }
    
    public function faqAction()
    {
        $em             = $this->getDoctrine()->getManager();
        $locale         = $this->get('request')->getLocale();
        $faqs           = $em->getRepository('TeaCampusCommonBundle:Faq')->findBy(array('locale'=>$locale), array('position' => 'ASC'));
        
        return $this->render('TeaCampusCommonBundle:Home:faq.html.twig', array(
            'faqs' => $faqs,
        ));
    }
    
    public function mentionsAction()
    {
        $em             = $this->getDoctrine()->getManager();
        $locale         = $this->get('request')->getLocale();
        $popularPosts       = $em->getRepository('ApplicationSonataNewsBundle:Post')->findPopular();
        $popularProjects    = $em->getRepository('TeaCampusCommonBundle:Projet')->findPopular();
        $popularVideos      = $em->getRepository('TeaCampusCommonBundle:Video')->findPopular();
        $tags               = $em->getRepository('ApplicationSonataClassificationBundle:Tag')->findBy(array('context'=>'news'),null,25);
        
        return $this->render('TeaCampusCommonBundle:Home:mentions.html.twig', array(
            'popularPosts'     => $popularPosts,
            'popularProjects'  => $popularProjects,
            'popularVideos'    => $popularVideos,
            'tags'             => $tags,
        ));
        
       
    }
    
    
}
