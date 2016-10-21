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
 * Set expression consisting of a union of included and a union of excluded
 * sub-expressions. Includes all dates which are included in one of the 
 * "inclusion" union expect those that are included in one of the "exclusion"
 * union.
 *
 * @author Simon Mall
 */
class TEDifference implements TemporalExpressionInterface {
    
    private $included;
    private $excluded;
    
    public function __construct() {
        
        $this->included = new TEUnion();
        $this->excluded = new TEUnion();
        
        
    }

    public function addInclusion(TemporalExpressionInterface $included) {
        
        $this->included->addItem($included);
        
    }
    
    public function addExclusion(TemporalExpressionInterface $excluded) {
        
        $this->excluded->addItem($excluded);
        
    }
    
    public function includes(\DateTime $date) {
        
        return $this->included->includes($date) && !$this->excluded->includes($date);
        
    }

}
