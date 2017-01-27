<?php

namespace Presentit\Resource;

use InvalidArgumentException;

trait HoldsTransformer
{
    /**
     * A transformer instance.
     *
     * @var mixed
     */
    protected $transformer;

    /**
     * Set a transformer.
     *
     * @param mixed $transformer
     * @return $this
     */
    public function setTransformer($transformer)
    {
        if( ! method_exists($transformer, 'transform')) {
            throw new InvalidArgumentException('Invalid transformer object, the transformer must implement `transform` method.');
        }

        $this->transformer = $transformer;

        return $this;
    }

    /**
     * Get the transformer.
     *
     * @return mixed
     */
    public function getTransformer()
    {
        return $this->transformer;
    }
}
