<?php

namespace TeaCampus\CommonBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;


class TeaAndMeController extends Controller
{
    
    
    public function indexAction()
    {
        $em             = $this->getDoctrine()->getManager();
        $locale         = $this->get('request')->getLocale();
        $videos         = $em->getRepository('TeaCampusCommonBundle:TeaAndMe')->findBy(array('locale'=>$locale,'enabled'=>true),array('date'=>'DESC'));
        $tags           = $em->getRepository('ApplicationSonataClassificationBundle:Tag')->findBy(array('context'=>'teaandme'),null,25);
        
        $request        = $this->get('request');
        $paginator      = $this->get('knp_paginator');
        $pagination     = $paginator->paginate(
                                $videos, $request->query->get('page', 1)/* page number */, 20/* limit per page */
                        );
        
        return $this->render('TeaCampusCommonBundle:TeaAndMe:index.html.twig', array(
            'pagination'     => $pagination,
            'tags'           => $tags
        ));
    }
    
    public function showAction($id)
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
    
    public function deleteAction() {
        $request    = $this->get('request');
        $em         = $this->getDoctrine()->getManager();
        $id         = $request->request->get('id');
        $repository = $em->getRepository("TeaCampusCommonBundle:TeaAndMe");
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
            $tags               = $em->getRepository('ApplicationSonataClassificationBundle:Tag')->findBy(array('context'=>'teaandme'),null,25);
            
            $request            = $this->get('request');
            $paginator      = $this->get('knp_paginator');
            $pagination     = $paginator->paginate(
                                    $videos, $request->query->get('page', 1)/* page number */, 10/* limit per page */
                            );
            
            return $this->render('TeaCampusCommonBundle:TeaAndMe:index.html.twig', array(
                'tag' => $tag,
                'pagination'        => $pagination,
                'tags'             => $tags,
            ));
        }
    }
    
    public function addAction(){
        
    }
    
    
}
