<?php 

function fb_option_page(){
	ob_start() ?>
		<div class="wrap">
			<h1>Processing manager options</h1>
			<h2> how to use it </h2>
		</div>

	<?php
	echo ob_get_clean();
}

function fb_add_options_link(){
	add_options_page("processing manager options","Processing Manager","manage_options","processing-manager-option","fb_option_page" );

}

add_action('admin_menu','fb_add_options_link' );
?>