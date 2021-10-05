<?php
header('Content-Type: application/json');

$WP_PATH = implode("/", (explode("/", $_SERVER["PHP_SELF"], -6)));

$dir = $_SERVER['DOCUMENT_ROOT'].$WP_PATH.'/app/helptext/content/pages';

$list = array(); //main array

if(is_dir($dir)){
    if($dh = opendir($dir)){
        while(($file = readdir($dh)) != false){

            if($file == "." or $file == ".."){
                //...
            } else { //create object with two fields
                $list3 = array(
                'file' => $file);
                array_push($list, $list3);
            }
        }
    }

    $return_array = array('files'=> $list);

    echo json_encode($return_array);
}