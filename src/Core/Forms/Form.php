<?php

namespace VD\Core\Forms;

use Illuminate\Database\Eloquent\Model;

class Form
{
    protected static function hasRelations(Model $model, array $columns)
    {
        $with = [];
        $cols = [];

        foreach ($columns as $key => $data) {
            $relation = explode('.', $key);
            if (is_array($relation) && $relation[0] === 'with') {
                $select = $relation[2] ?? false;
                $value = $relation[1];
                if($select) {
                    $value .= ":{$select}"; 
                }
                array_push($with, $value);
            }else {
                array_push($cols, $key);
            }
        }

        if (count($with) > 0) {
            return $model->with($with);
        }
        return $model->select($cols);
    }
}
