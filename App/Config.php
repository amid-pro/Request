<?php

class Config
{

    public static function set(): void
    {

        $default_values = [
            "SSL_VERIFICATION" => 1
        ];

        foreach ($default_values as $default_key => $default_value) {
            putenv($default_key . "=" . $default_value);
        }

        $env_file = ROOT . DIRECTORY_SEPARATOR . '.env';

        if (file_exists($env_file) && is_writable($env_file)) {

            $file = fopen($env_file, "r");

            while (($string = fgets($file)) !== false) {

                $string = trim($string);

                if ($string != "") {
                    $parts = mb_split('\=', $string, 2);
                    putenv(trim($parts[0]) . "=" . trim($parts[1]));
                }
            }

            fclose($file);
        }
    }
}
