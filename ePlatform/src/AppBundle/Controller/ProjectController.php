<?php

namespace AppBundle\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class ProjectController extends Controller
{
    /**
     * @Route("/electricity", name="electricity")
     */
    public function electricityAction(){
        return $this->render('projects/electricity.html.twig');
    }

    /**
     * @Route("/mechanics", name="mechanics")
     */
    public function mechanicsAction(){
        return $this->render('projects/mechanics.html.twig');
    }

    /**
     * @Route("/optics", name="optics")
     */
    public function opticsAction(){
        return $this->render('projects/optics.html.twig');
    }
}