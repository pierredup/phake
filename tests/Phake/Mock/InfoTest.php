<?php
/*
 * Phake - Mocking Framework
 *
 * Copyright (c) 2010-2022, Mike Lively <mike.lively@sellingsource.com>
 * All rights reserved.
 *
 * Redistribution and use in source and binary forms, with or without
 * modification, are permitted provided that the following conditions
 * are met:
 *
 *  *  Redistributions of source code must retain the above copyright
 *     notice, this list of conditions and the following disclaimer.
 *
 *  *  Redistributions in binary form must reproduce the above copyright
 *     notice, this list of conditions and the following disclaimer in
 *     the documentation and/or other materials provided with the
 *     distribution.
 *
 *  *  Neither the name of Mike Lively nor the names of his
 *     contributors may be used to endorse or promote products derived
 *     from this software without specific prior written permission.
 *
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS
 * "AS IS" AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT
 * LIMITED TO, THE IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS
 * FOR A PARTICULAR PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL THE
 * COPYRIGHT OWNER OR CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT,
 * INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING,
 * BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES;
 * LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER
 * CAUSED AND ON ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT
 * LIABILITY, OR TORT (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN
 * ANY WAY OUT OF THE USE OF THIS SOFTWARE, EVEN IF ADVISED OF THE
 * POSSIBILITY OF SUCH DAMAGE.
 *
 * @category   Testing
 * @package    Phake
 * @author     Mike Lively <m@digitalsandwich.com>
 * @copyright  2010 Mike Lively <m@digitalsandwich.com>
 * @license    http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @link       http://www.digitalsandwich.com/
 */

declare(strict_types=1);

namespace Phake\Mock;

use Phake;
use PHPUnit\Framework\TestCase;

class InfoTest extends TestCase
{
    /**
     * @var Phake\Mock\Info
     */
    private Phake\Mock\Info $info;

    /**
     * @Mock
     */
    private Phake\CallRecorder\Recorder $recorder;

    /**
     * @Mock
     */
    private Phake\Stubber\StubMapper $mapper;

    /**
     * @Mock
     */
    private Phake\Stubber\IAnswer $answer;

    /**
     * @Mock
     */
    private Phake\ClassGenerator\InvocationHandler\IInvocationHandler $handlerChain;

    public function setUp(): void
    {
        Phake::initAnnotations($this);
        $this->info = $this->getSUT();
    }

    private function getSUT(): Info
    {
        return new Info('name', $this->recorder, $this->mapper, $this->answer);
    }

    /**
     * Tests for the uniqid which is to ensure mocks are always different.
     */
    public function testObjectsNotEqual(): void
    {
        $secondInfo = $this->getSUT();

        $this->assertNotEquals($secondInfo, $this->info);
    }

    public function testGetCallRecorder(): void
    {
        $this->assertSame($this->recorder, $this->info->getCallRecorder());
    }

    public function testIsFrozen(): void
    {
        $this->assertFalse($this->info->isObjectFrozen());

        $this->info->freezeObject();
        $this->assertTrue($this->info->isObjectFrozen());

        $this->info->thawObject();
        $this->assertFalse($this->info->isObjectFrozen());
    }

    public function testGetStubMapper(): void
    {
        $this->assertSame($this->mapper, $this->info->getStubMapper());
    }

    public function testGetDefaultAnswer(): void
    {
        $this->assertSame($this->answer, $this->info->getDefaultAnswer());
    }

    public function testSetHandlerChain(): void
    {
        $this->info->setHandlerChain($this->handlerChain);
        $this->assertSame($this->handlerChain, $this->info->getHandlerChain());
    }

    public function testName(): void
    {
        $this->assertEquals('name', $this->info->getName());
    }

    public function testReset(): void
    {
        $this->info->freezeObject();
        $this->info->resetInfo();

        $this->assertFalse($this->info->isObjectFrozen());
        Phake::verify($this->mapper)->removeAllAnswers();
        Phake::verify($this->recorder)->removeAllCalls();
    }
}
