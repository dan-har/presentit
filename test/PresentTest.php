<?php

namespace Presentit\Test;

use Mockery as m;
use ReflectionClass;
use Presentit\Hidden;
use Presentit\Present;
use Presentit\Presentation;
use Presentit\Resource\Item;
use Presentit\Resource\Collection;
use Presentit\Resource\ResourceInterface;
use Presentit\Transformer\TransformerFactory;
use Presentit\Transformer\TransformerFactoryInterface;

class PresentTest extends \PHPUnit_Framework_TestCase
{
    public function tearDown() {
        m::close();
    }

    public function setUp() {

        $present = new ReflectionClass(Present::class);
        $property = $present->getProperty('transformerFactory');
        $property->setAccessible(true);

        $property->setValue($present, null);
    }

    public function testSetTransformerFactory()
    {
        $factory = m::mock(TransformerFactoryInterface::class);
        Present::setTransformerFactory($factory);

        $present = new ReflectionClass(Present::class);
        $property = $present->getProperty('transformerFactory');
        $property->setAccessible(true);

        $this->assertSame($factory, $property->getValue($present));
    }

    public function testGetTransformerFactory()
    {
        $factory = m::mock(TransformerFactoryInterface::class);
        Present::setTransformerFactory($factory);

        $this->assertSame($factory, Present::getTransformerFactory());
    }

    public function testDefaultFactoryIsCreatedIfNoFactoryIsSet()
    {
        $this->assertInstanceOf(TransformerFactory::class, Present::getTransformerFactory());
    }

    public function testGetResource()
    {
        $resource = m::mock(ResourceInterface::class);

        $present = new Present($resource);

        $this->assertEquals($resource, $present->getResource());
    }

    public function testPresentItemFactory()
    {
        $resource = 'foo';

        $present = Present::item($resource);

        $this->assertInstanceOf(Present::class, $present);

        $this->assertInstanceOf(Item::class, $present->getResource());
    }

    public function testPresentCollectionFactory()
    {
        $resource = ['foo'];

        $present = Present::collection($resource);

        $this->assertInstanceOf(Present::class, $present);

        $this->assertInstanceOf(Collection::class, $present->getResource());
    }

    public function testPresentEachIsAliasToCollection()
    {
        $resource = ['foo'];

        $present = Present::each($resource);

        $this->assertInstanceOf(Present::class, $present);

        $this->assertInstanceOf(Collection::class, $present->getResource());
    }

    public function testPresentEachFactory()
    {
        $resource = ['foo'];

        $present = Present::collection($resource);

        $this->assertInstanceOf(Present::class, $present);

        $this->assertInstanceOf(Collection::class, $present->getResource());
    }

    public function testPresentHiddenFunctionReturnHiddenObject()
    {
        $this->assertInstanceOf(Hidden::class, Present::hidden());
    }

    public function testPresetWithFunctionAssignTransformerOnTheResourceAndReturnPresentation()
    {
        $resource = m::mock(ResourceInterface::class);
        $factory = m::mock(TransformerFactoryInterface::class);
        Present::setTransformerFactory($factory);
        $transformer = function () {};

        $factory->shouldReceive('make')->with($transformer)->once();
        $resource->shouldReceive('setTransformer')->once();

        $present = new Present($resource);
        $presentation = $present->with($transformer);

        $this->assertInstanceOf(Presentation::class, $presentation);
    }
}
