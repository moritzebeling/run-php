<?php

class Image extends File {

    public function srcset( $sizes = [ 600, 1200, 2000 ] ): array
    {
        $srcset = [];
        foreach( $sizes as $size ){
            $srcset[] = (new Thumb( $this, $size ))->toArray();
        }
        return $srcset;
    }

    public function toArray(): array {
        return array_merge(
            parent::toArray(),
            [
                'srcset' => $this->srcset()
            ]
        );
    }

}
