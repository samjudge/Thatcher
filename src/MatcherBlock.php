<?php

namespace ZombieTfk\Thatcher;

class MatcherBlock
{
    /**
     * @var Matcher[]
     */
    private $matchers;

    public function __construct(Matcher ...$matchers) {
        $this->matchers = $matchers;
    }

    public function __invoke(...$input)
    {
        foreach($this->matchers as $matcher) {
            if($matcher->matches(...$input))
            {
                $fn = $matcher->getCallback();
                return $fn(
                    ...$matcher->getParsedParameters(
                        ...$input
                    )
                );
            }
        }
    }
}