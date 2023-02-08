<?php

namespace Helpers\Components;

use Illuminate\Http\Testing\MimeType;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Arr;
use Symfony\Component\Mime\MimeTypes;
use Illuminate\Support\Str;

class BinaryFile
{
    private ?MimeTypes $mime;

    /**
     * @param $binaryString
     * @return \Illuminate\Http\UploadedFile
     */
    public function create($binaryString): UploadedFile
    {
        $name = Str::uuid();
        $tmpFile = tmpfile();
        fwrite($tmpFile, $binaryString);

        return new UploadedFile(
            stream_get_meta_data($tmpFile)['uri'],
            $name,
            MimeType::from($name),
            0,
            true
        );
    }

    /**
     * @return \Symfony\Component\Mime\MimeTypes|null
     */
    private function getMimeTypes(): ?MimeTypes
    {
        if ($this->mime === null) {
            $this->mime = new MimeTypes;
        }

        return $this->mime;
    }

    /**
     * @param $filename
     * @return mixed|string
     */
    private function mimeType($filename): mixed
    {
        $extension = pathinfo($filename, PATHINFO_EXTENSION);

        return Arr::first($this->getMimeTypes()->getMimeTypes($extension)) ?? 'application/octet-stream';
    }
}
