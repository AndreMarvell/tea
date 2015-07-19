<?php

namespace TeaCampus\CommonBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use TeaCampus\CommonBundle\Entity\Projet;
use TeaCampus\CommonBundle\Form\ProjetType;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ProjetController extends Controller {

    public function indexAction() {
        $em = $this->getDoctrine()->getManager();
        $projetRepo = $em->getRepository('TeaCampusCommonBundle:Projet');
        $idselectedProjet = $projetRepo->getSelectedProjet();
        $idProjectOfTheMonth = $projetRepo->getProjectOfTheMonth();
        $idProjectOfTheWeek = $projetRepo->getProjectOfTheWeek();
        $popularPosts       = $em->getRepository('ApplicationSonataNewsBundle:Post')->findPopular();
        $popularProjects    = $em->getRepository('TeaCampusCommonBundle:Projet')->findPopular();
        $popularVideos      = $em->getRepository('TeaCampusCommonBundle:Video')->findPopular();
        $tags               = $em->getRepository('ApplicationSonataClassificationBundle:Tag')->findBy(array('context'=>'projet'),null,25);
        
        $selectedProjet = $projetRepo->findOneById($idselectedProjet);
        $projetofthemonth = $projetRepo->findOneById($idProjectOfTheMonth);
        $projetoftheweek = $projetRepo->findOneById($idProjectOfTheWeek);

        $query = $em->createQuery(
                'SELECT p FROM TeaCampusCommonBundle:Projet p
                                    WHERE p.private = false
                                    AND p.enabled = true
                                    ORDER BY p.date DESC'
        );
        $query->setMaxResults(4);
        $latestProject = $query->getResult();



        return $this->render('TeaCampusCommonBundle:Projet:index.html.twig', array('selectionOftea' => $selectedProjet,
                    'projectofthemonth' => $projetofthemonth,
                    'latestproject' => $latestProject,
                    'popularPosts'     => $popularPosts,
                    'popularProjects'  => $popularProjects,
                    'popularVideos'    => $popularVideos,
                    'tags'             => $tags,
                    'projectoftheweek' => $projetoftheweek));
    }

    public function addAction() {

        $em = $this->getDoctrine()->getManager();
        $context = $em->getRepository('ApplicationSonataClassificationBundle:Context')->find('projet');
        $form = $this->createForm(new ProjetType($em, $context), new Projet());

        $form->handleRequest($this->getRequest());
        if ($form->isValid()) {

            $projet = $form->getData();
            // On recupere le current user
            $usr = $this->get('security.context')->getToken()->getUser();
            $projet->setAuthor($usr);

            $em->persist($projet);
            $em->flush();

//            \Doctrine\Common\Util\Debug::dump($projet->getMedia());
//            exit;


            $html = $this->container->get('templating')->render('TeaCampusCommonBundle:Projet:profile_list_item.html.twig', array('projet' => $projet));
            $response = new Response();
            $response->setContent(json_encode(array("success" => true, "content" => $html)));
            $response->headers->set('Content-Type', 'application/json');

            return $response;
        }

        return $this->render('TeaCampusCommonBundle:Projet:add.html.twig', array('form' => $form->createView()));
    }

    public function deleteAction() {
        $request = $this->get('request');
        $em = $this->getDoctrine()->getManager();
        $id = $request->request->get('id');
        $repository = $em->getRepository("TeaCampusCommonBundle:Projet");
        $projet = $repository->find($id);

        $user = $this->get('security.context')->getToken()->getUser();

        $success = false;
        if (is_null($projet) || $user->getId() !== $projet->getAuthor()->getId()) {
            throw new NotFoundHttpException("Désolé, la page que vous avez demandée semble introuvable !");
        } else {
            $success = true;
            $em->remove($projet);
            $em->flush();
        }

        $response = new Response();
        $response->setContent(json_encode(array("success" => $success)));
        $response->headers->set('Content-Type', 'application/json');

        return $response;
    }

    public function listAction(Request $request) {
        $em = $this->getDoctrine()->getManager();
        $projetRepo = $em->getRepository('TeaCampusCommonBundle:Projet');
        $popularPosts       = $em->getRepository('ApplicationSonataNewsBundle:Post')->findPopular();
        $popularProjects    = $em->getRepository('TeaCampusCommonBundle:Projet')->findPopular();
        $popularVideos      = $em->getRepository('TeaCampusCommonBundle:Video')->findPopular();
        $tags               = $em->getRepository('ApplicationSonataClassificationBundle:Tag')->findBy(array('context'=>'projet'),null,25);
        $dql = "SELECT a FROM TeaCampusCommonBundle:Projet a WHERE a.private = false AND a.enabled = true ORDER BY a.date DESC";
        $query = $em->createQuery($dql);
        $paginator = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
                $query, $request->query->get('page', 1)/* page number */, 8/* limit per page */
        );


        return $this->render('TeaCampusCommonBundle:Projet:list.html.twig', array(
                    'popularPosts'     => $popularPosts,
                    'popularProjects'  => $popularProjects,
                    'popularVideos'    => $popularVideos,
                    'tags'             => $tags,
                    'pagination' => $pagination));
    }

    

    public function showAction($id) {
        $em = $this->getDoctrine()->getManager();
        $projetRepo = $em->getRepository('TeaCampusCommonBundle:Projet');
        $projet = $projetRepo->findOneById($id);

        if (is_null($projet)) {
            throw new NotFoundHttpException("Désolé, la page que vous avez demandée semble introuvable !");
        } else {
            $query = $em->createQuery(
                    'SELECT p FROM TeaCampusCommonBundle:Projet p
                                    WHERE p.private = false
                                    AND p.enabled = true
                                    ORDER BY p.date DESC'
            );

            $randomProject = $query->getResult();
            shuffle($randomProject);

            $newArray = array_splice($randomProject, 0, 3);

            return $this->render('TeaCampusCommonBundle:Projet:show.html.twig', array('project' => $projet,
                        'randomproject' => $newArray));
        }
    }

    public function editAction($id) {

        $em = $this->getDoctrine()->getManager();
        $context = $em->getRepository('ApplicationSonataClassificationBundle:Context')->find('projet');
        $project = $em->getRepository('TeaCampusCommonBundle:Projet')->find($id);

        $project = (is_null($project)) ? new Projet() : $project;
        $form = $this->createForm(new ProjetType($em, $context), $project);

        $form->handleRequest($this->getRequest());
        if ($form->isValid()) {

            $projet = $form->getData();

            $em->persist($projet);
            $em->flush();

            $html = $this->container->get('templating')->render('TeaCampusCommonBundle:Projet:profile_list_item.html.twig', array('projet' => $projet));
            $response = new Response();
            $response->setContent(json_encode(array("success" => true, "content" => $html)));
            $response->headers->set('Content-Type', 'application/json');

            return $response;
        }

        return $this->render('TeaCampusCommonBundle:Projet:edit.html.twig', array('form' => $form->createView()));
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
            
            $projects           = $em->getRepository('TeaCampusCommonBundle:Projet')->findByTag($tag->getId());
            $popularPosts       = $em->getRepository('ApplicationSonataNewsBundle:Post')->findPopular();
            $popularProjects    = $em->getRepository('TeaCampusCommonBundle:Projet')->findPopular();
            $popularVideos      = $em->getRepository('TeaCampusCommonBundle:Video')->findPopular();
            $tags               = $em->getRepository('ApplicationSonataClassificationBundle:Tag')->findBy(array('context'=>'projet'),null,25);

            $request            = $this->get('request');
            $paginator          = $this->get('knp_paginator');
            $pagination         = $paginator->paginate(
                                        $projects, $request->query->get('page', 1)/* page number */, 10/* limit per page */
                                );
            
            return $this->render('TeaCampusCommonBundle:Search:search.html.twig', array(
                'tag' => $tag,
                'search_title'      => 'project.search.title',
                'search_subhead'    => 'project.search.subhead',
                'search_path'       => 'tea_projects_search',
                'search_no_result'  => 'project.search.no_result',
                'aside_search_title'=> 'aside.project.search',
                'type'              => 'projects',
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

                $projects           = $em->getRepository('TeaCampusCommonBundle:Projet')->search($search);
                $popularPosts       = $em->getRepository('ApplicationSonataNewsBundle:Post')->findPopular();
                $popularProjects    = $em->getRepository('TeaCampusCommonBundle:Projet')->findPopular();
                $popularVideos      = $em->getRepository('TeaCampusCommonBundle:Video')->findPopular();
                $tags               = $em->getRepository('ApplicationSonataClassificationBundle:Tag')->findBy(array('context'=>'projet'),null,25);

                $request            = $this->get('request');
                $paginator          = $this->get('knp_paginator');
                $pagination         = $paginator->paginate(
                                            $projects, $request->query->get('page', 1)/* page number */, 10/* limit per page */
                                    );

                return $this->render('TeaCampusCommonBundle:Search:search.html.twig', array(
                    'search_title'      => 'project.search.title',
                    'search_subhead'    => 'project.search.subhead',
                    'search_path'       => 'tea_projects_search',
                    'search_no_result'  => 'project.search.no_result',
                    'aside_search_title'=> 'aside.project.search',
                    'type'              => 'projects',
                    'search'            => $search,
                    'pagination'        => $pagination,
                    'popularPosts'     => $popularPosts,
                    'popularProjects'  => $popularProjects,
                    'popularVideos'    => $popularVideos,
                    'tags'             => $tags,
                ));
            }
        }
        return $this->redirect( $this->generateUrl('tea_projects') );
                
    }
    
    

}
