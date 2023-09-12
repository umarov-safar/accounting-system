<?php

namespace App\Support\Actions;

use Closure;
use Illuminate\Support\Traits\ForwardsCalls;

class ActionDecorator
{
    use ForwardsCalls;
    use DecoratesAction;

    private Closure $chain;

    public function __construct(protected object $target, Closure $interceptor)
    {
        $this->chain = fn (...$parameters) => $this->target->execute(...$parameters);

        $this->addDecorator($interceptor);
    }

    public function execute(...$parameters)
    {
        return ($this->chain)(...$parameters);
    }

    public function __call(string $name, array $arguments)
    {
        $result = $this->forwardCallTo($this->target, $name, $arguments);

        if ($result === $this->target) {
            return $this;
        }

        if (($result instanceof ActionDecorator) && $result->target === $this->target) {
            $result->target = $this;
        }

        return $result;
    }

    private function addDecorator(Closure $interceptor): ActionDecorator|static
    {
        $chain = $this->chain;
        $current = $interceptor->bindTo($this->target);

        $this->chain = fn (...$parameters) => $current($chain, ...$parameters);

        return $this;
    }
}
