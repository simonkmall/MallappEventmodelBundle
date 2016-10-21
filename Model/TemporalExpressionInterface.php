<?php

namespace Mallapp\EventmodelBundle\Model;

use \DateTime;
use \DateInterval;


interface TemporalExpressionInterface
{
	
	public function includes(DateTime $date);
	
	
}