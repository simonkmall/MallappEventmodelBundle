<?php

namespace Mallapp\EventmodelBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('MallappEventmodelBundle:Default:index.html.twig');
    }
}
