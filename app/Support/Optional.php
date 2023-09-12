<?php

namespace App\Support;

use Illuminate\Contracts\Support\Responsable;
use Illuminate\Support\Optional as BaseOptional;
use RuntimeException;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Throwable;

/**
 * Обертка для расширения класса Optional.
 * @package App\Core\Support
 */
class Optional extends BaseOptional
{
    /**
     * Текст сообщения передаваемый в объект исключения, при отсутствии значения.
     */
    public const EXCEPTION_MESSAGE = 'None has no value';

    /**
     * @var Throwable|string|null Класс или экземпляр исключения, выбрасываемый по умолчанию
     */
    private $defaultException;

    /**
     * @var array|null Аргументы конструктора исключения по умолчанию.
     */
    private $defaultExceptionArgs;

    /**
     * OptionalWrapper constructor.
     * @param mixed $value
     */
    public function __construct($value)
    {
        if ($value instanceof BaseOptional) {
            $value = $value->value;
        }
        parent::__construct($value);
    }

    /**
     * Проверяет есть ли значение.
     *
     * @return bool
     */
    public function isEmpty(): bool
    {
        return $this->value === null;
    }

    /**
     * Возвращает значение или null, если оно не задано.
     *
     * @return mixed|null
     */
    public function value()
    {
        return $this->value;
    }

    /**
     * Если есть значение, возвращает его, иначе выбрасывает исключение.
     *
     * @return mixed
     * @noinspection PhpDocMissingThrowsInspection
     * @noinspection PhpUnhandledExceptionInspection
     */
    public function get()
    {
        if ($this->isEmpty()) {
            $this->throwException(null, static::EXCEPTION_MESSAGE);
        }

        return $this->value;
    }

    /**
     * Преобразует значение с помощью заданного конвертера.
     *
     * @param callable|string $target
     * @param array $params
     * @return static
     */
    public function map($target, ...$params): self
    {
        if ($this->isEmpty()) {
            return $this;
        }

        $args = array_merge([$this->value()], $params);

        if (is_callable($target)) {
            $this->value = $target(...$args);
        } else {
            $this->value = new $target(...$args);
        }

        return $this;
    }

    /**
     * Если значение отсутствует, делает попытку получить его от заданного $provider.
     *
     * @param mixed $provider
     * @return static
     */
    public function orElse($provider): self
    {
        if ($this->isEmpty()) {
            $value = value($provider);
            $this->value = ($value instanceof BaseOptional) ? $value->value : $value;
        }

        return $this;
    }

    /**
     * Выполняет заданный $action над значением, если оно присутствует.
     *
     * @param callable $action
     * @return static
     */
    public function ifPresent(callable $action): self
    {
        if (!$this->isEmpty()) {
            $action($this->value);
        }

        return $this;
    }

    /**
     * Если значением не заполнено - выбрасывает заданное исключение.
     *
     * @param null|Throwable|string $exception Объект исключения, имя класса или null
     * @param string $message Сообщение, которое будет передано в исключение
     * @param mixed $args Аргументы для sprintf
     * @return static
     * @noinspection PhpUnhandledExceptionInspection
     * @noinspection PhpDocMissingThrowsInspection
     */
    public function orElseThrow($exception = null, string $message = '', ...$args): self
    {
        if ($this->isEmpty()) {
            $this->throwException($exception, $message, $args);
        }

        return $this;
    }

    /**
     * Выбрасывает HttpException, если значение не заполнено.
     *
     * @param Response|Responsable|int $code
     * @param string $message
     * @param array $headers
     * @return static
     * @throws HttpException
     */
    public function orElseAbort($code, string $message = '', array $headers = []): self
    {
        abort_if($this->isEmpty(), $code, $message, $headers);

        return $this;
    }

    /**
     * Проверяет наличие значения и если оно присутствует - возвращает его. В противном
     * случае выбрасывает исключение заданного класса.
     *
     * @param string|Throwable $exception Имя класса исключения или уже созданный экземпляр
     * @param string $message Текст сообщения для случая когда задан класс исключения
     * @param mixed $args Аргументы для sprintf
     * @return mixed
     * @noinspection PhpDocMissingThrowsInspection
     * @noinspection PhpUnhandledExceptionInspection
     */
    public function getOrThrow($exception = null, string $message = '', ...$args)
    {
        if ($this->isEmpty()) {
            $this->throwException($exception, $message, $args);
        }

        return $this->value;
    }

    /**
     * Устанавливает имя класса или экземпляр класса исключения по умолчанию.
     * Если не установлено, в случае ошибки выбрасывается RuntimeException.
     *
     * @param string|Throwable|null $expection
     * @param array $args
     * @return $this
     */
    public function withDefaultException($expection, ...$args): self
    {
        $this->defaultException = $expection;
        $this->defaultExceptionArgs = empty($args) ? null : $args;

        return $this;
    }

    /**
     * Возвращает новый экземпляр с заданным значением.
     *
     * @param mixed|null $value
     * @return static
     */
    public static function create($value = null): self
    {
        return new static($value);
    }

    /**
     * Выбрасывает заданное исключение с указанным сообщением или сообщением по умолчанию.
     *
     * @param string|Throwable|null $exception
     * @param string $message
     * @param array $args
     *
     * @noinspection PhpUnhandledExceptionInspection
     * @noinspection PhpDocMissingThrowsInspection
     */
    protected function throwException($exception, string $message = '', array $args = []): void
    {
        if ($exception === null) {
            $exception = $this->getDefaultException();
        }

        if (is_string($exception)) {
            $exception = new $exception($this->makeExceptionMessage($message, $args));
        }

        throw $exception;
    }

    /**
     * Возвращает исключение по умолчанию.
     *
     * @return string|Throwable
     */
    protected function getDefaultException()
    {
        $exception = $this->defaultException ?? RuntimeException::class;
        if (!is_string($exception) || $this->defaultExceptionArgs === null) {
            return $exception;
        }

        return new $exception(...$this->defaultExceptionArgs);
    }

    /**
     * Формирует текст сообщения для исключения.
     *
     * @param string $message
     * @param array $args
     * @return string
     */
    protected function makeExceptionMessage(string $message, array $args): string
    {
        if (blank($message)) {
            return self::EXCEPTION_MESSAGE;
        }

        return count($args) > 0 ? sprintf($message, ...$args) : $message;
    }
}
