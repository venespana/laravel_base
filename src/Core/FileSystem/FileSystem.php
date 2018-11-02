<?php

namespace VD\Core\FileSystem;

use VD\Core\FileSystem\MimeTypes;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Storage;
use Illuminate\Contracts\Filesystem\FileNotFoundException;

class FileSystem
{
    protected $configs = null;
    protected $disk = 'default';

    public function __construct($configs, string $disk = 'default')
    {
        $this->setDisk($disk);
        $this->setConfigs($configs);
    }

    public function setDisk(string $disk) : FileSystem
    {
        $this->disk = $disk;
        return $this;
    }

    public function getDisk() : string 
    {
        return $this->disk;
    }

    public function setConfigs($configs) : FileSystem
    {
        if (is_null($configs) && !is_string($configs) && !is_array($configs)) {
            $type = gettype($configs);
            throw new \InvalidArgumentException("Configs attibute only acepts string or array. Input was: {$type}");
        } 

        $this->configs = [
            'disks' => [
                $this->disk => $configs
            ]
        ];
        return $this;
    }

    public function getConfigs()
    {
        config($this->configs);
        return config("disks.{$this->disk}");
    }

    public function files(string $directory = '')
    {
        return Storage::disk($this->disk)->files($directory);
    }

    public function directories(string $directory = '')
    {
        return Storage::disk($this->disk)->directories($directory);
    }

    public function exists(string $path) : bool
    {
        return Storage::disk($this->disk)->exists($path);
    }

    public function get(string $path)
    {
        if (!$this->exists($path)) {
            throw new FileNotFoundException("File not found on Disk: {$this->disk} at Path: {$path}");
        }

        return Storage::disk($this->disk)->get($path);
    }

    public function fileData(string $file)
    {
        if (!$this->exists($file)) {
            throw new FileNotFoundException("File not found on Disk: {$this->disk} at Path: {$file}");
        }

        $info = pathinfo($file);
        $mimeTypes = new MimeTypes();
        $extension = $info['extension'] ?? null;
        $type = $mimeTypes->getGroup($extension);
        
        $data = [
            'name' => $info['basename'] ?? $file,
            'extension' => $extension,
            'path' => $info['dirname'] ?? null,
            'size' => $this->size($file),
            'mimetype' => $mimeTypes->getMimeType($extension),
            'type' => $type,
        ];

        return array_merge($data, $this->extraData($type, $file));
    }

    protected function size(string $path) : string
    {
        return $this->humanSize(Storage::disk($this->disk)->size($path));
    }

    protected function humanSize(int $bytes, int $decimals = 2)
    {
        $size = array('b','kb','mb','gb','tb','pb','eb','zb','yb');
        $factor = floor((strlen($bytes) - 1) / 3);
        return sprintf("%.{$decimals}f ", $bytes / pow(1024, $factor)) . @$size[$factor];
    }

    protected function extraData(?string $type, string $path)
    {
        $result = [];
        
        if (method_exists($this, $type)) {
            $result = $this->$type($path);
        }

        return $result;
    }

    protected function image(string $path)
    {
        $image = Image::make($this->get($path));

        $iptc = $image->iptc() ?? [];
        $exif = $image->exif() ?? [];

        $data = [
            'width' => $image->width(),
            'height' => $image->height()
        ];

        return array_merge($data, $iptc, $exif);
    }
}