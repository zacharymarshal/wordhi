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
        $returnMatches = function($matches, $string) {
            return current($matches);
        };
        $token_patterns = [
            "/^\s+/"                   => ['type' => 'whitespace', 'value_fn' => $returnMatches],
            "/^<[^<]+?[\s]*\/?[\s]*>/" => [
                'type' => 'html-tag',
                'value_fn' => function($matches, $string) {
                    $matched = '';
                    $open_quote = null;
                    for ($i = 0; $i < strlen($string); $i++) {
                        if ('>' === $string[$i] && null === $open_quote) {
                            return $matched . $string[$i];
                        } elseif (in_array($string[$i], ['"', "'"]) && null === $open_quote) {
                            $open_quote = $string[$i];
                        } elseif ($open_quote === $string[$i]) {
                            $open_quote = null;
                        }

                        $matched .= $string[$i];
                    }
                    return $matched;
                }
            ],
            "/^&[^\s]*;/"              => ['type' => 'html-entity', 'value_fn' => $returnMatches],
            "/^[><\+\-!@#$%^&*();]/"   => ['type' => 'punctuation', 'value_fn' => $returnMatches],
            "/^\w+/"                   => ['type' => 'word', 'value_fn' => $returnMatches],
            "/^./"                     => [
                'type'     => 'unknown',
                'value_fn' => function($matches, $string) {
                    return $string[0];
                }
            ],
        ];

        foreach ($token_patterns as $pattern => $token) {
            preg_match($pattern, $string, $matches);

            if (!empty($matches)) {
                $value = $token['value_fn']($matches, $string);

                return [
                    'type'  => $token['type'],
                    'value' => $value,
                ];
            }
        }
    }
}
