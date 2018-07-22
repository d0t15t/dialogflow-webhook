<?php

use DialogFlow\Model\Context;
use DialogFlow\Model\Webhook\Request;
use PHPUnit\Framework\TestCase;

/**
 * Test the webhook request model.
 *
 * @covers \DialogFlow\Model\Webhook\Request
 * @covers \DialogFlow\Model\Query
 * @covers \DialogFlow\Model\QueryResult
 */
class RequestTest extends TestCase {

  /**
   * Tests request from Dialogflow is correctly processed.
   */
  public function testRequest() {
    // V1 JSON example, extended from:
    // https://github.com/dialogflow/fulfillment-webhook-json/blob/master/requests/v1/request.json
    $body = <<<EOL
{
  "originalRequest": {},
  "id": "7811ac58-5bd5-4e44-8d06-6cd8c67f5406",
  "sessionId": "1515191296300",
  "timestamp": "2018-01-05T22:35:05.903Z",
  "timezone": "",
  "lang": "en-us",
  "result": {
    "source": "agent",
    "resolvedQuery": "user's original query to your agent",
    "speech": "Text defined in Dialogflow's console for the intent that was matched",
    "action": "Matched Dialogflow intent action name",
    "actionIncomplete": false,
    "parameters": {
      "param": "param value"
    },
    "contexts": [
      {
        "name": "incoming context name",
        "parameters": {
          "param1": "foo",
          "param2": "bar"
        },
        "lifespan": 5
      }
    ],
    "metadata": {
      "intentId": "29bcd7f8-f717-4261-a8fd-2d3e451b8af8",
      "webhookUsed": "true",
      "webhookForSlotFillingUsed": "false",
      "nluResponseTime": 6,
      "intentName": "Name of Matched Dialogflow Intent"
    },
    "fulfillment": {
      "speech": "Text defined in Dialogflow's console for the intent that was matched",
      "messages": [
        {
          "type": 0,
          "speech": "Text defined in Dialogflow's console for the intent that was matched"
        }
      ]
    },
    "score": 1
  }
}
EOL;
    $body_decoded = json_decode($body, TRUE);
    $request = new Request($body_decoded);

    $this->assertEquals($body_decoded['timestamp'], $request->getTimestamp());
    $this->assertEquals($body_decoded['result']['metadata']['intentId'], $request->getResult()->getMetadata()->getIntentId());
    $this->assertEquals($body_decoded['result']['metadata']['intentName'], $request->getResult()->getMetadata()->getIntentName());
    $this->assertEquals('param value', $request->getResult()->getParameters()['param']);

    $context = new Context($body_decoded['result']['contexts'][0]);
    $this->assertEquals([$context], $request->getResult()->getContexts());
  }

}