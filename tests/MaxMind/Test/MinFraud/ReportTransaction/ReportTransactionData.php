<?php

declare(strict_types=1);

namespace MaxMind\Test\MinFraud\ReportTransaction;

class ReportTransactionData
{
    /**
     * @return array<string, mixed>
     */
    public static function fullRequest(): array
    {
        return self::decodeFile('full-request.json');
    }

    /**
     * @return array<string, mixed>
     */
    public static function minimalRequest(): array
    {
        return self::decodeFile('minimal-request.json');
    }

    /**
     * @return array<string, mixed>
     */
    private static function decodeFile(string $file): array
    {
        $contents = file_get_contents('tests/data/minfraud/reporttransaction/' . $file);
        if (!$contents) {
            throw new \Exception("Invalid test file $file");
        }

        return json_decode($contents, true);
    }
}
