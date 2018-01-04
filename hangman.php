<?php

function choiceWord() {
    $possibilities = file('dico.txt');
    /* Or 
     * $possibilities = explode("\n", file_get_contents('dico.txt'));
     * Not need trim oin this case.
     */
    $myst = trim($possibilities[rand(0, count($possibilities) - 1)]);

    return $myst;
}

/**
 * Initialize found.
 */
function getHidden(string $word): string {
    $result = '';
    
    for ($i = 0; $i < strlen($word); ++ $i) {
        $result .= '_';
    }

    return $result;
}

function treatCmd(string $line, string $myst): bool {
    $matches = [];
    $result = false;

    if (preg_match('/^\/(\w+)( \w+)*$/', $line, $matches)) {
        $result = true;

        switch (strtolower($matches[1])) { // CMD.
            case 'solution':
                echo "You should find '$myst', good luck." . PHP_EOL;
                break;
            case 'add':
                if (count($matches) > 2) {
                    $dico = fopen('dico.txt', 'a+');

                    foreach (explode(' ', $matches[2]) as $word) {
                        if (trim($word) != '') {
                            fwrite($dico, trim($word) . PHP_EOL);
                            echo "$word add to dictionnary!" . PHP_EOL;
                        }
                    }

                    fclose($dico);
                }
                break;
            default:
                $result = false;
                break;
        }
    }

    return $result;
}

/*
 * Init.
 */
$myst = choiceWord();
$found = getHidden($myst);
$equals = false;
$old = []; // Records letters provide by user.

/*
 * Start game.
 */
for ($life = 5; !$equals && $life > 0; -- $life) {
    echo "State $found" . PHP_EOL;
    if (count($old) > 0) echo 'You already try: ' . implode(', ', $old) . PHP_EOL;
    echo "Try to find the word (you have $life lifes): ";
    $handle = fopen('php://stdin', 'r');
    $line = strtolower(trim(fgets($handle)));

    if (treatCmd($line, $myst)) {
        ++ $life;
        continue;
    }

    switch (strlen($line)) {
        case 0:
            echo 'You miss to put a letter or a word!' . PHP_EOL;
            ++ $life;
            break;
        case 1: // Letter.
            $ok = false;

            // Tests of already tested.
            if (in_array($line, $old)) {
                echo 'Already tried!' . PHP_EOL;
                ++ $life;
                break;
            }

            // Replace occurences.
            for ($i = 0; $i < strlen($myst); ++ $i) {
                if ($myst[$i] == $line) {
                    $found[$i] = $line;
                    $ok = true;
                }
            }

            // Print result message.
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

            $old[] = $line;
            echo PHP_EOL;

            break;
        default: // Word.
            $equals = $line == $myst;
            $old[] = $line;
            printf('You try "%s", that\'s %scorrect%s', $line, ($equals ? '' : 'in'), PHP_EOL);
            break;

    }
};

if (!$equals) {
    echo 'You lose!' . PHP_EOL;
}
