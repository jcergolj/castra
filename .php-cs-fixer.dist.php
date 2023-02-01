<?php

use PhpCsFixer\Config;
use PhpCsFixer\Finder;
use AdamWojs\PhpCsFixerPhpdocForceFQCN\Fixer\Phpdoc\ForceFQCNFixer;

$finder = Finder::create()
    ->in([
        __DIR__.'/app',
        __DIR__.'/config',
        __DIR__.'/database',
        __DIR__.'/resources',
        __DIR__.'/routes',
        __DIR__.'/tests',
    ])
    ->name('*.php')
    ->notName('*.blade.php')
    ->ignoreDotFiles(true)
    ->ignoreVCS(true);

return (new Config())
    ->setFinder($finder)
    ->registerCustomFixers([
        new ForceFQCNFixer(),
    ])
    ->setRules([
        'AdamWojs/phpdoc_force_fqcn_fixer' => true,
    ])
    ->setRiskyAllowed(true)
    ->setUsingCache(true);
