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
}
