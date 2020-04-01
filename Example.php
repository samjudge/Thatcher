<?php

require_once "./vendor/autoload.php";

use function ZombieTfk\Thatcher\Aliases\Pattern;
use const ZombieTfk\Thatcher\Symbols\Aliases\_;
use function ZombieTfk\Thatcher\Symbols\Aliases\Match;
use function ZombieTfk\Thatcher\Symbols\Aliases\When;

Match(
    When(
        Pattern(),
        function() {
            echo "No data passed";
        }
    ),
    When(
        Pattern(_),
        function($x) {
            echo "Data contains exactly 1 element, $x";
        }
    ),
    When(
        Pattern(_,_),
        function($x, $y) {
            echo "Data contains exactly 2 elements, $x and $y";
        }
    ),
    When(
        Pattern('hello!', _,_),
        function($x, $y, $z) {
            echo "Data contains exactly 3 elements starting with $x, then $y and $z";
        }
    ),
    When(
        Pattern([_]),
        function($xs) {
            echo "Some Other Set -> " . json_encode($xs);
        }
    )
)("hello!", "myname jeff", "dasdsada");
