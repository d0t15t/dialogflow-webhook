<?php

namespace DialogFlow\Model;

use DialogFlow\Exception\InvalidContextException;

/**
 * Class Context
 *
 * @package DialogFlow\Model
 */
class Context extends Base
{

    /**
     * Regex matching context name format.
     */
    const CONTEXT_NAME_REGEX = '#^projects/[^/]+/agent/sessions/[^/]+/contexts/[a-zA-Z0-9-_]+$#';

    /**
     * {@inheritdoc}
     */
    public function __construct(array $data = []) {

        // V2 Context name must be prefixed with session.
        if (isset($data['name']) && !preg_match($this::CONTEXT_NAME_REGEX, $data['name'])) {
            throw new InvalidContextException();
        }

        parent::__construct($data);
    }

    /**
     * @return string
     */
    public function getName()
    {
        return parent::get('name');
    }

    /**
     * @return array
     */
    public function getParameters()
    {
        return parent::get('parameters', []);
    }

    /**
     * @return integer
     */
    public function getLifespan()
    {
        return parent::get('lifespanCount');
    }

    /**
     * {@inheritdoc}
     */
    public function add($name, $value) {

        // Accept legacy 'lifespan' property name.
        if ($name === 'lifespan') {
            $name = 'lifespanCount';
        }

        // V2 Context name must be prefixed with session.
        if ($name === 'name' && !preg_match($this::CONTEXT_NAME_REGEX, $value)) {
            throw new InvalidContextException();
        }

        parent::add($name, $value);
    }

}
