# SSO SDK

## Introduction

This SDK is a collection of libraries that allow you to interact with the SSO API.
The SDK is available only for PHP8.2 and above.

## Installation

To install the SDK, you can use composer. Run the following command:

```bash
composer require sso/sdk
```

## Usage

```php
// $myUser is your user object
$token = 'your_token';

$client = new Client($token);
$response = $client->register('email@test.com', '127.0.0.1', 'FR', 'en', 'c0023');

if ($response->isSuccess()) {
    $myUser->setLoginToken($response->getLoginToken());
    // Persist changes in database, it won't be displayed anymore
}

// Redirect to the website
header('Location: ' . $response->getRedirectUrl());
```