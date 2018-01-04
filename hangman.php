<?php

/*
 * Init.
 */
$possibilities = ['mystere', 'titi', 'toto'];
$myst = $possibilities[rand(0, count($possibilities) - 1)];
$found = '';
$equals = false;

/*
 * Initialize found.
 */
for ($i = 0; $i < strlen($myst); ++ $i) {
    $found .= '_';
}

/*
 * Start game.
 */
for ($life = 5; !$equals && $life > 0; -- $life) {
    echo "State $found" . PHP_EOL;
    echo "Try to find '$myst' (you have $life lifes): ";
    $handle = fopen('php://stdin', 'r');
    $line = strtolower(trim(fgets($handle)));

    switch (strlen($line)) {
        case 0:
            echo 'You miss to put a letter or a word!' . PHP_EOL;
            ++ $life;
            break;
        case 1: // Letter.
            $ok = false;

            for ($i = 0; $i < strlen($myst); ++ $i) {
                if ($myst[$i] == $line) {
                    $found[$i] = $line;
                    $ok = true;
                }
            }

            if ($ok) {
                ++ $life;

                if ($myst == $found) {
                    echo 'Congrats, you Win!';
                    $equals = true;
                    // exit(0); // Will be possible (no need else) in this case.
                } else {
                    echo 'Letter found!';
                }
            } else {
                echo 'Letter not found!';
            }

            echo PHP_EOL;

            break;
        default: // Word.
            $equals = $line == $myst;
            printf('You try "%s", that\'s %scorrect%s', $line, ($equals ? '' : 'in'), PHP_EOL);
            break;

    }
};

if (!$equals) {
    echo 'You lose!' . PHP_EOL;
}
