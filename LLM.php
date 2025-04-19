<?php

require __DIR__ . '/vendor/autoload.php';
require __DIR__ . '/api_keys.php';

use OpenAI\Client;

$client = OpenAI::client($api_keys['openai']);

function prompt(string $prompt, ?string $system = '', ?int $tokens = 20000, ?float $temp = 0.3): ?string {
    global $client;

    $result = $client->chat()->create([
        'model' => 'gpt-4.1-mini',
        'messages' => [
            [ 'role' => 'system', 'content' => $system, ],
            [ 'role' =>  'user',  'content' => $prompt, ],
        ],
        'temperature' => $temp,
        'max_tokens'  => $tokens,
    ]);

    if (isset($result['choices'][0]['message']['content'])) return $result['choices'][0]['message']['content'];
    else return null;
}

function categorise(string $msg, array $categories) {
    $cat_lines = [];
    foreach ($categories as $key => $description) $cat_lines[] = "$key - $description";
    $cat_text = implode("\n", $cat_lines);

    $prompt = "CATEGORIES:\n$cat_text\n\nMESSAGE:\n$msg";
    $system = "OUTPUT ONE KEYWORD ONLY. Your job is to select the right category for the message. Nothing more.";
    $response = prompt($prompt, $system, 20);

    $keyword = strtoupper(explode(' ', trim($response))[0]) ?? '';
    $options = array_map('strtoupper', array_keys($categories));

    if (in_array($keyword, $options)) return $keyword;

    return "UNKNOWN";
}
