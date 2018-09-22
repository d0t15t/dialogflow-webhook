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
     * Get the context name.
     *
     * Contexts names on V2 are prefixed with request session path. This
     * function strips the session before returning the name. Set $full argument
     * to TRUE in order to return the context full name.
     *
     * @param bool $full
     *   Set to TRUE to return the full qualified context name, including the
     *   session. By default the short name - without session prefix - will be
     *   returned.
     *
     * @return string
     */
    public function getName($full = FALSE)
    {
        if ($full) {
            return parent::get('name');
        }

        $regex = explode('/contexts/[a-zA-Z0-9-_]+$', static::CONTEXT_NAME_REGEX);
        preg_match($regex[0] . '/contexts/([a-zA-Z0-9-_]+$)' . $regex[1], parent::get('name'), $matches);

        return $matches[1];
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
