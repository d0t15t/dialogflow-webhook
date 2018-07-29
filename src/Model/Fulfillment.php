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

}
