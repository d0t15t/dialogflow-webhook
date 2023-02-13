<?php

namespace DialogFlow\Model;

/**
 * Class Event
 *
 * @package DialogFlow\Model
 */
class Event extends Base
{

    public function __construct(array $data = []) {
        parent::__construct($data);
    }

    public function jsonSerialize() {
        $event = parent::jsonSerialize();
        return [
            'followupEventInput' => [
                "name" => $event['name'] ?? NULL,
                "parameters" => $event['parameters'] ?? NULL,
                "languageCode" => $event->langcode ?? NULL,
            ],
        ];
    }

}

