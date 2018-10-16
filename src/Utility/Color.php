<?php

namespace Darkroom\Utility;

use Darkroom\Image;

class Color
{
    const MODE_ALLOCATE = 1;
    protected static $colorNames = [
        'aliceblue'            => 'F0F8FF',
        'antiquewhite'         => 'FAEBD7',
        'aqua'                 => '00FFFF',
        'aquamarine'           => '7FFFD4',
        'azure'                => 'F0FFFF',
        'beige'                => 'F5F5DC',
        'bisque'               => 'FFE4C4',
        'black'                => '000000',
        'blanchedalmond '      => 'FFEBCD',
        'blue'                 => '0000FF',
        'blueviolet'           => '8A2BE2',
        'brown'                => 'A52A2A',
        'burlywood'            => 'DEB887',
        'cadetblue'            => '5F9EA0',
        'chartreuse'           => '7FFF00',
        'chocolate'            => 'D2691E',
        'coral'                => 'FF7F50',
        'cornflowerblue'       => '6495ED',
        'cornsilk'             => 'FFF8DC',
        'crimson'              => 'DC143C',
        'cyan'                 => '00FFFF',
        'darkblue'             => '00008B',
        'darkcyan'             => '008B8B',
        'darkgoldenrod'        => 'B8860B',
        'darkgray'             => 'A9A9A9',
        'darkgreen'            => '006400',
        'darkgrey'             => 'A9A9A9',
        'darkkhaki'            => 'BDB76B',
        'darkmagenta'          => '8B008B',
        'darkolivegreen'       => '556B2F',
        'darkorange'           => 'FF8C00',
        'darkorchid'           => '9932CC',
        'darkred'              => '8B0000',
        'darksalmon'           => 'E9967A',
        'darkseagreen'         => '8FBC8F',
        'darkslateblue'        => '483D8B',
        'darkslategray'        => '2F4F4F',
        'darkslategrey'        => '2F4F4F',
        'darkturquoise'        => '00CED1',
        'darkviolet'           => '9400D3',
        'deeppink'             => 'FF1493',
        'deepskyblue'          => '00BFFF',
        'dimgray'              => '696969',
        'dimgrey'              => '696969',
        'dodgerblue'           => '1E90FF',
        'firebrick'            => 'B22222',
        'floralwhite'          => 'FFFAF0',
        'forestgreen'          => '228B22',
        'fuchsia'              => 'FF00FF',
        'gainsboro'            => 'DCDCDC',
        'ghostwhite'           => 'F8F8FF',
        'gold'                 => 'FFD700',
        'goldenrod'            => 'DAA520',
        'gray'                 => '808080',
        'green'                => '008000',
        'greenyellow'          => 'ADFF2F',
        'grey'                 => '808080',
        'honeydew'             => 'F0FFF0',
        'hotpink'              => 'FF69B4',
        'indianred'            => 'CD5C5C',
        'indigo'               => '4B0082',
        'ivory'                => 'FFFFF0',
        'khaki'                => 'F0E68C',
        'lavender'             => 'E6E6FA',
        'lavenderblush'        => 'FFF0F5',
        'lawngreen'            => '7CFC00',
        'lemonchiffon'         => 'FFFACD',
        'lightblue'            => 'ADD8E6',
        'lightcoral'           => 'F08080',
        'lightcyan'            => 'E0FFFF',
        'lightgoldenrodyellow' => 'FAFAD2',
        'lightgray'            => 'D3D3D3',
        'lightgreen'           => '90EE90',
        'lightgrey'            => 'D3D3D3',
        'lightpink'            => 'FFB6C1',
        'lightsalmon'          => 'FFA07A',
        'lightseagreen'        => '20B2AA',
        'lightskyblue'         => '87CEFA',
        'lightslategray'       => '778899',
        'lightslategrey'       => '778899',
        'lightsteelblue'       => 'B0C4DE',
        'lightyellow'          => 'FFFFE0',
        'lime'                 => '00FF00',
        'limegreen'            => '32CD32',
        'linen'                => 'FAF0E6',
        'magenta'              => 'FF00FF',
        'maroon'               => '800000',
        'mediumaquamarine'     => '66CDAA',
        'mediumblue'           => '0000CD',
        'mediumorchid'         => 'BA55D3',
        'mediumpurple'         => '9370D0',
        'mediumseagreen'       => '3CB371',
        'mediumslateblue'      => '7B68EE',
        'mediumspringgreen'    => '00FA9A',
        'mediumturquoise'      => '48D1CC',
        'mediumvioletred'      => 'C71585',
        'midnightblue'         => '191970',
        'mintcream'            => 'F5FFFA',
        'mistyrose'            => 'FFE4E1',
        'moccasin'             => 'FFE4B5',
        'navajowhite'          => 'FFDEAD',
        'navy'                 => '000080',
        'oldlace'              => 'FDF5E6',
        'olive'                => '808000',
        'olivedrab'            => '6B8E23',
        'orange'               => 'FFA500',
        'orangered'            => 'FF4500',
        'orchid'               => 'DA70D6',
        'palegoldenrod'        => 'EEE8AA',
        'palegreen'            => '98FB98',
        'paleturquoise'        => 'AFEEEE',
        'palevioletred'        => 'DB7093',
        'papayawhip'           => 'FFEFD5',
        'peachpuff'            => 'FFDAB9',
        'peru'                 => 'CD853F',
        'pink'                 => 'FFC0CB',
        'plum'                 => 'DDA0DD',
        'powderblue'           => 'B0E0E6',
        'purple'               => '800080',
        'red'                  => 'FF0000',
        'rosybrown'            => 'BC8F8F',
        'royalblue'            => '4169E1',
        'saddlebrown'          => '8B4513',
        'salmon'               => 'FA8072',
        'sandybrown'           => 'F4A460',
        'seagreen'             => '2E8B57',
        'seashell'             => 'FFF5EE',
        'sienna'               => 'A0522D',
        'silver'               => 'C0C0C0',
        'skyblue'              => '87CEEB',
        'slateblue'            => '6A5ACD',
        'slategray'            => '708090',
        'slategrey'            => '708090',
        'snow'                 => 'FFFAFA',
        'springgreen'          => '00FF7F',
        'steelblue'            => '4682B4',
        'tan'                  => 'D2B48C',
        'teal'                 => '008080',
        'thistle'              => 'D8BFD8',
        'tomato'               => 'FF6347',
        'turquoise'            => '40E0D0',
        'violet'               => 'EE82EE',
        'wheat'                => 'F5DEB3',
        'white'                => 'FFFFFF',
        'whitesmoke'           => 'F5F5F5',
        'yellow'               => 'FFFF00',
        'yellowgreen'          => '9ACD32',
    ];
    /** @var int[] The RGB color decimal values [R, G, B] */
    protected $rgbColor;
    /** @var int The color mode generator mode */
    protected $mode = self::MODE_ALLOCATE;
    /** @var bool The color transparency flag */
    protected $transparency_flag = false;

    /**
     * Color constructor.
     *
     * @param  int|int[]|string $redOrHex The RGB red decimal value or full color in hex format
     * @param int               $green    The RGB green decimal value
     * @param int               $blue     The RGB blue decimal value
     * @param int               $alpha    Transparency
     */
    public function __construct($redOrHex, $green = null, $blue = null, $alpha = 0)
    {
        if (is_null($green) && is_null($blue)) {
            if (is_array($redOrHex)) {
                $this->rgbColor = [
                    isset($redOrHex[0]) ? $redOrHex[0] : 0,
                    isset($redOrHex[1]) ? $redOrHex[1] : 0,
                    isset($redOrHex[2]) ? $redOrHex[2] : 0,
                    isset($redOrHex[3]) ? $redOrHex[3] : 0,
                ];
            } else {
                $this->rgbColor = self::toRgb($redOrHex);
            }
        } else {
            $this->rgbColor = [(int) $redOrHex, (int ) $green, (int) $blue, (int) $alpha];
        }
    }

    /**
     * Convert Hex base color to RGB
     *
     * @param string $hexColor     Color string in hex format
     * @param float  $transparency A decimal indicating the transparency level. 1 opaque and 0 full transparent
     *
     * @return int[] The Returns the RGB decimal values[red, green, blue]
     */
    public static function toRgb($hexColor, $transparency = 1.0)
    {
        $hexColor = strtolower($hexColor);

        if ($transparency > 1) {
            throw new \InvalidArgumentException('The transparency parameter can be greater than 1.0');
        }

        // Check named colors first
        if (isset(self::$colorNames[$hexColor])) {
            return self::toRgb(self::$colorNames[$hexColor], $transparency);
        }

        $cleanHex = trim($hexColor, "# ");
        $length   = strlen($cleanHex);

        if ($length === 6) {
            // Split format AABBCC
            list($red, $green, $blue) = str_split($cleanHex, 2);
        } else {
            // Split format ABC
            if ($length === 3) {
                list($red, $green, $blue) = str_split($cleanHex, 1);
                $red   .= $red;
                $green .= $green;
                $blue  .= $blue;
            } else {
                throw new \InvalidArgumentException("Invalid hex color {$hexColor}");
            }
        }

        return [hexdec($red), hexdec($green), hexdec($blue), (1 - $transparency) * 127];
    }

    /**
     * Mark the color as transparent
     */
    public function transparent()
    {
        $this->transparency_flag = true;
    }

    /**
     * Check if the color has been flagged as transparent
     *
     * @return bool
     */
    public function isTransparent()
    {
        return $this->transparency_flag;
    }

    /**
     * The in color in RGB format
     *
     * @return int[]
     */
    public function rgb()
    {
        return $this->rgbColor;
    }

    /**
     * Generate color for an specific image
     *
     * @param resource|Image $image The image to generate the color for
     *
     * @return int
     */
    public function color($image)
    {
        if (is_resource($image)) {
            $resource = $image;
        } else {
            if ($image instanceof Image) {
                $resource = $image->resource();
            }
        }

        if (!empty($resource)) {
            if ($this->mode & self::MODE_ALLOCATE) {
                list($red, $green, $blue, $alpha) = $this->rgb();
                return (int) imagecolorallocatealpha($resource, $red, $green, $blue, $alpha);
            }
        }

        return 0;
    }
}
