<?php

namespace TeaCampus\CommonBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
        $em             = $this->getDoctrine()->getManager();
        $lastNews       = $em->getRepository('ApplicationSonataNewsBundle:Post')->findLastNews(3);
        
        return $this->render('TeaCampusCommonBundle:Home:index.html.twig', array(
            'lastNews' => $lastNews
        ));
    }
}
