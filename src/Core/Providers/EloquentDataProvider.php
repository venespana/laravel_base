<?php

namespace VD\Core\Providers;

use Illuminate\Support\Carbon;
use Nayjest\Grids\EloquentDataProvider as NayjestEloquentDataProvider;

class EloquentDataProvider extends NayjestEloquentDataProvider
{
    protected function operators($operator, $value)
    {
        switch ($operator) {
            case "eq":
                $operator = '=';
                break;
            case "n_eq":
                $operator = '<>';
                break;
            case "gt":
                $operator = '>';
                break;
            case "lt":
                $operator = '<';
                break;
            case "ls_e":
                $operator = '<=';
                break;
            case "gt_e":
                $operator = '>=';
                break;
            case "in":
                if (!is_array($value)) {
                    $operator = '=';
                    break;
                }
                $operator = 'in';
                break;
        }

        return $operator;
    }

    /**
     * {@inheritdoc}
     */
    public function filter($fieldName, $operator, $value)
    {
        $operator = $this->operators($operator, $value);

        try {
            $value = Carbon::createFromFormat('d/m/Y', trim($value, '%'))->toDateString();
            if ($operator === 'like') {
                $value = "%$value%";
            }
            $this->src->whereDate($fieldName, $operator, $value);
        } catch (\Exception $e) {
            if ($operator === 'in') {
                $this->src->whereIn($fieldName, $value);
            } else {
                $this->src->where($fieldName, $operator, $value);
            }
        }
        return $this;
    }
}