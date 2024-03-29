<?php

namespace TeaCampus\CommonBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

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
    
    public function teaAndMeAction()
    {
        $em             = $this->getDoctrine()->getManager();
        $locale         = $this->get('request')->getLocale();
        $videos         = $em->getRepository('TeaCampusCommonBundle:TeaAndMe')->findBy(array('locale'=>$locale,'enabled'=>true),array('date'=>'DESC'));
        
        
        $request        = $this->get('request');
        $paginator      = $this->get('knp_paginator');
        $pagination     = $paginator->paginate(
                                $videos, $request->query->get('page', 1)/* page number */, 20/* limit per page */
                        );
        
        return $this->render('TeaCampusCommonBundle:TeaAndMe:andMe.html.twig', array(
            'pagination'     => $pagination,
        ));
    }
    
    public function teaAndMeShowAction($id)
    {
        $em             = $this->getDoctrine()->getManager();
        $locale         = $this->get('request')->getLocale();
        $video         = $em->getRepository('TeaCampusCommonBundle:TeaAndMe')->findOneBy(array('locale'=>$locale,'id'=>$id));
        
        if(is_null($video)){
            throw new NotFoundHttpException("Désolé, la page que vous avez demandée semble introuvable !");
        }else{
            
            return $this->render('TeaCampusCommonBundle:TeaAndMe:show.html.twig', array(
                   'video'     => $video,
               ));
        
        }
    }
    
    public function searchAction(Request $request)
    {
        if ($request->getMethod() == "POST") {
            
            $search = $request->request->get('search');
            
            if (!is_null($search) && !empty($search)) {
                
                $em             = $this->getDoctrine()->getManager();

                $formations     = $em->getRepository('TeaCampusCommonBundle:Video')->search($search,20);
                $projects       = $em->getRepository('TeaCampusCommonBundle:Projet')->search($search,20);
                $teaAndMe       = $em->getRepository('TeaCampusCommonBundle:TeaAndMe')->search($search,40);
                $users          = $em->getRepository('ApplicationSonataUserBundle:User')->search($search,50);
                
                
                
                
                return $this->render('TeaCampusCommonBundle:Search:index.html.twig', array(
                    'search_title'      => 'search.title',
                    'search'            => $search,
                    'formations'        => $formations,
                    'projects'          => $projects,
                    'teaAndMe'          => $teaAndMe,
                    'users'             => $users,
                ));
            }
        }
        return $this->redirect( $this->generateUrl('tea_homepage') );
                
    }
    
    
}
