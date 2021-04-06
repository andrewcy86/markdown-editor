<?php
global $wpdb, $current_user;

$WP_PATH = implode("/", (explode("/", $_SERVER["PHP_SELF"], -5)));

require_once($_SERVER['DOCUMENT_ROOT'].$WP_PATH.'/wp/wp-load.php');

$markdown_path = $_SERVER['DOCUMENT_ROOT'].$WP_PATH.'/app/helptext/content/'.$_POST['postvarspage'];

if(!empty($_POST['postvarsaction']) || !empty($_POST['postvarscontent'])){

//echo $_POST['postvarsaction'];
//echo $_POST['postvarscontent'];

if($_POST['postvarsaction'] == 'create')
{
 $file_name=$_POST['postvarsfname'];
 $folder= $_SERVER['DOCUMENT_ROOT'].$WP_PATH.'/app/helptext/content/';
 $ext=".md";
 $file_name=$folder."".$file_name."".$ext;
 $create_file = fopen($file_name, 'w');
 $write_text=$_POST['postvarscontent'];
 fwrite($create_file, stripslashes($write_text));
 fclose($create_file);
  echo 'Markdown file: '.$_POST['postvarsfname'].$ext.' created.';
}

if($_POST['postvarsaction'] == 'update')
{

 $write_text=$_POST['postvarscontent'];

 $edit_file = fopen($markdown_path, 'w');
	
 fwrite($edit_file, stripslashes($write_text));
 fclose($edit_file);
 echo 'Markdown file: '.$_POST['postvarspage'].' updated.';
}

if($_POST['postvarsaction'] == 'delete')
{
 $folder= $_SERVER['DOCUMENT_ROOT'].$WP_PATH.'/app/helptext/content/';
 $file_name=$folder."".$_POST['postvarspage'];
 unlink($file_name);
 echo 'Markdown file: '.$_POST['postvarspage'].' deleted.';
}

} else {
    echo "Update is not successful.";
}
?>