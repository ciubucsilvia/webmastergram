<?php

namespace App\Repositories;

class CoreRepository
{
    public function getPath($path)
    {
        return '/public/' . $path;
    }

    public function getUserPath($directory)
    {
        return auth()->user()->id . '/' . $directory;
    }
}
?>