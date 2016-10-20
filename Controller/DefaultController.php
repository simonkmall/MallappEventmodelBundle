<?php

namespace Mallapp\EventmodelBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Mallapp\EventmodelBundle\Model\DaySequence;

class DefaultController extends Controller
{
    public function indexAction()
    {
        
        $sequence = new DaySequence(\DateTime::createFromFormat('Y-m-d', '2016-02-15'), \DateTime::createFromFormat('Y-m-d', '2016-02-20'));
        
        return $this->render('MallappEventmodelBundle:Default:index.html.twig');
    }
}
