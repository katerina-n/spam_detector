<?php

declare(strict_types=1);

namespace App\Writer;

use RuntimeException;

class SuspiciousJsonWriter
{
    public function write(string $filePath, array $data): void
    {
        $json = \json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);

        if ($json === false) {
            throw new RuntimeException('Failed to encode suspicious data');
        }

        \file_put_contents($filePath, $json);
    }
}
