<?php

use DialogFlow\Model\Context;
use DialogFlow\Model\Webhook\Request;
use PHPUnit\Framework\TestCase;

/**
 * Test the webhook request model.
 *
 * Tests request from Dialogflow is correctly processed.
 */
class RequestTest extends TestCase {

  /**
   * Requested body JSON-encoded.
   *
   * @var array
   */
  protected $request_body;

  /**
   * {@inheritdoc}
   */
  public function setUp() {
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
      "param1": "foo",
      "param2": "bar"
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

    $this->request_body = json_decode($body, TRUE);
  }

  /**
   * Test general request values.
   *
   * @covers \DialogFlow\Model\Webhook\Request
   * @covers \DialogFlow\Model\Query
   */
  public function testGeneralData() {
    $request = new Request($this->request_body);

    $this->assertEquals('2018-01-05T22:35:05.903Z', $request->getTimestamp());
    $this->assertEquals('7811ac58-5bd5-4e44-8d06-6cd8c67f5406',$request->getId());
    $this->assertEquals('1515191296300',$request->getSessionId());
    $this->assertEquals(1, $request->getResult()->getScore());

    // 'status' is covered by the library, but it's not a valid webhook request
    // property, so this should be null.
    $this->assertNull($request->getStatus());
  }

  /**
   * Test request metadata.
   *
   * @covers \DialogFlow\Model\Metadata
   */
  public function testMetadata() {
    $request = new Request($this->request_body);
    $metadata = $request->getResult()->getMetadata();

    $this->assertEquals('29bcd7f8-f717-4261-a8fd-2d3e451b8af8', $metadata->getIntentId());
    $this->assertEquals('Name of Matched Dialogflow Intent', $metadata->getIntentName());
    $this->assertEquals('true', $metadata->getWebhookUsed());
  }

  /**
   * Test request params.
   *
   * @covers \DialogFlow\Model\QueryResult::getParameters()
   */
  public function testParameters() {
    $request = new Request($this->request_body);
    $this->assertEquals('foo', $request->getResult()->getParameters()['param1']);
    $this->assertEquals('bar', $request->getResult()->getParameters()['param2']);
    $this->assertCount(2, $request->getResult()->getParameters());
  }

  /**
   * Test request contexts.
   *
   * @covers \DialogFlow\Model\Context
   */
  public function testContexts() {
    $request = new Request($this->request_body);
    $context = new Context();
    $context->add('name', 'incoming context name');
    $context->add('parameters', ['param1' => 'foo', 'param2' => 'bar']);
    $context->add('lifespan', 5);

    $this->assertEquals([$context], $request->getResult()->getContexts());
  }

  /**
   * Tests request fulfillment.
   *
   * @covers \DialogFlow\Model\Fulfillment
   */
  public function testFulfillment() {
    $request = new Request($this->request_body);
    $fulfillment = $request->getResult()->getFulfillment();

    $this->assertEquals('Text defined in Dialogflow\'s console for the intent that was matched', $fulfillment->getSpeech());

    // This is the fulfillment suggested by Dialogflow in the Request, so it
    // shouldn't have properties only existing in the Response fulfillment.
    $this->assertNull($fulfillment->getContextOut());
    $this->assertNull($fulfillment->getDisplayText());
    $this->assertNull($fulfillment->getSource());
  }

}