<?php

namespace App\Domain\Support;

use Illuminate\Contracts\Support\Responsable;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

class MassOperationResult implements Responsable
{
    private array $processed = [];
    private array $errors = [];

    public function toResponse($request): Response
    {
        return response()->json([
            'data' => [
                'processed' => array_unique($this->processed),
                'errors' => $this->convertErrors(),
            ],
        ]);
    }

    public function success(int $id): void
    {
        $this->processed[] = $id;
    }

    public function error(int $id, string|Throwable $message = 'Неизвестная ошибка'): void
    {
        $this->errors[$id] = ($message instanceof Throwable) ? $message->getMessage() : $message;
    }

    private function convertErrors(): array
    {
        $result = [];

        foreach ($this->errors as $id => $message) {
            $result[] = ['id' => $id, 'message' => $message];
        }

        return $result;
    }
}
