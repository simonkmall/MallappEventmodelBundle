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
		
		$this->assertFalse($seqMinusFive->includes($beforeDate));
		$this->assertFalse($seqMinusFive->includes($atDate));
		$this->assertFalse($seqMinusFive->includes($afterDate));
		$this->assertFalse($seqMinusFive->includes($atTenDaysAfterDate));
		$this->assertFalse($seqMinusFive->includes($atElevenDaysAfterDate));
		
		
		// Try with a single date
		$seqOne = new DaySequence(\DateTime::createFromFormat(\DateTime::ISO8601, '2016-02-15T00:00:00+0000'));
		
		$this->assertFalse($seqOne->includes($beforeDate));
		$this->assertTrue($seqOne->includes($atDate));
		$this->assertFalse($seqOne->includes($afterDate));
		$this->assertFalse($seqOne->includes($atTenDaysAfterDate));
		$this->assertFalse($seqOne->includes($atElevenDaysAfterDate));
		
		
		// Try valid sequence of ten days
		$seqTen = new DaySequence(\DateTime::createFromFormat(\DateTime::ISO8601, '2016-02-15T00:00:00+0000'),
				\DateTime::createFromFormat(\DateTime::ISO8601, '2016-02-24T00:00:00+0000'));
		
		$this->assertFalse($seqTen->includes($beforeDate));
		$this->assertTrue($seqTen->includes($atDate));
		$this->assertTrue($seqTen->includes($afterDate));
		$this->assertTrue($seqTen->includes($atTenDaysAfterDate));
		$this->assertFalse($seqTen->includes($atElevenDaysAfterDate));
		
	}
	
	
}