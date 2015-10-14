<?php

namespace zacharyrankin\wordhi;

class Tokenizer
{
    public function tokenize($html)
    {
        $length = strlen($html);
        while ($length) {
            $token = $tokens[] = $this->getToken($html);

            $token_length = strlen($token['value']);
            $html = substr($html, $token_length);
            $length -= $token_length;
        }

        return $tokens;
    }

    public function getToken($string)
    {
        $first_char = $string[0];

        if ($this->isSpace($first_char)) {
            $token_type = 'whitespace';
            $token_value = $this->getSpaces($string);
        } elseif ($this->isHtml($first_char)) {
            $token_type = 'html-tag';
            $token_value = $this->getHtmlTag($string);
        } else {
            $token_type = 'word';
            $token_value = $this->getWord($string);
        }

        return [
            'type'  => $token_type,
            'value' => $token_value,
        ];
    }

    public function isSpace($string)
    {
        return (bool) preg_match("/\s/", $string);
    }

    public function getSpaces($string)
    {
        preg_match("/^\s+/", $string, $matches);

        return $matches[0];
    }

    public function isHtml($string)
    {
        return (bool) preg_match("/</", $string);
    }

    public function getHtmlTag($string)
    {
        preg_match("/<.+?[\s]*\/?[\s]*>/", $string, $matches);

        return $matches[0];
    }

    public function getWord($string)
    {
        preg_match("/^\w+/", $string, $matches);

        return $matches[0];
    }
}
