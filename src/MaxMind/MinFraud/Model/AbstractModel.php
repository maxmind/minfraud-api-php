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

    /**
     * @ignore
     */
    public function __get($attr)
    {
        if ($attr != "instance" && property_exists($this, $attr)) {
            return $this->$attr;
        }

        throw new \RuntimeException("Unknown attribute: $attr");
    }
}
