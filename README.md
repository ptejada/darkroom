# Darkroom [In Development]
Darkroom is a library that facilitates image manipulation operations in PHP. The core functionality of the library is 
implemented in the form of recipes which makes extending the image editor natural and intuitive.

[![Build Status](https://img.shields.io/travis/ptejada/darkroom/master.svg?style=flat)](https://travis-ci.org/ptejada/darkroom)
[![GitHub issues](https://img.shields.io/github/issues/ptejada/darkroom.svg)](https://github.com/ptejada/darkroom/issues)
[![GitHub license](https://img.shields.io/github/license/ptejada/darkroom.svg)](https://github.com/ptejada/darkroom/blob/master/LICENSE)

## Requirements
The Darkroom library has the following requirements:
- PHP 5.4+
- GD Library

### Installation
The library is still under development but you can still give it try.
Use composer to install the library in your project:
```
composer require darkroom/darkroom:dev-master
```

## Getting Started
The library API is inspired by the options and actions that an image editor GUI would have. The result is a fluent and 
descriptive OOP driven API which replaces the long functions with a lot of arguments.

There are three major components you should be aware of: The *Editor*, *Image* and *Recipes*. In a nutshell, the *Editor*
is use to create the *Image* object(s) and the *Recipes* are the actions that manipulate the image like crop and resize.

### Code Examples
Use the editor to open an image:
```php
$image = \Darkroom\Editor::open('image.jpg');   
```

Simple image crop:
```php
// 400x400 viewport
$image->edit()->crop->square(400); 
// 400x200 viewport
$image->edit()->crop->rectagle(400, 200);
```
By default the image crop viewport will start from the top left of the image or the `x,y` position `0,0`. The position
of the viewport can be specified as follows:
```php
// Crop 400x400 square at position (150, 100)
$image->edit()->crop->square(400)->at(150, 100);
```

Save image to file and get the reference:
```php
// Save the image edits and get the local file path of the new image
$newImagePath = $image->save()->localPath();

// Save the image edits and get the public URL of the new image
$newImageUrl = $image->save()->publicUrl();
```

Render the image to the standard output with its corresponding HTTP headers:
 ```php 
 $image->render();
 ```
 
 ## Available Recipes
 ### Crop
 Crop the image using a custom viewport dimension and position.
 
 **Modifiers:**
 - `rectangle( with, height )` - Creates rectangular viewport with specified dimensions 
 - `square( dimension )` - Creates square viewport with specified dimension
 - `at( x, y )` - Position the upper left corner of the viewport.
  
 ### Resize
Resize the image to different dimensions while respecting the original aspect ratio unless the `distort` modifier is used.

 **Modifiers:**
 - `to( with, height )` - Set the dimensions of the new image. The *height* is optional.
 - `heightTo( height )` - Set the height of the new image. The width will be automatically calculated.
 - `by( percent )` - Set the dimensions of the new image using a decimal percentage where 0.5 is 50% or half the size of the original image.
 - `withImageFill( image )` - Set an image to use as the background if the new image has a different ratio.
 - `withColorFill( color )` - Set a solid color to use as the background if the new image has a different ratio.
 - `distort()` - Ignores the original aspect ratio and resize the image exactly to the specified dimensions.

## TODO
- [ ] Complete the `Resize` recipe fill modifiers.
- [ ] Add configurations to the main `Darkroom\Editor`.
- [ ] Finalize the `Filesystem` storage.
- [ ] Implement registration system for custom recipes.
- [ ] Add unit test for the core functionality.
- [ ] Add `Transform` recipe to perform image rotation, mirror and flip.



