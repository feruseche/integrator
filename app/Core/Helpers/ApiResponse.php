<?php

namespace Core\Helpers;

final class ApiResponse
{
    private bool $type;
    private string $message;
    private array $data;

    public function __construct(bool $type, string $message, array $data = [])
    {
        $this->type    = $type;
        $this->message = $message;
        $this->data    = $data;
    }

    public function execute()
    {
        return response()->json([
            'status'  => $this->type,
            'message' => $this->message,
            'data'    => $this->data
        ]);
    }
}
