<?php

use DialogFlow\Model\Context;
use DialogFlow\Model\Webhook\Response;
use PHPUnit\Framework\TestCase;

/**
 * Test the webhook response model.
 *
 * @covers \DialogFlow\Model\Webhook\Response
 */
class ResponseTest extends TestCase {

  /**
   * Tests response to be sent to Dialogflow is correctly processed.
   */
  public function testResponse() {

    $response = new Response();
    $response->setSpeech('this text is spoken out loud if the platform supports voice interactions');

    $context = new Context();
    $context->add('name', 'projects/your-agents-project-id/agent/sessions/88d13aa8-2999-4f71-b233-39cbf3a824a0/contexts/context-name');
    $context->add('lifespan', 5);
    $context->add('parameters', ['param' => 'param value']);
    $response->addContext($context);

    $response->add('source', 'example.com');

    // V1 JSON example, extended from:
    // https://github.com/dialogflow/fulfillment-webhook-json/blob/master/responses/v1/response.json
    $expected_response = <<<EOL
{
  "fulfillmentText": "this text is spoken out loud if the platform supports voice interactions",
  "outputContexts": [
    {
      "name": "projects/your-agents-project-id/agent/sessions/88d13aa8-2999-4f71-b233-39cbf3a824a0/contexts/context-name",
      "lifespanCount": 5,
      "parameters": {
        "param": "param value"
      }
    }
  ],
  "source": "example.com"
}
EOL;

    $this->assertEquals(json_encode(json_decode($expected_response)), json_encode($response));

    // Test response with session.
    $response2 = new Response([], 'projects/your-agents-project-id/agent/sessions/88d13aa8-2999-4f71-b233-39cbf3a824a0');
    $response2->setSpeech('this text is spoken out loud if the platform supports voice interactions');

    $context2 = $response2->createContextFromSession('context-name');
    $context2->add('name', 'projects/your-agents-project-id/agent/sessions/88d13aa8-2999-4f71-b233-39cbf3a824a0/contexts/context-name');
    $context2->add('lifespan', 5);
    $context2->add('parameters', ['param' => 'param value']);
    $this->assertEquals($context, $context2);

    $response2->addContext($context);
    $response2->add('source', 'example.com');
    $this->assertEquals(json_encode(json_decode($expected_response)), json_encode($response2));
  }

}