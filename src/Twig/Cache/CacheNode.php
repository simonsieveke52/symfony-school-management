<?php

namespace App\Twig\Cache;

use App\Twig\TwigCacheExtension;
use Twig\Compiler;
use Twig\Node\Node;

class CacheNode extends Node
{

    private static int $cacheCount = 1;

    public function __construct(Node $key, Node $body, int $lineno = 0, string $tag = null)
    {
        parent::__construct(['key' => $key, 'body' => $body], [], $lineno, $tag);
    }

    public function compile(Compiler $compiler)
    {
        $i = self::$cacheCount++;
        $extension = TwigCacheExtension::class;
        $compiler
            ->addDebugInfo($this)
            ->write("\$twigCacheExtension = \$this->env->getExtension('{$extension}');\n")
            ->write("\$twigCacheBody{$i} = \$twigCacheExtension->getCachedValue(")
            ->subcompile($this->getNode('key'))
            ->raw(");\n")
            ->write("if(\$twigCacheBody{$i} !== null) {\n")
            ->indent()
            ->write("echo \$twigCacheBody{$i};\n")
            ->outdent()
            ->write("} else { \n")
            ->indent()
            ->write("ob_start();\n")
            ->subcompile($this->getNode('body'))
            ->write("\$twigCacheBody{$i} = ob_get_clean();\n")
            ->write("echo \$twigCacheBody{$i};\n")
            ->write("\$twigCacheExtension->setCachedValue(")
            ->subcompile($this->getNode('key'))
            ->raw(", \$twigCacheBody{$i});\n")
            ->outdent()
            ->write("}\n");
        ;

    }

}