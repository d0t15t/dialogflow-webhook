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
     */
    public function getMessages()
    {
        return parent::get('messages');
    }

    /**
     * Set fulfilment Messages.
     *
     * @param Messages $messages
     */
    public function setMessages(Messages $messages)
    {
        parent::add('messages', $messages);
    }

    /**
     * Return fulfillment event.
     *
     * @return array
     *   The fulfillment event.
     *
     */
    public function getEvent()
    {
        return parent::get('event');
    }

    /**
     * Set fulfilment event.
     */
    public function setEvent(Event $event)
    {
       parent::add('event', $event);
    }

}
