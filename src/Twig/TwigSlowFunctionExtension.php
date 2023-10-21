<?php
// src/Twig/AppExtension.php
namespace App\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class TwigSlowFunctionExtension extends AbstractExtension
{
    public function getFunctions()
    {
        return [
            new TwigFunction('slow', [$this, 'decideToSlow']),
        ];
    }

    public function decideToSlow()
    {
        return sleep(2);
    }
}
