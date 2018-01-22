<?php

class P {
    protected static $msg = 'Je suis au dessus !';

    public static function stat() {
        return static::$msg;
    }

    public static function sel() {
        var_dump(new static());
        var_dump(new self());
        return self::$msg;
    }
}

class E1 extends P {
    protected static $msg = 'E1';
}

class E2 extends P {
    protected static $msg = 'E2';
}

try {
    echo E1::sel() . PHP_EOL;
    echo E1::stat() . PHP_EOL;
} catch (MetNotFoundException $e) {
} catch (MathException $m) {
}
