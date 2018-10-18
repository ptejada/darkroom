# Darkroom

Darkroom is a library that facilitates simple image manipulation operations in PHP. The core functionality of the library is 
implemented in the form of _tools_ which makes extending the image editor natural and intuitive.

[![Build Status](https://travis-ci.org/ptejada/darkroom.svg?branch=master)](https://travis-ci.org/ptejada/darkroom)
[![StyleCI](https://github.styleci.io/repos/116521231/shield?branch=master)](https://github.styleci.io/repos/116521231)
[![Maintainability](https://api.codeclimate.com/v1/badges/dc02c2d048e626a55f8a/maintainability)](https://codeclimate.com/github/ptejada/darkroom/maintainability)
[![Test Coverage](https://api.codeclimate.com/v1/badges/dc02c2d048e626a55f8a/test_coverage)](https://codeclimate.com/github/ptejada/darkroom/test_coverage)

# Requirements
The Darkroom library has the following requirements:
- PHP 5.6+
- GD Library

# Installation

Use composer to install the library in your project:
```
composer require darkroom/darkroom
```
<details>
<summary>Table of Contents</summary>
<section>

<!--ts-->
   * [Darkroom](#darkroom)
   * [Requirements](#requirements)
   * [Installation](#installation)
   * [Getting Started](#getting-started)
   * [Tools](#tools)
      * [Built-in tools](#built-in-tools)
         * [crop](#crop)
         * [resize](#resize)
         * [rotate](#rotate)
         * [stamp](#stamp)
      * [Custom tools](#custom-tools)
   * [The Storage](#the-storage)
      * [Location and naming pattern](#location-and-naming-pattern)
      * [Custom path generator](#custom-path-generator)
      * [Sending to external service](#sending-to-external-service)
      * [Custom Storage](#custom-storage)
   * [TODO](#todo)

<!-- Added by: vagrant, at: 2018-09-28T03:48+00:00 -->

<!--te-->

</section>
</details>


# Getting Started
The library API is inspired by the options and actions that an image editor GUI would have. The result is a fluent and 
descriptive OOP driven API which replaces the long functions with a lot of arguments.

There are three major components you should be aware of: The *Editor*, *Image* and *Tools*. In a nutshell, the *Editor*
is use to create the *Image* object(s) and the *Tools* are the actions that manipulate the image like crop and resize.

Use the editor to open an image:
```php
$image = \Darkroom\Editor::open('image.jpg');
```

Simple image crop:
```php
// 400x400 viewport
$image->edit()->crop()->square(400); 
// 400x200 viewport
$image->edit()->crop()->rectagle(400, 200);
```

By default the image crop viewport will start from the top left of the image or the `x,y` position `0,0`. The position
of the viewport can be specified as follows:
```php
// Crop 400x400 square at position (150, 100)
$image->edit()->crop()->square(400)->at(150, 100);
```

Save image to file system and get the reference:
```php
// Save the image edits and get the local file path of the new image
$newImagePath = $image->save()->localPath();

// Save the image edits and get the public URL of the new image
$newImageUrl = $image->save()->publicUrl();
```

To render the image to the standard output with its corresponding HTTP headers:
```php 
$image->render();
```
 
# Tools

Every image edit is handled by a `Tool`. Image edits include but are not limited to cropping, resizing placing 
watermarks. The library comes with built-in tools and also provides the option for custom tools to be registered

## Built-in tools

All tools are accessed from the Image reference `edit()` factory.

```php
$image = \Darkroom\Editor::open('image.jpg');

// The call to resize() will return an intance of \Darkroom\Tool\Resize
$image->edit()->resize()->to(400);
```

### crop
Crop the image using a custom viewport dimension and position.

**Modifiers:**
- `rectangle( with, height )` - Creates rectangular viewport with specified dimensions 
- `square( dimension )` - Creates square viewport with specified dimension
- `at( x, y )` - Position the upper left corner of the viewport.

### resize
Resize the image to different dimensions while respecting the original aspect ratio unless the `distort` modifier is used.

**Modifiers:**
- `to( with, height )` - Set the dimensions of the new image. The *height* is optional.
- `heightTo( height )` - Set the height of the new image. The width will be automatically calculated.
- `by( percent )` - Set the dimensions of the new image using a decimal percentage where 0.5 is 50% or half the size of the original image.
- `withImageFill( image )` - Use an image for the unused area if the new image has a different ratio.
- `withColorFill( color )` - Use solid color for the unused area if the new image has a different aspect ratio.
- `withTransparentFill()` - Makes the background of the unused area transparent.
- `distort()` - Ignores the original aspect ratio and resize the image exactly to the specified dimensions.

### rotate
Rotate the image either left or right based on an angle.

**Modifiers:**
- `left( degrees )` - Rotate image to the left.
- `right( degrees )` - Rotate image to the Right.
- `withColorFill( color )` - Set a fill background color. 
- `withTransparentFill()` - Makes the background transparent.

### stamp
Stamp image with another position at one or more positions. Can be use to place watermarks in images.

**Modifiers:**
- `with( image )` - Sets the image to stamp with.
- `at( x, y )` - Coordinates where to stamp the image. This modifier could be call multiple times to stamp at multiple locations
- `opacity( level )` - Sets the opacity level for the stamp where 1 is opaque and 0 is transparent 

## Custom tools

You create your own tool or recipe by implementing the `\Darkroom\Tool\Tool` interface. Alternatively you can extend
`\Darkroom\Tool\AbstractTool` which already implements the interface. You may register any amount of tools used them 
in the same way built-in tools are used.

```php
// Registers a new tool which can be accesed as 'flip'
\Darkroom\Editor::registerTool('flip', '\MyApp\Image\Tool\Flip');

// Usage
$image = Editor::open('my-image.jpg');
// Use new 'flip' tool
$image->edit()->flip()->vertical();
$image->save();
```

Been able to use your own custom tools is a very powerful feature. With great power comes great responsibility. 
`Editor::registerTool()` even allows you to overwrite built-tools with your own.

# The Storage

The library comes with a zero configuration built-in storage system. By default images will be saved in the 
folder `/storage/images/` located in the document root of your application. The images will be furthered
organized in a monthly folder follow by a random name. Ex: `2018-09/ad57ryht-a5gf-s5hr2AWg.jpg`.

The image storage if fully customizable and can even support storage to external services like AWS S3. You may access 
and configure the underlying storage system with `\Darkroom\Editor::config()->storage()`.

## Location and naming pattern
 
Example update both the root path and the image naming pattern:

```php
$store = \Darkroom\Editor::config()->storage();
// Updates where images will be stored
$store->setBasePath('/var/www/path/to/public/folder/');

// Updates the default image naming pattern
// The new patten will generate name a string of 16 characters
$store->setPathPattern('%16'); 
```
In the path pattern any letter character will be process by the PHP `date()` function and replaced with its output. 
Any % follow by a number represents a random string where the number is the length of the string. Ex: `%6` will 
generate a 6 characters long random string. The default pattern is`Y-m/%32`.

## Custom path generator

If you need even more control over how and where the images are been stored you can configure the built-in storage
with a PHP `callable` with `\Darkroom\Editor::config()->storage()->setPathGenerator(callable)`. The path generator
callable receives and instance of the image been save as `\Darkroom\Image`. You may return the new absolute path where
the image will be stored in the file system.
```php
\Darkroom\Editor::config()->storage()->setPathGenerator(function($image, $basePath){
    // Saved new images using the original name suffixed with a
    return $basePath . DIRECTORY_SEPRARATOR . $image->file()->name() . '_' . microtime(true);
});
```

## Sending to external service
 
Since the path generator has access to the Image reference it can also be used as a bridge to store the image in a
separate system or storage service like AWS S3. Instead of returning a `string` for the local file system path 
you may return anything else like a `boolean` or a custom object and it wil be used as the return value for
`Image::save()` calls.

```php
// Configuration
\Darkroom\Editor::config()->storage()->setPathGenerator(function($image){
    $buffer = $image->renderTo(tmpfile());
    // Code to upload stream to AWS S3 or other service
    
    // You can also return a reference to the external service like the URL or resource id as long as 
    // is wrapped in an object.         
    return true;
});

// Usage
Editor::open('my-image.jpg')->save(); // Returns true
``` 

## Custom Storage

You should not include a lot of code in a `closure` to handle your custom image saving logic. If you need more room 
to handle saving the images consider making your own implementation of the `\Darkroom\Storage\Storage` interface. 
You can then register your Storage implementation in the editor.
  
```php
// Configuration
\Darkroom\Editor::config()->useStorage(new MyCustomStore);
```   
 
# TODO
- [X] Complete the `Resize` tool fill modifiers.
- [X] Add `Rotate` tool to perform image rotation.
- [X] Implement registration system for custom tools.
- [ ] Add unit test for the core functionality.
