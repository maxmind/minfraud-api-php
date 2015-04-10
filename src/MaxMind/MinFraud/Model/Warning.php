<?php

namespace MaxMind\MinFraud\Model;

/**
 * Class Warning
 * @package MaxMind\MinFraud\Model
 */
class Warning extends AbstractModel
{
    protected $code;
    protected $warning;
    protected $input;

    /**
     * @param $response
     * @param array $locales
     */
    public function __construct($response, $locales = array('en'))
    {
        $this->code = $this->get($response['code']);
        $this->warning = $this->get($response['warning']);
        $this->input = $this->get($response['input']);
    }

    /**
     * @return string
     */
    public function code()
    {
        return $this->code;
    }

    /**
     * @return string
     */
    public function warning()
    {
        return $this->warning;
    }

    /**
     * @return array
     */
    public function input()
    {
        return $this->input;
    }
}
