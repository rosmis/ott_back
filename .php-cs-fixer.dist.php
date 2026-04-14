<?php

declare(strict_types=1);

use PhpCsFixer\Config;
use PhpCsFixer\Finder;

return (new Config())
    ->setRiskyAllowed(false)
    ->setRules([
        '@PhpCsFixer' => true,
        'blank_line_before_statement' => [
            'statements' => [
                'break',
                'case',
                'continue',
                'declare',
                'default',
                'exit',
                'do',
                'for',
                'foreach',
                'goto',
                'if',
                'include',
                'include_once',
                'return',
                'switch',
                'throw',
                'try',
                'while',
                'yield',
                'yield_from',
            ],
        ],
        'braces_position' => [
            'anonymous_classes_opening_brace' => 'next_line_unless_newline_at_signature_end',
        ],
        'global_namespace_import' => true,
        'increment_style' => [
            'style' => 'post',
        ],
        'multiline_whitespace_before_semicolons' => [
            'strategy' => 'no_multi_line',
        ],
        'new_with_parentheses' => [
            'anonymous_class' => true,
        ],
        'ordered_imports' => [
            'sort_algorithm' => 'alpha',
            'imports_order' => [
                'class',
                'const',
                'function',
            ],
        ],
        'ordered_types' => [
            'null_adjustment' => 'always_first',
        ],
        'php_unit_data_provider_method_order' => false,
        'phpdoc_align' => false,
        'phpdoc_separation' => false,
        'phpdoc_to_comment' => [
            'ignored_tags' => ['link'],
        ],
        'phpdoc_types_order' => [
            'null_adjustment' => 'always_last',
        ],
        'return_assignment' => false,
        'single_line_empty_body' => false,
        'string_implicit_backslashes' => [
            'double_quoted' => 'ignore',
            'single_quoted' => 'ignore',
        ],
        'trailing_comma_in_multiline' => [
            'elements' => ['arrays', 'match'],
        ],
        'unary_operator_spaces' => false,
        'yoda_style' => false,

        'attribute_empty_parentheses' => true,
        'comment_to_phpdoc' => true,
        'declare_strict_types' => true,
        'get_class_to_class_keyword' => true,
        'is_null' => true,
        'list_syntax' => true,
        'logical_operators' => true,
        'modernize_strpos' => true,
        'not_operator_with_successor_space' => true,
        'ordered_interfaces' => true,
        'ordered_traits' => true,
        'php_unit_attributes' => true,
        'php_unit_construct' => true,
        'php_unit_set_up_tear_down_visibility' => true,
        'php_unit_test_annotation' => ['style' => 'prefix'],
        'php_unit_test_case_static_method_calls' => ['call_type' => 'self'],
        'phpdoc_array_type' => true,
        'simplified_if_return' => true,
        'static_lambda' => true,
        'stringable_for_to_string' => true,
        'ternary_to_null_coalescing' => true,
    ])
    // 💡 by default, Fixer looks for `*.php` files excluding `./vendor/` - here, you can groom this config
    ->setFinder(
        (new Finder())
            // 💡 root folder to check
            ->in(__DIR__)
            // 💡 additional files, eg bin entry file
            // ->append([__DIR__.'/bin-entry-file'])
            // 💡 folders to exclude, if any
            // ->exclude([/* ... */])
            // 💡 path patterns to exclude, if any
            // ->notPath([/* ... */])
            // 💡 extra configs
            // ->ignoreDotFiles(false) // true by default in v3, false in v4 or future mode
            // ->ignoreVCS(true) // true by default
    )
;
