<?php

namespace App\Models;

use App\Exceptions\ValidationException;
use Illuminate\Database\Eloquent\Model as BaseModel;
use Validator;

abstract class Model extends BaseModel
{
    /**
     * The storage format of the model's date columns.
     *
     * @var string
     */
    protected $dateFormat = 'Y-m-d H:i:s';

    /**
     * The rules to be applied to the data.
     *
     * @var array
     */
    protected $rules = [];

    /**
     * The "booting" method of the model.
     *
     * @return void
     */
    public static function boot()
    {
        parent::boot();

        // before create or update
        static::saving(function (Model $model) {
            // validation check
            $v = Validator::make($model->attributes, $model->parseRules());
            if ($v->fails()) {
                throw new ValidationException($v->errors());
            }
        });
    }

    /**
     * Set a given attribute on the model.
     *
     * @param string $key
     * @param mixed  $value
     *
     * @return $this
     */
    public function setAttribute($key, $value)
    {
        // empty string instead of null.
        if (is_array($value)) {
            array_walk_recursive($value, function (&$attribute) {
                if (!is_bool($attribute)) {
                    $attribute = trim($attribute);
                }

                $attribute = $attribute === '' ? null : $attribute;
            });
        } else {
            if (!is_bool($value)) {
                $value = trim($value);
            }
        }

        parent::setAttribute($key, $value === '' ? null : $value);
    }

    /**
     * Get the attributes that should be converted to dates.
     *
     * @return array
     */
    public function getDates()
    {
        return $this->dates;
    }

    /**
     * Get the validator rules.
     *
     * @return array
     */
    public function getRules()
    {
        return $this->rules;
    }

    /**
     * Retrive rules before replace the :id.
     *
     * @param array $rules
     *
     * @return array
     */
    public function parseRules(array $rules = [])
    {
        $rules = $rules ?: $this->rules;

        foreach ($rules as &$rule) {
            if (is_array($rule)) {
                return $this->parseRules($rule);
            }

            if (preg_match('/:id/', $rule)) {
                $rule = str_replace(':id', $this->getAttribute('id'), $rule);
            }
        }

        return $rules;
    }

    /**
     * Trigger the query builder scope.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param string                                $queryString
     *
     * @return void
     */
    public function scopeFilter($query, $queryString = null)
    {
        value(new Filter($this, $query))->build($queryString);
    }
}
