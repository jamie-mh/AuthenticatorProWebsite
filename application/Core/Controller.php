<?php

namespace AuthPro\Core;

abstract class Controller
{
    protected function getQueryParameter(string $name, $defaultValue = null, int $filter = FILTER_DEFAULT)
    {
        if (!isset($_GET[$name])) {
            return $defaultValue;
        }

        $value = filter_input(INPUT_GET, $name, $filter);
        return $value ?? $defaultValue;
    }
}
