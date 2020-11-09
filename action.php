<?php
session_start();


//	set defaut timezone
//date_default_timezone_set('Asia/Kolkata');
//	set img_directory for store image
$compress_img_dir = "./user_img/";
//	create directory if does not exist
if (!file_exists($compress_img_dir)) {
    mkdir($compress_img_dir, 0777, true);
}
//	set data array to store all data
$data   = array();
// error handling purpose
$errors = array();

//save JPEG image
function saveJPEG($file, $orgWidth, $orgHeight, $newWidth, $newHeight, $imgName, $imgDir, $quality = 100){
	$newImgName = $imgName."-".$newWidth."x".$newHeight.".jpg";
	$newImgLayout = imagecreatetruecolor($newWidth, $newHeight);
	$source = imagecreatefromjpeg($file);
	imagecopyresized($newImgLayout, $source, 0, 0, 0, 0, $newWidth, $newHeight, $orgWidth, $orgHeight);
	if (imagejpeg($newImgLayout, $imgDir.$newImgName)) {
    //if (imagejpeg($newImgLayout, $newImgPath, $quality)) {
	    imagedestroy($newImgLayout);
	    return $newImgName;
	} else {
		return false;
	};
}
function save_jpg($data){
	$newImgLayout = imagecreatetruecolor($data["newWidth"], $data["newHeight"]);
	$source = imagecreatefromjpeg($data["file"]);
	imagecopyresized($newImgLayout, $source, 0, 0, 0, 0, $data["newWidth"], $data["newHeight"], $data["orgWidth"], $data["orgHeight"]);
	if (imagejpeg($newImgLayout, $data["imgPath"], $data["quality"])) {
	    return true;
	} else {
		return false;
	};
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    extract($_POST);
    //	change extention jpeg into jpg
    var_dump($_POST);
    //	set cropped image name according to date & time
    $img_name = date("Ymd_His") . ".jpg";
    $imgPath = $compress_img_dir . "IMG_1_" . $img_name;
    
    $isImgCreated = false;
    if (file_exists($compress_img_dir)) {
        // blob method
        //if (isset($action) && $action === "blob") {
            if ($_FILES['cropped_image']) {
                $img_tmp_name = $_FILES["cropped_image"]["tmp_name"];
                //save image in file"
                if (empty($errors)) {
                    $file = $_FILES['cropped_image']['tmp_name'];
                    list($orgWidth, $orgHeight, $type) = getimagesize($file);
                    $nw   = $orgWidth/3;
                    $nh  = $orgHeight/3;
                    
                    if ($type == IMAGETYPE_JPEG) {
                        if($im1 = saveJPEG($file, $orgWidth, $orgHeight, $orgWidth, $orgHeight, time(), $compress_img_dir)){
                            /*if($im2 = saveJPEG($file, $orgWidth, $orgHeight, $nw, $nh, time(), $compress_img_dir)){
	                            echo $im2;
	                            if($im3 = saveJPEG($file, $orgWidth, $orgHeight, 100, 115, time(), $compress_img_dir)){
		                            echo $im3;
	                            }
                            }*/
                            echo $im1;
                            $isImgCreated = true;
                        } else {
                            $isImgCreated = false;
                            echo "\nDid not upload server side compress image.\n";
                        }
                    }
                }
            } else {
                echo 'file not found.';
            }
        //}
    } else {
	    echo 'Folder found.';
    }
    
    //	all array data encode into json for client
    //echo json_encode($data);
}

?>