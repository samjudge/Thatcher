<?php
namespace ZombieTfk\Thatcher\Aliases;

use ZombieTfk\Thatcher\Pattern;

if(!function_exists('Pattern')) {
    function Pattern(...$matches) {
        return new Pattern(...$matches);
    }
}