<?php

use zacharyrankin\just_test\Test;
use zacharyrankin\wordhi\Tokenizer;

require_once __DIR__ . '/../vendor/autoload.php';

Test::create('should tokenize whitespace before character', function(Test $test) {
    $tokenizer = new Tokenizer;
    $tokens = $tokenizer->tokenize("    a");
    $test->equals(
        $tokens,
        [
            ['type' => 'whitespace', 'value' => '    '],
            ['type' => 'word', 'value' => 'a']
        ]
    );
});
