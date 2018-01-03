<?php

$possibilities = ['mystere', 'titi', 'toto'];
$myst = $possibilities[rand(0, count($possibilities) - 1)];

echo "Try to find '$myst': ";
$handle = fopen('php://stdin', 'r');
$line = trim(fgets($handle));

printf('You try "%s", that\'s %scorrect%s', $line, ($line == $myst ? '' : 'in'), PHP_EOL);
