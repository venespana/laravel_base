<?php

namespace VD\Core\Tables\Traits;

use VD\Core\Enums\Inputs;

trait RequestFields
{
    protected $requestValidator = null;
    protected $labelBase = 'VD::forms';

    protected  function buttons(...$buttons)
    {
        foreach ($buttons as $button) {
            $options = $this->prepareData($button['options'] ?? []);
            $this->add(
                $button['name'], 
                $button['type'] ?? 'submit', 
                $this->fieldOptions($button['name'], $options, 'button')
            );
        }   
        return $this;
    }

    private function prepareData($data)
    {
        $result = [];
        if (is_string($data)) {
            $data = explode('|', $data);            
        }

        foreach ($data as $key => $value) {
            if (is_numeric($key)) {
                $result[] = $value;
                continue;
            }

            $value = trim($value);
            if (!is_null($value) && !empty($value)) {
                $value = "{$key}:{$value}";
            } else {
                $value = $key;
            }

            $result[] = $value;
        }

        return $result;
    }

    protected function fields() 
    {
        $fields = $this->requestValidator::fields();

        foreach ($fields as $key => $field) {
            $data = $this->prepareData($field);
            $type = $this->fieldType($data);
            $this->add($key, $type, $this->fieldOptions($key, $data, $type));
        }

        return $this;
    }

    protected function fieldOptions(string $name, array $data, string $type) : array
    {
        $options = [
            'label' => $this->setLabel($data, $name),
            'attr' => $this->setAttrs($data)
        ];
        
        if (method_exists($this, "{$type}Options")) {
            $options = array_merge($options, $this->{"{$type}Options"}($data));
        }

        return $options;
    }

    protected function selectOptions(array $data)
    {
        $choices = '';
        $empty = 'Select one...';
        
        foreach ($data as $key) {
            [$type, $value] = array_pad(explode(':', $key, 2), 2, '');
            if ($type === 'choices' || $type === 'values') {
                $choices = $value;
            }
        }        
        
        $result = [
            'choices' => json_decode($choices, true) ?? [],
            'empty_value' => $empty
        ];

        return $result;
    }

    protected function fieldType(array $data) : string
    {
        $type = Inputs::default();
        foreach ($data as $value) {
            if (Inputs::isValidValue($value)) {
                $type = $value;
                break;
            }
        }
        return $type;
    }

    protected function setLabel(array $data, $default) : string
    {
        $label = $default;
        foreach ($data as $key) {
            [$type, $value] = array_pad(explode(':', $key, 2), 2, $label);
            if ($type === 'label') {
                $label = $value;
                break;
            }
        }
        
        $trans = "{$this->labelBase}.{$label}";
        $result = __($trans);
        return ($result === $trans) ? $label : $result;
    }

    protected function setAttrs(array $data)
    {
        $attrs = [
            'required',
            'min',
            'max',
        ];

        $result = [];

        foreach ($data as $key) {
            [$type, $value] = array_pad(explode(':', $key, 2), 2, '');
            if (!in_array($type, $attrs)) {
                continue;
            }
            $result[$type] = $value;
        }
        return $result;
    }
}