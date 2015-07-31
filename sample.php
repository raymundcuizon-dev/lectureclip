
<?php

/* * ********************************************************
 * function resizejpeg:
 *
 *  = creates a resized image based on the max width
 *    specified as well as generates a thumbnail from
 *    a rectangle cut from the middle of the image.
 *
 *    @dir    = directory image is stored in
 *    @newdir = directory new image will be stored in
 *    @img    = the image name
 *    @max_w  = the max width of the resized image
 *    @max_h  = the max height of the resized image
 *    @th_w   = the width of the thumbnail
 *    @th_h   = the height of the thumbnail
 *
 * ******************************************************** */

/*function resizejpeg($dir, $newdir, $img, $max_w, $max_h, $th_w, $th_h) {
    // set destination directory
    if (!$newdir)
        $newdir = $dir;

    // get original images width and height
    list($or_w, $or_h, $or_t) = getimagesize($dir . $img);

    // make sure image is a jpeg
    if ($or_t == 2) {

        // obtain the image's ratio
        $ratio = ($or_h / $or_w);

        // original image
        $or_image = imagecreatefromjpeg($dir . $img);

        // resize image?
        if ($or_w > $max_w || $or_h > $max_h) {

            // resize by height, then width (height dominant)
            if ($max_h < $max_w) {
                $rs_h = $max_h;
                $rs_w = $rs_h / $ratio;
            }
            // resize by width, then height (width dominant)
            else {
                $rs_w = $max_w;
                $rs_h = $ratio * $rs_w;
            }

            // copy old image to new image
            $rs_image = imagecreatetruecolor($rs_w, $rs_h);
            imagecopyresampled($rs_image, $or_image, 0, 0, 0, 0, $rs_w, $rs_h, $or_w, $or_h);
        }
        // image requires no resizing
        else {
            $rs_w = $or_w;
            $rs_h = $or_h;

            $rs_image = $or_image;
        }

        // generate resized image
        imagejpeg($rs_image, $newdir . $img, 100);

        $th_image = imagecreatetruecolor($th_w, $th_h);

        // cut out a rectangle from the resized image and store in thumbnail
        $new_w = (($rs_w / 2) - ($th_w / 2));
        $new_h = (($rs_h / 2) - ($th_h / 2));

        imagecopyresized($th_image, $rs_image, 0, 0, $new_w, $new_h, $rs_w, $rs_h, $rs_w, $rs_h);

        // generate thumbnail
        imagejpeg($th_image, $newdir . 'thumb_' . $img, 100);

        return true;
    }

    // Image type was not jpeg!
    else {
        return false;
    }
}
?>

<?php

//$dir = '';
$img = 'download.jpg';

resizejpeg('', 'img/', $img, 600, 400, 300, 150); */

echo "<script>alert('blablabla');</script>";
?>