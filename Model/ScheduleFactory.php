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


/**
 * Generates the Schedule object tree from a JSON string.
 * The JSON string must be generated via the JsonSerializable interface, i.e.
 * via json_encode($schedule).
 *
 * @author Simon Mall
 */
class ScheduleFactory {
    
    public static function createScheduleFromJson($jsonString) {
        
        $schedule = new Schedule();
        
        $jsonObject = json_decode($jsonString, true);
        
        if ($jsonObject == NULL) {
            
            return $schedule;
            
        }
        
        
        if (!is_array($jsonObject)) {
            
            throw new Exception("Eventmodel: Invalid JSON String provided");
            
        }
        
        // Loop through all elements in the schedule
        
        foreach ($jsonObject as $element) {
            
            $eventId = $element['eventId'];
            
            $includedUnionArray = $element['expression']['difference']['included']['union'];
     
            foreach ($includedUnionArray as $includedExpression) {
                
                $schedule->includeExpressionForEvent($eventId, 
                        self::createTemporalExpression($includedExpression)
                        );
                
            }
            
            $excludedUnionArray = $element['expression']['difference']['excluded']['union'];
            
            foreach ($excludedUnionArray as $excludedExpression) {
                
                $schedule->excludeExpressionForEvent($eventId,
                        self::createTemporalExpression($excludedExpression)
                        );
                
            }
            
        }
        
        return $schedule;
        
    }
    
    private static function createTemporalExpression($expressionArray) {
        
        // Recursively create the object tree for the temporal expression.
        
        $objectName = array_keys($expressionArray)[0];
        
        switch ($objectName) {
            
            case "union":
                
                return self::createUnion($expressionArray['union']);
            
            case "intersection":
                
                return self::createIntersection($expressionArray['intersection']);
            
            case "difference":
                
                return self::createDifference($expressionArray['difference']);
            
            case "singleDay":
                
                return self::createSingleDay($expressionArray['singleDay']);
            
            case "daySequence":
                
                return self::createDaySequence(
                        $expressionArray['daySequence']['startDate'],
                        $expressionArray['daySequence']['endDate']
                        );
            
            case "everyNthWeek":
                
                return self::createNth(
                        $expressionArray['everyNthWeek']['startDate'], 
                        $expressionArray['everyNthWeek']['endDate'], 
                        $expressionArray['everyNthWeek']['dayOfWeek'], 
                        $expressionArray['everyNthWeek']['weekInterval']
                        );
            
        }
        
        
    }
    
    private static function createUnion($unionArray) {
    
        $returnUnionObject = new TEUnion();
                
        foreach ($unionArray as $union) {
            
            $returnUnionObject->addItem(self::createTemporalExpression($union));
            
        }
        
        return $returnUnionObject;
        
    }
    
    private static function createIntersection($intersectArray) {
    
        $returnIntersectionObject = new TEIntersection();
                
        foreach ($intersectArray as $intersect) {
            
            $returnIntersectionObject->addItem(self::createTemporalExpression($intersect));
            
        }
        
        return $returnIntersectionObject;
        
    }
    
    private static function createDifference($differenceArray) {
        
        $returnDifference = new TEDifference();
        
        $returnDifference->overrideIncludedUnion(
                self::createUnion($differenceArray['included']['union'])
            );
        
        $returnDifference->overrideExcludedUnion(
                self::createUnion($differenceArray['excluded']['union'])
            );
        
        return $returnDifference;
        
    }
    
    private static function createSingleDay($dateString) {
        
        $date = \DateTime::createFromFormat(\DateTime::ISO8601,$dateString);
        
        return new SingleDay($date);
                        
    }
    
    private static function createDaySequence($startDateString, $endDateString) {
        
        $startDate = \DateTime::createFromFormat(\DateTime::ISO8601,$startDateString);

        $endDate = null;
        
        if ($endDateString != null) {
            
            $endDate = \DateTime::createFromFormat(\DateTime::ISO8601,$endDateString);
            
        }
        
        return new DaySequence($startDate, $endDate);
        
    }
    
    private static function createNth($startDateString, $endDateString, $dayOfWeek, $weekInterval) {
        
        $startDate = \DateTime::createFromFormat(\DateTime::ISO8601,$startDateString);

        $endDate = null;
        
        if ($endDateString != null) {
            
            $endDate = \DateTime::createFromFormat(\DateTime::ISO8601,$endDateString);
            
        }
        
        return new EveryNthWeek($startDate, $dayOfWeek, $weekInterval, $endDate);
        
    }
    
}
