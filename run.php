<?php

declare(strict_types=1);

require_once __DIR__ . '/vendor/autoload.php';

use App\Engine\RulesEngine;
use App\Event\EventFactory;
use App\Processor\EventProcessor;
use App\Reader\JsonLineEventReader;
use App\Rule\HighBlockRateRule;
use App\Rule\ManyUniqueReceiversRule;
use App\Stats\UserStatsRepository;
use App\Writer\SuspiciousJsonWriter;

if ($argc < 2) {
    \fwrite(STDERR, "Usage: php run.php events.json\n");
    exit(1);
}

$inputFile = $argv[1];
$outputFile = 'suspicious.json';

$repository = new UserStatsRepository();
$eventFactory = new EventFactory();
$reader = new JsonLineEventReader($eventFactory);
$processor = new EventProcessor($repository);

foreach ($reader->read($inputFile) as $event) {
    $processor->process($event);
}

$rules = [
    new ManyUniqueReceiversRule(),
    new HighBlockRateRule(),
];

$engine = new RulesEngine($rules);
$suspiciousUsers = $engine->evaluate($repository);

$writer = new SuspiciousJsonWriter();
$writer->write($outputFile, $suspiciousUsers);

echo "Done. Output written to {$outputFile}\n";
