<?php
/*
Plugin Name: 	Sticky Notice
Version: 		1.0
Release Date:	2 August, 2013
Plugin URI: 	http://eliasmamun.portbliss.org/products/wordpress/plugins/sticky_notice
Description: 	This adds a fixed semi-transparent highlighted text box in your site with your own text.
				This may be helpful if you need to add any argent notice (like - The site is under maintenance).
				You can edit the text with the WordPress content editor and place the box in any corner or at 
				the center of the page. You can also choose color-theme from four options and set the font-size
				as your choice. 
				

Author: 		A. K. M. Elias Mamun
Author URI: 	http://eliasmamun.portbliss.org/

Copyright 2013	A. K. M. Elias Mamun  (email : elias.mamun@gmail.com)

				This program is free software; you can redistribute it and/or modify
				it under the terms of the GNU General Public License, version 2, as 
				published by the Free Software Foundation.
				
				This program is distributed in the hope that it will be useful,
				but WITHOUT ANY WARRANTY; without even the implied warranty of
				MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
				GNU General Public License for more details.
				
				You should have received a copy of the GNU General Public License
				along with this program; if not, write to the Free Software
				Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

/********** Iinitial values while the plugin is avtivated **********/
function activate_trs_sticky_notice()
{
	$check_install = get_option('trs_sticky_notice_text');
	if($check_install==''){
	update_option('trs_sticky_notice_text','Hi, Add your own text here ...');
	update_option('trs_sticky_notice_theme','orange');
	update_option('trs_sticky_notice_position','bottom-right');
	update_option('trs_sticky_notice_font_size',140);
	error_log('WordPress Plugin "Sticky Notice" (Version 1.0) activated');}
	
}
register_activation_hook(__FILE__,'activate_trs_sticky_notice');

/********** Deactivation Log **********/
function deactivate_trs_sticky_notice()
{
	error_log('WordPress Plugin "Sticky Notice" (Version 1.0) deactivated');
}
register_deactivation_hook(__FILE__,'deactivate_trs_sticky_notice');


/********** Class : Sticky Note - Creating Front End Interface **********/
class trs_sticky_notice
{
	public function __construct($class,$position,$font_size)
	{
		if($position=='center')
		{
			?>
            <script type="text/javascript">
			jQuery(document).ready(function(e) {
				var inw = window.innerWidth;
				var inh = window.innerHeight;
				var w = jQuery('.trs_sticky_notice_text_box').width();
				var h = jQuery('.trs_sticky_notice_text_box').height();
				var right = (inw-w)/2;
				var top = (inh-h)/2;
				//alert(right+'='+top);
                jQuery('.trs_sticky_notice_text_box').css({'top':top+'px','right':right+'px'});
            });
			</script>
            <?php
		}
		else
		{
			$positions = explode('-',$position);
		}
		?>
        <style type="text/css">
		.trs_sticky_notice_text_box{position:fixed; <?php if(isset($positions[0])&&isset($positions[1])){ echo $positions[0]; ?>:2.5em; <?php echo $positions[1];} else { ?> top:2.5em; right:2.5em; <?php } ?>:2.5em; z-index:50000; color:#000; cursor:default;}
		.trs_sticky_notice_text_box_inner{border-radius:10px; padding:1em 1.2em; border:2px solid #f00; <?php if(isset($font_size)){ echo 'font-size:'.$font_size; }?>%;  background:url('<?php echo plugins_url( 'red.png' , __FILE__ ); ?>');}
		.trs_sticky_notice_text_box_inner h1,.trs_sticky_notice_text_box_inner h2,.trs_sticky_notice_text_box_inner h3,.trs_sticky_notice_text_box_inner h4,
		.trs_sticky_notice_text_box_inner h5,.trs_sticky_notice_text_box_inner h6{font-size:inherit;}
		.trs_sticky_notice_text_box_inner p{margin:0; padding:0;}
		.trs_sticky_notice_text_box .red{border:2px solid #f00; background:url('<?php echo plugins_url( 'red.png' , __FILE__ ); ?>');}
		.trs_sticky_notice_text_box .blue{border:2px solid #09f; background:url('<?php echo plugins_url( 'blue.png' , __FILE__ ); ?>');}
		.trs_sticky_notice_text_box .green{border:2px solid #090; background:url('<?php echo plugins_url( 'green.png' , __FILE__ ); ?>');}
		.trs_sticky_notice_text_box .orange{border:2px solid #f90; background:url('<?php echo plugins_url( 'orange.png' , __FILE__ ); ?>');}		
		</style>
        <div class="trs_sticky_notice_text_box">
            <div class="trs_sticky_notice_text_box_inner <?php echo $class; ?>">
            	<?php echo str_replace('\\"','"',get_option('trs_sticky_notice_text')); ?>
            </div>
        </div>
		<?php
	}
}

/********** Function to create object **********/
function trs_sticky_notice()
{
	$trs_sticky_notice_object = new trs_sticky_notice(get_option(trs_sticky_notice_theme),get_option(trs_sticky_notice_position),get_option(trs_sticky_notice_font_size));
}
add_action('wp_footer', 'trs_sticky_notice');

/********** Creating Admin Panel Interface **********/
function trs_sticky_notice_edit(){
	if($_POST['trs-sticky-notice-hidden']=='kjasashd')
	{
		update_option('trs_sticky_notice_text',$_POST['trs-sticky-notice-textarea']);
		update_option('trs_sticky_notice_theme',$_POST['trs_sticky_notice_check_box']);
		update_option('trs_sticky_notice_position',$_POST['trs_sticky_notice_position_check_box']);
		update_option('trs_sticky_notice_font_size',$_POST['trs_sticky_notice_font_size']);
		?>
        <script type="text/javascript">
		var pathname = window.location.href;
		window.location = pathname;
		</script>
        <?php
	}
	?>    
    <div class="wrap">
    <h2>Sticky Notice Settings</h2>
    <form action="" method="post" id="trs-sticky-notice-form" >
    <p style="font-size:140%; font-weight:bold; margin-bottom:0;"><label for="trs-sticky-notice-textarea">Text :</label></p>
    <p style="margin-top:0.2em;">   
    <?php
	$settings = array('media_buttons' => false,'textarea_rows'=>3,'wpautop'=>false);
	$content = str_replace('\\"','"',get_option('trs_sticky_notice_text'));
	wp_editor($content, 'trs-sticky-notice-textarea', $settings); ?> 
    </p>
    <p style="font-size:140%; font-weight:bold; margin-bottom:0;">Font-size:</p>
    <p style="margin-top:0.2em;">
    	<input style="width:4em; text-align:center;" maxlength="3" type="text" id="trs_sticky_notice_font_size" name="trs_sticky_notice_font_size" value="<?php echo get_option(trs_sticky_notice_font_size); ?>" />%
    </p>    
    <p style="font-size:140%; font-weight:bold; margin-bottom:0;">Theme:</p>
    <script type="text/javascript">
	jQuery(document).ready(function(e) {
		var border_color = jQuery('#trs_sticky_notice_font_size').css('border-color');
		var bg_color = jQuery('#trs_sticky_notice_font_size').css('background-color');
        jQuery('#trs-sticky-notice-form input[type="radio"]').each(function(index, element) {
            if((jQuery(this).val()=='<?php echo get_option(trs_sticky_notice_theme); ?>')||(jQuery(this).val()=='<?php echo get_option(trs_sticky_notice_position); ?>'))
			{
				jQuery(this).prop('checked', true);
			}
        });
		jQuery('#trs_sticky_notice_form_submit').click(function(e) {
            if(isNaN(jQuery('#trs_sticky_notice_font_size').val()))
			{
				alert('Font-size should be a number (percent)');
				e.preventDefault();
				jQuery('#trs_sticky_notice_font_size').css({'background':'#f99','border-color':'#f00'});
			}
        });
		jQuery('#trs_sticky_notice_font_size').keydown(function(e) {
			if(isNaN(jQuery(this).val())){
			jQuery(this).css({'background':'#f99','border-color':'#f00'});
			}
			else
			{
				jQuery(this).css({'background':bg_color,'border-color':border_color});
			}
        });
		jQuery('#trs_sticky_notice_font_size').change(function(e) {
			if(isNaN(jQuery(this).val())){
			jQuery(this).css({'background':'#f99','border-color':'#f00'});
			}
			else
			{
				jQuery(this).css({'background':bg_color,'border-color':border_color});
			}
        });
		jQuery('#trs_sticky_notice_font_size').click(function(e) {
			if(isNaN(jQuery(this).val())){
			jQuery(this).css({'background':'#f99','border-color':'#f00'});
			}
			else
			{
				jQuery(this).css({'background':bg_color,'border-color':border_color});
			}
        });
    });	
	</script>
    <p style="margin-top:0.2em;">
    <?php $colors = array('red','blue','green','orange'); 
	foreach($colors as $color) { ?>
    <input type="radio" value="<?php echo $color; ?>" id="<?php echo $color; ?>-check-box" name="trs_sticky_notice_check_box" />
    <label style="text-transform:capitalize;" for="<?php echo $color; ?>-check-box"><?php echo $color; ?></label><br />
    <?php } ?>
    </p>
    
    <p style="font-size:140%; font-weight:bold; margin-bottom:0;">Position:</p>
    <p style="margin-top:0.2em;">
    <?php
    $positions = array('top-left','top-right','bottom-left','bottom-right','center'); 
	$name = array('Top-Left','Top-Right','Bottom-Left','Bottom-Right','Center');
	$i = 0;
	foreach($positions as $position) { ?>
    <input type="radio" value="<?php echo $position; ?>" id="trs_sticky_notice_<?php echo $position; ?>_check_box" name="trs_sticky_notice_position_check_box" />
    <label style="text-transform:capitalize;" for="trs_sticky_notice_<?php echo $position; ?>_check_box"><?php echo $name[$i]; ?></label><br />
    <?php
	$i++; } ?>    
    </p>    
    <input type="hidden" value="kjasashd" name="trs-sticky-notice-hidden" /><br />
    <input id="trs_sticky_notice_form_submit" type="submit" class="button button-primary" name="submit" value="Save" />    
    </form>
    </div>    
    <?php	
}
function trs_sticky_notice_menu()
{
	add_menu_page('Sticky Notice Settings','Sticky Notice','manage_options','trs_sticky_notice','trs_sticky_notice_edit');
}
add_action('admin_menu','trs_sticky_notice_menu');
/********** End **********/

