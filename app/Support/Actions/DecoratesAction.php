<?php

namespace App\Support\Actions;

use Closure;
use LogicException;

trait DecoratesAction
{
    protected array $decoratedStates = [];

    protected function decorateCall(Closure $interceptor): ActionDecorator|static
    {
        return $this->addDecorator($interceptor);
    }

    protected function decorateResult(Closure $interceptor): ActionDecorator|static
    {
        return $this->addDecorator(function (callable $chain, ...$parameters) use ($interceptor) {
            $result = $chain(...$parameters);

            return $interceptor($result);
        });
    }

    protected function decorateState(Closure $provider): ActionDecorator|static
    {
        return $this->addDecorator(function (callable $chain, ...$parameters) use ($provider) {
            $this->setDecoratedState($provider(...$parameters));

            $result = $chain(...$parameters);

            $this->restoreDecoratedState();

            return $result;
        });
    }

    protected function addDecorator(Closure $interceptor): ActionDecorator|static
    {
        return new ActionDecorator($this->decorator ?? $this, $interceptor);
    }

    protected function setDecoratedState(array $newState): void
    {
        $oldState = [];
        foreach ($newState as $field => $value) {
            $oldState[$field] = $this->{$field};
            $this->{$field} = $value;
        }

        $this->decoratedStates[] = $oldState;
    }

    protected function restoreDecoratedState(): void
    {
        if (empty($this->decoratedStates)) {
            throw new LogicException('Стек состояний пуст');
        }

        $oldState = array_pop($this->decoratedStates);
        foreach ($oldState as $field => $value) {
            $this->{$field} = $value;
        }
    }
}
