<?php

declare(strict_types=1);

namespace MaxMind\Test\MinFraud\ReportTransaction;

class ReportTransactionData
{
    public static function fullRequest(): array
    {
        return self::decodeFile('full-request.json');
    }

    public static function minimalRequest(): array
    {
        return self::decodeFile('minimal-request.json');
    }

    private static function decodeFile(string $file): array
    {
        $contents = file_get_contents('tests/data/minfraud/reporttransaction/' . $file);
        if (!$contents) {
            throw new \Exception("Invalid test file $file");
        }

        return json_decode($contents, true);
    }
}
