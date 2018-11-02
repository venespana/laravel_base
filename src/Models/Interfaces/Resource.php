<?php

namespace VD\Models\Interfaces;

interface Resource
{
    public function getResource() : string;

    public function getResources() : array;
}