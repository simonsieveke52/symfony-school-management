<?php
namespace App\Twig\Cache;

interface CacheableInterface
{
    public function getId(): int;
    public function getUpdatedAt(): \DateTimeInterface;
}