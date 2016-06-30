<?php

$header = <<<EOF
(c) Rob Bast <rob.bast@gmail.com>

For the full copyright and license information, please view
the LICENSE file that was distributed with this source code.
EOF;

$finder = (new Symfony\Component\Finder\Finder)
    ->files()
    ->name('*.php')
    ->in(__DIR__)
    ->exclude('vendor')
;

/* Based on dev-master|^2.0 of php-cs-fixer */
return (new PhpCsFixer\Config('ISO3166', 'ISO3166 style guide'))
    ->setUsingCache(true)
    ->setUsingLinter(true)
    ->setRiskyAllowed(true)
    ->setRules([
        // default
        '@PSR2' => true,
        '@Symfony' => true,
        // additionally
        'concat_with_spaces' => true,
        'concat_without_spaces' => false,
        'header_comment' => ['header' => $header],
        'no_unused_imports' => false,
        'phpdoc_align' => false,
        'phpdoc_order' => true,
        'phpdoc_summary' => false,
        'simplified_null_return' => false,
    ])
    ->finder($finder)
;
