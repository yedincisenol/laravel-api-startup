<?php

namespace App\Transformers;

use League\Fractal\TransformerAbstract;

abstract class Transformer extends TransformerAbstract
{
    /**
     * Create a new collection resource object.
     *
     * @param mixed                        $data
     * @param TransformerAbstract|callable $transformer
     * @param string                       $resourceKey
     *
     * @return Collection
     */
    protected function collection($data, $transformer, $resourceKey = null)
    {
        return new Collection($data, $transformer, $resourceKey);
    }
}
