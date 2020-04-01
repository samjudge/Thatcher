<?php

namespace ZombieTfk\Thatcher;

use ZombieTfk\Thatcher\Exceptions\NonMatchingInputException;
use ZombieTfk\Thatcher\Symbols\AnonymousSymbol;
use function ZombieTfk\Thatcher\Util\first;
use function ZombieTfk\Thatcher\Util\tail;

class Pattern {
    private $matchables;

    public function __construct(...$matchables) {
        $this->matchables = $matchables;
    }

    public function __invoke(...$args) {
        return new self(...$args[0]);
    }

    function matches(...$arguments) {
        try {
            $out = $this->getParsedParameters(...$arguments);
        } catch (NonMatchingInputException $e) {
            return false;
        }
        return count($out) === count($this->matchables);
    }

    /**
     * @param mixed ...$arguments
     * @return array|mixed
     * @throws NonMatchingInputException
     */
    public function getParsedParameters(...$arguments) {
        $matchables = $this->matchables;
        if($matchables === []) {
            if($arguments === []) {
                return [];
            }
            throw new NonMatchingInputException();
        }
        if($arguments === []) {
            return [];
        }
        $matchable = first($matchables);
        $argument = first($arguments);
        //deal with single character matches
        if($matchable === $argument ||
            $matchable === AnonymousSymbol::class
        ) {
            return array_merge(
                [ $argument ],
                (new Pattern(
                    ...tail($matchables)
                ))->getParsedParameters(
                    ...tail($arguments)
                )
            );
        }
        //deal with set matches
        if($this->isAnonymousGroupOp($matchable)) {
            $out =
                $this->recursivelyAddUntilLastPattern(
                    $arguments,
                    $matchables
                );
            return $this->mergeWithLastKeyWhileArray(
                $out
            );
        }
        throw new NonMatchingInputException();
    }

    private function mergeWithLastKeyWhileArray($input) {
        for($x = 0; $x < count($input); $x++) {
            if(isset($input[$x - 1])) {
                if (is_array($input[$x]) && is_array($input[$x - 1])) {
                    $removedA = array_splice($input, $x - 1, 1);
                    $removedB = array_splice($input, $x - 1, 1);
                    $removed = array_merge(
                        $removedA[0],
                        $removedB[0]
                    );
                    array_splice($input, $x - 1, 0, [ $removed ]);
                    $x = 0;
                }
            }
        }
        return $input;
    }


    private function recursivelyAddUntilLastPattern($args, $matchables) {
        //if this is last matchable
        //return all remaining data as a set
        if($matchables === [[AnonymousSymbol::class]]) {
            return [ $args ];
        }
        //the pattern to be matched in order to exit
        $out = [];
        try {
            $nextPattern = (new Pattern(
                ...tail($matchables)
            ))->getParsedParameters(
                ...tail($args)
            );
            //check if it's last instance of pattern in set (otherwise continue)
            if(count($nextPattern) === count(tail($args))) {
                if(count($nextPattern) !== 0) {
                    $out[] = [ first($args) ];
                }
                return array_merge(
                    $out,
                    $nextPattern
                )
                    ;
            } else {
                $out[] = [ first($args) ];
                return array_merge(
                    array_merge(
                        $out,
                        $nextPattern
                    ),
                    $this->recursivelyAddUntilLastPattern(
                        [ first($nextPattern) ],
                        $matchables
                    )
                );
            }
        } catch(NonMatchingInputException $e) {
            $out[] = [ first($args) ];
            $submatches = $this->recursivelyAddUntilLastPattern(
                tail($args),
                $matchables
            );
            return array_merge(
                $out,
                $submatches
            );
        }
    }

    private function isAnonymousGroupOp($matchable) {
        if(is_array($matchable)) {
            if($matchable[0] === AnonymousSymbol::class) {
                return true;
            }
        }
        return false;
    }
}
