<?php

namespace DialogFlow\Model\Webhook;

use DialogFlow\Model\Base;
use DialogFlow\Model\Context;
use DialogFlow\Model\Fulfillment;

/**
 * Class Response.
 *
 * Data model for a webhook response.
 *
 * @package DialogFlow\Model\Webhook
 */
class Response extends Base {

    /**
     * @var \DialogFlow\Model\Fulfillment
     */
    protected $fulfillment;

    /**
     * Get response fulfillment.
     *
     * @return \DialogFlow\Model\Fulfillment
     */
    public function getFulfillment() {
        if (!($this->fulfillment instanceof Fulfillment)) {
            $this->fulfillment = new Fulfillment();
        }
        return $this->fulfillment;
    }

    /**
     * Set response fulfillment.
     *
     * @param \DialogFlow\Model\Fulfillment $fulfillment
     *   The fulfillment instance.
     */
    public function setFulfillment(Fulfillment $fulfillment) {
        $this->fulfillment = $fulfillment;
    }

    /**
     * Set response speech.
     *
     * @param string $string
     *   The response speech message.
     *
     * @deprecated use fulfilment setter add().
     */
    public function setSpeech($string) {
        $this->getFulfillment()->add('text', $string);
    }

    /**
     * Set display text.
     *
     * Set the text displayed on the user device screen.
     *
     * @param string $string
     *   The text copy.
     *
     * @deprecated removed in DialogFlow API V2.
     */
    public function setDisplayText($string) {
        NULL;
    }

    /**
     * Add a context to the response.
     *
     * @param \DialogFlow\Model\Context $context
     *   The Context to be added.
     */
    public function addContext(Context $context) {
        $contexts = $this->get('outputContexts');
        $contexts[] = $context;
        $this->add('outputContexts', $contexts);
    }

    public function jsonSerialize() {
        // Prepend fulfillmentText.
        $json = ['fulfillmentText' => $this->getFulfillment()->getText()];

        // Append fulfillmentMessages, if any.
        if ($messages = $this->getFulfillment()->getMessages()) {
            $json['fulfillmentMessages'] = array_map(function($message) {
                return $message->jsonSerialize();
            }, $messages);
        }

        return $json + parent::jsonSerialize();
    }

}
