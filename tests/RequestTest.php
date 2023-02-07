<?php

use DialogFlow\Model\Context;
use DialogFlow\Model\Intent;
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
    protected function setUp(): void {
        // V2 JSON example, extended from:
        // https://raw.githubusercontent.com/dialogflow/fulfillment-webhook-json/master/requests/v2/request.json
        $body = <<<EOL
{
  "responseId": "7811ac58-5bd5-4e44-8d06-6cd8c67f5406",
  "session": "projects/your-agents-project-id/agent/sessions/88d13aa8-2999-4f71-b233-39cbf3a824a0",
  "queryResult": {
    "queryText": "user's original query to your agent",
    "parameters": {
      "param1": "foo",
      "param2": "bar"
    },
    "allRequiredParamsPresent": true,
    "fulfillmentText": "Text defined in Dialogflow's console for the intent that was matched",
    "fulfillmentMessages": [
      {
        "text": {
          "text": [
            "Text defined in Dialogflow's console for the intent that was matched"
          ]
        }
      }
    ],
    "outputContexts": [
      {
        "name": "projects/your-agents-project-id/agent/sessions/88d13aa8-2999-4f71-b233-39cbf3a824a0/contexts/generic-context-name",
        "lifespanCount": 5,
        "parameters": {
          "param1": "foo",
          "param2": "bar"
        }
      }
    ],
    "intent": {
      "name": "projects/your-agents-project-id/agent/intents/29bcd7f8-f717-4261-a8fd-2d3e451b8af8",
      "displayName": "Name of Matched Dialogflow Intent",
      "webhookState": 2
    },
    "intentDetectionConfidence": 1,
    "diagnosticInfo": {},
    "languageCode": "en"
  },
  "originalDetectIntentRequest": {}
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

        $this->assertFalse(method_exists($request, 'getTimestamp'));
        $this->assertFalse(method_exists($request, 'getStatus'));
        $this->assertEquals('7811ac58-5bd5-4e44-8d06-6cd8c67f5406', $request->getId());
        $this->assertEquals('projects/your-agents-project-id/agent/sessions/88d13aa8-2999-4f71-b233-39cbf3a824a0', $request->getSession());
        $this->assertEquals('88d13aa8-2999-4f71-b233-39cbf3a824a0', $request->getSessionId());
        $this->assertEquals(1, $request->getResult()
            ->getIntentDetectionConfidence());
    }

    /**
     * Test request metadata.
     *
     * @covers \DialogFlow\Model\Metadata
     */
    public function testMetadata() {
        $request = new Request($this->request_body);
        $intent = $request->getResult()->getIntent();

        $this->assertEquals('29bcd7f8-f717-4261-a8fd-2d3e451b8af8', $intent->getIntentId());
        $this->assertEquals('projects/your-agents-project-id/agent/intents/29bcd7f8-f717-4261-a8fd-2d3e451b8af8', $intent->getIntentId(TRUE));
        $this->assertEquals('Name of Matched Dialogflow Intent', $intent->getIntentName());
        $this->assertTrue($intent->getWebhookUsed());
        $this->assertEquals(Intent::WEBHOOK_STATE_ENABLED_FOR_SLOT_FILLING, $intent->getWebhookState());
    }

    /**
     * Test request params.
     *
     * @covers \DialogFlow\Model\QueryResult::getParameters()
     */
    public function testParameters() {
        $request = new Request($this->request_body);
        $this->assertEquals('foo', $request->getResult()
            ->getParameters()['param1']);
        $this->assertEquals('bar', $request->getResult()
            ->getParameters()['param2']);
        $this->assertCount(2, $request->getResult()->getParameters());
    }

    /**
     * Test context with old name.
     *
     * @covers \DialogFlow\Model\Context
     *
     */
    public function testExceptionIsThrownCreatingContextWithOldNameFormat() {
        $this->expectException(\DialogFlow\Exception\InvalidContextException::class);
        $context = new Context();
        $context->add('name', 'invalid context name');
    }

    /**
     * Test context with old name.
     *
     * @covers \DialogFlow\Model\Context
     *
     */
    public function testExceptionIsThrownAddingOldNameFormatToContext() {
        $this->expectException(\DialogFlow\Exception\InvalidContextException::class);
        $context = new Context(['name' => 'invalid name']);
    }

    /**
     * Test request contexts.
     *
     * @covers \DialogFlow\Model\Context
     */
    public function testContexts() {
        $request = new Request($this->request_body);
        $context = new Context();
        $context->add('name', 'projects/your-agents-project-id/agent/sessions/88d13aa8-2999-4f71-b233-39cbf3a824a0/contexts/generic-context-name');
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
    }

    /**
     * Test request contexts short/full names.
     *
     * @covers \DialogFlow\Model\Context
     */
    public function testContextsNames() {
        $request = new Request($this->request_body);
        $this->assertEquals('generic-context-name', $request->getResult()->getContexts()[0]->getName());

        $context = new Context();
        $context->add('name', 'projects/your-agents-project-id/agent/sessions/88d13aa8-2999-4f71-b233-39cbf3a824a0/contexts/new-generic-context-name');
        $context->add('parameters', ['param1' => 'foo', 'param2' => 'bar']);
        $context->add('lifespan', 5);

        $this->assertEquals('new-generic-context-name', $context->getName());
        $this->assertEquals('projects/your-agents-project-id/agent/sessions/88d13aa8-2999-4f71-b233-39cbf3a824a0/contexts/new-generic-context-name', $context->getName(TRUE));
    }

}