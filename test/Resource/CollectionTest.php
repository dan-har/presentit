<?php

namespace Presentit\Test\Resource;

use Mockery as m;
use Presentit\Test\Stubs\TransformerStub;
use Presentit\Resource\Collection;

class CollectionTest extends \PHPUnit_Framework_TestCase
{
    public function tearDown() {
        m::close();
    }

    public function testGetResource()
    {
        $collection = new Collection(['foo','bar']);
        $this->assertEquals(['foo','bar'], $collection->get());
    }

    public function testCollectionShouldExceptOnlyArrayOrTraversableObject()
    {
        $this->setExpectedException(\InvalidArgumentException::class);

        $collection = new Collection(1);
    }

    public function testTransformReturnEmptyArrayByDefaultAndIfNoTransformerIsSet()
    {
        $collection = new Collection(['foo'=>'bar']);

        $this->assertEquals([], $collection->transform());
    }

    public function testTransformUsesTransformerOnEachResource()
    {
        $collection = new Collection([
            ['foo' => 'bar'],
            ['baz' => 'qux'],
        ]);

        $transformer = m::mock(TransformerStub::class);

        $collection->setTransformer($transformer);

        foreach($collection->get() as $resource) {
            $transformer->shouldReceive('transform')->with($resource)->andReturn($resource)->once();
        }

        $collection->transform();
    }
}
