<?php

$possibilities = ['mystere', 'titi', 'toto'];
$myst = $possibilities[rand(0, count($possibilities) - 1)];

do {
    echo "Try to find '$myst': ";
    $handle = fopen('php://stdin', 'r');
    $line = strtolower(trim(fgets($handle)));
    $equals = $line == $myst;

    printf('You try "%s", that\'s %scorrect%s', $line, ($equals ? '' : 'in'), PHP_EOL);
} while (!$equals);
