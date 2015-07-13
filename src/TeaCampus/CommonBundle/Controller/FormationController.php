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
        $categories     = $em->getRepository('ApplicationSonataClassificationBundle:Category')->findCategories($locale);
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
        $em             = $this->getDoctrine()->getManager();
        $locale         = $this->get('request')->getLocale();

        $main_subject   = $em->getRepository('ApplicationSonataClassificationBundle:Category')->findOneBy(array('slug'=>$subject,'locale'=>$locale));
        
        if(is_null($main_subject)){
            throw new NotFoundHttpException("Désolé, la page que vous avez demandée semble introuvable !");
        }else{
            
            $videos         = $em->getRepository('TeaCampusCommonBundle:Video')->findSubjectVideos($main_subject->getId());

            return $this->render('TeaCampusCommonBundle:Formation:subject.html.twig', array(
                'subject' => $main_subject,
                'videos'  => $videos,
            ));
        }
    }
    
    public function chapterAction($subject, $chapter)
    {
        $em             = $this->getDoctrine()->getManager();
        $locale         = $this->get('request')->getLocale();
        $main_chapter   = $em->getRepository('ApplicationSonataClassificationBundle:Category')->findOneBy(array('slug'=>$chapter,'locale'=>$locale));
        
        if(is_null($main_chapter) || is_null($main_chapter->getParent()) || $main_chapter->getParent()->getSlug()!==$subject){
            throw new NotFoundHttpException("Désolé, la page que vous avez demandée semble introuvable !");
        }else{
            
            $videos             = $em->getRepository('TeaCampusCommonBundle:Video')->findChapterVideos($main_chapter->getId());
            $popularPosts       = $em->getRepository('ApplicationSonataNewsBundle:Post')->findPopular();
            $popularProjects    = $em->getRepository('TeaCampusCommonBundle:Projet')->findPopular();
            $popularVideos      = $em->getRepository('TeaCampusCommonBundle:Video')->findPopular();
            $tags               = $em->getRepository('ApplicationSonataClassificationBundle:Tag')->findBy(array('context'=>'news'),null,25);
            
            $request            = $this->get('request');
            $paginator      = $this->get('knp_paginator');
            $pagination     = $paginator->paginate(
                                    $videos, $request->query->get('page', 1)/* page number */, 10/* limit per page */
                            );
            
            return $this->render('TeaCampusCommonBundle:Formation:chapter.html.twig', array(
                'chapter' => $main_chapter,
                'pagination'  => $pagination,
                'popularPosts'     => $popularPosts,
                'popularProjects'  => $popularProjects,
                'popularVideos'    => $popularVideos,
                'tags'             => $tags,
            ));
        }
        
    }
    
   public function videoAction($subject, $chapter, $id)
    {
        $em             = $this->getDoctrine()->getManager();
        $locale         = $this->get('request')->getLocale();
        $video          = $em->getRepository('TeaCampusCommonBundle:Video')->findOneBy(array('id'=>$id,'locale'=>$locale));
        
        if(is_null($video) || is_null($video->getCategory()) || $video->getCategory()->getSlug()!==$chapter || is_null($video->getCategory()->getParent()) || $video->getCategory()->getParent()->getSlug()!==$subject){
            throw new NotFoundHttpException("Désolé, la page que vous avez demandée semble introuvable !");
        }else{
            
            $popularPosts       = $em->getRepository('ApplicationSonataNewsBundle:Post')->findPopular();
            $popularProjects    = $em->getRepository('TeaCampusCommonBundle:Projet')->findPopular();
            $popularVideos      = $em->getRepository('TeaCampusCommonBundle:Video')->findPopular();
            $tags               = $em->getRepository('ApplicationSonataClassificationBundle:Tag')->findBy(array('context'=>'news'),null,25);
                        
            return $this->render('TeaCampusCommonBundle:Formation:show.html.twig', array(
                'video'  => $video,
                'popularPosts'     => $popularPosts,
                'popularProjects'  => $popularProjects,
                'popularVideos'    => $popularVideos,
                'tags'             => $tags,
            ));
        }
         
    }
   
}
