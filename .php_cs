<?php

$finder = Symfony\Component\Finder\Finder::create()
    ->notPath('bootstrap/*')
    ->notPath('storage/*')
    ->notPath('resources/view/mail/*')
    ->notPath('vendor')
    ->in(__DIR__)
    ->name('*.php')
    ->notName('_ide_helper.php')
    ->notName('_ide_helper_models.php')
    ->notName('*.blade.php')
    ->ignoreDotFiles(true)
    ->ignoreVCS(true);

return PhpCsFixer\Config::create()
    ->setRules([
        '@PSR2' => true,
        'array_syntax' => ['syntax' => 'short'],
    ])
    ->setFinder($finder);