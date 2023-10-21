<?php

namespace App\Twig;

use App\Twig\Cache\CacheableInterface;
use App\Twig\Cache\CacheTokenParser;
use Symfony\Component\Cache\Adapter\AdapterInterface;
use Symfony\Component\Cache\CacheItem;
use Twig\Extension\AbstractExtension;
use Twig\TokenParser\TokenParserInterface;

class TwigCacheExtension extends AbstractExtension
{

    private AdapterInterface $cache;

    public function __construct(AdapterInterface $cache)
    {
        $this->cache = $cache;
    }

    /**
     * @return array<TokenParserInterface>
     */
    public function getTokenParsers(): array
    {
        return [
            new CacheTokenParser()
        ];
    }

    public function getCachedValue(string $key): ?string
    {
        return $this->getItem($key)->get();
    }

    public function setCachedValue(string $key, string $value): void
    {
        $item = $this->getItem($key);
        $item->set($value);
        $this->cache->save($item);
    }

    private function getItem(string $key): CacheItem
    {
        //$className = get_class($entity);
       // $className = substr($className, strrpos($className, '\\') + 1);
        //$key = $entity->getId() . $className . $entity->getUpdatedAt()->getTimestamp();
        return $this->cache->getItem($key);
    }

}