<?php

namespace Presentit\Transformer;

interface TransformerFactoryInterface
{
    /**
     * Build a transformer object.
     *
     * @param mixed|callable $transformer
     * @return mixed
     */
    public function make($transformer);
}
