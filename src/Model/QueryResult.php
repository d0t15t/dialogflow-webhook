<?php

namespace DialogFlow\Model;

/**
 * Class QueryResult
 *
 * @package DialogFlow\Model
 */
class QueryResult extends Base
{
    /**
     * QueryResult constructor.
     *
     * @param array $data
     */
    public function __construct(array $data)
    {
        if (!empty($data['outputContexts'])) {
            foreach ($data['outputContexts'] as $key => $context) {
                $data['contexts'][$key] = new Context($context);
            }
        }

        if (!empty($data['fulfillmentText'])) {
            $data['fulfillment'] = new Fulfillment(['text' => $data['fulfillmentText']]);
        }

        if (!empty($data['intent'])) {
            $data['intent'] = new Intent($data['intent']);
        }

        parent::__construct($data);
    }

    /**
     * @return string
     */
    public function getSource()
    {
        return parent::get('source');
    }

    /**
     * @return string
     */
    public function getResolvedQuery()
    {
        return parent::get('resolvedQuery');
    }

    /**
     * @return string
     */
    public function getAction()
    {
        return parent::get('action');
    }

    /**
     * @return bool
     */
    public function getActionIncomplete()
    {
        return parent::get('actionIncomplete');
    }

    /**
     * @return array
     */
    public function getParameters()
    {
        return parent::get('parameters', []);
    }

    /**
     * @return array|Context[]
     */
    public function getContexts()
    {
        return parent::get('contexts', []);
    }

    /**
     * @return Fulfillment
     */
    public function getFulfillment()
    {
        return parent::get('fulfillment');
    }

    /**
     * @return Intent
     */
    public function getIntent()
    {
        return parent::get('intent');
    }

    /**
     * @return Intent
     *
     * @deprecated use getIntent()
     *
     */
    public function getMetadata()
    {
        return $this->getIntent();
    }

    /**
     * @return float
     */
    public function getIntentDetectionConfidence() {
        return parent::get('intentDetectionConfidence');
    }

    /**
     * @return float
     *
     * @deprecated use getIntentDetectionConfidence().
     */
    public function getScore()
    {
        return $this->getIntentDetectionConfidence();
    }

}
