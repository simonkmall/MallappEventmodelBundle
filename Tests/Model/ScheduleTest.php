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

namespace Mallapp\EventmodelBundle\Tests\Model;

use Mallapp\EventmodelBundle\Model\Schedule;
use Mallapp\EventmodelBundle\Model\DaySequence;
use Mallapp\EventmodelBundle\Model\SingleDay;
use Mallapp\EventmodelBundle\Model\EveryNthWeek;
use Mallapp\EventmodelBundle\Model\TEIntersection;
use Mallapp\EventmodelBundle\Model\SimpleDate;



/**
 * Description of ScheduleTest
 *
 * @author Simon Mall
 */
class ScheduleTest extends \PHPUnit_Framework_TestCase {
    
    public function testSimpleScheduleSmokeTest() {
        
        // Create simple schedule
        
        $schedule = new Schedule();
        
        $eventId = 143;
        $secondEventId = 94;
        
        $schedule->includeExpressionForEvent($eventId, new DaySequence(SimpleDate::create("2016-10-21"), SimpleDate::create("2016-10-27")));
        $schedule->excludeExpressionForEvent($eventId, new SingleDay(SimpleDate::create("2016-10-23")));

        $schedule->includeExpressionForEvent($secondEventId, new DaySequence(SimpleDate::create("2016-10-21"), SimpleDate::create("2016-10-27")));
        $schedule->excludeExpressionForEvent($secondEventId, new DaySequence(SimpleDate::create("2016-10-22"), SimpleDate::create("2016-10-27")));
        
        $oneEvent = $schedule->datesForEventId($eventId, SimpleDate::create("2016-10-01"), SimpleDate::create("2016-10-31"));
        $allEvents = $schedule->allDates(SimpleDate::create("2016-10-01"), SimpleDate::create("2016-10-31"));
        
        // Assert just the array sizes... just a rough smoke test,
        // content is not yet evaluated
        $this->assertEquals(count($oneEvent), 6);
        $this->assertEquals(count($allEvents), 7);
        
        
    }
    
    
    public function testDateRangeLimitation() {
        
        // Create simple schedule
        
        $schedule = new Schedule();
        
        $eventId = 143;
        
        $schedule->includeExpressionForEvent($eventId, new DaySequence(SimpleDate::create("2016-10-01"), SimpleDate::create("2016-10-31")));
        $schedule->excludeExpressionForEvent($eventId, new SingleDay(SimpleDate::create("2016-10-23")));
        
        $allEvents = $schedule->allDates(SimpleDate::create("2016-10-05"), SimpleDate::create("2016-10-25"));
        
        // Assert just the array sizes... just a rough smoke test,
        // content is not yet evaluated
        $this->assertEquals(count($allEvents), 20);
        
        
    }
    
        
    public function testContents() {
        
        // Create simple schedule
        
        $schedule = new Schedule();
        
        $eventId = 143;
        
        $schedule->includeExpressionForEvent($eventId, new DaySequence(SimpleDate::create("2016-10-01"), SimpleDate::create("2016-10-05")));
        $schedule->excludeExpressionForEvent($eventId, new DaySequence(SimpleDate::create("2016-10-02"), SimpleDate::create("2016-10-04")));
        
        $allEvents = $schedule->allDates(SimpleDate::create("2016-10-01"), SimpleDate::create("2016-10-05"));
        
        // Assert just the array sizes... just a rough smoke test,
        // content is not yet evaluated
        $this->assertEquals(count($allEvents), 2);
        $this->assertEquals($allEvents[0]->getEventId(), $eventId);
        $this->assertEquals($allEvents[0]->getDate()->format("Y-m-d H:i:s"), "2016-10-01 00:00:00");
        $this->assertEquals($allEvents[1]->getEventId(), $eventId);
        $this->assertEquals($allEvents[1]->getDate()->format("Y-m-d H:i:s"), "2016-10-05 00:00:00");
        
    }
    
    public function testJsonSerialization() {
        
        // Create a more complex (nested) schedule
        
        $schedule = new Schedule();
        
        $schedule->includeExpressionForEvent(0, new SingleDay(SimpleDate::create("2014-07-01")));
        $schedule->includeExpressionForEvent(1, new DaySequence(SimpleDate::create("2016-10-01"), SimpleDate::create("2016-10-30")));
        $schedule->includeExpressionForEvent(2, new EveryNthWeek(SimpleDate::create("2016-12-30"), 3, 2));
        
        $schedule->excludeExpressionForEvent(3, new SingleDay(SimpleDate::create("2014-07-01")));
        $schedule->excludeExpressionForEvent(4, new DaySequence(SimpleDate::create("2016-10-28"), SimpleDate::create("2016-11-04")));
        $schedule->excludeExpressionForEvent(5, new EveryNthWeek(SimpleDate::create("2016-12-30"), 3, 3));
        
        $jsonString = json_encode($schedule);
        
        // Assert that a valid jsonSting was created
        
        $this->assertFalse($jsonString == FALSE);
        
        // Convert back to object and assert content (at least partially)
        
        $jsonObject = json_decode($jsonString, true);
        
        $this->assertTrue(is_array($jsonObject));
        
        $this->assertEquals(count($jsonObject), 6);
        
        $this->assertTrue(is_array($jsonObject[0]));
        
        $this->assertArrayHasKey("eventId", $jsonObject[0]);
        
        $this->assertEquals($jsonObject[0]['eventId'], 0);
        
        $this->assertArrayHasKey("expression", $jsonObject[0]);
        
        $expression = $jsonObject[0]['expression'];
        
        $this->assertTrue(is_array($expression));
        
        $this->assertArrayHasKey("difference", $expression);
        
        $this->assertTrue(is_array($expression['difference']));
        
        $this->assertArrayHasKey("excluded", $expression['difference']);
        
        $this->assertArrayHasKey("included", $expression['difference']);
        
        $this->assertTrue(is_array($expression['difference']['included']));
        
        $this->assertArrayHasKey("union", $expression['difference']['included']);
        
        $union = $expression['difference']['included']['union'];
        
        $this->assertTrue(is_array($union));
        
        $this->assertTrue(is_array($union[0]));
        
        $this->assertArrayHasKey("singleDay", $union[0]);
        
        $this->assertEquals($union[0]['singleDay'], "2014-07-01T00:00:00+0000");
        
        $endDateString = $jsonObject[1]['expression']['difference']['included']['union'][0]['daySequence']['endDate'];
        
        $this->assertEquals($endDateString, "2016-10-30T00:00:00+0000");
        
        $startDateString = $jsonObject[2]['expression']['difference']['included']['union'][0]['everyNthWeek']['startDate'];
        
        $this->assertEquals($startDateString, "2016-12-30T00:00:00+0000");
 
        $weekInterval = $jsonObject[5]['expression']['difference']['excluded']['union'][0]['everyNthWeek']['weekInterval'];
        
        $this->assertEquals($weekInterval, 3);

    }
    
}
