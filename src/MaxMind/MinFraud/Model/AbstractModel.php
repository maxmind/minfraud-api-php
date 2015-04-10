<?php

namespace MaxMind\MinFraud\Model;

/**
 * Class AbstractModel
 * @package MaxMind\MinFraud\Model
 */
abstract class AbstractModel
{
    /**
     * @param $var
     * @param mixed $default
     * @return mixed
     */
    protected function get(&$var, $default = null)
    {
        return isset($var) ? $var : $default;
    }
}
