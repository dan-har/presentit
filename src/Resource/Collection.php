<?php

namespace Presentit\Resource;

use Traversable;
use InvalidArgumentException;

class Collection implements ResourceInterface
{
    use HoldsTransformer;

    /**
     * The traversable resource.
     *
     * @var array|Traversable
     */
    protected $resource;

    /**
     * @param array|Traversable $resource
     */
    public function __construct($resource)
    {
        if(! is_array($resource) && ! $resource instanceof Traversable) {
            throw new InvalidArgumentException('Resource must be an array or a Traversable object');
        }

        $this->resource = $resource;
    }

    /**
     * Create a new resource collection instance.
     *
     * @param array|Traversable $resource
     * @return static
     */
    static public function create($resource)
    {
        return new static($resource);
    }

    /**
     * Get the resource value.
     *
     * @return mixed
     */
    public function get()
    {
        return $this->resource;
    }

    /**
     * Present each item of the collection using a transformer.
     *
     * @return array
     */
    public function transform()
    {
        $presentation = [];

        if( ! $this->transformer) {
            return $presentation;
        }

        foreach ($this->resource as $key => $resource) {
            $presentation[$key] = Item::create($resource)->setTransformer($this->transformer)->transform();
        }

        return $presentation;
    }
}
