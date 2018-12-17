<?php

namespace App\Extensions\Eloquent;

use Request;
use Illuminate\Database\Eloquent\SoftDeletes;

class Filter
{
    /**
     * The current model instance.
     *
     * @var \Illuminate\Database\Eloquent\Model
     */
    private $model;

    /**
     * The current builder instance.
     *
     * @var \Illuminate\Database\Eloquent\Builder
     */
    private $query;

    /**
     * The methods of the predefined process.
     *
     * @var array
     */
    private $methods = [
        'limit',
        'skip',
        'sort',
        'include',
        'with_trashed',
    ];

    /**
     * The attributes that are accept dynamic filter.
     *
     * @var array
     */
    private $filterable = [];

    /**
     * The key that should be excluded from filter.
     *
     * @var array
     */
    protected $except = [
        'api_token',
        'page',
        'cursor',
        'prev',
        'pagination',
        'include[]',
        'q'
    ];

    /**
     * Determine allowable operations to the pattern.
     *
     * @var array
     */
    private $pattern = '/!=|=|<=|<|>=|>/';

    /**
     * Create a new filter instance.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  string  $queryString
     * @return void
     */
    public function __construct($model, & $query)
    {
        $this->model = $model;
        $this->query = $query;
        $this->filterable = $model->filterable ?: ['*'];
    }

    /**
     * Apply the query string uri to query.
     */
    public function build($queryString = null)
    {
        // build
        $this->generate(
            explode('&', urldecode(
                rawurldecode(
                    $queryString ?: $_SERVER["QUERY_STRING"]
                )
            ))
        );
    }

    /**
     * Generate query via parsed filters.
     *
     * @param  array  $args
     * @var array
     */
    private function generate(array $args)
    {
        foreach ($args as $arg) {
            if (preg_match($this->pattern, $arg, $matches)) {
                $operator = $matches[0];

                list($key, $value) = explode($operator, $arg);

                // skip the suspended parameters
                if (in_array($key, $this->except)) {
                    continue;
                }

                $this->handle([
                    'key'      => $key,
                    'operator' => $operator,
                    'value'    => $this->normalize($value)
                ]);
            }
        }
    }

    /**
     * Normalizing the type value.
     *
     * @param  string  $value
     * @return mixed
     */
    private function normalize($value)
    {
        switch ($value) {
            case 'true':
                return true;
                break;

            case 'false':
                return false;
                break;

            case 'null':
                return null;
                break;
        }

        return $value;
    }

    /**
     * Determine the field filterable or not.
     *
     * @param  string  $key
     * @return boolean
     */
    private function isFilterable($key)
    {
        return in_array('*', $this->filterable) or in_array($key, $this->filterable);
    }

    /**
     * Handle the query.
     *
     * @var array
     */
    private function handle($param)
    {
        // call this instance method
        if (in_array($param['key'], $this->methods)) {
            $this->{camel_case('filter_'. $param['key'])}($param);
        }
        // call the model's method.
        // if you need special filter maybe use this example.
        // Eg, create the filterByDistance method and use distance in query.
        elseif (method_exists($this->model, camel_case('filterBy_'. $param['key']))) {
            $this->model->{camel_case('filterBy_'. $param['key'])}($this->query, $param['value']);
        }
        // filter query if the field is filterable
        else {
            if ($this->isFilterable($param['key'])) {
                if (is_null($param['value'])) {
                    if ($param['operator'] == '!=') {
                        $this->query->whereNotNull($param['key']);
                    } else {
                        $this->query->whereNull($param['key']);
                    }
                }
                else {
                    if (preg_match('/\*/', $param['value'])) {
                        $param['operator'] = 'like';
                        $param['value'] = str_replace('*', '%', $param['value']);
                    }

                    $this->query->where($param['key'], $param['operator'], $param['value']);
                }
            }
        }
    }

    /**
     * The predefined "with_trashed" method.
     *
     * @param  array  $param
     * @return void
     */
    private function filterWithTrashed(array $param)
    {
        if ($param['value'] == true && in_array(SoftDeletes::class, class_uses($this->model))) {
            $this->query->withTrashed();
        }
    }

    /**
     * The predefined "limit" method.
     *
     * @param  array  $param
     * @return void
     */
    private function filterLimit(array $param)
    {
        if (is_int($param['value']) && $param['value'] > 0) {
            $this->query->limit($param['value']);
        }
    }

    /**
     * The predefined "skip" method.
     *
     * @param  array  $param
     * @return void
     */
    private function filterSkip(array $param)
    {
        if (is_int($param['value']) && $param['value'] > 0) {
            $this->query->skip($param['value']);
        }
    }

    /**
     * The predefined "sort" method.
     *
     * @param  array  $param
     * @return void
     */
    private function filterSort(array $param)
    {
        $columns = explode(',', $param['value']);

        foreach ($columns as $column) {
            // the default direction
            $direction = 'asc';

            if (preg_match('/^-/', $column)) {
                // removed the decreasing symbol
                $column = ltrim($column, '-');

                // change direction
                $direction = 'desc';
            }

            $this->query->orderBy($column, $direction);
        }
    }

    /**
     * The predefined "include" method.
     *
     * @param  array  $param
     * @return void
     */
    private function filterInclude(array $param)
    {
        $this->query->with(explode(',', $param['value']));
    }
}