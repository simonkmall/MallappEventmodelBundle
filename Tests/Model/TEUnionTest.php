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

use Mallapp\EventmodelBundle\Model\TEUnion;
use Mallapp\EventmodelBundle\Model\SingleDay;
use Mallapp\EventmodelBundle\Model\SimpleDate;



/**
 * Description of TEUnionTest
 *
 * @author Simon Mall
 */
class TEUnionTest extends \PHPUnit_Framework_TestCase {
    
    public function testIncludes() {
        
        // Prepare the union class
        
        $union = new TEUnion();
        
        $union->addItem(new SingleDay(SimpleDate::create("2016-10-21")));
        $union->addItem(new SingleDay(SimpleDate::create("2016-10-23")));
        
        // Test the includes function
        
        $this->assertTrue($union->includes(SimpleDate::create("2016-10-21")));
        $this->assertTrue($union->includes(SimpleDate::create("2016-10-23")));
        
        $this->assertFalse($union->includes(SimpleDate::create("2016-10-20")));
        $this->assertFalse($union->includes(SimpleDate::create("2016-10-22")));
        $this->assertFalse($union->includes(SimpleDate::create("2016-10-24")));
        
	
    }
    
}
