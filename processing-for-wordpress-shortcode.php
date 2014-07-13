<?php

add_shortcode('processing', function($args){
	$sketch = $args['sketch'];
	$query = new WP_Query(
		array(
			'post_type' => 'fb_sketch', 
			'orderby' => 'title',
			'name' => $sketch
		)
	);




	if($query->have_posts()){
		while ($query->have_posts()) {
			$query->the_post();
			$post = get_post_custom($query->ID);

			if($post['fb_sketch_title'][0] == $sketch){
				//Variables that affect the canvas
				$title = $post['fb_sketch_title'][0];
				$width = $post['fb_sketch_width'][0];
				$height = $post['fb_sketch_height'][0];
				$author = $post['fb_sketch_author'][0];
				$author_website = $post['fb_sketch_author_website'][0];
				$display_options = $post['fb_display_options_checkbox'][0];
				$dowload = $post['fb_dowload_checkbox'][0];
				$sketch_title = get_the_title($query->ID);
				$sketch_content = get_the_content();
				$custom_css_bool = $post['fb_custom_css_checkbox'][0];
				$custom_css = $post['fb_custom_css_text_area'][0];
			
				
				$upload_dir_path = wp_upload_dir(); 
				$upload_dir = $upload_dir_path['basedir'];
				
				$fb_file_paths = $upload_dir_path['basedir'].'/sketches/'.$title.'/';
				$fb_file_url = $upload_dir_path['baseurl'].'/sketches/'.$title.'/';

				$fb_file_url_final = '';

				if(file_exists($fb_file_paths)){
					
					$dir = opendir($fb_file_paths); 

					while($file = readdir($dir)) {
						if($file != '.' && $file != '..' && !is_dir($fb_file_paths.$file))
						{
							$ext = pathinfo($file, PATHINFO_EXTENSION);

			        		if($ext == 'pde'){
			        			$fb_file_url_final .= $fb_file_url . $file . " ";
			        		}
						}
					}

				}

				$output .= '<div style="width:'.$width.'; "><canvas class="fb_sketch" id="fb_'.$sketch.'" data-processing-sources="'.$fb_file_url_final.'" style=" width:'.$width.';height:'.$height.';"></canvas>';
				if($display_options == "yes"){	
					if(!$sketch_content == ""){
						$output .='<p style="margin:auto;text-align:center">"'.$sketch_content.'"</p>';
					}
							
					$output .='<div id="fb_'.$sketch.'_informations"><p id="fb_'.$sketch.'_informations_title">'.$sketch.'</p><p id="fb_'.$sketch.'_informations_author" > developed by : <a target="blank" href="'.$author_website.'">'.$author.'</a></p></div>';
					
					if($dowload == "yes"){
						$output .='<a id="fb_'.$sketch.'_dowload" href="'.$fb_file_url.$title.'.zip" target="_blank">Download</a></div></p>';
					}else{
						$output .='</p>';
					}
				}else{
					if($dowload == "yes"){
						$output .='<a id="fb_'.$sketch.'_dowload" href="'.$fb_file_url.$title.'.zip" target="_blank">Download</a>';
					}
				}

				if($custom_css_bool == "yes"){
					$output .= '<style>'.$custom_css.'</style>';
				}

				
			}
			// else{
			// 	$output =  "this sketch does not exist";
			// }
		}
	}

return $output;
});

