<?php

namespace Darkroom\Storage;

use Darkroom\Image;
use Darkroom\Utility\Str;

/**
 * Class Store serves as default image storage
 *
 * @package Darkroom\Storage
 */
class Store implements Storage
{
    /** @var string The cached base path */
    protected $basePath;
    /** @var callable Optional path generator */
    protected $pathGenerator;
    /** @var string Pattern to to generate new names */
    protected $pathPattern;

    /**
     * Store constructor.
     */
    public function __construct()
    {
        $this->pathPattern = 'Y-m/%6-%4-%6';
    }

    /**
     * Saves an image
     *
     * @param Image  $image   Image reference
     * @param string $altName Alternative file name
     *
     * @return bool|File
     */
    public function save(Image $image, $altName = null)
    {
        $path = $altName ? $this->basePath() . $altName : $this->newPath($image, $altName);
        if (is_string($path)) {
            return $image->renderTo($path . '.' . $image->file()->extension());
        }
        return $path;
    }

    /**
     * Generates a new path name
     *
     * @param Image  $image   Image reference
     * @param string $altName Alternative name
     *
     * @return string
     */
    public function newPath(Image $image, $altName = null)
    {
        if (is_callable($this->pathGenerator)) {
            return call_user_func($this->pathGenerator, $image, $this->basePath(), $altName);
        }

        return $this->basePath() . Str::name($this->pathPattern);
    }

    /**
     * Sets the storage base path
     *
     * @param string $path The base path as string
     */
    public function setBasePath($path)
    {
        if (is_dir($path)) {
            $this->basePath = str_replace('//', '/', realpath($path) . '/');
        }

        throw new \InvalidArgumentException("Path '{$path}' does not exists or is not a directory.");
    }

    /**
     * Sets a new path generator
     *
     * @param callable $generator A callable to generate new path names. Receives image reference as first parameter
     *                            and base storage path as second parameter.
     */
    public function setPathGenerator($generator)
    {
        if (is_callable($generator)) {
            $this->pathGenerator = $generator;
        }

        throw new \InvalidArgumentException('Path generator must be callable, ' . gettype($generator) . ' given.');
    }

    /**
     * Sets the path generator pattern.
     *
     * @see Str::name
     *
     * @param string $pattern The pattern.
     */
    public function setPathPattern($pattern)
    {
        if (is_string($pattern)) {
            $this->pathPattern = $pattern;
        } else {
            throw new \InvalidArgumentException('The pattern must be a string, ' . gettype($pattern) . ' given.');
        }
    }

    /**
     * The storage base path
     *
     * @return string
     */
    protected function basePath()
    {
        if (empty($this->basePath)) {
            if (empty($_SERVER["DOCUMENT_ROOT"])) {
                if ($root = $this->findProjectRoot()) {
                    if (is_dir($root . 'public')) {
                        $basePath = $root . '/public/storage/images/';
                    } else {
                        $basePath = $root . '/storage/images/';
                    }
                }
            } else {
                $basePath = $_SERVER["DOCUMENT_ROOT"] . '/storage/images/';
            }

            if (!empty($basePath)) {
                $this->setBasePath($basePath);
            }
        }

        return $this->basePath;
    }

    /**
     * Find composer based project root path
     *
     * @param string $path Path to find the project root from
     *
     * @return null|string The project root path or null
     */
    protected function findProjectRoot($path = __DIR__)
    {
        $needle = '/vendor/';
        if ($start = strpos($path, $needle)) {
            return substr($path, 0, $start + strlen($needle));
        }

        return null;
    }
}
