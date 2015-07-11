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
        $em                  = $this->getDoctrine()->getManager();
        
        
        
    }
    public function addAction()
    {
        
    }
    
    public function deleteAction()
    {
        
        
    }
    
   public function listAction(Request $request)
    {
        
         
    }
   
    public function searchAction()
    {
        
         
    }
    
     public function showAction($id)
    {
        
         
    }
    
    public function editAction($id)
    {
        
        
    }
}
