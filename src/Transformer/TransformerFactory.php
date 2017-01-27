<?php

namespace Presentit\Transformer;

class TransformerFactory implements TransformerFactoryInterface
{
    /**
     * Build a transformer object.
     *
     * @param mixed|callable $transformer
     * @return mixed
     * @throws \Presentit\Transformer\ResolveTransformerException
     */
    public function make($transformer)
    {
        // try to create a transformer by class name, if we can autoload
        // the class from the namespace we will create a new class and
        // return the new transformer.
        if(is_string($transformer)) {
            return $this->makeTransformerClass($transformer);
        }

        // if we get a callable, then we will create a callable transformer
        // that warps the callable and implements a transform function so
        // any resource will know a single interface for transforming.
        else if(is_callable($transformer)) {
            return $this->makeCallableTransformer($transformer);
        }

        // in case we get an object check if it is an instance of a transformer
        // that implements the transform method.
        else if(is_object($transformer) && method_exists($transformer, 'transform')) {
            return $transformer;
        }

        throw new ResolveTransformerException("Cannot resolve transformer");
    }

    /**
     * Make a transformer from a class name.
     *
     * @param string $transformer
     * @return mixed
     * @throws \Presentit\Transformer\ResolveTransformerException
     */
    protected function makeTransformerClass($transformer)
    {
        if( ! class_exists($transformer)) {
            throw new ResolveTransformerException("Cannot resolve transformer $transformer");
        }

        return new $transformer();
    }

    /**
     * Make a callable transformer.
     *
     * @param callable $transformer
     * @return \Presentit\Transformer\CallableTransformer
     */
    protected function makeCallableTransformer(callable $transformer)
    {
        return new CallableTransformer($transformer);
    }
}
