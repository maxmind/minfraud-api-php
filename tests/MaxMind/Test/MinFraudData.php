<?php

namespace MaxMind\Test;

class MinFraudData
{
    public static function factorsFullResponse()
    {
        return self::decodeFile('factors-response.json');
    }

    public static function insightsFullResponse()
    {
        return self::decodeFile('insights-response.json');
    }

    public static function scoreFullResponse()
    {
        return self::decodeFile('score-response.json');
    }

    public static function fullRequest()
    {
        return self::decodeFile('full-request.json');
    }

    private static function decodeFile($file)
    {
        return json_decode(file_get_contents('tests/data/' . $file), true);
    }
}
