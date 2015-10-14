<?php

use zacharyrankin\just_test\Test;
use zacharyrankin\wordhi\Tokenizer;

require_once __DIR__ . '/../vendor/autoload.php';

Test::create('should tokenize whitespace before word', function(Test $test) {
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

Test::create('should tokenize words', function(Test $test) {
    $tokenizer = new Tokenizer;
    $tokens = $tokenizer->tokenize("the book");
    $test->equals(
        $tokens,
        [
            ['type' => 'word', 'value' => 'the'],
            ['type' => 'whitespace', 'value' => ' '],
            ['type' => 'word', 'value' => 'book'],
        ]
    );
});

Test::create('should tokenize html tags', function(Test $test) {
    $tokenizer = new Tokenizer;
    $tokens = $tokenizer->tokenize("<p>c</p>");
    $test->equals(
        $tokens,
        [
            ['type' => 'html-tag', 'value' => '<p>'],
            ['type' => 'word', 'value' => 'c'],
            ['type' => 'html-tag', 'value' => '</p>'],
        ]
    );
});

Test::create('should tokenize html tags with attributes', function(Test $test) {
    $tokenizer = new Tokenizer;
    $tokens = $tokenizer->tokenize("<p style=\"color: blue;\">d</p>");
    $test->equals(
        $tokens,
        [
            ['type' => 'html-tag', 'value' => "<p style=\"color: blue;\">"],
            ['type' => 'word', 'value' => 'd'],
            ['type' => 'html-tag', 'value' => '</p>'],
        ]
    );
});
