<?php

$finder = PhpCsFixer\Finder::create()
    ->in(__DIR__);

$config = PhpCsFixer\Config::create()
    ->setRiskyAllowed(true)
    ->setRules([
        '@PSR2' => true,
        '@Symfony' => true,
    ])
    ->setFinder($finder);

return $config;
