<?php

declare(strict_types=1);

namespace MaxMind\MinFraud\Model;

/**
 * Model with the disposition set by custom rules.
 *
 * In order to receive a disposition, you must be using minFraud custom rules.
 *
 * @property-read string|null $action The action to take on the transaction as
 * defined by your custom rules. The current set of values are "accept",
 * "manual_review", "reject", and "test". If you do not have custom rules set
 * up, `null` will be returned.
 * @property-read string|null $reason The reason for the action. The current
 * possible values are "custom_rule" and "default". If you do not have custom
 * rules set up, `null` will be returned.
 * @property-read string|null $ruleLabel The label of the custom rule that was
 * triggered. If you do not have custom rules set up, the triggered custom rule
 * does not have a label, or no custom rule was triggered, `null` will be
 * returned.
 */
class Disposition extends AbstractModel
{
    /**
     * @internal
     *
     * @var string|null
     */
    protected $action;

    /**
     * @internal
     *
     * @var string|null
     */
    protected $reason;

    /**
     * @internal
     *
     * @var string|null
     */
    protected $ruleLabel;

    public function __construct(?array $response, array $locales = ['en'])
    {
        parent::__construct($response, $locales);
        $this->action = $this->safeArrayLookup($response['action']);
        $this->reason = $this->safeArrayLookup($response['reason']);
        $this->ruleLabel = $this->safeArrayLookup($response['rule_label']);
    }
}
