<?php

declare(strict_types=1);

namespace MaxMind\Test;

class MinFraudData
{
    public static function factorsFullResponse(): array
    {
        return self::decodeFile('factors-response.json');
    }

    public static function insightsFullResponse(): array
    {
        return self::decodeFile('insights-response.json');
    }

    public static function scoreFullResponse(): array
    {
        return self::decodeFile('score-response.json');
    }

    public static function fullRequest(): array
    {
        return self::decodeFile('full-request.json');
    }

    private static function decodeFile(string $file): array
    {
        $contents = file_get_contents('tests/data/minfraud/' . $file);
        if (!$contents) {
            throw new \Exception("getting tests file $file failed!");
        }

        return json_decode($contents, true);
    }
}
