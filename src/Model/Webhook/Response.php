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
     * The request session, used to prefix contexts automatically, if any.
     *
     * @var string
     */
    protected $session;

    /**
     * Response constructor.
     *
     * @param array $data
     *   Response data.
     *
     * @param string $request_session
     *   The request session, used to automatically prefix contexts. This is
     *   optional for back-compatibility reasons, but ideally request session
     *   string should always be passed on creating responses.
     */
    public function __construct(array $data = [], $request_session = NULL) {
        $this->session = $request_session;
        parent::__construct($data);
    }

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
     *
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

    /**
     * Helper to create V2 Context.
     *
     * V2 API contexts names must be prefixed with the request session. This
     * helper allows developers to create contexts without passing the session.
     *
     * @param string $name
     *   Context name, if not already it will be prefixed with session.
     * @param array $data
     *   Context object data.
     *
     * @return \DialogFlow\Model\Context
     *   A context instance.
     */
    public function createContextFromSession($name, array $data = []) {
        $data['name'] = $name;
        if ($this->session && isset($data['name']) && !preg_match(Context::CONTEXT_NAME_REGEX, $data['name'])) {
            $data['name'] = rtrim($this->session , '/') . '/contexts/' . $data['name'];
        }
        return new Context($data);
    }

    public function jsonSerialize() {
        $json = [];
        if ($text = $this->getFulfillment()->getText()) {
          $json['fulfillmentText'] = $this->getFulfillment()->getText();
        }
        if ($messages = $this->getFulfillment()->getMessages()) {
          $json['fulfillmentMessages'] = [
              ['text' => [
                'text' => $messages['texts'],
              ]],
              ['payload' => [
                'richContent' =>
                  [$messages['messages']],
              ]],
          ];
        }

        if ($event = $this->getFulfillment()->getEvent()) {
          $json['followupEventInput'] = array_filter([
            "name" => $event['name'],
            "parameters" => $event['parameters'],
            "languageCode" => $event->langcode,
          ], fn ($e) => $e);
        }

        return $json + parent::jsonSerialize();
    }

}
