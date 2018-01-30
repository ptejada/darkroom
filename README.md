# Darkroom [In Development]
Darkroom is a library that facilitates simple image manipulation operations in PHP. The core functionality of the library is 
implemented in the form of _tools_ which makes extending the image editor natural and intuitive.

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

There are three major components you should be aware of: The *Editor*, *Image* and *Tools*. In a nutshell, the *Editor*
is use to create the *Image* object(s) and the *Tools* are the actions that manipulate the image like crop and resize.

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
 
 ## Available Tools
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
 - `withImageFill( image )` - Use an image for the unused area if the new image has a different ratio.
 - `withColorFill( color )` - Use solid color for the unused area if the new image has a different aspect ratio.
 - `withTransparentFill()` - Makes the background of the unused area transparent.
 - `distort()` - Ignores the original aspect ratio and resize the image exactly to the specified dimensions.
 
 ### Rotate
 Rotate the image either left or right based on an angle.
 
 **Modifiers:**
  - `left( degrees )` - Rotate image to the left.
  - `right( degrees )` - Rotate image to the Right.
  - `withColorFill( color )` - Set a fill background color. 
  - `withTransparentFill()` - Makes the background transparent.
  
### Stamp
Stamp image with another position at one or more positions. Can be use to place watermarks in images.

**Modifiers:**
- `with( image )` - Sets the image to stamp with.
- `at( x, y )` - Coordinates where to stamp the image. This modifier could be call multiple times to stamp at multiple locations
- `opacity( level )` - Sets the opacity level for the stamp where 1 is opaque and 0 is transparent 

## TODO
- [X] Complete the `Resize` tool fill modifiers.
- [X] Add `Rotate` tool to perform image rotation.
- [ ] Add configurations to the main `Darkroom\Editor`.
- [ ] Finalize the `Filesystem` storage.
- [ ] Implement registration system for custom tools.
- [ ] Add unit test for the core functionality.



