<?php

declare(strict_types=1);

namespace MaxMind\MinFraud\Model;

/**
 * Model with the disposition set by custom rules.
 *
 * In order to receive a disposition, you must be using minFraud custom rules.
 */
class Disposition implements \JsonSerializable
{
    /**
     * @var string|null The action to take on the transaction as
     *                  defined by your custom rules. The current set of values are "accept",
     *                  "manual_review", "reject", and "test". If you do not have custom rules set
     *                  up, `null` will be returned.
     */
    public readonly ?string $action;

    /**
     * @var string|null The reason for the action. The current
     *                  possible values are "custom_rule" and "default". If you do not have custom
     *                  rules set up, `null` will be returned.
     */
    public readonly ?string $reason;

    /**
     * @var string|null The label of the custom rule that was
     *                  triggered. If you do not have custom rules set up, the triggered custom rule
     *                  does not have a label, or no custom rule was triggered, `null` will be
     *                  returned.
     */
    public readonly ?string $ruleLabel;

    /**
     * @param array<string, mixed>|null $response
     */
    public function __construct(?array $response)
    {
        $this->action = $response['action'] ?? null;
        $this->reason = $response['reason'] ?? null;
        $this->ruleLabel = $response['rule_label'] ?? null;
    }

    /**
     * @return array<string, mixed>
     */
    public function jsonSerialize(): array
    {
        $js = [];

        if ($this->action !== null) {
            $js['action'] = $this->action;
        }

        if ($this->reason !== null) {
            $js['reason'] = $this->reason;
        }

        if ($this->ruleLabel !== null) {
            $js['rule_label'] = $this->ruleLabel;
        }

        return $js;
    }
}
