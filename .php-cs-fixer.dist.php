<?php

if (!file_exists(__DIR__ . '/src')) {
    exit(0);
}

$finder = PhpCsFixer\Finder::create()
    ->in(__DIR__ . '/src')
    ->in(__DIR__ . '/tests')
    ->append([__FILE__])
;

$config = new PhpCsFixer\Config();

return $config->setRules([
    '@PHP81Migration' => true,
    '@PHP82Migration' => true,
    '@PHPUnit100Migration:risky' => true,
    'protected_to_private' => false,
    'no_unused_imports' => true,
    'strict_param' => true,
    'array_syntax' => ['syntax' => 'short'],
    'concat_space' => ['spacing' => 'one'],
])
    ->setRiskyAllowed(true)
    ->setCacheFile(__DIR__ . '/var/.php-cs-fixer.cache')
    ->setFinder($finder)
;
