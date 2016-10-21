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

use Mallapp\EventmodelBundle\Model\ScheduleFactory;
use Mallapp\EventmodelBundle\Model\TEUnion;
use Mallapp\EventmodelBundle\Model\TEIntersection;
use Mallapp\EventmodelBundle\Model\Schedule;
use Mallapp\EventmodelBundle\Model\DaySequence;
use Mallapp\EventmodelBundle\Model\SingleDay;
use Mallapp\EventmodelBundle\Model\SimpleDate;




/**
 * Description of ScheduleFactoryTest
 *
 * @author Simon Mall
 */
class ScheduleFactoryTest extends \PHPUnit_Framework_TestCase {
    
    public function testFactory() {
        
        // Create a complex schedule which nests multiple set expressions
        
        $schedule = new Schedule();
        
        $unionOne = new TEUnion();
        $unionOne->addItem(new SingleDay(SimpleDate::create("2014-07-01")));
        $unionOne->addItem(new SingleDay(SimpleDate::create("2014-07-02")));
        $unionOne->addItem(new SingleDay(SimpleDate::create("2014-07-03")));
  
        $deepIntersect = new TEIntersection();
        $deepIntersect->addItem(new DaySequence(SimpleDate::create("2014-01-01"), SimpleDate::create("2014-07-03")));
        $deepIntersect->addItem(new DaySequence(SimpleDate::create("2014-07-03"), SimpleDate::create("2014-12-31")));
                
        $unionTwo = new TEUnion();
        $unionTwo->addItem($deepIntersect);
        $unionTwo->addItem(new SingleDay(SimpleDate::create("2014-07-04")));
        $unionTwo->addItem(new SingleDay(SimpleDate::create("2014-07-05")));
        
        $intersect = new TEIntersection();
        $intersect->addItem($unionOne);
        $intersect->addItem($unionTwo);
        
        $schedule->includeExpressionForEvent(0, $intersect);
        
        // Assert before encoding/decoding
        $this->assertSchedule($schedule);

        // Encode to JSON
        $jsonString = json_encode($schedule);
        
        // Assert that a valid jsonSting was created
        $this->assertFalse($jsonString == FALSE);
        
        //print($jsonString);
        
        // Re-build with factory ("decode")
        $rebuildedSchedule = ScheduleFactory::createScheduleFromJson($jsonString);
        
        // Assert after encoding/decoding
        $this->assertSchedule($rebuildedSchedule);
        
        
    }
    
    private function assertSchedule($schedule) {
        
        $allEvents = $schedule->allDates(SimpleDate::create("2013-01-01"), SimpleDate::create("2016-12-31"));
        
        $this->assertEquals(count($allEvents), 1);
        $this->assertEquals($allEvents[0]->getDate()->format("Y-m-d H:i:s"), "2014-07-03 00:00:00");
        
    }
    
}
