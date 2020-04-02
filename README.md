# Thatcher
A PHP pattern matcher for arguments and arrays of data

Thatcher matches data sets of data and based on that data runs a callback

I was inspired by functionality in other langauges like haskell and rust to make functionality
availiable in a langauge like php, even if it's only something basic

Here is a short section with a quick example of a basic implementaion
```php
<?php

require_once "./vendor/autoload.php";

use function ZombieTfk\Thatcher\Aliases\Pattern;
use const ZombieTfk\Thatcher\Symbols\Aliases\_;
use function ZombieTfk\Thatcher\Symbols\Aliases\Match;
use function ZombieTfk\Thatcher\Symbols\Aliases\When;

Match(
    When(
        Pattern(), //an empty pattern, which will match when the given set is empty
        function() {
            echo "No data passed";
        }
    ),
    When(
        Pattern(_), //a _ is an alias for any 1 item in a set
        function($x) {
            echo "Data contains exactly 1 element, $x";
        }
    ),
    When(
        Pattern(_,_), //in this case any set which is exactly 2 items long
        function($x, $y) {
            echo "Data contains exactly 2 elements, $x and $y";
        }
    ),
    When( //this one will be ran with the given data below
        Pattern('hello!', _,_), //match any set with exactly 3 items and the first item is the value 'hello!'
        function($x, $y, $z) {
            echo "Data contains exactly 3 elements starting with $x, then $y and $z";
        }
    ),
    When(
        Pattern([_]), //Match any number of items. As this is the last pattern we are using, it will be checked against last
        function($xs) {
            echo "Some Other Set -> " . json_encode($xs);
        }
    )
)("hello!", "myname jeff", "dasdsada");
```
In this case the function associated with `Pattern('hello!', _,_)` will be ran, as it is the first of the `When` clauses that matches in input from top to bottom.

Here are some examples of other patterns you could use

```php
<?php

require_once "./vendor/autoload.php";

use function ZombieTfk\Thatcher\Aliases\Pattern;
use const ZombieTfk\Thatcher\Symbols\Aliases\_;

Pattern(_,_,_);   //Match any set that size = 3
Pattern(_,_,[_]); //Match any set that size >= 3
Pattern([_],_,_);   //Match any set that size >= 3
Pattern([_]); //Match any set (Will simply return the passed input)

// sometimes you might want to do something like this
// to capture the the first, second, and last two items in the set
// as independant parameters to a function used with this pattern
// in a `When` clause
Pattern(_,_[_],_,_);

When(
    Pattern(_,_[_],_,_), //an empty pattern, which will match when the given set is empty
    function($a,$b,$xs,$c,$d) {
        echo "No data passed";
    }
)

//In this example, you can get the set of items before and the set of items after the first occurence of the word "peter"
Pattern([_],"peter",[_]);

//So trying to match something like this, remember that the last set will be everything after the first time it matches the given item
$pattern = Pattern([_],_,[_]);
print_r($pattern->getParsedParameters('x',7,8,9,0,1,2,3,4,5,'x',7));

/* OUTPUT
Array
(
    [0] => Array
        (
            [0] => x
        )

    [1] => 7
    [2] => Array
        (
            [0] => 8
            [1] => 9
            [2] => 0
            [3] => 1
            [4] => 2
            [5] => 3
            [6] => 4
            [7] => 5
            [8] => x
            [9] => 7
        )

)
*/
```

Calling the `getParsedParameters` method with invalid data will result in a NonMatchingInputException, so make sure that you call
the `matches` method first to check or handle it yourself Similarly, the same Exception will be raised in a Match clause
if the given data does not match any of it's patterns. It is usually good practice to include both a `Pattern()` and final `Pattern([_])` in your matches.

Finally, there Classes as well, don't worry, the aliases are just basic functions (or consts) hiding the `new`'ing them up

```php
<?php

//..

//this is the same as the first example, except now the final Matcher's passed function will be called
//as the input data has changed to no longer meet the conditions of the original pattern it matched
(new MatcherBlock(
    new Matcher(
        new Pattern(),
        function() {
            echo "No data passed";
        }
    ),
    new Matcher(
        new Pattern(_),
        function($x) {
            echo "Data contains exactly 1 element, $x";
        }
    ),
    new Matcher(
        new Pattern(_,_),
        function($x, $y) {
            echo "Data contains exactly 2 elements, $x and $y";
        }
    ),
    new Matcher(
        new Pattern('hello!', _,_),
        function($x, $y, $z) {
            echo "Data contains exactly 3 elements starting with $x, then $y and $z";
        }
    ),
    new Matcher(
        new Pattern([_]),
        function($xs) {
            echo "Some Other Set -> " . json_encode($xs);
        }
    )
))("goodbye", "myname steve", "dasdsada");
```
