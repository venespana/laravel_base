<?php

namespace VD\Core\Tables\Traits;

trait RequestFormsExtra
{
    protected function extras() : array
    {
        return [];
    }

    public static function fields() : array
    {
        $validator = new static;
        $rules = $validator->rules();
        $extras = $validator->extras();
            
        foreach ($extras as $field => $value) {
            $rules[$field] = trim(($rules[$field] ?? '') . "|{$value}", '|');
        }

        return $rules;
    }
}