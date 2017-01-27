<?php

namespace Presentit\Test\Resource;

use Mockery as m;
use Presentit\Hidden;
use Presentit\Presentation;
use Presentit\Resource\Item;
use Test\Stubs\TransformerStub;

class ItemTest extends \PHPUnit_Framework_TestCase
{
    public function tearDown() {
        m::close();
    }

    public function testGetResource()
    {
        $item = new Item('foo');

        $this->assertEquals('foo', $item->get());
    }

    public function testTransformUsesTransformer()
    {
        $item = new Item('foo');

        $transformer = m::mock(TransformerStub::class);
        $transformer->shouldReceive('transform')->once()->andReturn([]);

        $item->setTransformer($transformer);
        $item->transform();
    }

    public function testTransformHandleHiddenEntities()
    {
        $item = new Item([
            'foo' => 'bar',
            'baz' => m::mock(Hidden::class),
        ]);

        $item->setTransformer($transformer = new TransformerStub());

        $this->assertEquals(['foo' => 'bar'], $item->transform());
    }

    public function testTransformHandleHiddenEntitiesNestedInArray()
    {
        $hidden = m::mock(Hidden::class);

        $item = new Item([
            'foo' => 'bar',
            'baz' => [
                'qux' => $hidden,
                'foo'=> [
                    'bar' => $hidden,
                ]
            ],
        ]);

        $item->setTransformer($transformer = new TransformerStub());

        $this->assertEquals(['foo' => 'bar', 'baz' => ['foo'=>[] ] ], $item->transform());
    }

    public function testTransformHandlePresentationEntities()
    {
        $presentation = m::mock(Presentation::class);
        $item = new Item(['baz' => $presentation]);
        $item->setTransformer($transformer = new TransformerStub());

        $presentation->shouldReceive('show')->andReturn(['qux' => 'bar']);

        $this->assertEquals(['baz' => ['qux' => 'bar']], $item->transform());
    }

    public function testTransformHandlePresentationEntitiesNestedInArray()
    {
        $presentation = m::mock(Presentation::class);
        $item = new Item([
            'foo' => [
                'baz' => $presentation,
                'qux' => [
                    'foo' => $presentation,
                ],
            ],
        ]);
        $item->setTransformer($transformer = new TransformerStub());

        $presentation->shouldReceive('show')->andReturn(['qux' => 'bar']);

        $this->assertEquals(['foo' => ['baz' => ['qux' => 'bar'], 'qux' => ['foo' => ['qux' => 'bar']] ] ], $item->transform());
    }
}
