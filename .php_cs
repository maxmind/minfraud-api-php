<?php

return PhpCsFixer\Config::create()
    ->setRiskyAllowed(true)
    ->setRules([
        '@Symfony' => true,
        '@Symfony:risky' => true,
        'array_syntax' => ['syntax' => 'short'],
        'combine_consecutive_unsets' => true,
        'concat_space' => [ 'spacing' => 'one'],
        'heredoc_to_nowdoc' => true,
        'list_syntax' => ['syntax' => 'long'],
        'no_extra_consecutive_blank_lines' => ['break', 'continue', 'extra', 'return', 'throw', 'use', 'parenthesis_brace_block', 'square_brace_block', 'curly_brace_block'],
        'no_short_echo_tag' => true,
        'no_unreachable_default_argument_value' => true,
        'no_useless_else' => true,
        'no_useless_return' => true,
        'ordered_imports' => true,
        'pre_increment' => false,
        // Although we generally want to use assertSame, there are times
        // when we do not, such as comparing two object for non-referential
        // equality.
        'php_unit_strict' => false,
        'php_unit_test_class_requires_covers' => true,
        'phpdoc_add_missing_param_annotation' => true,
        'phpdoc_no_alias_tag' => false,
        'phpdoc_order' => true,
        'semicolon_after_instruction' => true,
        'strict_comparison' => true,
        'strict_param' => true,
        'yoda_style' => false,
    ])
    ->setFinder(
        PhpCsFixer\Finder::create()
            ->in(__DIR__)
    )
;
