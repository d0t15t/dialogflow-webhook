<?php

namespace DialogFlow\Model;

/**
 * Class Fulfillment
 *
 * @package DialogFlow\Model
 */
class Fulfillment extends Base
{

    /**
     * @return string
     */
    public function getText() {
        return parent::get('text');
    }

    /**
     * Set fulfilment text.
     *
     * @param string $text
     */
    public function setText($text) {
        parent::add('text', $text);
    }

    /**
     * @return string
     *
     * @deprecated use getText().
     */
    public function getSpeech()
    {
        return $this->getText();
    }

    /**
     * @return string
     *
     * @deprecated use getText().
     */
    public function getDisplayText()
    {
        return $this->getText();
    }

    /**
     * Return fulfillment messages.
     *
     * @return array
     *   The fulfillment messages.
     *
     * TODO: Implement messages.
     */
    public function getMessages() {
        return [];
    }

}
