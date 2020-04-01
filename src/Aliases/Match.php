<?php

namespace ZombieTfk\Thatcher\Symbols\Aliases;

use ZombieTfk\Thatcher\MatcherBlock;
use ZombieTfk\Thatcher\Pattern;
use ZombieTfk\Thatcher\Symbols\AnonymousSymbol;

if(!function_exists('Match')) {
    function Match(...$matchers) {
        return new MatcherBlock(...$matchers);
    }
}