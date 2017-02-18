<?php

namespace Presentit\Test\Resource;

use ReflectionClass;
use Presentit\Test\Stubs\TransformerStub;
use Presentit\Test\Stubs\HoldTransformerStub;

class HoldsTransformerTest extends \PHPUnit_Framework_TestCase
{
    public function testSetTransformer()
    {
        $resource = new HoldTransformerStub();

        $resource->setTransformer($transformer = new TransformerStub());

        $class = new ReflectionClass($resource);
        $property = $class->getProperty("transformer");
        $property->setAccessible(true);

        $this->assertSame($transformer, $property->getValue($resource));
    }

    public function testSetTransformerExceptionWhenTransformMethodNotExists()
    {
        $this->setExpectedException(\InvalidArgumentException::class);

        $item = new HoldTransformerStub();
        $item->setTransformer(new \stdClass());
    }

    public function testGetTransformer()
    {
        $resource = new HoldTransformerStub();

        $resource->setTransformer($transformer = new TransformerStub());

        $this->assertEquals($transformer, $resource->getTransformer());
    }
}
