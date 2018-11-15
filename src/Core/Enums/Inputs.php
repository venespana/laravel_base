<?php

namespace VD\Core\Enums;

use VD\Core\Enums;

class Inputs extends Enums
{
    const TEXT = 'text';
    const EMAIL = 'email';
    const ASSWORD = 'password';
    const TEXTAREA = 'textarea';
    const HIDDEN = 'hidden';
    const NUMBER = 'number';
    const FILE = 'file';
    const SELECT = 'select';

    protected static function data()
    {
        $data = [];
        foreach (Inputs::getConstants() as $constant) {
            $data[$constant] = [
                'title' => $constant
            ];
        }
        return $data;
    }

    public static function values(array $less = [])
    {
        return array_keys(Inputs::getValues('title', $less));
    }

    public static function default()
    {
        return static::TEXT;
    }
}