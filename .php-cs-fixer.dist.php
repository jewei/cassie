<?php

return (new PhpCsFixer\Config())
    ->setRiskyAllowed(true)
    ->setRules([
        '@PhpCsFixer' => true,
        'multiline_whitespace_before_semicolons' => [
            'strategy' => 'no_multi_line',
        ],
        'concat_space' => [
            'spacing' => 'one',
        ],
        'no_extra_blank_lines' => [
            'tokens' => ['case'],
        ],
        'php_unit_test_class_requires_covers' => false,
        'not_operator_with_successor_space' => true,
        'blank_line_before_statement' => false,
        'php_unit_internal_class' => false,
        'php_unit_method_casing' => false,
        'use_arrow_functions' => true,
        'phpdoc_to_comment' => false,
        'strict_param' => true,
        'yoda_style' => false,
    ]);
