<?php

declare(strict_types=1);

namespace MaxMind\MinFraud\Model;

/**
 * Class AbstractModel.
 *
 * @internal
 */
abstract class AbstractModel implements \JsonSerializable
{
    /**
     * @param array|null $response the array corresponding to the object in the
     *                             minFraud Insights response
     * @param array      $locales  list of locale codes to use in name property from
     *                             most preferred to least preferred
     */
    // @phpstan-ignore-next-line
    public function __construct(?array $response, array $locales = ['en'])
    {
        // @phpstan-ignore-next-line
        $this->rawResponse = $response;
    }

    /**
     * Convenience method to safely get value from array that might be null.
     *
     * @param mixed $var
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
     * @param string $attr The attribute to get
     *
     * @return mixed The value for the attribute
     */
    public function __get(string $attr)
    {
        if ($attr !== 'instance' && property_exists($this, $attr)) {
            return $this->{$attr};
        }

        throw new \RuntimeException("Unknown attribute: $attr");
    }

    /**
     * @internal
     *
     * @param string $attr The attribute to determine if set
     *
     * @return bool The isset for the attribute
     */
    public function __isset(string $attr): bool
    {
        return isset($this->{$attr});
    }

    /**
     * @return array data that can be serialized by json_encode
     */
    public function jsonSerialize(): ?array
    {
        // @phpstan-ignore-next-line
        return $this->rawResponse;
    }
}
