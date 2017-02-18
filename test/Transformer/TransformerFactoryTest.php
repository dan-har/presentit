<?php

namespace Presentit\Test\Transformer;

use Presentit\Transformer\CallableTransformer;
use Presentit\Test\Stubs\TransformerStub;
use Presentit\Transformer\TransformerFactory;
use Presentit\Transformer\ResolveTransformerException;

class TransformerFactoryTest extends \PHPUnit_Framework_TestCase
{
    public function testFactoryMakeClassFromNamespace()
    {
        $factory = new TransformerFactory();

        $transformer = $factory->make(TransformerStub::class);

        $this->assertInstanceOf(TransformerStub::class, $transformer);
    }

    public function testFactoryThrowsResolveExceptionIfClassNotExists()
    {
        $factory = new TransformerFactory();

        $this->setExpectedException(ResolveTransformerException::class);

        $factory->make('unknownClass');
    }

    public function testFactoryCreatesCallableTransformerFromCallable()
    {
        $factory = new TransformerFactory();

        $transformer = $factory->make(function () {
            return [];
        });

        $this->assertInstanceOf(CallableTransformer::class, $transformer);
    }

    public function testFactoryAcceptsTransformerInstanceAndReturnIt()
    {
        $factory = new TransformerFactory();

        $transformer = new TransformerStub();

        $this->assertEquals($transformer, $factory->make($transformer));
    }

    public function testFactoryThrowsResolveExceptionIfTransformerNotImplementsTransformMethod()
    {
        $factory = new TransformerFactory();
        $transformer = new \stdClass();

        $this->setExpectedException(ResolveTransformerException::class);

        $factory->make($transformer);
    }
}
