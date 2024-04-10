<?php

if (!file_exists(__DIR__ . '/src')) {
    exit(0);
}

$finder = PhpCsFixer\Finder::create()
    ->in(__DIR__ . '/src')
    ->in(__DIR__ . '/tests')
    ->append([__FILE__])
    ->exclude(__DIR__ . '/src/bin/phpunit')
;

$config = new PhpCsFixer\Config();

return $config->setRules([
    '@PHP71Migration' => true,
    '@PHPUnit75Migration:risky' => true,
    '@Symfony' => true,
    '@Symfony:risky' => true,
    'protected_to_private' => false,
    'no_unused_imports' => true,
    'strict_param' => true,
    'array_syntax' => ['syntax' => 'short'],
    'concat_space' => ['spacing' => 'one'],
])
    ->setRiskyAllowed(true)
    ->setCacheFile(__DIR__ . '/var/cache/.php-cs-fixer.cache')
    ->setFinder($finder)
;
