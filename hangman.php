<?php

$possibilities = ['mystere', 't' => 'titi', 'toto'];

foreach ($possibilities as $k => $v) {
    echo "Key: $k, Val: $v\n";
}


$myst = 'mystere';

echo 'Give it a try: ';
$handle = fopen('php://stdin', 'r');
$line = trim(fgets($handle));

printf('You try "%s", that\'s %scorrect%s', $line, ($line == $myst ? '' : 'in'), PHP_EOL);
