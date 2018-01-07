<?php

namespace Darkroom;

/**
 * Class File
 *
 * @package Darkroom
 */
class File
{
    protected $filePath;
    protected $fileExists;

    /**
     * File constructor.
     *
     * @param $filePath
     */
    public function __construct($filePath)
    {
        $this->filePath = $filePath;
    }

    /**
     * The file extension
     *
     * @return string
     */
    public function extension()
    {
        return pathinfo($this->filePath(), PATHINFO_EXTENSION);
    }

    /**
     * The file name without extension
     *
     * @return string
     */
    public function name()
    {
        return pathinfo($this->filePath(), PATHINFO_FILENAME);
    }

    /**
     * The file name with extension
     *
     * @return string
     */
    public function filename()
    {
        return pathinfo($this->filePath(), PATHINFO_BASENAME);
    }

    /**
     * The absolute to path to the file directory
     *
     * @return string
     */
    public function directory()
    {
        return pathinfo($this->filePath(), PATHINFO_DIRNAME);
    }

    /**
     * Save content to file
     *
     * @param mixed $content The content to save to the file
     */
    public function save($content)
    {
        if (!$this->isURL()) {
            file_put_contents($this->filePath(), (string)$content);
        }
    }

    /**
     * Check if it is an URL
     *
     * @return string
     */
    public function isURL()
    {
        return filter_var($this->filePath(), FILTER_VALIDATE_URL);
    }

    /**
     * Get the original file path
     *
     * @return string
     */
    public function filePath()
    {
        return $this->filePath;
    }

    /**
     * Check is the file exists
     *
     * @return bool
     */
    public function exists()
    {
        if (is_null($this->fileExists)) {
            if ($this->isURL()) {
                $headers          = get_headers($this->filePath());
                $status           = isset($headers[0]) ? $headers[0] : '';
                $this->fileExists = strpos($status, '200 OK') !== false;
            } else {
                $this->fileExists = file_exists($this->filePath());
            }
        }

        return $this->fileExists;
    }
}
