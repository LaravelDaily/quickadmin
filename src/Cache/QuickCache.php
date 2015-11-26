<?php
namespace Laraveldaily\Quickadmin\Cache;

use Illuminate\Filesystem\Filesystem;

class QuickCache
{

    private $cacheDir;
    private $files;

    public function __construct()
    {
        $this->cacheDir = storage_path('framework'. DIRECTORY_SEPARATOR .'cache'. DIRECTORY_SEPARATOR);
        $this->files    = new Filesystem();
        if (!file_exists($this->cacheDir)) {
            mkdir($this->cacheDir);
        }
    }

    /**
     * Put information into cache file
     *
     * @param            $filename
     * @param            $content
     */
    public function put($filename, $content)
    {
        // Merge existing content to new content
        if (file_exists($this->cacheDir . $filename) && file_get_contents($this->cacheDir . $filename) != '') {
            $cachedContent = $this->get($filename);
            $content       = array_merge($content, $cachedContent);
        }
        $this->files->put($this->cacheDir . $filename, json_encode($content));
    }

    /**
     * Get file contents
     *
     * @param $filename
     *
     * @return string
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    public function get($filename)
    {
        return (array) json_decode($this->files->get($this->cacheDir . $filename));
    }

    /**
     * Delete cache file
     *
     * @param $filename
     */
    public function destroy($filename)
    {
        $this->files->delete($this->cacheDir . $filename);
    }
}