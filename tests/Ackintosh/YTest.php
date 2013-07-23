<?php
use Ackintosh\Y;

class YTest extends PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        $this->Y = Y::equals();
        $ref = new ReflectionClass('Ackintosh\Y');
        $this->operationStack = $ref->getProperty('operationStack');
        $this->operationStack->setAccessible(true);
    }

    /**
     * @dataProvider provider_extractMethodName
     */
    public function test_extractMethodName($name, $methodName, $argument)
    {
        $ref = new ReflectionMethod('Ackintosh\Y', 'extractMethodName');
        $ref->setAccessible(true);
        $this->assertEquals(array($methodName, $argument), $ref->invoke($this->Y, $name));
    }

    public function provider_extractMethodName()
    {
        return array(
            array('_10X', 'XmultipliedBy', 10),
            array('_10X_squared', 'X_squaredMultipliedBy', 10),
        );
    }

    /**
     * @test
     */
    public function XmultipliedBy_stacks_Closure()
    {
        $ref = new ReflectionMethod('Ackintosh\Y', 'XmultipliedBy');
        $ref->setAccessible(true);

        $stack_count_before = count($this->operationStack->getValue($this->Y));
        $ref->invoke($this->Y, 5);
        $stack_count_after = count($this->operationStack->getValue($this->Y));

        $this->assertEquals($stack_count_after, $stack_count_before + 1);

        $closure = array_pop($this->operationStack->getValue($this->Y));
        $this->assertInstanceOf('Closure', $closure);
        $this->assertEquals(10, $closure(2));
    }

    /**
     * @test
     */
    public function X_squaredMultipliedBy_stacks_Closure()
    {
        $ref = new ReflectionMethod('Ackintosh\Y', 'X_squaredMultipliedBy');
        $ref->setAccessible(true);

        $stack_count_before = count($this->operationStack->getValue($this->Y));
        $ref->invoke($this->Y, 5);
        $stack_count_after = count($this->operationStack->getValue($this->Y));

        $this->assertEquals($stack_count_after, $stack_count_before + 1);

        $closure = array_pop($this->operationStack->getValue($this->Y));
        $this->assertInstanceOf('Closure', $closure);
        $this->assertEquals(20, $closure(2));
    }

    /**
     * @test
     */
    public function _X_stacks_Closure()
    {
        $stack_count_before = count($this->operationStack->getValue($this->Y));
        $this->Y->_X();
        $stack_count_after = count($this->operationStack->getValue($this->Y));

        $this->assertEquals($stack_count_after, $stack_count_before + 1);

        $closure = array_pop($this->operationStack->getValue($this->Y));
        $this->assertInstanceOf('Closure', $closure);
        $this->assertEquals(5, $closure(5));
    }

    /**
     * @test
     */
    public function _X_squared_stacks_Closure()
    {
        $stack_count_before = count($this->operationStack->getValue($this->Y));
        $this->Y->_X_squared();
        $stack_count_after = count($this->operationStack->getValue($this->Y));

        $this->assertEquals($stack_count_after, $stack_count_before + 1);

        $closure = array_pop($this->operationStack->getValue($this->Y));
        $this->assertInstanceOf('Closure', $closure);
        $this->assertEquals(25, $closure(5));
    }

    /**
     * @test
     */
    public function _plus_stacks_Closure()
    {
        $stack_count_before = count($this->operationStack->getValue($this->Y));
        $this->Y->_plus(10);
        $stack_count_after = count($this->operationStack->getValue($this->Y));

        $this->assertEquals($stack_count_after, $stack_count_before + 1);

        $closure = array_pop($this->operationStack->getValue($this->Y));
        $this->assertInstanceOf('Closure', $closure);
        $dummy = 5;
        $this->assertEquals(10, $closure($dummy));
    }

    /**
     * @test
     */
    public function _minus_stacks_Closure()
    {
        $stack_count_before = count($this->operationStack->getValue($this->Y));
        $this->Y->_minus(10);
        $stack_count_after = count($this->operationStack->getValue($this->Y));

        $this->assertEquals($stack_count_after, $stack_count_before + 1);

        $closure = array_pop($this->operationStack->getValue($this->Y));
        $this->assertInstanceOf('Closure', $closure);
        $dummy = 5;
        $this->assertEquals(-10, $closure($dummy));
    }
}

