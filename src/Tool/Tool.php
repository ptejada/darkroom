<?php

namespace Darkroom\Tool;


use Darkroom\ImageEditor;

/**
 * Interface Tool defines the structure for an editor tool
 *
 * @package Darkroom\Tool
 */
interface Tool {
    /**
     * Tool constructor.
     *
     * @param ImageEditor $imageEditor $imageEditor
     * @param callable    $callback Callback executed when the image resource is updated
     */
    public function __construct(ImageEditor $imageEditor, callable $callback);

    /**
     * Tool initialization. Receives parameters provided to the tool factory.
     *
     * @return void
     */
    public function init();

    /**
     * Saves the changes to the image
     *
     * @return ImageEditor The image editor. The instance received in constructor
     */
    public function apply();

    /**
     * Whether the filter has been applied or not
     *
     * @return bool
     */
    public function applied();
}
