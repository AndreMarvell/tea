<?php

namespace TeaCampus\CommonBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Application\Sonata\MediaBundle\Entity\Media;
use TeaCampus\CommonBundle\Entity\TeaAndMe;
use TeaCampus\CommonBundle\Form\TeaAndMeType;


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
    
    public function addAction(Request $request){
        
        $em         = $this->getDoctrine()->getManager();
        $context    = $em->getRepository('ApplicationSonataClassificationBundle:Context')->find('teaandme');
        $form       = $this->createForm(new TeaAndMeType($em, $context), new TeaAndMe());

        $form->handleRequest($request);
        
        if ($form->isValid()) {

            $teaAndMe   = $form->getData();
            // On recupere le current user
            $usr        = $this->get('security.context')->getToken()->getUser();
            $teaAndMe->setAuthor($usr);
            
            // Getting sonata media manager service
            $mediaManager = $this->container->get('sonata.media.manager.media');
            
            // Getting sonata media object and saving media
            $media = new Media;
            $media->setBinaryContent($request->files->get('file'));
            $media->setContext('teaandme');
            $media->setProviderName('sonata.media.provider.file');
            $mediaManager->save($media);
            
            $teaAndMe->setMedia($media);
           

            $em->persist($teaAndMe);
            $em->flush();

            $html = $this->container->get('templating')->render('TeaCampusCommonBundle:TeaAndMe:profile_list_item.html.twig', array('video' => $teaAndMe));
            $response = new Response();
            $response->setContent(json_encode(array("success" => true, "content" => $html)));
            $response->headers->set('Content-Type', 'application/json');

            return $response;
 
            
        }
 
        return $this->render('TeaCampusCommonBundle:TeaAndMe:add.html.twig', array(
            'form' => $form->createView()
        ));
    }
    
    
}
