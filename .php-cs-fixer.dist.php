<?php

$finder = PhpCsFixer\Finder::create()
    ->in(__DIR__ . '/src');

$config = new PhpCsFixer\Config();
return $config->setRules([
    '@Symfony' => true,
    '@DoctrineAnnotation' => true,
])
    ->setFinder($finder);
