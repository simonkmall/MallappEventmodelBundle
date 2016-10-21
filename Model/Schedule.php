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

use \DateTime;
use \DateInterval;
use \DatePeriod;

/**
 * Main class for the interaction with the EventmodelBundle.
 * Dates can be added and excluded and associated with eventId's.
 * List of Dates can be obtained. Complete Datastructure is serializable as
 * Json for persisting it in a DB.
 *
 * @author Simon Mall
 */
class Schedule
{

    protected $elements = array();
    
    static public function createFromJson($JsonString) {
        
        
    }
    
    public function jsonString() {
        
        
    }
    
    public function datesForEventId($eventId, DateTime $fromDate, DateTime $toDate) {

        if ((!$this->elementForEventExists($eventId)) || ($fromDate > $toDate)) {
            
            // Return an empty array
            return array();
            
        }
        
        $elementsArray = array();
        $elementsArray[] = $this->elements[$eventId];
        
        return $this->getDatesFromElementArray($fromDate, $toDate, $elementsArray);
        
    }
    
    public function allDates(DateTime $fromDate, DateTime $toDate) {

        if ($fromDate > $toDate) {
            
            // Return an empty array
            return array();
            
        }
        
        // Get dates from the whole elements array
        return $this->getDatesFromElementArray($fromDate, $toDate, $this->elements);
        
    }
    
    private function getDatesFromElementArray(DateTime $fromDate, DateTime $toDate, $elementArray) {
        
        $eventDatesArray = array();
        
        $fromDate->setTime(0, 0, 0);
        $toDate->setTime(0, 0, 0);
        
        $interval = DateInterval::createFromDateString('1 day');
        
        // Add a day to the toDate to include it in the period.
        $toDate->add($interval);
        
        $period = new DatePeriod($fromDate, $interval, $toDate);
        
        // Loop through all dates in the range
        foreach ($period as $currentDate) {
            
            // Loop through all events
            foreach ($elementArray as $element) {
            
                if ($element->getExpression()->includes($currentDate)) {
                    
                    $eventDatesArray[] = new EventDate($element->getEventId(), $currentDate);
                    
                }
            
            }
            
        }
        
        return $eventDatesArray;
        
    }
    
    public function includeExpressionForEvent($eventId, TemporalExpressionInterface $expression) {
 
        $this->createElementIfNotExisting($eventId);
        
        $this->getElement($eventId)->getExpression()->addInclusion($expression);
        
    }
    
    public function excludeExpressionForEvent($eventId, TemporalExpressionInterface $expression) {
        
        $this->createElementIfNotExisting($eventId);
        
        $this->getElement($eventId)->getExpression()->addExclusion($expression);
        
    }
    
    private function createElementIfNotExisting($eventId) {
        
        $this->validateEventId($eventId);
    
        if (!$this->elementForEventExists($eventId)) {
            
            $this->createElement(new MutableScheduleElement(new TEDifference(), $eventId));
            
        }
        
    }
    
    private function createElement(MutableScheduleElement $element) {
        
        $eventId = $element->getEventId();
        
        $this->validateEventId($eventId);
        
        $this->elements[$eventId] = $element;
        
    }
    
    
    private function elementForEventExists($eventId) {
    
        $this->validateEventId($eventId);
        
        if (array_key_exists($eventId, $this->elements)) {
            
            return true;
            
        }
        
        return false;
        
    }
    
    private function getElement($eventId) {
        
        if ($this->elementForEventExists($eventId)) {
            
            return $this->elements[$eventId];
            
        }
        else {
            
            return null;
            
        }
        
    }
    

    private function validateEventId($eventId) {
        
        if (!is_int($eventId)) {
            
            throw new Exception("Invalid eventId given (".$eventId."). Must be of type Integer.");
            
        }
    }
    


}

