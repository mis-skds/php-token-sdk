# PHP Token SDK

This SDK allows you to interact with the Token Management System API.

## Installation

```bash
composer require mis-sdks/php-token-sdk
```

## Usage

```php
use TokenSDK\TokenClient;

$client = new TokenClient('https://your-domain.com', 'your-api-key');

// Issue a token
$response = $client->issueToken([
    'location_id' => 1,
    'category_id' => 2,
]);

print_r($response);
```

## Available Methods

- issueToken(array $data)
- callNextToken(array $data)
- callTokenById($tokenId, array $data)
- skipToken($tokenId)
- completeToken($tokenId)
- getDisplayData($locationId = null)
