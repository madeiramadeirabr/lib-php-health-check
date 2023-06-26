<?php

namespace Tests\Unit\Infra\Datasources\Memory;

use MadeiraMadeira\HealthCheck\Infra\Datasources\Memory\HashMapMemory;
use PHPUnit\Framework\TestCase;

class HashMapMemoryTest extends TestCase
{
    public function testSetAndGet()
    {
        $memory = new HashMapMemory();
        $memory->set('foo', 'bar');
        $result = $memory->get('foo');

        $this->assertEquals('bar', $result, 'should return value when key exists');
    }

    public function testGetWhenKeyNotExists()
    {
        $memory = new HashMapMemory();
        $result = $memory->get('foo');

        $this->assertEquals(null, $result, 'should return null when key not exists');
    }
    
    public function testAll()
    {
        $memory = new HashMapMemory();
        $memory->set('foo1', 'bar1');
        $memory->set('foo2', 'bar2');
        $memory->set('foo3', 'bar3');

        $result = $memory->all();
        
        $this->assertEquals(
            [
                'bar1',
                'bar2',
                'bar3',
            ], 
            $result, 
            'should return array of values'
        );
    }

    public function testClear()
    {
        $memory = new HashMapMemory();
        $memory->set('foo1', 'bar1');
        $memory->set('foo2', 'bar2');
        $memory->set('foo3', 'bar3');

        $result = $memory->all();
        
        $this->assertEquals(
            [
                'bar1',
                'bar2',
                'bar3',
            ], 
            $result, 
            'should return array of values'
        );

        $memory->clear();

        $result = $memory->all();
        
        $this->assertEquals(
            [
            ], 
            $result, 
            'should return an array without values'
        );
    }
}