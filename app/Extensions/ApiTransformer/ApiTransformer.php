<?php

namespace App\Extensions\ApiTransformer;

use Dingo\Api\Http\Request;
use Dingo\Api\Transformer\Adapter\Fractal;
use Dingo\Api\Transformer\Binding;
use Illuminate\Contracts\Pagination\Paginator as IlluminatePaginator;

class ApiTransformer extends Fractal
{
    public function transform($response, $transformer, Binding $binding, Request $request)
    {
        $this->parseFractalIncludes($request);

        $resource = $this->createResource($response, $transformer, $parameters = $binding->getParameters());

        // If the response is a paginator then we'll create a new paginator
        // adapter for Laravel and set the paginator instance on our
        // collection resource.
        if ($response instanceof IlluminatePaginator) {
            $paginator = $this->createPaginatorAdapter($response);

            $resource->setPaginator($paginator);
        }

        if ($this->shouldEagerLoad($response)) {
            $eagerLoads = $this->mergeEagerLoads($transformer, $this->fractal->getRequestedIncludes());

            $response->load($eagerLoads);
        }

        foreach ($binding->getMeta() as $key => $value) {
            $resource->setMetaValue($key, $value);
        }

        $resource->setMetaValue('include', [
            'available' => $transformer->getAvailableIncludes(),
            'default'   => $transformer->getDefaultIncludes(),
        ]);

        $binding->fireCallback($resource, $this->fractal);

        $identifier = isset($parameters['identifier']) ? $parameters['identifier'] : null;

        return $this->fractal->createData($resource, $identifier)->toArray();
    }
}
