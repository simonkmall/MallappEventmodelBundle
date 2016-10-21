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

use DateTime;
use DateInterval;

class EveryNthWeek implements TemporalExpressionInterface
{
	
	
	protected $startDate;
	protected $actualFirstDate;
	protected $endDate;
	protected $dayOfWeek;
	protected $weekInterval;

	
	/***
	 * Creates a Temporal Expression object for everye nth Week.
	 * @param DateTime $startDate
	 * @param int $dayOfWeek The day of the week according to ISO-8601 (1 for Monday through 7 for Sunday)
	 * @param int $weekInterval
	 * @param DateTime $endDate
	 */
	public function __construct(DateTime $startDate, $dayOfWeek, $weekInterval, DateTime $endDate = null)
	{
		$this->startDate = $startDate;
		$this->actualFirstDate = clone $startDate;
		$this->endDate = $endDate;
		$this->dayOfWeek = $dayOfWeek;
		$this->weekInterval = $weekInterval;
		
		// Check for invalid input
		
		// Default to one week interval
		if ($this->weekInterval < 1) {
			$this->weekInterval = 1;
		}
		
		// Default to Monday
		if (($this->dayOfWeek < 1) || ($this->dayOfWeek > 7)) {
			$this->dayOfWeek = 1;
		}
		
		// Calculate the actual fist date 

		if ($this->dayOfWeek($this->startDate) > $this->dayOfWeek) {
			
			// We have to increment a week because our startday is later in the week than our dayOfWeek
			// E.g. Startdate is a Wednesday, but dayOfWeek is Monday. So we actually start on Monday of next Week.
			// We have to actually increment the startdate by 7 - (diff between the two dayOfWeeks)
			$incrementBy = 7 - ($this->dayOfWeek($this->startDate) - $this->dayOfWeek);
			
			$this->actualFirstDate->add(new DateInterval('P'.$incrementBy.'D'));
				
		}
		else if ($this->dayOfWeek($this->startDate) < $this->dayOfWeek) {
			
			$incrementBy = $this->dayOfWeek - $this->dayOfWeek($this->startDate);
			
			$this->actualFirstDate->add(new DateInterval('P'.$incrementBy.'D'));
			
		}
		
	
		
	}
	
	public function includes(DateTime $date) 
	{
		
		return $this->dateAfterStartDate($date) && 
			$this->dateBeforeEndDate($date) && 
			$this->dayOfWeekMatches($date) && 
			$this->weekIntervalMatches($date);
		
	}
	
	
	private function dateAfterStartDate(\DateTime $date) {
		
		
		return $this->startDate <= $date;
			
	}
	
	private function dateBeforeEndDate(\DateTime $date) {
		
		if ($this->endDate == null) {
			
			// No enddate, so every date is before it.
			return true;
			
		}
		
		return $date <= $this->endDate;
		
	}
	
	private function dayOfWeekMatches(DateTime $date) {
		
		return $this->dayOfWeek == $this->dayOfWeek($date);
		
	}
	
	private function weekIntervalMatches(DateTime $date) {
		
		
		$intervalInDays = $this->actualFirstDate->diff($date)->days;
		
		$intervalInWeeks = floor($intervalInDays / 7);
		
		return $intervalInWeeks % $this->weekInterval == 0;
		
	}
	
	private function dayOfWeek(DateTime $date) {
		
		return intval($date->format("N"));
		
	}
	
	
}