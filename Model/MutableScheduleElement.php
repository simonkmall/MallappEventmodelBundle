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
 * Description of MutableScheduleElement
 *
 * @author Simon Mall
 */
class MutableScheduleElement {
    
    private $eventId;
    
    private $expression;
    
    /**
     * 
     * @param TemporalExpressionInterface $expression A TemporalExpression
     * @param int $eventId Reference to an event (the event itself is not managed by the Eventmodel Bundle.
     */
    public function __construct(TEDifference $DiffExpression, $eventId) {
        
        $this->eventId = $eventId;
        $this->expression = $DiffExpression;
        
    }

    function getEventId() {
        return $this->eventId;
    }
    
    function getExpression() {
        return $this->expression;
    }


    
}
