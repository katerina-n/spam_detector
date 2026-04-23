<?php

declare(strict_types=1);

namespace App\Reader;

use App\Event\EventFactory;
use RuntimeException;

class JsonLineEventReader
{
    public function __construct(
        private EventFactory $eventFactory
    ) {
    }

    public function read(string $filePath): \Traversable
    {
        $handle = \fopen($filePath, 'rb');

        if ($handle === false) {
            throw new RuntimeException("Cannot open file: {$filePath}");
        }

        while (($line = \fgets($handle)) !== false) {
            $line = \trim($line);

            if ($line === '') {
                continue;
            }
            $data = \json_decode($line, true);
            if (!\is_array($data)) {
                throw new RuntimeException('Invalid JSON line: ' . $line);
            }

            yield $this->eventFactory->fromArray($data);
        }

        \fclose($handle);
    }
}
