<?php


namespace Gbox;

class JSON {

    static function stringify($model) {
        return json_encode($model, JSON_PRETTY_PRINT);
    }

    static function parse($string, $assoc=true) {
        return json_decode($string, $assoc);
    }

}
