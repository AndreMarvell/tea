<?php

namespace TeaCampus\CommonBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use TeaCampus\CommonBundle\Entity\Videos;
use TeaCampus\CommonBundle\Form\ProjetType;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

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
        $tags               = $em->getRepository('ApplicationSonataClassificationBundle:Tag')->findBy(array('context'=>'video'),null,25);
        
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
            $tags               = $em->getRepository('ApplicationSonataClassificationBundle:Tag')->findBy(array('context'=>'video'),null,25);
            
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
            $tags               = $em->getRepository('ApplicationSonataClassificationBundle:Tag')->findBy(array('context'=>'video'),null,25);
                        
            return $this->render('TeaCampusCommonBundle:Formation:show.html.twig', array(
                'video'  => $video,
                'popularPosts'     => $popularPosts,
                'popularProjects'  => $popularProjects,
                'popularVideos'    => $popularVideos,
                'tags'             => $tags,
            ));
        }
         
    }
    
    public function deleteAction() {
        $request    = $this->get('request');
        $em         = $this->getDoctrine()->getManager();
        $id         = $request->request->get('id');
        $repository = $em->getRepository("TeaCampusCommonBundle:Video");
        $video      = $repository->find($id);

        $user = $this->get('security.context')->getToken()->getUser();

        $success = false;
        if (is_null($video) || $user->getId() !== $video->getAuthor()->getId()) {
            throw new NotFoundHttpException("Désolé, la page que vous avez demandée semble introuvable !");
        } else {
            $success = true;
            $em->remove($video);
            $em->flush();
        }

        $response = new Response();
        $response->setContent(json_encode(array("success" => $success)));
        $response->headers->set('Content-Type', 'application/json');

        return $response;
    }
    
    public function tutoringRequestAction() {
        $request        = $this->get('request');
        $em             = $this->getDoctrine()->getManager();
        $motivations    = $request->request->get('motivations');

        $user = $this->get('security.context')->getToken()->getUser();

        $success = false;
        
        if (!is_null($user) && !empty($motivations)) {
        
            $tutoringRequest = new \TeaCampus\CommonBundle\Entity\TutoringRequest();
            $tutoringRequest ->setAuthor($user)
                             ->setMotivations($motivations);
            $em->persist($tutoringRequest);
            $em->flush();
            $success = true;
        }

        $response = new Response();
        $response->setContent(json_encode(array("success" => $success)));
        $response->headers->set('Content-Type', 'application/json');

        return $response;
    }
    
    public function tagAction($tag)
    {
        $em         = $this->getDoctrine()->getManager();
        $tag = $this->get('sonata.classification.manager.tag')->findOneBy(array(
            'slug'    => $tag,
            'enabled' => true,
        ));
        if (!$tag || !$tag->getEnabled()) {
            throw new NotFoundHttpException('Unable to find the tag');
        }else{
            
            $videos             = $em->getRepository('TeaCampusCommonBundle:Video')->findByTag($tag->getId());
            $popularPosts       = $em->getRepository('ApplicationSonataNewsBundle:Post')->findPopular();
            $popularProjects    = $em->getRepository('TeaCampusCommonBundle:Projet')->findPopular();
            $popularVideos      = $em->getRepository('TeaCampusCommonBundle:Video')->findPopular();
            $tags               = $em->getRepository('ApplicationSonataClassificationBundle:Tag')->findBy(array('context'=>'video'),null,25);
            
            $request            = $this->get('request');
            $paginator      = $this->get('knp_paginator');
            $pagination     = $paginator->paginate(
                                    $videos, $request->query->get('page', 1)/* page number */, 10/* limit per page */
                            );
            
            return $this->render('TeaCampusCommonBundle:Search:search.html.twig', array(
                'tag' => $tag,
                'search_title'      => 'video.search.title',
                'search_subhead'    => 'video.search.subhead',
                'search_path'       => 'tea_formations_search',
                'search_no_result'  => 'video.search.no_result',
                'aside_search_title'=> 'aside.video.search',
                'type'              => 'videos',
                'pagination'        => $pagination,
                'popularPosts'     => $popularPosts,
                'popularProjects'  => $popularProjects,
                'popularVideos'    => $popularVideos,
                'tags'             => $tags,
            ));
        }
    }
    
    public function searchAction(Request $request)
    {
        if ($request->getMethod() == "POST") {
            $search = $request->request->get('search');
            
            if (!is_null($search) && !empty($search)) {
                
                $em         = $this->getDoctrine()->getManager();

                $videos             = $em->getRepository('TeaCampusCommonBundle:Video')->search($search);
                $popularPosts       = $em->getRepository('ApplicationSonataNewsBundle:Post')->findPopular();
                $popularProjects    = $em->getRepository('TeaCampusCommonBundle:Projet')->findPopular();
                $popularVideos      = $em->getRepository('TeaCampusCommonBundle:Video')->findPopular();
                $tags               = $em->getRepository('ApplicationSonataClassificationBundle:Tag')->findBy(array('context'=>'projet'),null,25);

                $request            = $this->get('request');
                $paginator          = $this->get('knp_paginator');
                $pagination         = $paginator->paginate(
                                            $videos, $request->query->get('page', 1)/* page number */, 10/* limit per page */
                                    );

                return $this->render('TeaCampusCommonBundle:Search:search.html.twig', array(
                    'search_title'      => 'video.search.title',
                    'search_subhead'    => 'video.search.subhead',
                    'search_path'       => 'tea_formations_search',
                    'search_no_result'  => 'video.search.no_result',
                    'aside_search_title'=> 'aside.video.search',
                    'type'              => 'videos',
                    'search'            => $search,
                    'pagination'        => $pagination,
                    'popularPosts'     => $popularPosts,
                    'popularProjects'  => $popularProjects,
                    'popularVideos'    => $popularVideos,
                    'tags'             => $tags,
                ));
            }
        }
        return $this->redirect( $this->generateUrl('tea_formations') );
                
    }
    
    public function addAction(){
        
    }
   
}
