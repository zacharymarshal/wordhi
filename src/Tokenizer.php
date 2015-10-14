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
        } elseif ($this->isHtmlTag($first_char)) {
            $token_type = 'html-tag';
            $token_value = $this->getHtmlTag($string);
        } elseif ($this->isHtmlEntity($string)) {
            $token_type = 'html-entity';
            $token_value = $this->getHtmlEntity($string);
        } elseif ($this->isPunctuation($first_char)) {
            $token_type = 'punctuation';
            $token_value = $first_char;
        } elseif ($this->isWord($first_char)) {
            $token_type = 'word';
            $token_value = $this->getWord($string);
        } else {
            $token_type = 'unknown';
            $token_value = $first_char;
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

    public function isHtmlTag($string)
    {
        return (bool) preg_match("/</", $string);
    }

    public function getHtmlTag($string)
    {
        preg_match("/<.+?[\s]*\/?[\s]*>/", $string, $matches);

        return $matches[0];
    }

    public function isWord($char)
    {
        return (bool) preg_match("/\w/", $char);
    }

    public function getWord($string)
    {
        preg_match("/^\w+/", $string, $matches);

        if (empty($matches)) {
            return '';
        }

        return $matches[0];
    }

    public function isPunctuation($char)
    {
        $punc_marks = [',','!','@','#','$','%','^','&','*','(',')', ';'];

        return in_array($char, $punc_marks);
    }

    public function isHtmlEntity($string)
    {
        return (bool) preg_match("/^&[^\s]*;/", $string);
    }

    public function getHtmlEntity($string)
    {
        preg_match("/^&[^\s]*;/", $string, $matches);

        return $matches[0];
    }
}
