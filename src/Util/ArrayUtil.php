<?php

namespace ZombieTfk\Thatcher\Util;

function first($array) {
    return $array[0];
}

function last($array) {
    return $array[count($array) - 1];
}

function head($array) {
    return array_slice($array, 0, count($array) - 1);
}

function tail($array) {
    return array_slice($array, 1);
}