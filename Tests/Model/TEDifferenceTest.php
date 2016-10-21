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

use Mallapp\EventmodelBundle\Model\TEDifference;
use Mallapp\EventmodelBundle\Model\SingleDay;
use Mallapp\EventmodelBundle\Model\DaySequence;
use Mallapp\EventmodelBundle\Model\SimpleDate;



/**
 * Description of TEDifferenceTest
 *
 * @author Simon Mall
 */
class TEDifferenceTest extends \PHPUnit_Framework_TestCase {
    
    public function testIncludes() {
        
        // Prepare the union class
        
        $difference = new TEDifference();
        
        $difference->addInclusion(new DaySequence(SimpleDate::create("2016-10-21"), SimpleDate::create("2016-11-21")));
       
        $difference->addExclusion(new SingleDay(SimpleDate::create("2016-10-22")));
        $difference->addExclusion(new SingleDay(SimpleDate::create("2016-11-01")));
        $difference->addExclusion(new SingleDay(SimpleDate::create("2016-11-21")));
        
        
        // Test the includes function
        
        $this->assertTrue($difference->includes(SimpleDate::create("2016-10-21")));
        $this->assertTrue($difference->includes(SimpleDate::create("2016-10-23")));
        $this->assertTrue($difference->includes(SimpleDate::create("2016-10-31")));
        $this->assertTrue($difference->includes(SimpleDate::create("2016-11-02")));
        $this->assertTrue($difference->includes(SimpleDate::create("2016-11-05")));
        $this->assertTrue($difference->includes(SimpleDate::create("2016-11-20")));
        
        $this->assertFalse($difference->includes(SimpleDate::create("2016-10-20")));
        $this->assertFalse($difference->includes(SimpleDate::create("2016-10-22")));
        $this->assertFalse($difference->includes(SimpleDate::create("2016-11-01")));
        $this->assertFalse($difference->includes(SimpleDate::create("2016-11-21")));
        $this->assertFalse($difference->includes(SimpleDate::create("2016-11-22")));
        
	
    }
    
}
