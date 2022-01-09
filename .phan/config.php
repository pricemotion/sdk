<?php declare(strict_types=1);

$vendor = ['vendor/psr/cache/src', 'vendor/psr/log/src', 'vendor/symfony/cache-contracts'];

return [
    'target_php_version' => '7.4',
    'directory_list' => ['src', ...$vendor],
    'exclude_analysis_directory_list' => $vendor,
    'plugins' => [
        'AlwaysReturnPlugin',
        'DollarDollarPlugin',
        'DuplicateArrayKeyPlugin',
        'DuplicateExpressionPlugin',
        'EmptyStatementListPlugin',
        'LoopVariableReusePlugin',
        'PregRegexCheckerPlugin',
        'PrintfCheckerPlugin',
        'SleepCheckerPlugin',
        'UnreachableCodePlugin',
        'UseReturnValuePlugin',
    ],
];
