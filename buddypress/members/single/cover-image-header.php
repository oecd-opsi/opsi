<?php
/**
 * BuddyPress - Users Cover Image Header
 *
 * @package BuddyPress
 * @subpackage bp-legacy
 */

?>

<?php

/**
 * Fires before the display of a member's header.
 *
 * @since 1.2.0
 */
do_action( 'bp_before_member_header' ); ?>

<div id="cover-image-container">
	<a id="header-cover-image" href="<?php bp_displayed_user_link(); ?>"></a>

	<div id="item-header-cover-image">
		<div id="item-header-avatar">
			<a href="<?php bp_displayed_user_link(); ?>">

				<?php bp_displayed_user_avatar( 'type=full' ); ?>

			</a>
      
      <?php if (bp_displayed_user_id() == get_current_user_id() ) { ?>
      <div class="avatar_actions profile_actions">
      <a href="<?php bp_members_component_link( 'profile', 'change-avatar' ); ?>" title="<?php echo __( "Edit Profile Photo", 'buddypress' ); ?>">
        <span class="fa-stack fa-lg">
          <i class="fa fa-circle fa-stack-2x"></i>
          <i class="fa fa-pencil fa-stack-1x fa-inverse" aria-hidden="true"></i>
        </span>
			</a>
      </div>
      <?php } ?>
		</div><!-- #item-header-avatar -->

		<div id="item-header-content">

			<?php if ( bp_is_active( 'activity' ) && bp_activity_do_mentions() ) : ?>
				<h2 class="user-nicename"><?php the_title(); ?> | @<?php bp_displayed_user_mentionname(); ?></h2>
			<?php endif; ?>

			<div id="item-buttons"><?php

				/**
				 * Fires in the member header actions section.
				 *
				 * @since 1.2.6
				 */
				do_action( 'bp_member_header_actions' ); ?></div><!-- #item-buttons -->

			<span class="activity" data-livestamp="<?php bp_core_iso8601_date( bp_get_user_last_activity( bp_displayed_user_id() ) ); ?>"><?php bp_last_activity( bp_displayed_user_id() ); ?></span>

			<?php

			/**
			 * Fires before the display of the member's header meta.
			 *
			 * @since 1.2.0
			 */
			do_action( 'bp_before_member_header_meta' ); ?>

			<div id="item-meta">

				<?php if ( bp_is_active( 'activity' ) ) : ?>

					<div id="latest-update">

						<?php bp_activity_latest_update( bp_displayed_user_id() ); ?>

					</div>

				<?php endif; ?>

				<?php

				 /**
				  * Fires after the group header actions section.
				  *
				  * If you'd like to show specific profile fields here use:
				  * bp_member_profile_data( 'field=About Me' ); -- Pass the name of the field
				  *
				  * @since 1.2.0
				  */
				 do_action( 'bp_profile_header_meta' );

				?>
         
        <?php
        
          $bd_all_user_fields = bd_fetch_all_user_fields(bp_displayed_user_id());

          if (isset($bd_all_user_fields['Facebook']) && strip_tags($bd_all_user_fields['Facebook']) != '') {

            echo '
              <a href="'. strip_tags($bd_all_user_fields['Facebook']) .'" title="'. get_the_title() .' on Facebbook" class="wpfai-facebook wpfai-link blank">
                <span class="fa-stack fa-lg">
                  <i class="fa fa-circle fa-stack-2x"></i>
                  <i class="fa fa-facebook fa-stack-1x fa-inverse" aria-hidden="true"></i>
                </span>
              </a>            
            ';
          }
          
          if (isset($bd_all_user_fields['Twitter']) && strip_tags($bd_all_user_fields['Twitter']) != '') {

            echo '
              <a href="https://'. strip_tags($bd_all_user_fields['Twitter']) .'" title="'. get_the_title() .' on Twitter" class="wpfai-twitter wpfai-link blank">
                <span class="fa-stack fa-lg">
                  <i class="fa fa-circle fa-stack-2x"></i>
                  <i class="fa fa-twitter fa-stack-1x fa-inverse" aria-hidden="true"></i>
                </span>
              </a>            
            ';
          }
          
          if (isset($bd_all_user_fields['Linkedin']) && strip_tags($bd_all_user_fields['Linkedin']) != '') {

            echo '
              <a href="'. strip_tags($bd_all_user_fields['Linkedin']) .'" title="'. get_the_title() .' on Twitter" class="wpfai-linkedin wpfai-link blank">
                <span class="fa-stack fa-lg">
                  <i class="fa fa-circle fa-stack-2x"></i>
                  <i class="fa fa-linkedin fa-stack-1x fa-inverse" aria-hidden="true"></i>
                </span>
              </a>            
            ';
          }
          
        ?>

			</div><!-- #item-meta -->

		</div><!-- #item-header-content -->

	</div><!-- #item-header-cover-image -->
  <?php if (bp_displayed_user_id() == get_current_user_id() ) { ?>
  <div class="cover_actions profile_actions">
    <a href="<?php bp_members_component_link( 'profile', 'change-cover-image' ); ?>" title="<?php echo __( "Edit Cover Image", 'buddypress' ); ?>">
      <span class="fa-stack fa-lg">
        <i class="fa fa-circle fa-stack-2x"></i>
        <i class="fa fa-camera fa-stack-1x fa-inverse" aria-hidden="true"></i>
      </span>
    </a>
  </div>
  <?php } ?>
</div><!-- #cover-image-container -->

<?php

/**
 * Fires after the display of a member's header.
 *
 * @since 1.2.0
 */
do_action( 'bp_after_member_header' ); ?>

<div id="template-notices" role="alert" aria-atomic="true">
	<?php

	/** This action is documented in bp-templates/bp-legacy/buddypress/activity/index.php */
	do_action( 'template_notices' ); ?>

</div>
