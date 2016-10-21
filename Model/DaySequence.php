<?php

/*
 * The MIT License
 *
 * Copyright 2016 Simon Mall.
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 */

namespace Mallapp\EventmodelBundle\Model;



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
	
	
	public function includes(\DateTime $date) 
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
