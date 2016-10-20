<?php

namespace Mallapp\EventmodelBundle\Tests\Model;

use Mallapp\EventmodelBundle\Model\DaySequence;

class DaySequenceTest extends \PHPUnit_Framework_TestCase
{
	
	
	public function testInclude()
	{
		
		// Prepare test dates for assertion
		$beforeDate = \DateTime::createFromFormat(\DateTime::ISO8601, '2016-02-14T00:00:00+0000');
		$atDate = \DateTime::createFromFormat(\DateTime::ISO8601, '2016-02-15T00:00:00+0000');
		$afterDate = \DateTime::createFromFormat(\DateTime::ISO8601, '2016-02-16T00:00:00+0000');
		$atTenDaysAfterDate = \DateTime::createFromFormat(\DateTime::ISO8601, '2016-02-24T00:00:00+0000');
		$atElevenDaysAfterDate = \DateTime::createFromFormat(\DateTime::ISO8601, '2016-02-25T00:00:00+0000');

		
		
		// Try with invalid interval
		$seqMinusFive = new DaySequence(\DateTime::createFromFormat(\DateTime::ISO8601, '2016-02-15T00:00:00+0000'), 
				\DateTime::createFromFormat(\DateTime::ISO8601, '2016-02-10T00:00:00+0000'));
		
		$this->assertFalse($seqMinusFive->includesDate($beforeDate));
		$this->assertFalse($seqMinusFive->includesDate($atDate));
		$this->assertFalse($seqMinusFive->includesDate($afterDate));
		$this->assertFalse($seqMinusFive->includesDate($atTenDaysAfterDate));
		$this->assertFalse($seqMinusFive->includesDate($atElevenDaysAfterDate));
		
		
		// Try with a single date
		$seqOne = new DaySequence(\DateTime::createFromFormat(\DateTime::ISO8601, '2016-02-15T00:00:00+0000'));
		
		$this->assertFalse($seqOne->includesDate($beforeDate));
		$this->assertTrue($seqOne->includesDate($atDate));
		$this->assertFalse($seqOne->includesDate($afterDate));
		$this->assertFalse($seqOne->includesDate($atTenDaysAfterDate));
		$this->assertFalse($seqOne->includesDate($atElevenDaysAfterDate));
		
		
		// Try valid sequence of ten days
		$seqTen = new DaySequence(\DateTime::createFromFormat(\DateTime::ISO8601, '2016-02-15T00:00:00+0000'),
				\DateTime::createFromFormat(\DateTime::ISO8601, '2016-02-24T00:00:00+0000'));
		
		$this->assertFalse($seqTen->includesDate($beforeDate));
		$this->assertTrue($seqTen->includesDate($atDate));
		$this->assertTrue($seqTen->includesDate($afterDate));
		$this->assertTrue($seqTen->includesDate($atTenDaysAfterDate));
		$this->assertFalse($seqTen->includesDate($atElevenDaysAfterDate));
		
	}
	
	
}