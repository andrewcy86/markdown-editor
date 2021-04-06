<?php
global $wpdb, $current_user;

$WP_PATH = implode("/", (explode("/", $_SERVER["PHP_SELF"], -5)));

require_once($_SERVER['DOCUMENT_ROOT'].$WP_PATH.'/wp/wp-load.php');

$plugin_dir = dirname(plugin_dir_url( __FILE__ ));

$markdown_path = $_SERVER['DOCUMENT_ROOT'].$WP_PATH.'/app/helptext/content/'.$_GET['page'];

$file_content = file_get_contents($markdown_path);

?>
<link rel="stylesheet" href="<?php echo $plugin_dir; ?>/asset/css/editormd.css" />
<form id="editor_form">
    <input type="submit" value="Update" style="float:right; margin-bottom:2%;">
    
<div id="editor">
    <textarea style="display:none;" id="mdeditor"><?php echo $file_content; ?></textarea>
</div>
</form>
<script src="https://code.jquery.com/jquery-1.11.0.min.js"></script>
<script src="<?php echo $plugin_dir; ?>/asset/editormd.min.js"></script>
<script src="<?php echo $plugin_dir; ?>/asset/languages/en.js"></script>
<script type="text/javascript">
    jQuery(function() {

        var editor = editormd("editor", {
             width  : "100%",
             height : "500px",
             //emoji  : true,
            path   : "<?php echo $plugin_dir; ?>/asset/lib/"
        });

jQuery( "#editor_form" ).submit(function( event ) {
  event.preventDefault();
  //alert( editor.getMarkdown() );

jQuery.post(
   '<?php echo $plugin_dir; ?>/scripts/update_content.php',{
postvarsaction : 'update',
postvarspage : '<?php echo $_GET['page']; ?>',
postvarscontent : editor.getMarkdown()
}, 
   function (response) {
      //if(!alert(response)){
      alert(response);
      location.reload();
      //}
   });
   
   
});

    });
</script>