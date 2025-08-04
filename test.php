<?php

require 'vendor/autoload.php';

use TokenSDK\TokenClient;

$client = new TokenClient('https://your-api-url.com/api', 'YOUR_API_KEY_OR_TOKEN'); // Replace with real base URL and auth if needed

// Example: Call next token
$response = $client->callNextToken([
    'servicepoint_id' => 3
]);

print_r($response);
