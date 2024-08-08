<?php

$photos = glob('../img/galleries/**/*.jpg');

$sizes = [
    "small" => ["width" => 150, "height" => 150, "square" => true],
    "medium" => ["width" => 300, "height" => 300, "square" => false],
    "large" => ["width" => 1024, "height" => 1024, "square" => false],
];

foreach ($photos as $photo):
    foreach($sizes as $size=>$area):
        if (str_contains($photo, "_$size")):
            echo "$photo deleted\n";
            unlink($photo);
        endif;
    endforeach;
endforeach;

$photos = glob('../img/galleries/**/*.jpg');
foreach ($photos as $photo):
    $info = pathinfo($photo);
    foreach($sizes as $size=>$area):
        $resized = $info["dirname"]."/".$info["filename"]."_$size".".".$info["extension"];
        $img = new imagick($photo);

        $sizeWidth = $area["width"];
        $sizeHeight = $area["height"];
        if ($area["square"] === true) {
            $img->cropThumbnailImage($sizeWidth, $sizeHeight);
        }
        else {
            $imageWidth = $img->getImageWidth();
            $imageHeight = $img->getImageHeight();
            if ($imageWidth < $imageHeight) {
                $img->scaleImage($sizeWidth, 0);
            }
            else if ($imageWidth > $imageHeight) {
                $img->scaleImage(0, $sizeHeight);
            }
            else {
                $img->scaleImage($sizeWidth, $sizeHeight);
            }
        }
        $img->writeImage($resized);
        echo "$resized created\n";
    endforeach;
endforeach;