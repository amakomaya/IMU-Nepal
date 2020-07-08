<?php
namespace App\Helpers;

use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Auth;
use Yagiten\Nepalicalendar\Calendar;

Class BackendHelper {
	public static function resizeAndCropImage($max_width, $max_height, $source_file, $dst_dir, $quality = 80){
	    $imgsize = getimagesize($source_file);
	    $width = $imgsize[0];
	    $height = $imgsize[1];
	    $mime = $imgsize['mime'];
	 
	    switch($mime){
	        case 'image/gif':
	            $image_create = "imagecreatefromgif";
	            $image = "imagegif";
	            break;
	 
	        case 'image/png':
	            $image_create = "imagecreatefrompng";
	            $image = "imagepng";
	            $quality = 7;
	            break;
	 
	        case 'image/jpeg':
	            $image_create = "imagecreatefromjpeg";
	            $image = "imagejpeg";
	            $quality = 80;
	            break;
	 
	        default:
	            return false;
	            break;
	    }
	     
	    $dst_img = imagecreatetruecolor($max_width, $max_height);
	    imagealphablending($dst_img, false);
		imagesavealpha($dst_img, true);
	    $src_img = $image_create($source_file);
	     
	    $width_new = $height * $max_width / $max_height;
	    $height_new = $width * $max_height / $max_width;
	    //if the new width is greater than the actual width of the image, then the height is too large and the rest cut off, or vice versa
	    if($width_new > $width){
	        //cut point by height
	        $h_point = (($height - $height_new) / 2);
	        //copy image
	        imagecopyresampled($dst_img, $src_img, 0, 0, 0, $h_point, $max_width, $max_height, $width, $height_new);
	    }else{
	        //cut point by width
	        $w_point = (($width - $width_new) / 2);
	        imagecopyresampled($dst_img, $src_img, 0, 0, $w_point, 0, $max_width, $max_height, $width_new, $height);
	    }
	     
	    $image($dst_img, $dst_dir, $quality);
	 
	    if($dst_img)imagedestroy($dst_img);
	    if($src_img)imagedestroy($src_img);
	}

	public function arraysAsKeyValueCount($records){
		foreach ($records as $key => $value) {
            $count[$key] = count($value);
        }
        return $count;
	}

	public function fiscalYear(){
		$currentDate = date('Y-m-d');
        $currentNepliDate = ViewHelper::convertEnglishToNepali($currentDate);
        list($currentNepaliYear, $curretnNepaliMonth, $currentNepliDate) = explode('-', $currentNepliDate);
        if($curretnNepaliMonth=="01" || $curretnNepaliMonth=="02" || $curretnNepaliMonth=="03"){
                $curentFiscalYearEndAt = $currentNepaliYear-1;
            }else{
                $curentFiscalYearEndAt = $currentNepaliYear;
            }
        
        $nepaliYearList = array();
        $nepaliYear = 2073; 
        while ($nepaliYear <= $curentFiscalYearEndAt) {

            $substrFiscalYear = substr($nepaliYear, 2, 4);
            $substrNextFiscalYear = $substrFiscalYear+1;
            $nepaliYearList[$nepaliYear] = $substrFiscalYear.'/'.$substrNextFiscalYear;
            $nepaliYear++;
        } 

        return $nepaliYearList;
	}
}