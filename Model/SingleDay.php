<?php

namespace Mallapp\EventmodelBundle\Model;

use Mallapp\EventmodelBundle\Model\TemporalExpressionInterface;


class SingleDay implements TemporalExpressionInterface
{
	
	protected $singleDate;

	
	/**
         * Creates a Temporal Expression object for a single date.
         * @param \DateTime $date
         */
	public function __construct(\DateTime $date)
	{

		$this->singleDate = $date;

	}
	
	
	public function includesDate(\DateTime $date) 
	{
		
		return $this->singleDate == $date;

	}
	
	
}
