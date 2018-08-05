DialogFlow Webhook Fulfillment PHP sdk
==============

[![Build Status](https://travis-ci.org/gambry/dialogflow-webhook.svg?branch=master)](https://travis-ci.org/gambry/dialogflow-webhook)
[![version][packagist-version]][packagist-url]
[![Downloads][packagist-downloads]][packagist-url]

[packagist-url]: https://packagist.org/packages/gambry/dialogflow-webhook
[packagist-version]: https://img.shields.io/packagist/v/gambry/dialogflow-webhook.svg?style=flat
[packagist-downloads]: https://img.shields.io/packagist/dm/gambry/dialogflow-webhook.svg?style=flat

This is an unofficial php sdk for [Dialogflow][1] [Fulfillment][2].

_If you are looking for [Detect Intent and Agent APIs][3] php sdk have a look a the [official repo][4]_.

```
Dialogflow: Build brand-unique, natural language interactions for bots, applications and devices.
```

## Install:

Via composer:

```
$ composer require gambry/dialogflow-webhook
```

## Usage:

In your webhook request handler:
```php
require_once __DIR__.'/vendor/autoload.php';

if ($webhook_json = json_decode($request_body, TRUE)) {
    $request = new \DialogFlow\Model\Webhook\Request($webhook_json);
    $intent_name = $request->getResult()->getIntent()->getIntentName();
    
    if ($intent_name === 'HelloWorld') {
        $fulfillment = new \DialogFlow\Model\Fulfillment();
        $fulfillment->setText('Hi from the fulfilment!');
        
        $response = new \DialogFlow\Model\Webhook\Response();
        $response->setFulfillment($fulfillment);
        
        echo json_encode($response);
    }
}
```
**Note: depending by the way you handle the request the library auto-loading, the `$request_body` variable and the way to return the `$response` may vary.**

[1]: https://dialogflow.com
[2]: https://dialogflow.com/docs/sdks#fulfillment
[3]: https://dialogflow.com/docs/sdks#detect_intent_and_agent_apis
[4]: https://github.com/GoogleCloudPlatform/google-cloud-php-dialogflow