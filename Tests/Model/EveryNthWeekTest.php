<?php

namespace Mallapp\EventmodelBundle\Tests\Model;

use Mallapp\EventmodelBundle\Model\EveryNthWeek;

class EveryNthWeekTest extends \PHPUnit_Framework_TestCase
{
	
	
	public function testInclude()
	{
		

		// Try with invalid input, check for input validation to default values 1 and check case A: start date is after the weekday
		// Must default to "every monday" where as the startday is a tuesday.
		
		$every = new EveryNthWeek(\DateTime::createFromFormat(\DateTime::ISO8601, '2016-10-18T00:00:00+0000'), 15, -10); 
		
		$this->assertFalse($every->includes(\DateTime::createFromFormat(\DateTime::ISO8601, '2016-10-10T00:00:00+0000')));
		$this->assertFalse($every->includes(\DateTime::createFromFormat(\DateTime::ISO8601, '2016-10-17T00:00:00+0000')));
		$this->assertFalse($every->includes(\DateTime::createFromFormat(\DateTime::ISO8601, '2016-10-18T00:00:00+0000')));
		$this->assertFalse($every->includes(\DateTime::createFromFormat(\DateTime::ISO8601, '2016-10-19T00:00:00+0000')));
		$this->assertFalse($every->includes(\DateTime::createFromFormat(\DateTime::ISO8601, '2016-10-23T00:00:00+0000')));
		$this->assertTrue($every->includes(\DateTime::createFromFormat(\DateTime::ISO8601, '2016-10-24T00:00:00+0000')));
		$this->assertFalse($every->includes(\DateTime::createFromFormat(\DateTime::ISO8601, '2016-10-25T00:00:00+0000')));
		$this->assertTrue($every->includes(\DateTime::createFromFormat(\DateTime::ISO8601, '2016-10-31T00:00:00+0000')));
		$this->assertTrue($every->includes(\DateTime::createFromFormat(\DateTime::ISO8601, '2017-05-22T00:00:00+0000')));
		$this->assertTrue($every->includes(\DateTime::createFromFormat(\DateTime::ISO8601, '2023-10-23T00:00:00+0000')));		
		
		// Try case B: start date is before the weekday. And try every second week. Still no enddate
		
		$every = new EveryNthWeek(\DateTime::createFromFormat(\DateTime::ISO8601, '2016-10-18T00:00:00+0000'), 3, 2);
		
		$this->assertFalse($every->includes(\DateTime::createFromFormat(\DateTime::ISO8601, '2016-10-05T00:00:00+0000')));
		$this->assertFalse($every->includes(\DateTime::createFromFormat(\DateTime::ISO8601, '2016-10-12T00:00:00+0000')));
		$this->assertFalse($every->includes(\DateTime::createFromFormat(\DateTime::ISO8601, '2016-10-17T00:00:00+0000')));
		$this->assertFalse($every->includes(\DateTime::createFromFormat(\DateTime::ISO8601, '2016-10-18T00:00:00+0000')));
		$this->assertTrue($every->includes(\DateTime::createFromFormat(\DateTime::ISO8601, '2016-10-19T00:00:00+0000')));
		$this->assertFalse($every->includes(\DateTime::createFromFormat(\DateTime::ISO8601, '2016-10-20T00:00:00+0000')));
		$this->assertFalse($every->includes(\DateTime::createFromFormat(\DateTime::ISO8601, '2016-10-26T00:00:00+0000')));
		$this->assertTrue($every->includes(\DateTime::createFromFormat(\DateTime::ISO8601, '2016-11-02T00:00:00+0000')));
		$this->assertFalse($every->includes(\DateTime::createFromFormat(\DateTime::ISO8601, '2016-11-09T00:00:00+0000')));
		$this->assertTrue($every->includes(\DateTime::createFromFormat(\DateTime::ISO8601, '2016-11-16T00:00:00+0000')));
		
		// Every third friday and an end date
		
		$every = new EveryNthWeek(\DateTime::createFromFormat(\DateTime::ISO8601, '2016-10-18T00:00:00+0000'), 5, 3, \DateTime::createFromFormat(\DateTime::ISO8601, '2016-12-31T00:00:00+0000'));
		
		$this->assertTrue($every->includes(\DateTime::createFromFormat(\DateTime::ISO8601, '2016-10-21T00:00:00+0000')));
		$this->assertTrue($every->includes(\DateTime::createFromFormat(\DateTime::ISO8601, '2016-11-11T00:00:00+0000')));
		$this->assertTrue($every->includes(\DateTime::createFromFormat(\DateTime::ISO8601, '2016-12-02T00:00:00+0000')));
		$this->assertTrue($every->includes(\DateTime::createFromFormat(\DateTime::ISO8601, '2016-12-23T00:00:00+0000')));
		
		$this->assertFalse($every->includes(\DateTime::createFromFormat(\DateTime::ISO8601, '2016-10-28T00:00:00+0000')));
		$this->assertFalse($every->includes(\DateTime::createFromFormat(\DateTime::ISO8601, '2016-11-04T00:00:00+0000')));		
		$this->assertFalse($every->includes(\DateTime::createFromFormat(\DateTime::ISO8601, '2016-11-18T00:00:00+0000')));
		$this->assertFalse($every->includes(\DateTime::createFromFormat(\DateTime::ISO8601, '2016-11-25T00:00:00+0000')));
		$this->assertFalse($every->includes(\DateTime::createFromFormat(\DateTime::ISO8601, '2016-12-09T00:00:00+0000')));
		$this->assertFalse($every->includes(\DateTime::createFromFormat(\DateTime::ISO8601, '2016-12-16T00:00:00+0000')));
		$this->assertFalse($every->includes(\DateTime::createFromFormat(\DateTime::ISO8601, '2016-12-30T00:00:00+0000')));
		$this->assertFalse($every->includes(\DateTime::createFromFormat(\DateTime::ISO8601, '2017-01-06T00:00:00+0000')));
		$this->assertFalse($every->includes(\DateTime::createFromFormat(\DateTime::ISO8601, '2017-01-13T00:00:00+0000')));
		
		
		
	}
	
	
}