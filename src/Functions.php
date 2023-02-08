<?php

use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\File\File;

if (!function_exists('convert_base64_to_file')) {

    /**
     * @param string $value
     * @return \Illuminate\Http\UploadedFile
     */
    function convert_base64_to_file(string $value): UploadedFile
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

if (!function_exists('can_one')) {

    /**
     * @param ...$permissions
     * @return string
     */
    function can_one(...$permissions): string
    {
        $gates = implode(',', $permissions);
        return "can.one:{$gates}";
    }

}

if (!function_exists('user')) {

    /**
     * @return \Illuminate\Contracts\Auth\Authenticatable
     */
    function user(): Authenticatable
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
        $sequenceQuery = DB::select(DB::raw("SELECT NEXT VALUE FOR [dbo].[${sequence_name}]"));
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
    function binary()
    {
        return app('binary');
    }
}
