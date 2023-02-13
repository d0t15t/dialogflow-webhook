<?php

namespace DialogFlow\Model;

/**
 * Class Messages
 *
 * @package DialogFlow\Model
 */
class Messages extends Base
{

    public function __construct($data = []) {
        parent::__construct($data);
    }

    public function jsonSerialize() {
        return array_reduce(parent::jsonSerialize(), function ($carry, $message) {
            $item = array_map(function ($e, $key) {
                switch ($key) {
                    case 'text':
                      return [$key => [$key => $e]];
                    case 'richContent':
                      return ['payload' => [$key => [$e]]];
                }
            }, $message, array_keys($message));
            $carry[] = reset($item);
            return $carry;
        }, []);
    }

}

