<?php

namespace App\Core;

class QueryParser
{

    public static function buildData(string $query_string = null)
    {

        if (null !== $query_string) {
            parse_str($query_string, $output);
            return $output;
        }
        return $query_string;
    }
}
