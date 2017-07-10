<?php

namespace MaxMind\MinFraud\Model;

/**
 * Model with the disposition set by custom rules.
 *
 * In order to receive a disposition, you must be use the minFraud custom
 * rules.
 *
 * @property-read string|null $action The action to take on the transaction as
 * defined by your custom rules. The current set of values are "accept",
 * "manual_review", and "reject". If you do not have custom rules set up,
 * `null` will be returned.
 * @property-read string|null $reason The reason for the action. The current
 * possible values are "custom_rule", "block_list", and "default". If you do
 * not have custom rules set up, `null` will be returned.
 */
class Disposition extends AbstractModel
{
    /**
     * @internal
     */
    protected $action;

    /**
     * @internal
     */
    protected $reason;

    public function __construct($response, $locales = ['en'])
    {
        parent::__construct($response, $locales);
        $this->action = $this->safeArrayLookup($response['action']);
        $this->reason = $this->safeArrayLookup($response['reason']);
    }
}
