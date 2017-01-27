<?php

namespace Presentit\Transformer;

class CallableTransformer
{
    /**
     * The callable transformer.
     *
     * @var callable
     */
    protected $callable;

    /**
     * @param callable $callable
     */
    public function __construct(callable $callable)
    {
        $this->callable = $callable;
    }

    /**
     * Transform a resource.
     *
     * @param mixed $resource
     * @return array
     */
    public function transform($resource)
    {
        return call_user_func_array($this->callable, [$resource]);
    }
}
