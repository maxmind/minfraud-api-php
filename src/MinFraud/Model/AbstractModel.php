<?php

namespace MaxMind\MinFraud\Model;

/**
 * Class AbstractModel.
 *
 * @internal
 */
abstract class AbstractModel implements \JsonSerializable
{
    /**
     * @param array $response the array corresponding to the object in the
     *                        minFraud Insights response
     * @param array $locales  list of locale codes to use in name property from
     *                        most preferred to least preferred
     */
    public function __construct($response, $locales = ['en'])
    {
        $this->rawResponse = $response;
    }

    /**
     * Convenience method to safely get value from array that might be null.
     *
     * @param $var
     * @param mixed $default
     *
     * @return mixed
     *
     * @internal
     */
    protected function safeArrayLookup(&$var, $default = null)
    {
        return isset($var) ? $var : $default;
    }

    /**
     * @internal
     *
     * @param $attr The attribute to get
     *
     * @return The value for the attribute
     */
    public function __get($attr)
    {
        if ($attr !== 'instance' && property_exists($this, $attr)) {
            return $this->$attr;
        }

        throw new \RuntimeException("Unknown attribute: $attr");
    }

    /**
     * @internal
     *
     * @param $attr The attribute to determine if set
     *
     * @return The isset for the attribute
     */
    public function __isset($attr)
    {
        return isset($this->$attr);
    }

    /**
     * Returns data that can be serialized by json_encode.
     */
    public function jsonSerialize()
    {
        return $this->rawResponse;
    }
}
