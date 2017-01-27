<?php

namespace Presentit;

use Traversable;
use Presentit\Resource\Item;
use Presentit\Resource\Collection;
use Presentit\Resource\ResourceInterface;
use Presentit\Transformer\TransformerFactory;
use Presentit\Transformer\TransformerFactoryInterface;

class Present
{
    /**
     * The transformer factory.
     *
     * @var \Presentit\Transformer\TransformerFactoryInterface
     */
    protected static $transformerFactory;

    /**
     * The resource to be presented.
     *
     * @var \Presentit\Resource\ResourceInterface
     */
    protected $resource;

    /**s
     * @param \Presentit\Resource\ResourceInterface $resource
     */
    public function __construct(ResourceInterface $resource)
    {
        $this->resource = $resource;
    }

    /**
     * Set the transformer factory.
     *
     * @param \Presentit\Transformer\TransformerFactoryInterface $transformerFactory
     */
    static public function setTransformerFactory(TransformerFactoryInterface $transformerFactory)
    {
        self::$transformerFactory = $transformerFactory;
    }

    /**
     * Get the transformer factory.
     *
     * @return \Presentit\Transformer\TransformerFactoryInterface
     */
    static public function getTransformerFactory()
    {
        if( ! self::$transformerFactory) {
            self::$transformerFactory = new TransformerFactory();
        }

        return self::$transformerFactory;
    }

    /**
     * Present an item.
     *
     * @param mixed $resource
     * @return static
     */
    static public function item ($resource)
    {
        return new static(Item::create($resource));
    }

    /**
     * Present a collection.
     *
     * @param array|Traversable $resource
     * @return static
     */
    static public function collection($resource)
    {
        return new static(Collection::create($resource));
    }

    /**
     * Present a collection.
     *
     * @param mixed $resource
     * @return static
     */
    static public function each($resource)
    {
        return self::collection($resource);
    }

    /**
     * Hide the presented key.
     *
     * @return Hidden
     */
    static public function hidden()
    {
        return Hidden::key();
    }

    /**
     * Set a transformer on the resource.
     *
     * @param mixed|callable $transformer
     * @return Presentation
     */
    public function with($transformer)
    {
        $concrete = self::getTransformerFactory()->make($transformer);

        $this->resource->setTransformer($concrete);

        return Presentation::of($this->resource);
    }

    /**
     * Get the resource.
     *
     * @return ResourceInterface
     */
    public function getResource()
    {
        return $this->resource;
    }
}
