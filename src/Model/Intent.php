<?php

namespace DialogFlow\Model;

/**
 * Class Intent
 *
 * @package DialogFlow\Model
 */
class Intent extends Base
{

    /**
     * Possible states returned by webhookState().
     */
    const WEBHOOK_STATE_UNSPECIFIED = 0;
    const WEBHOOK_STATE_ENABLED = 1;
    const WEBHOOK_STATE_ENABLED_FOR_SLOT_FILLING = 2;

    /**
     * @param bool $full
     *   Set to TRUE to return the full intentId, inclusive of session prefix.
     *
     * @return string
     */
    public function getIntentId($full = FALSE)
    {
        $intent_full = parent::get('name');

        if ($full) {
            return $intent_full;
        }

        $components = explode('/intents/', $intent_full);

        return end($components);
    }

    /**
     * @return string
     */
    public function getIntentDisplayName()
    {
      return parent::get('displayName');
    }

    /**
     * @return string
     *
     * @deprecated use getIntentDisplayName().
     */
    public function getIntentName()
    {
        return $this->getIntentDisplayName();
    }

    /**
     * @return int
     */
    public function getWebhookState() {
        return (int) parent::get('webhookState');
    }

    /**
     * @return bool
     *
     * @deprecated use getWebhookState().
     */
    public function getWebhookUsed()
    {
        return $this->getWebhookState() !== $this::WEBHOOK_STATE_UNSPECIFIED;
    }

}
