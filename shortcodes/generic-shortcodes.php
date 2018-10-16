<?php

add_shortcode( 'case_study_steps_finish', 'case_study_steps_finish_func' );
function case_study_steps_finish_func ( $atts ) {
	
	$acf_get_fields = acf_get_fields( 'group_5ae729d53fd82' );
	
	$out = '<ul id="acf_steps">';
	
	$i = 0;
	foreach ( $acf_get_fields as $group_field ) {
		
		$out .= '
			<li class="form-step removecursor">
				
					<span class="stepliwrap">
						<span class="step_button_text">'. $group_field['label'] .'</span> 
						<span class="stepicon">
							<i class="fa fa-times-circle-o fa-stack-2x fai"></i>
							<span class="fa-stack fa-lg fai ellipsis">
								<i class="fa fa-circle-o fa-stack-2x"></i>
								<i class="fa fa-ellipsis-h fa-stack-1x"></i>
							</span>
							<i class="fa fai fa-check-circle-o fa-stack-2x"></i>
							<i class="fa fai fa-clock-o fa-stack-2x"></i>
						</span>
					</span>
		';
		
		if ( $i == 3 ) {
			$out .= '
				<span class="nextdown"><i class="fa fa-ellipsis-v" aria-hidden="true"></i></span>
			</li>
			';
			break;
		} else {
			$i++;
			$out .= '
				<span class="nextdown"><i class="fa fa-chevron-down" aria-hidden="true"></i></span>
			</li>
			';
		}
		
		
	}
	
	$out .= '
		<li class="form-step last_step active">
			<span class="stepliwrap">
				<span class="step_button_text">Finish</span> 
				<span class="stepicon">
					<span class="fa-stack fa-lg fai flag">
						<i class="fa fa-circle-o fa-stack-2x"></i>
						<i class="fa fa-flag-checkered fa-stack-1x"></i>
					</span>
				</span>
			</span>
		</li>
	
	';
	$out .= '</ul>';
	
	return $out;
	
}


add_shortcode( 'innovation_name', 'innovation_name_func' );
function innovation_name_func ( $atts ) {
	$a = shortcode_atts( array(
        'title' => '',
    ), $atts );
	
	return $a['title'];
	
}

add_shortcode( 'signup_link', 'signup_link_func' );
function signup_link_func ( $atts ) {
	$a = shortcode_atts( array(
        'text' => '',
    ), $atts );
	
	return '<a href="'. home_url( '/register/' ) .'" title="'. $a['text'] .'">'. $a['text'] .'</a>';
	
}
add_shortcode( 'joinorlogin', 'joinorlogin_func' );
function joinorlogin_func ( $atts ) {
	
	ob_start();
	?>
	
	<h4><?php echo __( 'Join our community:', 'opsi' ); ?></h4>
	<p>
		<?php echo __( 'It only takes a few minutes to complete the form and share your project.', 'opsi'); ?>
	</p>
	<div class="row">
		<div class="col-xs-6">
			<a href="<?php echo get_permalink( get_page_by_path( 'login' ) ); ?>" title="Log In" class="button btn btn-default btn-sm btn-block ">
				<?php echo __( 'Log in', 'opsi' ); ?> <i class="fa fa-sign-in" aria-hidden="true"></i>
			</a>
		</div>
		<div class="col-xs-6">
			<a href="<?php echo get_permalink( get_page_by_path( 'register' ) ); ?>" title="Register" class="button btn btn-success btn-sm btn-block">
				<?php echo __( 'Join', 'opsi' ); ?> <i class="fa fa-user-plus" aria-hidden="true"></i>
			</a>
		</div>
	</div>
	
	<?php
	$out = ob_get_contents();
	ob_end_clean();
	
	return $out;
	
}