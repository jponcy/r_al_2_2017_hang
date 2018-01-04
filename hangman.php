<?php

$possibilities = ['mystere', 'titi', 'toto'];
$myst = $possibilities[rand(0, count($possibilities) - 1)];
$equals = false;

for ($life = 5; !$equals && $life > 0; -- $life) {
    echo "Try to find '$myst' (you have $life lifes): ";
    $handle = fopen('php://stdin', 'r');
    $line = strtolower(trim(fgets($handle)));
    $equals = $line == $myst;

    printf('You try "%s", that\'s %scorrect%s', $line, ($equals ? '' : 'in'), PHP_EOL);
};

if (!$equals) {
    echo 'You lose!' . PHP_EOL;
}
