<?php
/*
Plugin Name: Processing for wordpress
Plugin URI: http://tutorpocessing.com
Description: Processing maanger allow you to simply add and integrate processing sketches to your website.
Version: 1.4
Author: Fabax
Author URI: http://tutoprocessing.com
License: A "Slug" license name e.g. GPL2
*/

class FB_Processing_Post_Type{
	public function __construct(){	
			$this->register_post_type();
		$this->metaboxes();
		$this->helpers();
		$this->changeSketchProperties();
		$this->ajaxHandler();


	}

	public function register_post_type(){
		$args = array(
			'labels' => array(
				'name' => 'Sketches',
				'singular_name' => 'Sketches',
				'add_new' => 'add a new sketch',
				'add_new_item' => 'add a new sketch',
				'edit_item' => 'edit a sketch',
				'new_item' => 'add new sketch',
				'view_item' => 'View sketch',
				'search_items' => 'Search sketch',
				'not_found' => 'No sketch found',
				'not_found_in_trash' => 'No sketch found in trash'
			),
			'query_var' => 'sketches',
			'rewrite' => array(
				'slug' => 'sketches',
			),
			'public' => true,
			'menu_position' => 5,
			'menu_icon' => content_url(). '/plugins/processing4wp/img/icon-grey.png',
			'supports' => array(
				'title',
				'thumbnail',
				'editor'
			)
		);

		register_post_type('fb_sketch', $args);
	}

	// Appearance of the control panel for the sketches
	public function metaboxes(){
		//Metabox pour les infos du sketch ----------------------------
		function fb_add_sketch_options_metabox(){
			add_meta_box('fb_sketch_options','Sketch Options', 'fb_display_sketch_option_form', 'fb_sketch', 'side');
		}

		function fb_save_infos_sketch_options($id){
			if(isset($_POST['fb_sketch_title'])){

			}

			//peristence des données dans le panel d'administration
			if(isset($_POST['fb_sketch_height']) || isset($_POST['fb_sketch_width']) || isset($_POST['fb_sketch_author']) || isset($_POST['fb_sketch_author_website'])){
				update_post_meta($id,'fb_sketch_title',strip_tags($_POST['fb_sketch_title']));
				update_post_meta($id,'fb_sketch_author',strip_tags($_POST['fb_sketch_author']));
				update_post_meta($id,'fb_sketch_author_website',strip_tags($_POST['fb_sketch_author_website']));
				update_post_meta($id,'fb_sketch_height',strip_tags($_POST['fb_sketch_height']));
				update_post_meta($id,'fb_sketch_images',strip_tags($_POST['fb_sketch_images']));
				update_post_meta($id,'fb_sketch_width',strip_tags($_POST['fb_sketch_width']));
				update_post_meta($id,'fb_display_options_checkbox', strip_tags($_POST[ 'fb_display_options_checkbox' ]));
				update_post_meta($id,'fb_jprocessing_checkbox', strip_tags($_POST[ 'fb_jprocessing_checkbox' ]));
				update_post_meta($id,'fb_jprocessing_checkbox_two', strip_tags($_POST[ 'fb_jprocessing_checkbox_two' ]));
				update_post_meta($id,'fb_dowload_checkbox', strip_tags($_POST[ 'fb_dowload_checkbox' ]));
	

				for ($i=1; $i < 11; $i++) { 
					$checkbox = 'fb_display_fields_checkbox'.$i;
					$variable_name = 'variable-name'.$i;
					$variable_min = 'variable-min'.$i;
					$variable_max = 'variable-max'.$i;
					$variable_default = 'variable-default'.$i;

					update_post_meta($id,$checkbox, strip_tags($_POST[ $checkbox ]));
					update_post_meta($id,$variable_name, strip_tags($_POST[ $variable_name ]));
					update_post_meta($id,$variable_min, strip_tags($_POST[ $variable_min ]));
					update_post_meta($id,$variable_max, strip_tags($_POST[ $variable_max ]));
					update_post_meta($id,$variable_default, strip_tags($_POST[ $variable_default ]));
				}
			}

			//upload du sketch
			if(fb_user_can_save($id, 'fb_upload_nonce_field')){

				if(isset($_POST['fb_sketch_title_good']) && 0 < count(strlen(trim($_POST['fb_sketch_title_good'])))){
					$fb_sketch_title_good = stripcslashes(strip_tags($_POST['fb_sketch_title_good']));
					update_post_meta($id,'fb_sketch_title_good',strip_tags($_POST['fb_sketch_title_good']));
				}

				//upload happens here
				$zipFile = $_FILES['fb_zip_file'];
				$nameFolderUrl2  = basename($zipFile['name'], ".zip"); 
				$url1 = get_home_path() . 'wp-content/uploads/sketches/';
				$url2 = get_home_path() . 'wp-content/uploads/sketches/'.$nameFolderUrl2.'/';  
				

				if(isset($zipFile) && ! empty($zipFile)){
					//check if file is a zip
					if(fb_is_valid_zip($zipFile['name'])){
						$response = wp_upload_bits($zipFile['name'],null,file_get_contents($zipFile['tmp_name'])); 
						if(0 == strlen(trim($response['error']))){
							update_post_meta($id,'zip',$response['url']);
							fb_unzip($zipFile, $url1);
							fb_unzip($zipFile, $url2);
							fb_save_zip($zipFile, $url2,$nameFolderUrl2);
						}
					}else{
						update_post_meta($id,'zip','invalid-file-name');
					}
				}
			}

			$maj = true;
			if($maj == true){
				$url = get_home_path() . 'wp-content/uploads/sketches/';
				$title = get_post_meta($id, 'fb_sketch_title', true);
				
				fb_clean_Sketch_version($url,$title,$id);
			}
			
		}


		function fb_display_sketch_option_form($post){
			$meta_element_class = get_post_meta($post->ID, 'fb_sketch_size_options_meta_box', true); //true ensures you get just one value instead of an array
			$html ="";
			$title = get_post_meta($post->ID, 'fb_sketch_title', true);
			$height = get_post_meta($post->ID, 'fb_sketch_height', true);
			$images = get_post_meta($post->ID, 'fb_sketch_images', true);
			$width = get_post_meta($post->ID, 'fb_sketch_width', true);
			$author = get_post_meta($post->ID, 'fb_sketch_author', true);
			$author_website = get_post_meta($post->ID, 'fb_sketch_author_website', true);
			$checkbox_display = get_post_meta( $post->ID );
			$checkbox_jProcessing = get_post_meta( $post->ID );
			$checkbox_passing_function = get_post_meta( $post->ID );

			$html .='
				<input style="margin-bottom:5px;" placeholder="Title (same name as your zip file)" type="text" class="widefat" name="fb_sketch_title" id="fb_sketch_title" value="'.$title.'"/>
				<div id="fb_form_bottom">
				<input style="margin-bottom:5px;" placeholder="Author" type="text" class="widefat" name="fb_sketch_author" id="fb_sketch_author" value="'.$author.'"/>
				<input style="margin-bottom:5px;" placeholder="Author website" type="text" class="widefat" name="fb_sketch_author_website" id="fb_sketch_author_website" value="'.$author_website.'"/>
				<input style="margin-bottom:5px;" placeholder="Canvas Width" type="text" class="widefat" name="fb_sketch_width" id="fb_sketch_width" value="'.$width.'"/>
				<input style="margin-bottom:5px;" placeholder="Canvas Height" type="text" class="widefat" name="fb_sketch_height" id="fb_sketch_height" value="'.$height.'"/>
				<input style="margin-bottom:5px;" placeholder="image names" type="text" class="widefat" name="fb_sketch_images" id="fb_sketch_images" value="'.$images.'"/>';
	
			//display the form
			echo $html;
			?>
			<hr>
			<div style="width:100%;float:left;">	
			<div style="width:50%;float:left;">		 
				<label style="font-size:10px;"><b>Display informations</b></label><br>
		        <label for="fb_display_options_checkbox-radio-one">
		            <input type="radio" name="fb_display_options_checkbox" id="fb_display_options_checkbox-one" value="yes" <?php if ( isset ( $checkbox_display['fb_display_options_checkbox'] ) ) checked( $checkbox_display['fb_display_options_checkbox'][0], 'yes' ); ?>>
		           <label for="checkbox">Yes </label>
		        </label>
		        <label for="fb_display_options_checkbox-two">
		            <input type="radio" name="fb_display_options_checkbox" id="fb_display_options_checkbox-two" value="no" <?php if ( isset ( $checkbox_display['fb_display_options_checkbox'] ) ) checked( $checkbox_display['fb_display_options_checkbox'][0], 'no' ); ?>>
		            <label for="checkbox">No </label>
		        </label>
			</div>

			<div style="width:50%;float:left;">		 
				<label style="font-size:10px;"><b>Enable Responsive</b></label><br>
		        <label for="fb_jprocessing_checkbox-radio-one">
		            <input type="radio" name="fb_jprocessing_checkbox" id="fb_jprocessing_checkbox-one" value="yes" <?php if ( isset ( $checkbox_jProcessing['fb_jprocessing_checkbox'] ) ) checked( $checkbox_jProcessing['fb_jprocessing_checkbox'][0], 'yes' ); ?>>
		           <label for="checkbox">Yes </label>
		        </label>
		        <label for="fb_jprocessing_checkbox-two">
		            <input type="radio" name="fb_jprocessing_checkbox" id="fb_jprocessing_checkbox-two" value="no" <?php if ( isset ( $checkbox_jProcessing['fb_jprocessing_checkbox'] ) ) checked( $checkbox_jProcessing['fb_jprocessing_checkbox'][0], 'no' ); ?>>
		            <label for="checkbox">No </label>
		        </label>
			</div>
			<div style="width:50%;float:left;">		 
				<label style="font-size:10px;"><b>Enable dowload button</b></label><br>
		        <label for="fb_dowload_checkbox-radio-one">
		            <input type="radio" name="fb_dowload_checkbox" id="fb_dowload_checkbox-one" value="yes" <?php if ( isset ( $checkbox_jProcessing['fb_dowload_checkbox'] ) ) checked( $checkbox_jProcessing['fb_dowload_checkbox'][0], 'yes' ); ?>>
		           <label for="checkbox">Yes </label>
		        </label>
		        <label for="fb_dowload_checkbox-two">
		            <input type="radio" name="fb_dowload_checkbox" id="fb_dowload_checkbox-two" value="no" <?php if ( isset ( $checkbox_jProcessing['fb_dowload_checkbox'] ) ) checked( $checkbox_jProcessing['fb_dowload_checkbox'][0], 'no' ); ?>>
		            <label for="checkbox">No </label>
		        </label>

			</div>
				<div style="width:50%;float:left;">		 
				<label style="font-size:10px;"><b>Enable full screen</b></label><br>
		        <label for="fb_jprocessing_checkbox_two-radio-one">
		            <input type="radio" name="fb_jprocessing_checkbox_two" id="fb_jprocessing_checkbox_two-one" value="yes" <?php if ( isset ( $checkbox_jProcessing['fb_jprocessing_checkbox_two'] ) ) checked( $checkbox_jProcessing['fb_jprocessing_checkbox_two'][0], 'yes' ); ?>>
		           <label for="checkbox">Yes </label>
		        </label>
		        <label for="fb_jprocessing_checkbox_two-two">
		            <input type="radio" name="fb_jprocessing_checkbox_two" id="fb_jprocessing_checkbox_two-two" value="no" <?php if ( isset ( $checkbox_jProcessing['fb_jprocessing_checkbox_two'] ) ) checked( $checkbox_jProcessing['fb_jprocessing_checkbox_two'][0], 'no' ); ?>>
		            <label for="checkbox">No </label>
		        </label>
			</div>
		
			</div>
			
			<?php

			wp_nonce_field(plugin_basename(__FILE__), 'fb_upload_nonce_field');
			if(display_uploaded_sketch($title)){
				$html2 .="<p>sketch : <b>".$title."</b> is uploaded</p>";
			}
			
			$html2 .='<input style="margin-top:20px;" type="file" id="fb_zip_file" name="fb_zip_file" value="">';
			$html2 .='<p class="fb_zip_fin_text">Make sure you upload a complete processing project as a zip file</p>';
			$html2 .='<button id="fb_remove_sketch">remove sketch</button></div>';
			
			echo $html2;
		}

		function display_uploaded_sketch($title){
			$bool = false;
			if(is_dir($url)){
				$bool = true;
			}
			return $bool;
		}

		// ADD NEW COLUMN
		function fb_columns_head($defaults) {
		    $defaults['shortcode'] = 'shortcode';
		    return $defaults;
		}
		 
		// SHOW THE FEATURED IMAGE
		function fb_columns_content($column_name, $post_ID) {
		    if ($column_name == 'shortcode') {
		    	$title = get_post_meta($post_ID, 'fb_sketch_title', true);
		    	echo $shortcode = '[processing sketch="'.$title.'"]';
		    }
		}

		// Remove Featured Image Metabox from Custom Post Type Edit Screens
		function remove_image_box() {
		 if ($current_user->user_level < 10){
		   remove_meta_box('postimagediv','fb_sketch','side');
		 }
		}


		add_action('do_meta_boxes', 'remove_image_box');
		//----------------------
		add_filter('manage_posts_columns', 'fb_columns_head');
		add_action('manage_posts_custom_column', 'fb_columns_content', 10, 2);
		
		//All the action thing ----------------------------
		//link metaboxes to the wordpress admin 		
		add_action('add_meta_boxes', 'fb_add_sketch_options_metabox');
		add_action('save_post','fb_save_infos_sketch_options' );
		//fields metabox
	}


	public function helpers(){
	
		function fb_is_valid_zip($filename){
			$path_parts = pathinfo($filename);
			$response = false;

			if('zip' == strtolower($path_parts['extension'])){
				$response = true;
			}
			return $response;
		}
	
		function fb_user_can_save($id, $nonce){
			$isAutoSave = wp_is_post_autosave( $id );
			$isRevision = wp_is_post_revision( $id );
			$isValidNonce = (isset($_POST[$nonce]) && wp_verify_nonce($_POST[$nonce],plugin_basename(__FILE__ )));
			return ! ($isAutoSave || $isRevision) && $isValidNonce;
		}

		function fb_unzip($zipFile,$newFolderLocation){
			$fileToUnzip = $zipFile['tmp_name'];
			$zip = new ZipArchive;
			$res = $zip->open($fileToUnzip);

			if(!is_int($res)){
    			$zip->extractTo($newFolderLocation);
		    	$zip->close();
		    	$path = wp_upload_dir();
		    	$path = $path['path'];
		    	$pathToZip = $path.'/'.$zipFile['name'];

		    	if(file_exists($pathToZip)){
				    unlink($pathToZip);
				}
			} 
		
		}

		function fb_save_zip($zipFile,$newFolderLocation,$name){
			$fileToUnzip = $zipFile['tmp_name'];
			copy($fileToUnzip, $newFolderLocation.$name.'.zip');
		}

	}

//--------------------------changeSketchProperties--------------------------//
//--------------------------------------------------------------------------//

	public function changeSketchProperties(){
		
		function fb_clean_Sketch_version($url,$title,$id){
			//variables form form 
			$images = get_post_meta($id, 'fb_sketch_images', true);
			$checkbox = get_post_meta( $id );
			$jprocessing_bool = $checkbox['fb_jprocessing_checkbox'][0];
			$jprocessing_bool_fc = $checkbox['fb_jprocessing_checkbox_two'][0];
			//$passing_bool = $checkbox['fb_passing_function_checkbox'][0];
			
			//Path to forlders and files
			$currentFolder = $url.$title.'/';
			$currentFile = $url.$title.'/'.$title.'.pde';
			$originalFile = $url.$title.'/'.$title.'/'.$title.'.pde';
			
			//stop sketch form updatign is there is no sketch 
			if($title != ""){
				if(is_dir($url.$title)){
					unlink($currentFile);
					copy($originalFile, $currentFile);

					//image replacement
					if($images != ""){
						replaceImagesPaths($title,$currentFile,$images);
					}
					//activate responsive
					if($jprocessing_bool == "yes"){
						$replacement = "jProcessingJS(this);\n";
						addJprocessing($currentFile, $replacement);
					}

					if($jprocessing_bool_fc == "yes"){
						$replacement = "jProcessingJS(this, {fullscreen:true});\n";
						addJprocessing($currentFile, $replacement);
					}
				}
			}
		}

		function addJprocessing($currentFile, $replacement){
			$pattern = "(size\((.*?)\)\s*?;)";
			$type = "total";
			replaceLine($currentFile,$pattern,$replacement,$type);
		}

		function replaceImagesPaths($sketchTitle, $currentFile,$imageString){
			$imageNames =  (explode(",",$imageString));
			$imageNumber = sizeof($imageNames);

			$type = "partial";
			for ($i=0; $i < $imageNumber; $i++) { 
				$replacement = 'wp-content/uploads/sketches/'.$sketchTitle.'/'.$imageNames[$i];
				replaceLine($currentFile,$imageNames[$i],$replacement, $type);
			}
		}

		function replaceLine($file,$lineToReplace,$replacement, $type){
			$reading = fopen($file, 'r');
			$writing = fopen('myfile.tmp', 'w');

			$replaced = false;

			while (!feof($reading)) {
			  $line = fgets($reading);
			  if (stristr($line,$lineToReplace)) {
			  	if($type == "partial"){	
			  		$line = str_replace($lineToReplace, $replacement, $line);
			  		$replaced = true;
			  	}
			  }else if ( preg_match($lineToReplace,$line)) {
			  	if($type == "total"){
			  		$line = preg_replace($lineToReplace, $replacement, $line);
			    	$replaced = true;
			  	}
			  }
			  fputs($writing, $line);
			}

			fclose($reading); fclose($writing);
			// might as well not overwrite the file if we didn't replace anything
			if ($replaced) 
			{
			  rename('myfile.tmp', $file);
			} else {
			  unlink('myfile.tmp');
			}
		}

	}

	public function ajaxHandler(){

		if($_REQUEST['callfunction'] == "removeSketch"){
	        $removeSketch = $_REQUEST['removeSketch'];
	        if ( $removeSketch == 'yes' ) {
	        	$upload_dir = wp_upload_dir(); 
				$dirToDelete = $upload_dir['basedir'].'/sketches/'.$_REQUEST['sketchTitle'].'/';
	        	if (is_dir($dirToDelete)) {
				    system("rm -rf ".escapeshellarg($dirToDelete));
				    echo 'processing sketch '.$_REQUEST['sketchTitle'].' deleted';
				}
	        }
		}

		if($_REQUEST['callfunction'] == "getFolderDir"){
	        	$upload_dir = wp_upload_dir(); 
				$dirToCheck = $upload_dir['basedir'].'/sketches/'.$_REQUEST['sketchTitle'].'/';
	        	if (is_dir($dirToCheck)) {
				    echo 'true';
				}else{
					 echo 'false';
				}
		}
	}
}

function fb_add_admin_script(){
	wp_enqueue_script('fb_admin', plugins_url('processing4wp/js/admin.js'));
	wp_enqueue_script('fb_ajax', plugins_url('processing4wp/js/ajaxHandler.js'));
}

function fb_init(){
	new FB_Processing_Post_Type();
	include dirname(__FILE__) . '/processing-for-wordpress-shortcode.php';
	include dirname(__FILE__) . '/add-processing.php';
}

add_action('admin_enqueue_scripts','fb_add_admin_script');
add_action('init','fb_init');


?>