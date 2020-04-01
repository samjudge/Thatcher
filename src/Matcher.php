<?php

namespace ZombieTfk\Thatcher;

class Matcher
{
    /**
     * @var Pattern
     */
    private $pattern;

    private $callback;

    public function __construct(Pattern $pattern, Callable $callback) {
        $this->pattern = $pattern;
        $this->callback = $callback;
    }

    public function matches(...$input) : bool
    {
        return $this->pattern->matches(...$input);
    }

    public function getParsedParameters(...$input)
    {
        return $this->pattern->getParsedParameters(...$input);
    }

    public function getCallback()
    {
        return $this->callback;
    }
}