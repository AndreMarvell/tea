<?php

namespace Application\Sonata\NewsBundle\Controller;

use Sonata\NewsBundle\Controller\PostController as BaseController;
use Symfony\Component\HttpFoundation\Request;

class PostController extends BaseController
{
    /**
     * @return RedirectResponse
     */
    public function homeAction()
    {
        return $this->redirect($this->generateUrl('sonata_news_archive'));
    }
    /**
     * @param array $criteria
     * @param array $parameters
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function renderArchive(array $criteria = array(), array $parameters = array())
    {
        $em             = $this->getDoctrine()->getManager();
        $mostRead       = $em->getRepository('ApplicationSonataNewsBundle:Post')->findMostRead();
        $tags           = $em->getRepository('ApplicationSonataClassificationBundle:Tag')->findByContext('news');
        
        $pager = $this->getPostManager()->getPager(
            $criteria,
            $this->getRequest()->get('page', 1)
        );
        $parameters = array_merge(array(
            'pager'            => $pager,
            'blog'             => $this->get('sonata.news.blog'),
            'tag'              => false,
            'collection'       => false,
            'route'            => $this->getRequest()->get('_route'),
            'route_parameters' => $this->getRequest()->get('_route_params'),
            'mostRead'         => $mostRead,
            'tags'         => $tags
        ), $parameters);
        
        $response = $this->render(sprintf('SonataNewsBundle:Post:archive.%s.twig', $this->getRequest()->getRequestFormat()), $parameters);
        
        if ('rss' === $this->getRequest()->getRequestFormat()) {
            $response->headers->set('Content-Type', 'application/rss+xml');
        }
        
        return $response;
    }
    /**
     * @return Response
     */
    public function archiveAction()
    {
        return $this->renderArchive();
    }
    
    public function searchAction(Request $request) {
        
        $em             = $this->getDoctrine()->getManager();
        $mostRead       = $em->getRepository('ApplicationSonataNewsBundle:Post')->findMostRead();
        $tags           = $em->getRepository('ApplicationSonataClassificationBundle:Tag')->findByContext('news');
        
        
        if ($request->getMethod() == "POST") {
            $search = $request->request->get('search');
            
            if (!is_null($search) && !empty($search)) {
                
                $posts  = $em->getRepository('ApplicationSonataNewsBundle:Post')->search($search);
                 
                $paginator  = $this->get('knp_paginator');
                $pagination = $paginator->paginate(
                    $posts, $request->query->get('page', 1)/* page number */, 10/* limit per page */
                );
                return $this->render('ApplicationSonataNewsBundle::search.html.twig', array(
                    'mostRead'  => $mostRead,
                    'tags'      => $tags,
                    'pagination'=> $pagination,
                    'search'    => $search
                ));
            }
        }
        return $this->redirect( $this->generateUrl('sonata_news_home') );
        
        
    }
    
    /**
     * @throws NotFoundHttpException
     *
     * @param $permalink
     *
     * @return Response
     */
    public function viewAction($permalink)
    {
        $em             = $this->getDoctrine()->getManager();
        $mostRead       = $em->getRepository('ApplicationSonataNewsBundle:Post')->findMostRead();
        $tags           = $em->getRepository('ApplicationSonataClassificationBundle:Tag')->findByContext('news');
        
        $post = $this->getPostManager()->findOneByPermalink($permalink, $this->container->get('sonata.news.blog'));
        if (!$post || !$post->isPublic()) {
            throw new NotFoundHttpException('Unable to find the post');
        }
        if ($seoPage = $this->getSeoPage()) {
            $seoPage
                ->setTitle($post->getTitle())
                ->addMeta('name', 'description', $post->getAbstract())
                ->addMeta('property', 'og:title', $post->getTitle())
                ->addMeta('property', 'og:type', 'blog')
                ->addMeta('property', 'og:url', $this->generateUrl('sonata_news_view', array(
                    'permalink'  => $this->getBlog()->getPermalinkGenerator()->generate($post, true),
                ), true))
                ->addMeta('property', 'og:description', $post->getAbstract())
            ;
        }
        return $this->render('SonataNewsBundle:Post:view.html.twig', array(
            'post' => $post,
            'form' => false,
            'blog' => $this->get('sonata.news.blog'),
            'mostRead'  => $mostRead,
            'tags'      => $tags,
        ));
    }
    
}

