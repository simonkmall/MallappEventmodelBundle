<?php

namespace Mallapp\EventmodelBundle\Model;

use Mallapp\EventmodelBundle\Model\TemporalExpressionInterface;


class DaySequence implements TemporalExpressionInterface
{
	
	
	protected $startDate;
	protected $endDate;
	
	/***
	 * Creates a Temporal Expression object for a sequence of consequtive days given a start and an end date.
	 * @param DateTime $startDate
	 * @param DateTime $endDate And EndDate of null indicates a single date (i.e. the start date)
	 */
	public function __construct(\DateTime $startDate, \DateTime $endDate = null)
	{

		$this->startDate = $startDate;
		
		if ($endDate == null) {
			
			$this->endDate = clone $startDate;
			
		}
		else {
			
			$this->endDate = $endDate;
			
		}

	}
	
	
	public function includesDate(\DateTime $date) 
	{
		
		return $this->dateAfterStartDate($date) && $this->dateBeforeEndDate($date);

	}
	
	
	private function dateAfterStartDate(\DateTime $date) {
		
		
		return $this->startDate <= $date;
			
	}
	
	private function dateBeforeEndDate(\DateTime $date) {
		
		return $date <= $this->endDate;
		
	}
	

	
}
