<?php

namespace Presentit;

use Presentit\Resource\ResourceInterface;

class Presentation
{
    /**
     * The resource being presented.
     *
     * @var \Presentit\Resource\ResourceInterface
     */
    protected $resource;

    /**
     * @param \Presentit\Resource\ResourceInterface $resource
     */
    public function __construct(ResourceInterface $resource)
    {
        $this->resource = $resource;
    }

    /**
     * Make a new presentation of a resource.
     *
     * @param \Presentit\Resource\ResourceInterface $resource
     * @return static
     */
    static public function of(ResourceInterface $resource)
    {
        return new static($resource);
    }

    /**
     * Show the presentation of the resource.
     *
     * @return array
     */
    public function show()
    {
        return $this->resource->transform();
    }
}
