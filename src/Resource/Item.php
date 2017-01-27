<?php

namespace Presentit\Resource;

use Presentit\Hidden;
use Presentit\Presentation;

class Item implements ResourceInterface
{
    use HoldsTransformer;

    /**
     * An object represent a resource.
     *
     * @var mixed
     */
    protected $resource;

    /**
     * @param mixed $resource
     */
    public function __construct($resource)
    {
        $this->resource = $resource;
    }

    /**
     * Create a new resource item instance.
     *
     * @param mixed $resource
     * @return static
     */
    static public function create($resource)
    {
        return new static($resource);
    }

    /**
     * Get the item resource.
     *
     * @return mixed
     */
    public function get()
    {
        return $this->resource;
    }

    /**
     * Present the item using a transformer.
     *
     * @return array
     */
    public function transform()
    {
        if( ! $this->transformer) {
            return [];
        }

        $presentation = $this->transformer->transform($this->resource);

        return $this->transformToArray($presentation);
    }

    /**
     * Transform a presentation to an array.
     *
     * @param array $presentation
     * @return array
     */
    protected function transformToArray(array $presentation)
    {
        $array = [];

        // iterate over all the presentation values and search for arrays and
        // for the nested special entities, Presentation and Hidden objects
        // and handle each entity in the appropriate way.
        foreach ($presentation as $key => $item) {

            // in case of an array recursively iterate over all the array
            // value and check for special entities.
            if(is_array($item)) {
                $array[$key] = $this->transformToArray($item);
            }

            // if we find an hidden entity ignore the key so it won't be shown in the
            // final presentation then continue to the next item in the presentation
            else if($item instanceof Hidden) {
                continue;
            }

            // if an item is a presentation object run the show function on it to transform
            // the resource presentation tree and save the presentation on the array.
            else if($item instanceof Presentation) {
                $array[$key] = $item->show();
            }

            // on any other value we just save the value and key and move on
            // to the next item.
            else {
                $array[$key] = $item;
            }
        }

        return $array;
    }
}
