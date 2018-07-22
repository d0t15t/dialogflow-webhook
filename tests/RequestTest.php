<?php

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
    $body = <<<EOL
{
  "id": "",
  "timestamp": "2017-08-30T03:35:53.052Z",
  "lang": "en",
  "result": {
    "source": "agent",
    "resolvedQuery": "query would go here",
    "speech": "",
    "action": "userinfo",
    "actionIncomplete": false,
    "parameters": {
      "Staff": "John"
    },
    "contexts": [],
    "metadata": {
      "intentId": "userinfo123",
      "webhookUsed": "true",
      "webhookForSlotFillingUsed": "false",
      "intentName": "UserInfo"
    },
    "fulfillment": {
      "speech": "",
      "messages": [
        {
          "type": 0,
          "speech": ""
        }
      ]
    },
    "score": 1.0
  },
  "status": {
    "code": 200,
    "errorType": "success"
  },
  "sessionId": "2b0d2a35-293d-4175-94ba-f399e0bde1bd"
}
EOL;
    $body_decoded = json_decode($body, TRUE);
    $request = new Request($body_decoded);

    $this->assertEquals($body_decoded['timestamp'], $request->getTimestamp());
    $this->assertEquals($body_decoded['result']['metadata']['intentId'], $request->getResult()->getMetadata()->getIntentId());
    $this->assertEquals($body_decoded['result']['metadata']['intentName'], $request->getResult()->getMetadata()->getIntentName());
    $this->assertEquals('John', $request->getResult()->getParameters()['Staff']);
  }

}