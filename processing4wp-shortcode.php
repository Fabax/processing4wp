<?php

add_shortcode('processing', function($args){
	$sketch = $args['sketch'];
	$query = new WP_Query(
		array(
			'post_type' => 'fb_sketch', 
			'orderby' => 'title'
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

				$sketch_title = get_the_title($query->ID);
				$sketch_content = get_the_content();
				
				$fb_file_paths = 'wp-content/uploads/sketches/'.$title.'/';

				if (is_dir($fb_file_paths)) {
					
				    if ($dh = opendir($fb_file_paths)) {   
				        while (($file = readdir($dh)) !== false) {
				        	if ($file != "." && $file != ".." ) {
				        		$ext = pathinfo($file, PATHINFO_EXTENSION);
				        		if($ext == 'pde'){
				        			$sketch_path .= $fb_file_paths.$file . " ";
				        		}
				        	}
				        }
				        closedir($dh);
				    }
				}

				$output .= '<canvas id="'.$sketch_title.'" data-processing-sources="'.$sketch_path.'" style=" position:relative;float:left; width:'.$width.';height:'.$height.';"></canvas>';
				if($display_options == "yes"){			
					$output .='<p> <b>'.$sketch_title.'</b> developed by : <a target="blank" href="'.$author_website.'">'.$author.'</a></p>';
					$output .='<p><b>Description </b>: '.$sketch_content.'</p>';
				}
			
			}
		}
	}

return $output;
});

