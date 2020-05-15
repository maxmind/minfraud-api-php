<?php

namespace MaxMind\Test\MinFraud\ReportTransaction;

class ReportTransactionData
{
    public static function fullRequest()
    {
        return self::decodeFile('full-request.json');
    }

    public static function minimalRequest()
    {
        return self::decodeFile('minimal-request.json');
    }

    private static function decodeFile($file)
    {
        return json_decode(file_get_contents('tests/data/minfraud/reporttransaction/' . $file), true);
    }
}
