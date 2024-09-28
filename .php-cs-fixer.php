<?php

declare(strict_types=1);

$finder = (new PhpCsFixer\Finder())
    ->in(__DIR__)
    ->exclude('var')
;

return (new PhpCsFixer\Config())
    ->setRules([
        '@PER-CS' => true,
        'declare_strict_types' => true,
        'no_trailing_comma_in_singleline' => true,
        'no_unused_imports' => true,
        'no_useless_concat_operator' => true,
        'no_useless_nullsafe_operator' => true,
        'no_useless_sprintf' => true,
        'non_printable_character' => true,
        'object_operator_without_whitespace' => true,
        'operator_linebreak' => ['position' => 'beginning'],
        'phpdoc_order' => ['order' => ['param', 'throws', 'return']],
        'phpdoc_scalar' => true,
        'phpdoc_single_line_var_spacing' => true,
        'phpdoc_to_comment' => true,
        'phpdoc_trim' => true,
        'phpdoc_trim_consecutive_blank_line_separation' => true,
        'phpdoc_types' => true,
        'phpdoc_var_without_name' => true,
        'self_accessor' => true,
        'semicolon_after_instruction' => true,
        'set_type_to_cast' => true,
        'simple_to_complex_string_variable' => true,
        'single_line_comment_spacing' => true,
        'single_line_comment_style' => true,
        'single_quote' => ['strings_containing_single_quote_chars' => false],
        'space_after_semicolon' => true,
        'standardize_not_equals' => true,
        'strict_comparison' => true,
        'strict_param' => true,
        'switch_continue_to_break' => true,
        'type_declaration_spaces' => true,
        'types_spaces' => true,
        'whitespace_after_comma_in_array' => true,
    ])
    ->setRiskyAllowed(true)
    ->setFinder($finder)
    ->setCacheFile(__DIR__ . '/var/php-cs-fixer')
;
