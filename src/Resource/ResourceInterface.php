<?php

namespace Presentit\Resource;

interface ResourceInterface
{
    /**
     * Transform the resource to a custom presentation.
     *
     * @return array
     */
    public function transform();

    /**
     * Set a transformer.
     *
     * @param mixed $transformer
     * @return $this
     */
    public function setTransformer($transformer);
}
