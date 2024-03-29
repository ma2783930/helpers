<?php

use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Http\Testing\MimeType;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\File\File;

if (!function_exists('base64_to_file')) {

    /**
     * @param string $value
     * @return \Illuminate\Http\UploadedFile
     */
    function base64_to_file(string $value): UploadedFile
    {
        if (str_contains($value, ';base64')) {
            [, $value] = explode(';', $value);
            [, $value] = explode(',', $value);
        }

        $binaryData  = base64_decode($value);
        $tmpFile     = tmpfile();
        $tmpFilePath = stream_get_meta_data($tmpFile)['uri'];

        file_put_contents($tmpFilePath, $binaryData);

        $tmpFileObject = new File($tmpFilePath);
        $file          = new UploadedFile(
            $tmpFileObject->getPathname(),
            $tmpFileObject->getFilename(),
            $tmpFileObject->getMimeType(),
            0,
            true
        );

        app()->terminating(function () use ($tmpFile) {
            fclose($tmpFile);
        });

        return $file;
    }

}

if (!function_exists('binary_to_file')) {
    /**
     * @param $binaryString
     * @return \Illuminate\Http\UploadedFile
     */
    function binary_to_file($binaryString): UploadedFile
    {
        $name    = Str::uuid();
        $tmpFile = tmpfile();
        fwrite($tmpFile, $binaryString);

        $file = new UploadedFile(
            stream_get_meta_data($tmpFile)['uri'],
            $name,
            MimeType::from($name),
            0,
            true
        );

        app()->terminating(function () use ($tmpFile) {
            fclose($tmpFile);
        });

        return $file;
    }
}

if (!function_exists('can_one')) {

    /**
     * @param ...$permissions
     * @return string
     */
    function can_one(...$permissions): string
    {
        $collection = [];
        foreach ($permissions as $permission) {
            if (property_exists($permission, 'value')) {
                $collection[] = $permission->value;
            } else {
                $collection[] = $permission;
            }
        }

        $gates = implode(',', $collection);
        return "can.one:{$gates}";
    }
}

if (!function_exists('user')) {

    /**
     * @return \Illuminate\Contracts\Auth\Authenticatable|null
     */
    function user(): ?Authenticatable
    {
        return auth()->user();
    }
}

if (!function_exists('get_sequence_next_value')) {

    /**
     * @param $sequence_name
     * @return int
     */
    function get_sequence_next_value($sequence_name): int
    {
        $sequenceQuery = DB::select(DB::raw("SELECT NEXT VALUE FOR [dbo].[$sequence_name]"));
        return (int)implode(
            get_object_vars($sequenceQuery[0])
        );
    }
}

if (!function_exists('is_binary')) {

    /**
     * @param $value
     * @return bool
     */
    function is_binary($value): bool
    {
        return false === mb_detect_encoding((string)$value, null, true);
    }
}


if (!function_exists('binary')) {
    /**
     * @return mixed
     */
    function binary(): mixed
    {
        return app('binary');
    }
}


if (!function_exists('en_numbers_to_persian')) {
    /**
     * @param $string
     * @return array|string
     */
    function en_numbers_to_persian($string): array|string
    {
        $characters = [
            '1' => '۱',
            '2' => '۲',
            '3' => '۳',
            '4' => '۴',
            '5' => '۵',
            '6' => '۶',
            '7' => '۷',
            '8' => '۸',
            '9' => '۹',
            '0' => '۰',
        ];
        return str_replace(
            array_keys($characters),
            array_values($characters),
            $string
        );
    }
}
