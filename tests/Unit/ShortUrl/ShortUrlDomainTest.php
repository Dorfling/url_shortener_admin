<?php

namespace Tests\Unit\ShortUrl;

use App\Libraries\ShortUrl\ShortUrlDomainLibrary;
use PHPUnit\Framework\TestCase;

class ShortUrlDomainTest extends TestCase
{
    /**
     * A basic unit test example.
     *
     * @return void
     */
    public function testDomainValidation()
    {
        /**
         * The valid characters for use in domains are:
         *
         * Any letters of the alphabet;  example: abcdefg.com.au
         * Any numbers 0 to 9, example: 369.com.au
         * You can also use a hyphen (-), example: domain-registration.com.au
         * Domain names cannot begin or end with a hyphen
         * You can use a combination of numbers, letters and hyphens, example: open24-7.com.au
         * You can use multiple instances of hyphens, but not a double hyphen
         * Domain names can begin and end in a number, example: 9-5.com.au
         * Other forms of punctuation, symbols or accent characters cannot be used.
         * The name's length must be between the range 3 and 63 characters
         */
        $testUrlPass1 = 'test.com';
        $testUrlPass2 = 'test.co';
        $testUrlPass3 = 'test.co.za';
        $testUrlPass4 = 'test1.co.za';
        $testUrlPass5 = '1test.co.za';
        $testUrlPass6 = 'te-st.co.za';
        $testUrlPass7 = 't-e-s-t.co.za';
        $testUrlPass8 = 'www.test.co.za';
        $testUrlPass9 = 'sub.www.test.co.za';
        $testUrlFail1 = 'http://test.com';
        $testUrlFail2 = 'https://test.com';
        $testUrlFail3 = 'test';
        $testUrlFail4 = '-test.com';
        $testUrlFail5 = 'test-.com';
        //$testUrlFail6 = 'te--st.com';//Double dash is actually sort of valid, for IDN's that get a xn--
        $testUrlFail7 = 'te$st.com';
        $testUrlFail8 = 'te()st.com';
        $testUrlFail9 = 'test.com/link';
        $testUrlFail10 = 'test.-.com/link';

        //Pass Tests
        $this->assertTrue($this->getShortUrlDomainLibrary()->validDomainString($testUrlPass1)); // 'test.com'
        $this->assertTrue($this->getShortUrlDomainLibrary()->validDomainString($testUrlPass2)); // 'test.co'
        $this->assertTrue($this->getShortUrlDomainLibrary()->validDomainString($testUrlPass3)); // 'test.co.za'
        $this->assertTrue($this->getShortUrlDomainLibrary()->validDomainString($testUrlPass4)); // 'test1.co.za'
        $this->assertTrue($this->getShortUrlDomainLibrary()->validDomainString($testUrlPass5)); // '1test.co.za'
        $this->assertTrue($this->getShortUrlDomainLibrary()->validDomainString($testUrlPass6)); // 'te-st.co.za'
        $this->assertTrue($this->getShortUrlDomainLibrary()->validDomainString($testUrlPass7)); // 't-e-s-t.co.za'
        $this->assertTrue($this->getShortUrlDomainLibrary()->validDomainString($testUrlPass8)); // 'www.test.co.za'
        $this->assertTrue($this->getShortUrlDomainLibrary()->validDomainString($testUrlPass9)); // sub.www.test.co.za

        //Fail Tests
        $this->assertFalse($this->getShortUrlDomainLibrary()->validDomainString($testUrlFail1)); // 'http://test.com'
        $this->assertFalse($this->getShortUrlDomainLibrary()->validDomainString($testUrlFail2)); // 'https://test.com'
        $this->assertFalse($this->getShortUrlDomainLibrary()->validDomainString($testUrlFail3)); // 'test'
        $this->assertFalse($this->getShortUrlDomainLibrary()->validDomainString($testUrlFail4)); // '-test.com'
        $this->assertFalse($this->getShortUrlDomainLibrary()->validDomainString($testUrlFail5)); // 'test-.com'
//        $this->assertFalse($this->getShortUrlDomainLibrary()->validateDomainString($testUrlFail6)); // 'te--st.com'
        $this->assertFalse($this->getShortUrlDomainLibrary()->validDomainString($testUrlFail7)); // 'te$st.com'
        $this->assertFalse($this->getShortUrlDomainLibrary()->validDomainString($testUrlFail8)); // 'te()st.com'
        $this->assertFalse($this->getShortUrlDomainLibrary()->validDomainString($testUrlFail9)); // 'test.com/link'
        $this->assertFalse($this->getShortUrlDomainLibrary()->validDomainString($testUrlFail10)); // 'test.-.com/link'
    }

    private function getShortUrlDomainLibrary(): ShortUrlDomainLibrary
    {
        return new ShortUrlDomainLibrary();
    }
}
