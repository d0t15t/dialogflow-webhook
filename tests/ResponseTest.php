<?php

use DialogFlow\Model\Context;
use DialogFlow\Model\Webhook\Request;
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
    $response->setDisplayText('this text is displayed visually');

    $context = new Context();
    $context->add('name', 'context name');
    $context->add('lifespan', 5);
    $context->add('parameters', ['param' => 'param value']);
    $response->addContext($context);

    $response->add('source', 'example.com');

    // V1 JSON example, extended from:
    // https://github.com/dialogflow/fulfillment-webhook-json/blob/master/responses/v1/response.json
    $expected_response = <<<EOL
{
  "speech": "this text is spoken out loud if the platform supports voice interactions",
  "displayText": "this text is displayed visually",
  "contextOut": [
    {
      "name": "context name",
      "lifespan": 5,
      "parameters": {
        "param": "param value"
      }
    }
  ],
  "source": "example.com"
}
EOL;

    $this->assertEquals(json_encode(json_decode($expected_response)), json_encode($response->jsonSerialize()));
  }

}