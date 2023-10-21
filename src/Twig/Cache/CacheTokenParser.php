<?php

namespace App\Twig\Cache;

use Twig\Node\Node;
use Twig\Token;
use Twig\TokenParser\AbstractTokenParser;

class CacheTokenParser extends AbstractTokenParser
{

    public function parse(Token $token) {
        $lineno = $token->getLine();
        $stream = $this->parser->getStream();
        $key = $this->parser->getExpressionParser()->parseExpression();

        $key->setAttribute('always_defined', true);
        $stream->expect(Token::BLOCK_END_TYPE);
        $body = $this->parser->subparse([$this, 'decideCacheEnd'], true);
        $stream->expect(Token::BLOCK_END_TYPE);

        return new CacheNode($key, $body, $lineno, $this->getTag());
    }

    public function getTag(): string
    {
        return 'cache';
    }

    public function decideCacheEnd(Token $token): bool
    {
        return $token->test('endcache');
    }

}