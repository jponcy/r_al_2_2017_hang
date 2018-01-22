<?php

class Dictionary {
    const FILE_PATH = 'dico.txt';

    public function randWord(): string {
        $possibilities = file(self::FILE_PATH);

        return trim($possibilities[rand(0, count($possibilities) - 1)]);
    }

    /** Adds the given words to dictionnary. */
    public function add(string $words) {
        $dico = fopen(self::FILE_PATH, 'a+');

        foreach (explode(' ', $words) as $word) {
            if (trim($word) != '') {
                fwrite($dico, trim($word) . PHP_EOL);
                echo "$word add to dictionnary!" . PHP_EOL;
            }
        }

        fclose($dico);
    }
}


class Hangman {
    private $dico;
    private $myst;
    private $found;
    private $old;

    public function __construct() {
        $this->dico = new Dictionary();
    }

    private function init() {
        $this->myst = $this->dico->randWord();
        $this->generateHidden();
        $this->old = [];
    }

    /**
     * Initialize found.
     */
    private function generateHidden(): string {
        $this->found = '';

        for ($i = 0; $i < strlen($this->myst); ++ $i) {
            $this->found .= '_';
        }

        return $this->found;
    }

    private function solution() {
        echo 'You should find \'' . $this->myst . '\', good luck.' . PHP_EOL;
    }

    private function treatCmd(string $line): bool {
        $matches = [];
        $result = false;

        if (preg_match('/^\/(\w+)( \w+)*$/', $line, $matches)) {
            $result = true;

            switch (strtolower($matches[1])) { // CMD.
                case 'solution':
                    $this->solution();
                    break;
                case 'add':
                    if (count($matches) > 2) {
                        $this->dico->add($matches[2]);
                    }
                    break;
                default:
                    $result = false;
                    break;
            }
        }

        return $result;
    }

    private function treatTry(string $line) {
        switch (strlen($line)) {
            case 0:
                echo 'You miss to put a letter or a word!' . PHP_EOL;
                ++ $this->life;
                break;
            case 1: // Letter.
                $ok = false;

                // Tests of already tested.
                if (in_array($line, $this->old)) {
                    echo 'Already tried!' . PHP_EOL;
                    ++ $this->life;
                    break;
                }

                // Replace occurences.
                for ($i = 0; $i < strlen($this->myst); ++ $i) {
                    if ($this->myst[$i] == $line) {
                        $this->found[$i] = $line;
                        $ok = true;
                    }
                }

                // Print result message.
                if ($ok) {
                    ++ $this->life;

                    if ($this->myst == $this->found) {
                        echo 'Congrats, you Win!';
                        $this->equals = true;
                        // exit(0); // Will be possible (no need else) in this case.
                    } else {
                        echo 'Letter found!';
                    }
                } else {
                    echo 'Letter not found!';
                }

                $this->old[] = $line;
                echo PHP_EOL;

                break;
            default: // Word.
                $this->equals = $line == $this->myst;
                $this->old[] = $line;
                printf('You try "%s", that\'s %scorrect%s', $line, ($this->equals ? '' : 'in'), PHP_EOL);
                break;

        }
    }

    /**
     * Start a game.
     */
    public function start() {
        $this->init();
        $this->equals = false;

        for ($this->life = 5; !$this->equals && $this->life > 0; -- $this->life) {
            echo 'State ' . $this->found . PHP_EOL;
            if (count($this->old) > 0) echo 'You already try: ' . implode(', ', $this->old) . PHP_EOL;
            echo "Try to find the word (you have $this->life lifes): ";
            $handle = fopen('php://stdin', 'r');
            $line = strtolower(trim(fgets($handle)));

            if ($this->treatCmd($line)) {
                ++ $this->life;
                continue;
            }

            $this->treatTry($line);
        };

        if (!$this->equals) {
            echo 'You lose!' . PHP_EOL;
        }
    }
}

(new Hangman)->start();
