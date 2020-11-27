<?php

const IMAGE_HANDLERS = [
    IMAGETYPE_JPEG => [
        'load' => 'imagecreatefromjpeg',
        'save' => 'imagejpeg',
        'quality' => 100
    ],
    IMAGETYPE_PNG => [
        'load' => 'imagecreatefrompng',
        'save' => 'imagepng',
        'quality' => 0
    ],
    IMAGETYPE_GIF => [
        'load' => 'imagecreatefromgif',
        'save' => 'imagegif'
    ]
];

class Thumb {

    protected $temp = 'temporary';

    protected $image;

    protected $url;
    protected $dir;
    protected $new;

    protected $width;
    protected $height;
    protected $quality = 90;

    public function __construct( $image, $width = false, $height = false )
    {

        $this->image = $image;
        $this->width = $width;
        $this->height = $height;
        $this->url = $this->url();

        $this->generate();
    }

    public function url(): string
    {
        $this->url = implode('/',[
            $this->temp,
            $this->image->path,
            $this->image->title
        ]);

        $this->url .= '-'.implode('x',[
            $this->width === false ? 'auto' : $this->width,
            $this->height === false ? 'auto' : $this->height,
        ]);

        $this->url .= '.'.$this->image->extension;
        return $this->url;

    }

    public function exists(): bool
    {
        return file_exists( $this->url );
    }

    public function generate(): string
    {

        if( $this->exists() ){
            $this->new = false;
            return false;
        }
        $this->new = true;

        $dir = $this->temp.'/'.$this->image->path;

        if( !is_dir( $dir ) ){
            mkdir( $dir, 0777, true);
        }

        // 1. Load the image from the given $this->image->url
        // - see if the file actually exists
        // - check if it's of a valid image type
        // - load the image resource

        // get the type of the image
        // we need the type to determine the correct loader
        $type = exif_imagetype($this->image->url);

        // if no valid type or no handler found -> exit
        if (!$type || !IMAGE_HANDLERS[$type]) {
            return null;
        }

        // load the image with the correct loader
        $image = call_user_func(IMAGE_HANDLERS[$type]['load'], $this->image->url);

        // no image found at supplied location -> exit
        if (!$image) {
            return null;
        }


        // 2. Create a thumbnail and resize the loaded $image
        // - get the image dimensions
        // - define the output size appropriately
        // - create a thumbnail based on that size
        // - set alpha transparency for GIFs and PNGs
        // - draw the final thumbnail

        // get original image width and height
        $width = imagesx($image);
        $height = imagesy($image);

        // maintain aspect ratio when no height set
        if ($this->height == null) {

            // get width to height ratio
            $ratio = $width / $height;

            // if is portrait
            // use ratio to scale height to fit in square
            if ($width > $height) {
                $this->height = floor($this->width / $ratio);
            }
            // if is landscape
            // use ratio to scale width to fit in square
            else {
                $this->height = $this->width;
                $this->width = floor($this->width * $ratio);
            }
        }

        // create duplicate image based on calculated target size
        $thumbnail = imagecreatetruecolor($this->width, $this->height);

        // set transparency options for GIFs and PNGs
        if ($type == IMAGETYPE_GIF || $type == IMAGETYPE_PNG) {

            // make image transparent
            imagecolortransparent(
                $thumbnail,
                imagecolorallocate($thumbnail, 0, 0, 0)
            );

            // additional settings for PNGs
            if ($type == IMAGETYPE_PNG) {
                imagealphablending($thumbnail, false);
                imagesavealpha($thumbnail, true);
            }
        }

        // copy entire source image to duplicate image and resize
        imagecopyresampled(
            $thumbnail,
            $image,
            0, 0, 0, 0,
            $this->width, $this->height,
            $width, $height
        );


        // 3. Save the $thumbnail to disk
        // - call the correct save method
        // - set the correct quality level

        // save the duplicate version of the image to disk
        return call_user_func(
            IMAGE_HANDLERS[$type]['save'],
            $thumbnail,
            $this->url,
            IMAGE_HANDLERS[$type]['quality']
        );

    }

    public function toArray(): array
    {
        return [
            'url' => $this->url,
        ];
    }

}
