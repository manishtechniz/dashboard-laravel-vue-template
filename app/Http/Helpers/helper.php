<?php

function resolveApi($relativePath): string
{
    return env('BACKEND_URL') . '/' . ltrim($relativePath, '/');
}

function create422ErrorFormat(string $column, string $message, $preArray = [], $postArray = [])
{
    return array_merge(
        $preArray,
        [
            'message' => $message,
            'errors' => [
                $column => [$message],
            ],
        ],
        $postArray
    );
}
