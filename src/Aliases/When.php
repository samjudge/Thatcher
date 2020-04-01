<?php

namespace ZombieTfk\Thatcher\Symbols\Aliases;

use ZombieTfk\Thatcher\MatcherBlock;
use ZombieTfk\Thatcher\Matcher;
use ZombieTfk\Thatcher\Pattern;
use ZombieTfk\Thatcher\Symbols\AnonymousSymbol;

if(!function_exists('When')) {
    function When(Pattern $pattern, Callable $do) {
        return new Matcher($pattern, $do);
    }
}