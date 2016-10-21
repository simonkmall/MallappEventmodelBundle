<?php

namespace Mallapp\EventmodelBundle\Tests\Model;

use Mallapp\EventmodelBundle\Model\SingleDay;

class SingleDayTest extends \PHPUnit_Framework_TestCase
{
	
	
	public function testInclude()
	{
		   
		// Prepare test dates for assertion
		$beforeDate = \DateTime::createFromFormat(\DateTime::ISO8601, '2016-02-14T00:00:00+0000');
		$atDate = \DateTime::createFromFormat(\DateTime::ISO8601, '2016-02-15T00:00:00+0000');
		$afterDate = \DateTime::createFromFormat(\DateTime::ISO8601, '2016-02-16T00:00:00+0000');
		
		// Try with a single date
		$seqOne = new SingleDay(\DateTime::createFromFormat(\DateTime::ISO8601, '2016-02-15T00:00:00+0000'));
		
		$this->assertFalse($seqOne->includes($beforeDate));
		$this->assertTrue($seqOne->includes($atDate));
		$this->assertFalse($seqOne->includes($afterDate));

		

		
	}
	
	
}