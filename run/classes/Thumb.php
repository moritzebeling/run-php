<?php

const IMAGE_HANDLERS = [
    IMAGETYPE_JPEG => [
        'load' => 'imagecreatefromjpeg',
        'save' => 'imagejpeg',
        'quality' => 80
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

    protected $root;

    protected $image;
    protected $type;

    protected $url;
    protected $directory;
    protected $new;

    protected $width;
    protected $height;
    protected $quality = 90;

    public function __construct( $image, int $width = null, int $height = null )
    {
        $this->root = option('temporary');

        $this->image = $image;
        $this->width = $width;
        $this->height = $height;

        $this->url = $this->url();
        $this->generate();
    }

    public function image(): Image
    {
        return $this->image;
    }
    public function width( $fallback = false )
    {
        return is_int($this->width) ? $this->width : $fallback;
    }
    public function height( $fallback = false )
    {
        return is_int($this->height) ? $this->height : $fallback;
    }
    public function quality(): int
    {
        return $this->quality;
    }
    public function new(): bool
    {
        return $this->new;
    }

    public function url(): string
    {
        $this->url = implode('/',[
            $this->root,
            str_replace( option('content').DS, '', $this->image->path() ),
            slug( $this->image->name() )
        ]);

        $this->url .= '-'.implode('x',[
            $this->width('auto'),
            $this->height('auto')
        ]);

        $this->url .= '.'.$this->image->extension();
        return $this->url;

    }

    public function exists(): bool
    {
        return file_exists( $this->url() );
    }

    public function directory()
    {
        $this->directory = $this->root . DS . str_replace( option('content').DS, '', $this->image->path() );
        if( !is_dir( $this->directory ) ){
            mkdir( $this->directory, 0777, true);
        }
        return $this->directory;
    }

    public function type()
    {
        $type = exif_imagetype( $this->image->url() );
        if( !$type || !IMAGE_HANDLERS[$type] ){
            return null;
        }
        return $type;
    }

    public function error()
    {
        $this->url = $this->image()->url();
    }

    public function openFile( $type )
    {
        return call_user_func( IMAGE_HANDLERS[$type]['load'], $this->image->url() );
    }

    public function generate(): string
    {
        if( $this->exists() ){
            $this->new = false;
            return false;
        }
        $this->new = true;

        $this->directory();

        $type = $this->type();
        if( !$type ){
            $this->error();
            return null;
        }

        $file = $this->openFile( $type );

        if (!$file) {
            $this->error();
            return null;
        }

        $originalWidth = imagesx( $file );
        $originalHeight = imagesy( $file );

        if ($this->height == null) {

            $ratio = $originalWidth / $originalHeight;

            if( $originalWidth > $originalHeight ){
                $this->height = floor( $this->width / $ratio );
            } else {
                $this->height = $this->width;
                $this->width = floor( $this->width * $ratio );
            }
        }

        $thumbnail = imagecreatetruecolor( $this->width, $this->height );

        if ($type == IMAGETYPE_GIF || $type == IMAGETYPE_PNG) {
            imagecolortransparent(
                $thumbnail,
                imagecolorallocate($thumbnail, 0, 0, 0)
            );
            if( $type == IMAGETYPE_PNG ){
                imagealphablending($thumbnail, false);
                imagesavealpha($thumbnail, true);
            }
        }

        imagecopyresampled(
            $thumbnail,
            $file,
            0, 0, 0, 0,
            $this->width, $this->height,
            $originalWidth, $originalHeight
        );

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
            'src' => '/'.$this->url(),
            'width' => $this->width()
        ];
    }

}
