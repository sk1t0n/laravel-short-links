<?php

$finder = PhpCsFixer\Finder::create()
    ->exclude(['node_modules', 'storage', 'vendor'])
    ->in(__DIR__);

$config = new PhpCsFixer\Config();

return $config
    ->setRiskyAllowed(true)
    ->setRules([
        '@PhpCsFixer' => true,
        'echo_tag_syntax' => ['format' => 'long'],
        'multiline_whitespace_before_semicolons' => [
            'strategy' => 'no_multi_line',
        ],
        'yoda_style' => [
            'equal' => false,
            'identical' => false,
            'less_and_greater' => false,
        ],
        'blank_line_before_statement' => [
            'statements' => ['declare', 'return'],
        ],
        'no_alternative_syntax' => ['fix_non_monolithic_code' => false],
        'phpdoc_to_comment' => ['ignored_tags' => ['var']],
        'not_operator_with_successor_space' => true,
    ])->setFinder($finder);
