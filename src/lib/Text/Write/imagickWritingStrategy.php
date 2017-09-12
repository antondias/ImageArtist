<?php
namespace Treinetic\ImageArtist\lib\Text\Write;
use Treinetic\ImageArtist\lib\Helpers\ImageHelper;
use Treinetic\ImageArtist\lib\Image;
use Treinetic\ImageArtist\lib\Text\Color;
use Treinetic\ImageArtist\lib\Text\Font;
use Treinetic\ImageArtist\lib\Text\TextWriter;

/**
 * Created by PhpStorm.
 * User: imal365
 * Date: 9/12/17
 * Time: 10:09 AM
 */
class ImagickWritingStrategy implements WritingStrategy
{
    /** @var  \Treinetic\ImageArtist\lib\Text\TextWriter $textWriter */
    private $textWriter;
    private $imageHelper;

    public function __construct()
    {
        $this->imageHelper = new ImageHelper();
    }

    public function write()
    {


//        $angle;


        $writer = $this->textWriter;
        /** @var Font $font */
        $font = $writer->getFont();
        /** @var Color $color */
        $color = $writer->getColor();

        $im = new \Imagick();
        $background = new \ImagickPixel('none');

        $im->setBackgroundColor($background);

        $im->setFont($font->getPath());
        $im->setPointSize($writer->getSize());
        $im->setGravity(\Imagick::GRAVITY_WEST); //later we will have to change this


        $width = $writer->getWidth();
        $height = $writer->getHeight();
        $text = $writer->getText();
        $margin = $writer->getMargin();

        $im->newPseudoImage($width, $height, "pango:" . $text );
        $clut = new \Imagick();
        $clut->newImage(1, 1, new \ImagickPixel($color->toString()));
        $im->clutImage($clut);
        $clut->destroy();

        $im->setImageFormat("png");
        $image = imagecreatefromstring($im->getImageBlob());
        $template = $this->imageHelper->createTransparentTemplate($width+ (2*$margin),$height+ (2 *$margin));

        $img = new Image($template);
        $text = new Image($image);

        imagedestroy($image);
        imagedestroy($template);

        return $img->merge($text,$margin,$margin);
    }


    public function setWriter(TextWriter $textWriter)
    {
       $this->textWriter = $textWriter;
    }
}