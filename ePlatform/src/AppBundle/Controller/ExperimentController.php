<?php
/**
 * Created by PhpStorm.
 * User: Tsvetelin
 * Date: 30/12/2018
 * Time: 16:27
 */

namespace AppBundle\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\Annotation\Route;

class ExperimentController extends Controller
{
    /**
     * @Route("/experiments", name="experiments")
     */
    public function experimentsAction(){
        return $this->render('user/experiments.html.twig');
    }
}