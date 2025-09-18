# SSO SDK

## Introduction

This SDK is a collection of libraries that allow you to interact with the SSO API.
The SDK is compatible with PHP 7.4 and above.

## Installation

To install the SDK, you can use composer. Run the following command:

```bash
composer require dnxcorp/sso-sdk:v7.4.0
```

## Development

### Running Tests

This library includes comprehensive tests to ensure reliability and compatibility with PHP 7.4+.

#### Prerequisites

- PHP 7.4 or higher
- Composer

#### Install Dependencies

```bash
composer install
```

#### Run Tests

```bash
# Run all tests
composer test

# Or use PHPUnit directly
vendor/bin/phpunit

# Or use the simple test runner
php run-tests.php
```

#### Test Coverage

The test suite includes:

- **Response Class Tests**: Complete coverage of response handling, JSON parsing, and error scenarios
- **Client Class Tests**: Data preparation, URL encoding, parameter validation, and HTTP integration tests
- **ServiceEnum Tests**: Validation logic and constant verification
- **Integration Tests**: Mocked HTTP responses to test complete workflows

#### Test Structure

```
tests/
├── ResponseTest.php          # Response class unit tests
├── ClientTest.php           # Client class unit tests
├── ClientIntegrationTest.php # Integration tests with mocked HTTP
└── ServiceEnumTest.php      # ServiceEnum validation tests
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