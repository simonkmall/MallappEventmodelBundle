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
    
}
