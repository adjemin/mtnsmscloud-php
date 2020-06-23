# MTN SMS CLOUD PHP

[![Latest Stable Version](https://poser.pugx.org/adjemin/mtnsmscloud/v)](//packagist.org/packages/adjemin/mtnsmscloud) [![Total Downloads](https://poser.pugx.org/adjemin/mtnsmscloud/downloads)](//packagist.org/packages/adjemin/mtnsmscloud) [![Latest Unstable Version](https://poser.pugx.org/adjemin/mtnsmscloud/v/unstable)](//packagist.org/packages/adjemin/mtnsmscloud) [![License](https://poser.pugx.org/adjemin/mtnsmscloud/license)](//packagist.org/packages/adjemin/mtnsmscloud)


This repository provides suitables tools for performing sms campaign. Actually, only Api's from MTN SMS CLOUD are embedded.

## Requirements

PHP 5.6.0 and later.

## Composer

You can install the bindings via [Composer](http://getcomposer.org/). Run the following command:

```bash
composer require adjemin/mtnsmscloud
```

## Architecture
This repo has two main classes:

  - **BaseApi** in `./core/BaseApi.php`
  - **MTNSMSApi** in `./MTNSMSApi.php`

The first class is used to perform POST and GET HTTP request.
The second one is used for MTN SMS.

## Instanciation
Be sure to check the namespace first.

```php
use MtnSmsCloud/MTNSMSApi";

/**
 * Create a new Instance
 * 
 * @param string $sender_id = The desired sender_id
 * @param string $token = $token associated with $sender_id 
 */
$msa = new MTNSMSApi($sender_id, $token);

/**
 * Send a new Campaign
 * 
 * @param array $recipients {Ex: ["225xxxxxxxx", "225xxxxxxxx"]}
 * @param string $message
 */
return $msa->newCampaign($recipients, $message);

/**
 * Retrieves on created Campaign
 * 
 * @param string $campaign_id
 * @param string $message
 */
return $msa->getCampaign($campaign_id, $message);

```

> Made with :heart: by Adjemin