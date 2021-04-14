<?php
/**
 * Markdown editor throws a editing interface on top of Pico.
 * Used to control help text in the next generation ezDesktop/ezEmail interface
 *
 * @package EPA Markdown Editor Plugin
 * @author ERMD
 * @license GPL-2.0+
 * @link https://patt.epa.gov
 * @copyright 2021 US EPA
 *
 *            @wordpress-plugin
 *            Plugin Name: EPA Markdown Editor Plugin
 *            Plugin URI: https://patt.epa.gov
 *            Description:  Markdown editor throws a editing interface on top of Pico. Used to control help text in the next generation ezDesktop/ezEmail interface.
 *            Version: 1.0
 *            Author: ERMD
 *            Author URI: https://epa.gov
 *            Text Domain: markdown-editor
 *            Contributors: Andrew Yuen, Stephanie Schouw
 *            License: GPL-2.0+
 *            License URI: http://www.gnu.org/licenses/gpl-2.0.txt
 */
 
/**
 * Adding Submenu under Settings Tab
 *
 * @since 1.0
 */

function markdown_editor_add_menu() {
	add_submenu_page ( "options-general.php", "Markdown Editor", "Markdown Editor", "manage_options", "markdown-editor", "markdown_editor_page" );
}
add_action ( "admin_menu", "markdown_editor_add_menu" );

$plugin_dir = plugin_dir_url( __FILE__ );

wp_register_style('editor-style', $plugin_dir . 'asset/css/editormd.css' );

wp_enqueue_style('editor-style');

/**
 * Setting Page Options
 * - add setting page
 * - save setting page
 *
 * @since 1.0
 */
function markdown_editor_page() {
$plugin_dir = plugin_dir_url( __FILE__ );

	?>

<style>

.editormd-preview-close-btn {
    display: none;
}

#right ul {
    list-style: initial !important;
}

#left ul {
    list-style: none !important;
}

.editormd-form label {
    float: left !important;
    display: block !important;
    width: 65px !important;
    text-align: left !important;
    padding: 7px 0 15px 5px !important;
    margin: 0 0 2px !important;
    font-weight: normal !important;
}

#left {
  float: left;
  width: 10%;
}

#right {
  overflow: hidden;
}
</style>
<div class="wrap">
    
<h1>Markdown Files <span style="color:#026440; cursor: pointer;" onclick="location.reload();"><i class="fas fa-plus-circle"></i></span></h1>

<?php

$WP_PATH = implode("/", (explode("/", $_SERVER["PHP_SELF"], -4)));

//Temporary addition of web for dev environment
$path = $_SERVER['DOCUMENT_ROOT'].$WP_PATH.'/app/helptext/content/pages/';

$files = scandir($path);
?>
  <div id="left">
<ul>
<li><i class='fas fa-folder'></i> <a href='#' id='edit-folder-index' class='edit_page'><strong>index.md</strong></a></li>
</ul>
<ul style="padding-left: 15px;">
<?php
$i = 0;
foreach ($files as &$value) {
$i++;
if(strpos($value, '.md') !== false){
    if($value == 'index.md'){
    echo "<li><i class='fas fa-folder-open'></i> <a href='#' id='edit-".$i."' class='edit_page'>".$value."</a></li>";
    } else {
    echo "<li><i class='fas fa-folder-open'></i> <a href='#' id='edit-".$i."' class='edit_page'>".$value."</a> <span style='color:#d63638; cursor: pointer;' id='delete-".$i."' class='delete_page' title='".$value."'><i class='fas fa-trash-alt'></i></span></li>";
    }
}

}
?>
</ul>
  </div>
  <div id="right">

<form id="create_form">
    <input type="submit" value="Create New" style="float:right;">
  <label for="fname">Filename:</label>
  <input type="text" id="fname" name="fname"><br /><br />
  
<div id="create_new">
    <textarea style="display:none;" id="mdeditor"></textarea>
</div>

</form>

  </div>

</div>

<script src="<?php echo $plugin_dir; ?>/asset/editormd.min.js"></script>
<script src="<?php echo $plugin_dir; ?>/asset/languages/en.js"></script>

<script type="text/javascript">
    jQuery(function() {

jQuery('selectorForYourElement').css('display', 'none');
 
        var editor = editormd("create_new", {
             width  : "100%",
             height : "500px",
             //emoji  : true,  
            path   : "<?php echo $plugin_dir; ?>/asset/lib/"
        });
        
jQuery(".edit_page").click(function() {

var id = jQuery(this).attr('id');

if(id == 'edit-folder-index') {
jQuery("#right").load("<?php echo $plugin_dir; ?>scripts/editor.php?type=folderindex&page=index.md"); 
} else {
var n = id.replace("edit",'');
var edit_page_val = jQuery('#edit'+n).text();
jQuery("#right").load("<?php echo $plugin_dir; ?>scripts/editor.php?page="+edit_page_val);
}

});

jQuery(".delete_page").click(function() {

var id = jQuery(this).attr('id');
var n = id.replace("delete",'');

var delete_page_val = jQuery('#edit'+n).text();


jQuery.post(
   '<?php echo $plugin_dir; ?>/scripts/update_content.php',{
postvarsaction : 'delete',
postvarspage : delete_page_val
}, 
   function (response) {
      //if(!alert(response)){
      alert(response);
      location.reload();
      //}
   });
   
});


jQuery("#create_form").submit(function( event ) {
  event.preventDefault();
  //alert( editor.getMarkdown() );

var fname = jQuery('#fname').val();
if( fname == '' || fname.includes('.md') || fname == 'index' ) {
    alert('Filename cannot be empty, contain the extension ".md", or use the name "index"');
}
else{
    jQuery.post(
   '<?php echo $plugin_dir; ?>/scripts/update_content.php',{
postvarsaction : 'create',
postvarsfname : fname,
postvarscontent : editor.getMarkdown()
}, 
   function (response) {
      //if(!alert(response)){
      alert(response);
      location.reload();
      //}
   });
}
   
});
    });

</script>

<?php
}