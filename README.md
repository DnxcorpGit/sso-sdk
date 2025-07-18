# SSO SDK

## Introduction

This SDK is a collection of libraries that allow you to interact with the SSO API.
The SDK is compatible with PHP 7.4 and above.

## Installation

To install the SDK, you can use composer. Run the following command:

```bash
composer require dnxcorp/sso-sdk
```

## Usage

### Basic Usage

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

### Using ServiceEnum

```php
use Dnx\Sso\ServiceEnum;

$client = new Client($token);

// Using constants directly
$response = $client->register(
    'email@test.com',
    '127.0.0.1',
    'FR',
    'en',
    'c0023',
    ServiceEnum::PROFILE
);

// Or with string values
$response = $client->register(
    'email@test.com',
    '127.0.0.1',
    'FR',
    'en',
    'c0023',
    'profile' // or 'webcamsList'
);

// Validate service values
if (ServiceEnum::isValid($userInput)) {
    $response = $client->register(/* ... */, $userInput);
}
```