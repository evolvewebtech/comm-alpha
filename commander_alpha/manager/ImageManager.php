<?php
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of ImageManager
 *
 * @author francesco
 */
class ImageManager {
    //put your code here
    public static function createThumb($width, $height, $image, $thumbdir, $id){

        //set the dimensions for the thumbnail

        $thumb_width = $width * 0.10;
        $thumb_height = $height * 0.10;

        //create the thumbnail
        $thumb = imagecreatetruecolor($thumb_width, $thumb_height);
        imagecopyresampled($thumb, $image, 0, 0, 0, 0, $thumb_width, $thumb_height,
            $width, $height);
        imagejpeg($thumb, $thumbdir . '/' . $id . '.jpg', 100);
        imagedestroy($thumb);
    }

    public static function uploadPic($dir, $db, $image_caption, $image_username, $image_date){

        if ($_FILES['uploadfile']['error'] != UPLOAD_ERR_OK)
        {
            switch ($_FILES['uploadfile']['error']) {
            case UPLOAD_ERR_INI_SIZE:
                die('The uploaded file exceeds the upload_max_filesize directive ' .
                    'in php.ini.');
                break;
            case UPLOAD_ERR_FORM_SIZE:
                die('The uploaded file exceeds the MAX_FILE_SIZE directive that ' .
                    'was specified in the HTML form.');
                break;
            case UPLOAD_ERR_PARTIAL:
                die('The uploaded file was only partially uploaded.');
                break;
            case UPLOAD_ERR_NO_FILE:
                die('No file was uploaded.');
                break;
            case UPLOAD_ERR_NO_TMP_DIR:
                die('The server is missing a temporary folder.');
                break;
            case UPLOAD_ERR_CANT_WRITE:
                die('The server failed to write the uploaded file to disk.');
                break;
            case UPLOAD_ERR_EXTENSION:
                die('File upload stopped by extension.');
                break;
            }
        }

        list($width, $height, $type, $attr) = getimagesize($_FILES['uploadfile']['tmp_name']);

        // make sure the uploaded file is really a supported image
        $error = 'The file you uploaded was not a supported filetype.';
        switch ($type) {
        case IMAGETYPE_GIF:
            $image = imagecreatefromgif($_FILES['uploadfile']['tmp_name']) or
                die($error);
            break;
        case IMAGETYPE_JPEG:
            $image = imagecreatefromjpeg($_FILES['uploadfile']['tmp_name']) or
                die($error);
            break;
        case IMAGETYPE_PNG:
            $image = imagecreatefrompng($_FILES['uploadfile']['tmp_name']) or
                die($error);
            break;
        default:
            die($error);
        }

        //insert information into image table
        $query = 'INSERT INTO images (image_caption, image_username, image_date)
        VALUES ("' . $image_caption . '", "' . $image_username . '", "' . $image_date . '")';

        $result = mysql_query($query, $db) or die (mysql_error($db));

        //retrieve the image_id that MySQL generated automatically when we inserted
        //the new record
        $last_id = mysql_insert_id();

        // save the image to its final destination
        $image_id = $last_id;

        imagejpeg($image, $dir . '/' . $image_id  . '.jpg');
        imagedestroy($image);

        $img[]= $image_id;
        $img[]= $height;
        $img[]= $width;
        $img[]= $type;
        $img[]= $attr;

        return $img;
        //return $image_id;
    }

    public static function saveImage($id, $dir){

        if (isset($id) && ctype_digit($id) && file_exists($dir . '/' . $id . '.jpg')) {
            $image = imagecreatefromjpeg($dir . '/' . $id . '.jpg');
        } else {
            die('invalid image specified');
        }

        imagejpeg($image, $dir . '/' . $id . '.jpg', 100);

        return $image;
    }

    /*
     * use:
     * list($width, $height, $type, $attr) = ImageManager::getimagesize($dir . '/' . $image_id . '.jpg');
     */
    public static function getImgInfo($db, $id, $dir, $image_id){
        $query = 'SELECT image_id, image_caption, image_username, image_date
                    FROM images WHERE image_id = ' . $id;
        $result = mysql_query($query, $db) or die (mysql_error($db));
        extract(mysql_fetch_assoc($result));
        //list($width, $height, $type, $attr) = getimagesize($dir . '/' . $image_id . '.jpg');
        return getimagesize($dir . '/' . $image_id . '.jpg');
    }

}
?>