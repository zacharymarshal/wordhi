<?php

$html = "<p style=\"text-decoration: underline;\">&nbsp; &nbsp; Hello some text <em>and some's more text </em>, and something else...</p>";

$length = strlen($html);
while ($length) {

    $getToken = function($string) {
        $token_value = $string[0];

        return [
            'type' => 'character',
            'value' => $token_value,
        ];
    };

    $token = $tokens[] = $getToken($html);

    $token_length = strlen($token['value']);
    $html = substr($html, $token_length);
    $length -= $token_length;
}


var_dump($tokens); exit;
