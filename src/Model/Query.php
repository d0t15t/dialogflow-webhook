<?php

namespace DialogFlow\Model;

/**
 * Class Query
 *
 * @package DialogFlow\Model
 */
class Query extends Base
{
    /**
     * Query constructor.
     *
     * @param array $data
     */
    public function __construct($data = [])
    {

        if (!empty($data['queryResult'])) {
            $data['queryResult'] = new QueryResult($data['queryResult']);
        }

        // @TODO: Is this still needed in V2?
        if (!empty($data['status'])) {
            $data['status'] = new Status($data['status']);
        }

        parent::__construct($data);
    }

    /**
     * @return string
     */
    public function getId()
    {
        return parent::get('responseId');
    }

    /**
     * @return QueryResult
     */
    public function getResult()
    {
        return parent::get('queryResult');
    }

    /**
     * @return string
     */
    public function getSession()
    {
        return parent::get('session');
    }

    /**
     * @return string
     */
    public function getSessionId()
    {
      $session_components = explode('/', parent::get('session'));
      return end($session_components);
    }

}
