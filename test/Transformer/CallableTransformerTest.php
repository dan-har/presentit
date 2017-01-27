<?php

namespace Presentit\Test\Transformer;

use Presentit\Transformer\CallableTransformer;

class CallableTransformerTest extends \PHPUnit_Framework_TestCase
{
    public function testTransformRunCallableAndReturnResult()
    {
        $transformer = new CallableTransformer(function ($resource) {
            return [
                'origin' => $resource,
                'baz' => 'qux',
            ];
        });

        $this->assertEquals([
            'origin' => ['foo'],
            'baz' => 'qux',
        ], $transformer->transform(['foo']));
    }
}
