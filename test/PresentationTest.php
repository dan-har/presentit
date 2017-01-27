<?php

namespace Presentit\Test;

use Mockery as m;
use Presentit\Presentation;
use Presentit\Resource\ResourceInterface;

class PresentationTest extends \PHPUnit_Framework_TestCase
{
    public function tearDown() {
        m::close();
    }

    public function testShowShouldCallResourceTransform()
    {
        $resource = m::mock(ResourceInterface::class);

        $presentation = new Presentation($resource);

        $resource->shouldReceive('transform')->once();

        $presentation->show();
    }

    public function testShowReturnsPresentation()
    {
        $resource = m::mock(ResourceInterface::class);

        $presentation = new Presentation($resource);

        $resource->shouldReceive('transform')->andReturn(['foo'])->once();

        $this->assertEquals(['foo'], $presentation->show());
    }
}
