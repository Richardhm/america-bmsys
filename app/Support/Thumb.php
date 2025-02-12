<?php
    namespace App\Support;
    use CoffeeCode\Cropper\Cropper;
    class Thumb 
    {
        private $cropper;

        public function __construct()
        {
            $this->cropper = new Cropper("../public/storage/cache");
        }

        public function makes(string $uri,int $width,int $height=null)
        {
            $path = $this->cropper->make(config('filesystems.disks.public.root')."/".$uri,$width,$height);
            $file = 'cache/'.collect(explode("/",$path))->last();
            return $file; 

        }

        public function flush(string $img = null)
        {
            if($img) {
                $this->cropper->flush($img);
                return;
            }
            $this->cropper->flush();
            return;
        } 


    }