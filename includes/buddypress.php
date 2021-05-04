<?php

// no double opt-in
add_filter( 'bp_registration_needs_activation', '__return_false' );


// auto login users on registration
add_action( 'bp_core_signup_user', 'opsi_on_register_actions', 100, 1 );
function opsi_on_register_actions( $user_id ) {


	global $wpdb;

	// $wpdb->query( $wpdb->prepare( "UPDATE $wpdb->users SET user_status = 0 WHERE ID = %d", $user_id ) );

	// $u = new WP_User( $user_id );
	// Set role
	// $set_role = $u->set_role( 'pending' );

	$activation_key = get_user_meta( $user_id, 'activation_key', true );
	$signups_table  = buddypress()->members->table_name_signups;
	$signup_id = $wpdb->get_var( $wpdb->prepare(
		"
			SELECT signup_id
			FROM $signups_table
			WHERE activation_key = %s
		",
		$activation_key
	) );


	BP_Signup::activate( array ( $signup_id ) );

	// delete_user_meta( $user_id, 'activation_key' );


}



add_action( 'bp_core_activated_user', 'opsi_bp_core_activated_user', 10, 3);
function opsi_bp_core_activated_user ( $user_id, $key, $user ) {

	// notify the admin
	$subject = 'New pending member';
	$body    = 'A new user have just registered. The account has been set to pending. <a href="'. get_admin_url().'user-edit.php?user_id='.$user_id .'">Edit the user here</a>';
	$headers = array('Content-Type: text/html; charset=UTF-8');

	$sendto = get_field( 'emails_to_get_pending_user_notification', 'option' );

	if ( $sendto != '' ) {

		$emails = explode( ',', $sendto );

		foreach ( $emails as $em ) {
			wp_mail( trim( $em ), $subject, $body, $headers );

		}

	}

	// login the user
	wp_set_current_user($user_id);
	wp_set_auth_cookie($user_id);
	// wp_redirect( home_url() );
	// exit;

}

add_action( 'template_redirect', 'opsi_restrict_bp_pages' );
function opsi_restrict_bp_pages() {


	if ( is_user_logged_in() ) {

			$user = wp_get_current_user();

			if ( in_array( 'pending', (array) $user->roles ) && is_buddypress() && !bp_is_my_profile() ) {
				global $bp;

				wp_safe_redirect( $bp->bp_nav['profile']['link'] );
				die;
			}

			if ( in_array( 'pending', (array) $user->roles ) && is_buddypress() && bp_is_my_profile()  ) {

				$componenets_rem = array(
					'activity',
					'friends',
					'notifications',
					'groups',
					'messages',
				);

				if ( in_array( bp_current_component(), $componenets_rem ) ) {

					global $bp;

					wp_safe_redirect( $bp->bp_nav['profile']['link'] );
					die;
				}
			}

	}
}


add_filter( 'bp_is_active', 'opsi_conditionally_disable_components_for_pending', 10, 2 );
function opsi_conditionally_disable_components_for_pending( $enabled, $component ) {

    if ( is_user_logged_in() ) {

		global $bp;

		$user = wp_get_current_user();



		$componenets_rem = array(
			'activity',
		);
		foreach ( $componenets_rem as $rem ) {
			if ( $rem == $component && in_array( 'pending', (array) $user->roles ) )  {
				bp_core_remove_nav_item( $rem );
				$enabled = false ;
			}
		}
    }

    return $enabled;
}


add_filter( 'bp_before_member_header', 'opsi_display_warning_to_pending' );
function opsi_display_warning_to_pending () {

	if ( is_user_logged_in() ) {

			$user = wp_get_current_user();

			if ( in_array( 'pending', (array) $user->roles ) && is_buddypress() ) {
				?>
				<div class="alert alert-danger text-center">
				<?php
				echo __( 'Your account is pending approval, thus some functionality is not available.', 'opsi' ) ;
				?>
				</div>
				<?php
			}
	}
}

// Apply filter
add_filter('body_class', 'opsi_user_body_classes');

function opsi_user_body_classes($classes) {

	if ( is_user_logged_in() ) {
		$user = wp_get_current_user();

		if ( in_array( 'pending', (array) $user->roles ) && is_buddypress() ) {
			$classes[] = 'user_pending';
		}
	}
    return $classes;
}



add_filter( 'bp_core_avatar_class', 'nitro_bp_core_avatar_class');
function nitro_bp_core_avatar_class($classes) {

	$classes .= ' img-circle';

	return $classes;
}


// nav menu hooks
add_filter('wp_nav_menu_objects', 'bp_menu_items_tweak', 10, 2);
function bp_menu_items_tweak($items, $args) {

  if (($args->theme_location == 'mobile' || $args->theme_location == 'primary') && is_user_logged_in()) {
    $user = wp_get_current_user();

    $notifications_count = bp_notifications_get_unread_notification_count( $user->ID );

    foreach($items as $item) {
      // replace with bell icon and add bubble
      if (in_array('bp-notifications-nav', $item->classes)) {
        $item->title = '<i class="fa fa-bell" aria-hidden="true"></i><span class="button__badge '. ($notifications_count > 0 ? 'active' : 'default') .'">'. $notifications_count .'</span>';
      }
      // replace with avatar icon & name
      if (in_array('bp-user-nav', $item->classes)) {
        global $bp;
        $item->title = '<span class="hidden-xs">'.bp_core_get_user_displayname($user->ID) .'</span> '. bp_get_displayed_user_avatar( array('item_id' => $user->ID, 'type'=>'thumb') ) . ($args->theme_location == 'mobile' ? '<b class="caret"></b>' : '');
        $item->url = bp_loggedin_user_domain();
      }
    }
  }

  return $items;
}


add_filter( 'bp_user_can_create_groups', 'nitro_user_can_create_groups', 10, 2 );
function nitro_user_can_create_groups( $can_create, $restricted=false ){
    // maybe we don't want to override if it's restricted?
    if ( ! $restricted ){
        // get the logged in user's ID
        $user_ID = get_current_user_id();
        // some logic to determine if the current user can create a group
        if ( user_can_create_group( $user_ID ) ){
            // we will return this allowing them to create groups
            $can_create = true;
        } else {
          $can_create = false;
        }
    }
    return $can_create;
}

function user_can_create_group($userid) {

  if (current_user_can('administrator')) {
    return true;
  }

  if( current_user_can('bd_create_group')){
    return true;
  }

  return false;
}


add_action('load-users.php',function() {

if(isset($_GET['action']) && isset($_GET['bp_gid']) && isset($_GET['users'])) {
    $group_id = $_GET['bp_gid'];
    $users = $_GET['users'];
    foreach ($users as $user_id) {
        groups_join_group( $group_id, $user_id );
    }
}
    //Add some Javascript to handle the form submission
    add_action('admin_footer',function(){ ?>
    <script>
        jQuery("select[name='action']").append(jQuery('<option value="groupadd">Add to BP Group</option>'));
        jQuery("#doaction").click(function(e){
            if(jQuery("select[name='action'] :selected").val()=="groupadd") { e.preventDefault();
                gid=prompt("Please enter a BuddyPres Group ID","1");
                jQuery(".wrap form").append('<input type="hidden" name="bp_gid" value="'+gid+'" />').submit();
            }
        });
    </script>
    <?php
    });
});

function bp_get_roles() {
  if ( ! function_exists( 'get_editable_roles' ) ) {
    require_once ABSPATH . 'wp-admin/includes/user.php';
  }
  $roles = array();
  $get_editable_roles = get_editable_roles();
  foreach ($get_editable_roles as $key => $value) {
    if (strpos($key, 'bd_') !== false) {
      $roles[$key] = $value;
    }
  }

  return $roles;
}



if ( class_exists('BP_Group_Extension') ) : // Recommended, to prevent problems during upgrade or when Groups are disabled

    class BP_Group_Role_Access_Plugin_Extension extends BP_Group_Extension {

        var $visibility = 'private';
        var $format_notification_function;
        var $enable_edit_item = true;
        var $admin_metabox_context = 'side'; // The context of your admin metabox. See add_meta_box()
        var $admin_metabox_priority = 'default'; // The priority of your admin metabox. See add_meta_box()

        function __construct() {
            $bp = buddypress();

            $this->name = __('Role Access' , 'opsi');
            $this->slug = 'role_access';

            /* For internal identification */
            $this->id = 'group_role_access';
            $this->format_notification_function = 'bp_group_role_access_format_notifications';
            $this->create_step_position = 22;

        }

        /**
         * The content of the BP group documents tab of the group creation process
         *
         */
        function create_screen($group_id = null) {
            $bp = buddypress();
            if ( !bp_is_group_creation_step($this->slug) ) {
                return false;
            }
            $this->edit_create_markup($bp->groups->new_group_id);
            wp_nonce_field('groups_create_save_' . $this->slug);
        }

        /**
         * The routine run after the user clicks Continue from the creation step
         */
        function create_screen_save($group_id = null) {
            $bp = buddypress();

            check_admin_referer('groups_create_save_' . $this->slug);

            do_action('bp_group_role_access_group_create_save');
            $success = false;


            //Update permissions
            $valid_permissions = array('members' , 'mods_only');
            if ( isset($_POST['bp_group_role_access']) && !empty($_POST['bp_group_role_access']) && is_array($_POST['bp_group_role_access']) ) {
                $success = groups_update_groupmeta($bp->groups->new_group_id , 'group_role_access' , $_POST['bp_group_role_access']);
            }


            /* To post an error/success message to the screen, use the following */
            if ( !$success )
                bp_core_add_message(__('There was an error saving, please try again' , 'buddypress') , 'error');
            else
                bp_core_add_message(__('Settings Saved.' , 'buddypress'));
            do_action('bp_group_role_access_group_after_create_save');
        }

        /**
         * The content of the Group Role Access page of the group admin
         */
        function edit_screen($group_id = null) {
            $bp = buddypress();
            if ( !bp_is_group_admin_screen($this->slug) ) {
                return false;
            }
            //useful ur for submits & links
            $action_link = get_bloginfo('url') . '/' . bp_get_groups_root_slug() . '/' . $bp->current_item . '/' . $bp->current_action . '/' . $this->slug;
            $this->edit_create_markup($bp->groups->current_group->id);

            do_action('bp_group_role_access_group_admin_edit');
            ?>
            &nbsp;<p>
                <input type="submit" value="<?php _e('Save Changes' , 'buddypress') ?>" id="save" name="save" />
                <input type="hidden" name="setRoles" value="" />
            </p>
            <?php
            wp_nonce_field('groups_edit_save_' . $this->slug);
        }

        function edit_create_markup($gid) {
            $bp = buddypress();

            $group_role_access = groups_get_groupmeta($gid, 'group_role_access', true);
            if (empty($group_role_access) || !$group_role_access) {
              $group_role_access = array();
            }

            // get user data
            $userdata = get_userdata(bp_loggedin_user_id());
            $bp_group_role_access = groups_get_groupmeta($gid, 'group_role_access', true);
            if ($bp_group_role_access) {
              $intersect = array_intersect($userdata->roles, $bp_group_role_access);
            } else {
              $intersect = array();
            }

            //only show the roles persmissions if the site admin allows this to be changed at group-level
            ?>
            <p>
              <?php _e( 'By default all groups are open to all member roles.', 'opsi' ); ?><br />
              <?php _e( 'Which roles should be <b>DENIED</b> access to the group page?', 'opsi' ); ?>
            </p>
            <?php
              $bp_get_roles = bp_get_roles();
              if (!empty($bp_get_roles)) { ?>
                <div class="checkbox">
                  <?php foreach($bp_get_roles as $key => $value) {

                    if (in_array($key, $intersect)) { continue; }

                  ?>
                    <label for="group_role_access_<?php echo $key; ?>" style="display: block; <?php echo (in_array($key, $group_role_access) ? 'opacity: 0.6; ' : '' ); ?>"><input type="checkbox" name="group_role_access[]" id="group_role_access_<?php echo $key; ?>" value="<?php echo $key; ?>" <?php echo (in_array($key, $group_role_access) ? 'checked' : '' ); ?> /> <?php echo $value['name']; ?></label>
                  <?php } ?>
                </div>

              <?php } else { ?>
                <div class="alert alert-warning">
                <?php _e('No suitable roles were found', 'opsi'); ?>
                </div>
                <?php
              }
        }

        /**
         * The routine run after the user clicks Save from your admin tab
         */
        function edit_screen_save($group_id = null) {
            $bp = buddypress();
            do_action('bp_group_role_access_group_admin_save');
            $message = '';
            $type = '';


            if ( (!isset($_POST['save'])) && (!isset($_POST['setRoles'])) ) {
                return false;
            }

            check_admin_referer('groups_edit_save_' . $this->slug);
            // $message .= '<pre>'.print_r($_POST, true).'</pre>';
            // $message .= '<pre>'.print_r($this->slug, true).'</pre>';

            //check if group upload permision has chanced
            if ( isset($_POST['group_role_access']) && is_array($_POST['group_role_access']) ) {
                if ( true == groups_update_groupmeta($bp->groups->current_group->id , 'group_role_access' , $_POST['group_role_access']) ) {
                    if ( $message != '' ) {
                        $message .= '.     ';
                    }
                    groups_edit_group_settings($bp->groups->current_group->id, true, 'private');
                    $message .= __('Role Access Permissions changed successfully.' , 'opsi') . ' ';
                    $message .= __('The group was set to private.' , 'opsi') . '.';
                }
            }
            if ( !isset($_POST['group_role_access']) && $this->slug == 'role_access') {
              groups_delete_groupmeta( $bp->groups->current_group->id, 'group_role_access' );
              $message .= __('Role Access Permissions changed successfully.' , 'opsi') . ' ';
            }



            /* Post an error/success message to the screen */

            if ( '' == $message )
                bp_core_add_message(__('No changes were made. Either error or you didn\'t change anything' , 'opsi') , 'error');
            else
                bp_core_add_message($message , $type);

            do_action('bp_group_role_access_group_admin_after_save');
            bp_core_redirect(bp_get_group_permalink($bp->groups->current_group) . 'admin/' . $this->slug);
        }

        /**
         * @version 1, 25/4/2013
         * @since version 0.5
         * @author Stergatu
         */
        function display($group_id = null) {
            // do_action('bp_group_role_access_display');
            // add_action('bp_template_content_header' , 'bp_group_role_access_display_header');
            // add_action('bp_template_title' , 'bp_group_role_access_display_title');
            // bp_group_role_access_display();
        }

        /**
         * Add a metabox to the admin Edit group screen
         *
         */
        function admin_screen($group_id = null) {
            $this->edit_create_markup($group_id);
        }

        /**
         * The routine run after the group is saved on the Dashboard group admin screen
         * @param type $group_id
         */
        function admin_screen_save($group_id = null) {
            // Grab your data out of the $_POST global and save as necessary
            //Update permissions

            if ( isset($_POST['group_role_access']) && is_array($_POST['group_role_access']) ) {
              groups_update_groupmeta($group_id , 'group_role_access' , $_POST['group_role_access']);
            }
        }

        function widget_display() {
            ?>
            <div class="info-group">
                <h4><?php echo esc_attr($this->name) ?></h4>
                <p>
                    Not yet implemented
                </p>
            </div>
            <?php
        }

    }

    bp_register_group_extension('BP_Group_Role_Access_Plugin_Extension');



endif; // class_exists( 'BP_Group_Extension' )



add_action( 'bp_actions', 'nitro_remove_group_tabs' );
function nitro_remove_group_tabs() {

/**
 * @since 2.6.0 Introduced the $component parameter.
 *
 * @param string $slug      The slug of the primary navigation item.
 * @param string $component The component the navigation is attached to. Defaults to 'members'.
 * @return bool Returns false on failure, True on success.
 */

	if ( ! bp_is_group() ) {
		return;
	}

	$slug = bp_get_current_group_slug();
        // all existing default group tabs are listed here. Uncomment or remove.
	//	bp_core_remove_subnav_item( $slug, 'members' );
		bp_core_remove_subnav_item( $slug, 'role_access' );
		// bp_core_remove_subnav_item( $slug, 'send-invites' );
	//	bp_core_remove_subnav_item( $slug, 'admin' );
	//	bp_core_remove_subnav_item( $slug, 'forum' );

}



add_action('bp_actions', 'opsi_bp_groups_forum_first_tab');
function opsi_bp_groups_forum_first_tab() {
  global $bp;
  // echo '<pre>'.print_r($bp->groups, true).'</pre>';
  bp_core_new_subnav_item(
    array(
        'name' => __('All groups', 'opsi'),
        'slug' => 'all-groups',
        'parent_slug' => $bp->groups->slug,
        'parent_url' => $bp->groups->slug,
        'position' => 50,
        'screen_function' => 'false',
        'link' => bp_get_groups_directory_permalink()
        // 'link' => get_option('siteurl') . '/groups/create/step/group-details/'
    ));
    if (current_user_can('bd_create_group')) {
      bp_core_new_subnav_item(
      array(
          'name' => __('Create group', 'opsi'),
          'slug' => 'create-group',
          'parent_slug' => $bp->groups->slug,
          'parent_url' => $bp->groups->slug,
          'position' => 60,
          'screen_function' => 'false',
          'link' => get_option('siteurl') . '/groups/create/step/group-details/'
      ));
    }
}


add_action( 'bp_after_profile_field_content', 'nitro_bp_after_profile_field_content' );
function nitro_bp_after_profile_field_content() {

  global $wp_query, $bp;
  if (
        isset( $bp->canonical_stack['component'] ) && $bp->canonical_stack['component'] == 'profile'
    &&  (isset($bp->canonical_stack['action']) && $bp->canonical_stack['action'] == 'edit')
    &&  $bp->canonical_stack['action_variables'][0] == 'group'
    &&  $bp->canonical_stack['action_variables'][1] == 1

    ) {

    // $userdata = get_userdata( bp_loggedin_user_id() );
    $userdata = get_userdata( bp_displayed_user_id() );

    echo '
      <label for="email">E-mail <span class="description">'. bp_the_profile_field_required_label() .'</span></label>
      <input type="text" name="email" id="email" value="'. $userdata->user_email .'" class="regular-text">
    ';
  }
}

add_action( 'xprofile_updated_profile', 'nitro_xprofile_updated_profile', 10, 5 );
function nitro_xprofile_updated_profile($user_id, $posted_field_ids, $errors, $old_values, $new_values) {

  global $wp_query, $bp;
  if (
        $bp->canonical_stack['component'] == 'profile'
    &&  $bp->canonical_stack['action'] == 'edit'
    &&  $bp->canonical_stack['action_variables'][0] == 'group'
    &&  $bp->canonical_stack['action_variables'][1] == 1

    ) {


    $userdata = get_userdata($user_id);
    $user_email = $userdata->user_email;

    if (isset( $_POST['email']) && filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
      // check if user is really updating the value
      if ($user_email != $_POST['email']) {
          // check if email is free to use
          if (email_exists( $_POST['email'] )){
              // Email exists, do not update value.
              // Maybe output a warning.
          } else {
              $args = array(
                  'ID'         => $user_id,
                  'user_email' => esc_attr( $_POST['email'] )
              );
          wp_update_user( $args );
         }
      }
    }
  }

}


remove_filter( 'xprofile_field_options_before_save', 'bp_xprofile_sanitize_field_options' );
add_filter( 'xprofile_field_options_before_save', 'opsi_bp_xprofile_sanitize_field_options' );
function opsi_bp_xprofile_sanitize_field_options( $field_options = '' ) {
	if ( is_array( $field_options ) ) {
		return array_map( 'sanitize_textarea_field', $field_options );
	} else {
		return sanitize_text_field( $field_options );
	}
}

/**
 * Buddypress Header Type
 */

add_filter( 'bp_xprofile_get_field_types', 'nitro_get_field_types', 10, 1 );
function nitro_get_field_types($fields) {
    $fields = array_merge($fields, array('header' => 'nitro_bd_field_type_header'));
    $fields = array_merge($fields, array('repeater' => 'nitro_bd_field_type_repeater'));
    $fields = array_merge($fields, array('multiselectbox_opsi' => 'BP_XProfile_Field_Type_Multiselectbox_OPSI'));
    return $fields;
}



if (!class_exists('nitro_bd_field_type_header'))
{
    class nitro_bd_field_type_header extends BP_XProfile_Field_Type
    {
        public function __construct() {
            parent::__construct();

            $this->name = __( 'Text field with Header', 'bxcft' );

            $this->accepts_null_value = false;
            $this->supports_options = false;
            $this->supports_richtext = false;

            $this->set_format( '/.*?/', 'replace' );

            do_action( 'bp_xprofile_field_type_header', $this );
        }

        public function admin_field_html (array $raw_properties = array ())
        {
            global $field;

            $args = array(
                'type' => 'text'
            );

            $options = $field->get_children( true );
            if ($options) {
                foreach ($options as $o) {
                    if (strpos($o->name, 'header_') !== false) {
                        $args['header'] = str_replace('header_', '', $o->name);
                    }
                }
            }

            $html = $this->get_edit_field_html_elements(array_merge($args,$raw_properties));
        ?>
            <input <?php echo $html; ?> />
        <?php
        }

        public function edit_field_html (array $raw_properties = array ())
        {
            if ( isset( $raw_properties['user_id'] ) ) {
                unset( $raw_properties['user_id'] );
            }

            // HTML5 required attribute.
            if ( bp_get_the_profile_field_is_required() ) {
                $raw_properties['required'] = 'required';
            }

            $field = new BP_XProfile_Field(bp_get_the_profile_field_id());


            $args = array(
                'type'  => 'text',
                'value' => bp_get_the_profile_field_edit_value(),
            );
            $options = $field->get_children( true );
            if ($options) {
              $header = $options[0]->name;
            }

            $html = $this->get_edit_field_html_elements(array_merge($args,$raw_properties));

            $label = sprintf(
                '<h3>'. $header .'</h3>
                <hr />
                <label for="%s">%s%s</label>',
                    bp_get_the_profile_field_input_name(),
                    bp_get_the_profile_field_name(),
                    (bp_get_the_profile_field_is_required()) ?
                        ' ' . esc_html__( '(required)', 'buddypress' ) : ''
            );
            // Label.
            echo apply_filters('bxcft_field_label', $label, bp_get_the_profile_field_id(), bp_get_the_profile_field_type(), bp_get_the_profile_field_input_name(), bp_get_the_profile_field_name(), bp_get_the_profile_field_is_required());
            // Errors.
            do_action( bp_get_the_profile_field_errors_action() );
            // Input.
        ?>
            <input <?php echo $html; ?> />
        <?php
        }

        public function admin_new_field_html (\BP_XProfile_Field $current_field, $control_type = '')
        {
            $type = array_search( get_class( $this ), bp_xprofile_get_field_types() );
            if ( false === $type ) {
                return;
            }

            $class            = $current_field->type != $type ? 'display: none;' : '';
            $current_type_obj = bp_xprofile_create_field_type( $type );

            $options = $current_field->get_children( true );
            $header = '';
            if ( ! $options ) {
                $options = array();
                $i       = 1;
                while ( isset( $_POST[$type . '_option'][$i] ) ) {
                    $is_default_option = true;

                    $options[] = (object) array(
                        'id'                => -1,
                        'is_default_option' => $is_default_option,
                        'name'              => sanitize_textarea_field( stripslashes( $_POST[$type . '_option'][$i] ) ),
                    );

                    ++$i;
                }

                if ( ! $options ) {
                    $options[] = (object) array(
                        'id'                => -1,
                        'is_default_option' => false,
                        'name'              => '2',
                    );
                }
            } else {
              $header = $options[0]->name;
            }

        ?>
            <div id="<?php echo esc_attr( $type ); ?>" class="postbox bp-options-box" style="<?php echo esc_attr( $class ); ?> margin-top: 15px;">
                <h3><?php esc_html_e( 'Header text.', 'bxcft' ); ?></h3>
                <div class="inside">
                    <p>
                        <label for="<?php echo esc_attr( "{$type}_option1" ); ?>">
                            <?php esc_html_e('Header:', 'bxcft'); ?>
                        </label>
                        <input type="text" name="<?php echo esc_attr( "{$type}_option[1]" ); ?>"
                            id="<?php echo esc_attr( "{$type}_option1" ); ?>" value="<?php echo $header; ?>" />
                    </p>
                </div>
            </div>
        <?php
        }

        public function is_valid( $values ) {
            $this->validation_allowed_values = null;
            return parent::is_valid($values);
        }

        /**
         * Modify the appearance of value. Apply autolink if enabled.
         *
         * @param  string   $value      Original value of field
         * @param  int      $field_id   Id of field
         * @return string   Value formatted
         */
        public static function display_filter($field_value, $field_id = '') {

            $new_field_value = $field_value;

            if (!empty($field_value)) {
                if (!empty($field_id)) {
                    $field = BP_XProfile_Field::get_instance($field_id);
                    if ($field) {
                        $do_autolink = apply_filters('bxcft_do_autolink',
                            $field->get_do_autolink());
                        if ($do_autolink) {
                            $query_arg = bp_core_get_component_search_query_arg( 'members' );
                            $search_url = add_query_arg( array(
                                    $query_arg => urlencode( $field_value )
                                ), bp_get_members_directory_permalink() );
                            $new_field_value = '<a href="' . esc_url( $search_url ) .
                                '" rel="nofollow">' . $new_field_value . '</a>';
                        }
                    }
                }
            }

            /**
             * bxcft_number_minmax_display_filter
             *
             * Use this filter to modify the appearance of 'Number within
             * min/max values' field value.
             * @param  $new_field_value Value of field
             * @param  $field_id Id of field.
             * @return  Filtered value of field.
             */
            return apply_filters('bxcft_number_minmax_display_filter',
                $new_field_value, $field_id);
        }
    }
}


if (!class_exists('nitro_bd_field_type_repeater'))
{
    class nitro_bd_field_type_repeater extends BP_XProfile_Field_Type
    {
        public function __construct() {
            parent::__construct();

            $this->name = __( 'Repeater', 'abp' );

            $this->accepts_null_value = true;
            $this->supports_options = true;
            $this->supports_richtext = true;

            $this->set_format( '/.*?/', 'replace' );

            do_action( 'bp_xprofile_field_type_repeater', $this );
        }

        public function admin_field_html (array $raw_properties = array ())
        {
            global $field;

            $args = array(
                'type' => 'textarea',
                'class' => 'abp-repeater',
                'rows' => '10',
                'cols' => '100'
            );

            $options = $field->get_children( true );
            $field_val = '';
            if ( !empty($options) ) {
              foreach ($options as $o) {
                $field_val = $o->name;
              }
            }

            $html = $this->get_edit_field_html_elements(array_merge($args, $raw_properties));
        ?>
            <textarea <?php echo $html; ?>><?php echo $field_val; ?></textarea><br />
            <small><?php echo __('Pipe (|) separated, and line seperated. Options should be comma separated.', 'opsi'); ?></small><br />
            <small><?php echo __('Example:', 'opsi'); ?></small><br />
            <small><?php echo __('field type|label|field name|options', 'opsi'); ?></small><br />
            <small><?php echo __('Availale field types: text, textarea, select, date, URL', 'opsi'); ?></small>
        <?php
        }

        public function edit_field_html (array $raw_properties = array ())
        {
            if ( isset( $raw_properties['user_id'] ) ) {
                $user_id = $raw_properties['user_id'];
                unset( $raw_properties['user_id'] );
            }

            // HTML5 required attribute.
            if ( bp_get_the_profile_field_is_required() ) {
                $raw_properties['required'] = 'required';
            }

            $field = new BP_XProfile_Field(bp_get_the_profile_field_id());


            $args = array(
                'type'  => 'textarea',
                'class' => 'abp-repeater',
                'value' => bp_get_the_profile_field_edit_value()
            );
            $options = $field->get_children( true );
            if ($options) {
              foreach ($options as $o) {
                $fields = explode("\n", $o->name);
              }
            }

            $html = $this->get_edit_field_html_elements(array_merge($args, $raw_properties));

            $label = sprintf(
                '<legend>%s%s</legend>',
                    bp_get_the_profile_field_name(),
                    (bp_get_the_profile_field_is_required()) ?
                        ' ' . esc_html__( '(required)', 'buddypress' ) : ''
            );
            // Label.
            echo apply_filters('abp_field_label', $label, bp_get_the_profile_field_id(), bp_get_the_profile_field_type(), bp_get_the_profile_field_input_name(), bp_get_the_profile_field_name(), bp_get_the_profile_field_is_required());
            // Errors.
            do_action( bp_get_the_profile_field_errors_action() );
            // Input.

            $option_data = maybe_unserialize(BP_XProfile_ProfileData::get_value_byid( $field->id, bp_displayed_user_id()));

            $i = 0;
            $odai = 0; // option data auto increase

            if (!empty($fields)) {

              $num_of_fields = count($fields);
              $groups_of_fields = count($option_data) / $num_of_fields;
              $j = 0;

              if ($num_of_fields > 0 && !empty($option_data)) {
                for ($j = 0; $j < $groups_of_fields; $j++) {
                  echo '
                    <div class="repeater_wrap">
                    '. ($j > 0 ? '<hr />' : '') .'
                      <fieldset>
                        <div class="text-right"><a href="#" class="delete_fieldset">&times;</a></div>
                    ';

                  foreach($fields as $field_line) {
                    $f = explode('|', $field_line);

					if ( isset ( $f[2] ) ) {
						$f[2] = trim( $f[2] );
					} else {
						$f[2] = trim( $f[0] );
					}

                    echo '<label for="'. bp_get_the_profile_field_input_name() .'['. $i.'_'.$f[2] .']" class="repeater_label">'.$f[1];

                    if ($f[0] == 'text') {
						echo '<input value="'. ( isset( $option_data[$odai] ) ? $option_data[$odai] : '' ) .'" name="'. bp_get_the_profile_field_input_name() .'['. $i.'_'.$f[2] .']" type="text" id="'. bp_get_the_profile_field_input_name() .'['. $i.'_'.$f[2] .']" class="form-control" />';
						$odai++;
					}

                    if ($f[0] == 'url') {
						echo '<input value="'. ( isset( $option_data[$odai] ) ? $option_data[$odai] : '' ) .'" name="'. bp_get_the_profile_field_input_name() .'['. $i.'_'.$f[2] .']" type="url" id="'. bp_get_the_profile_field_input_name() .'['. $i.'_'.$f[2] .']" class="form-control" />';
						$odai++;
					}

                    if ($f[0] == 'email') {
						echo '<input value="'. ( isset( $option_data[$odai] ) ? $option_data[$odai] : '' ) .'" name="'. bp_get_the_profile_field_input_name() .'['. $i.'_'.$f[2] .']" type="email" id="'. bp_get_the_profile_field_input_name() .'['. $i.'_'.$f[2] .']" class="form-control" />';
						$odai++;
                    }

                    if ($f[0] == 'date') {

                      $temp_date = array();
					  if ( isset( $option_data[$odai] ) ) {
						$temp_date = explode('/', $option_data[$odai]);
					  }
                      if (!isset($temp_date[0])) {
                        $temp_date[0] = '';
                      }
                      if (!isset($temp_date[1])) {
                        $temp_date[1] = '';
                      }

                      echo '
                        <div class="opsi_date_field_wrap">';
                      echo '
                        <select class="opsi_date_month">
                          <option value="">----</option>
                          ';

                          for($d=1; $d<13; $d++) {
                            echo '<option value="'. $d .'" '. ($temp_date[0] == $d ? 'selected="selected"' : '') .'>'. date('F',strtotime('01.'.$d.'.2001')) .'</option>';
                          }

                      echo '
                        </select>
                      ';

                      echo '
                        <select class="opsi_date_year">
                          <option value="">----</option>
                        ';

                          for($d=date('Y'); $d>1900; $d--) {
                            echo '<option value="'. $d .'" '. ($temp_date[1] == $d ? 'selected="selected"' : '') .'>'. $d .'</option>';
                          }
                        ;
                      echo '
                        </select>
                      ';

                      echo '<input value="'. ( isset( $option_data[$odai] ) ? $option_data[$odai] : '' ) .'" name="'. bp_get_the_profile_field_input_name() .'['. $i.'_'.$f[2] .']" type="hidden" id="'. bp_get_the_profile_field_input_name() .'['. $i.'_'.$f[2] .']" class="form-control opsi_date_field_value" />';
					  $odai++;
                    }

                    if ($f[0] == 'textarea') {
                      echo '<textarea name="'. bp_get_the_profile_field_input_name() .'['. $i.'_'.$f[2] .']" id="'. bp_get_the_profile_field_input_name() .'['. $i.'_'.$f[2] .']" class="form-control">'. ( isset($option_data[$odai]) ? $option_data[$odai] : '' ) .'</textarea>';
					  $odai++;
                    }

                    if ($f[0] == 'select') {
                      echo '<select name="'. bp_get_the_profile_field_input_name() .'['. $i.'_'.$f[2] .']" id="'. bp_get_the_profile_field_input_name() .'['. $i.'_'.$f[2] .']" class="form-control">';

                      if (isset($f[3]) && !empty($f[3]))
                        foreach($f[3] as $select_option) {
                          echo '<option value="'. $select_option .'">'. $select_option .'</option>';
                        }
                      echo '</select>';
                    }

                    echo '</label>';
                  }



                  echo '
                      </fieldset>
                    </div>
                  ';

                  $i++;
                }
              }

              echo '<div class="repeater_wrap repeater_wrap_first wrap_'. $field->id .'">
                '. ($j > 0 ? '<hr />' : '') .'
                <fieldset>
                  <div class="text-right"><a href="#" class="delete_fieldset">&times;</a></div>
                ';


              foreach($fields as $field_line) {
                $f = explode('|', $field_line);

                if ( isset ( $f[2] ) ) {
					$f[2] = trim( $f[2] );
				} else {
					$f[2] = trim( $f[0] );
				}

                echo '<label for="'. bp_get_the_profile_field_input_name() .'['. $i.'_'.$f[2] .']" class="repeater_label">'.$f[1];

                if ($f[0] == 'text') {
                  echo '<input name="'. bp_get_the_profile_field_input_name() .'['. $i.'_'.$f[2] .']" type="text" id="'. bp_get_the_profile_field_input_name() .'['. $i.'_'.$f[2] .']" class="form-control" />';
                }

                if ($f[0] == 'url') {
                  echo '<input name="'. bp_get_the_profile_field_input_name() .'['. $i.'_'.$f[2] .']" type="url" id="'. bp_get_the_profile_field_input_name() .'['. $i.'_'.$f[2] .']" class="form-control" />';
                }

                if ($f[0] == 'email') {
                  echo '<input name="'. bp_get_the_profile_field_input_name() .'['. $i.'_'.$f[2] .']" type="email" id="'. bp_get_the_profile_field_input_name() .'['. $i.'_'.$f[2] .']" class="form-control" />';
                }

                if ($f[0] == 'date') {

                  echo '
                    <div class="opsi_date_field_wrap">';
                  echo '
                    <select class="opsi_date_month">
                      <option value="">----</option>
                      ';

                      for($d=1; $d<13; $d++) {
                        echo '<option value="'. $d .'">'. date('F',strtotime('01.'.$d.'.2001')) .'</option>';
                      }

                  echo '
                    </select>
                  ';

                  echo '
                    <select class="opsi_date_year">
                      <option value="">----</option>
                    ';

                      for($d=date('Y'); $d>1900; $d--) {
                        echo '<option value="'. $d .'">'. $d .'</option>';
                      }
                    ;
                  echo '
                    </select>
                  ';

                  echo '
                      <input name="'. bp_get_the_profile_field_input_name() .'['. $i.'_'.$f[2] .']" type="hidden" id="'. bp_get_the_profile_field_input_name() .'['. $i.'_'.$f[2] .']" class="form-control opsi_date_field_value" value="" />
                    </div>
                  ';
                }

                if ($f[0] == 'textarea') {
                  echo '<textarea name="'. bp_get_the_profile_field_input_name() .'['. $i.'_'.$f[2] .']" id="'. bp_get_the_profile_field_input_name() .'['. $i.'_'.$f[2] .']" class="form-control"></textarea>';
                }

                if ($f[0] == 'select') {
                  echo '<select name="'. bp_get_the_profile_field_input_name() .'['. $i.'_'.$f[2] .']" id="'. bp_get_the_profile_field_input_name() .'['. $i.'_'.$f[2] .']" class="form-control">';

                  if (isset($f[3]) && !empty($f[3]))
                    foreach($f[3] as $select_option) {
                      echo '<option value="'. $select_option .'">'. $select_option .'</option>';
                    }
                  echo '</select>';
                }

                echo '</label>';
              }
              echo '
                </fieldset>
              </div>
              <div class="additional_fieldsets_'. $field->id .'"></div>
              ';

              echo '<a href="#" id="add_'. $field->id .'" data-fid="'. $field->id .'" class="btn btn-primary add_fieldset_btn">'. __('Add', 'opsi') .' '. bp_get_the_profile_field_name() .'</a>';

              $i++;

            }
            ?>
            <span id="output-field_<?php echo $field->id; ?>"></span>
            <script>
              jQuery('document').ready(function() {
                var i = <?php echo $i - 1; ?>;
                var wrapper = jQuery('.wrap_<?php echo $field->id; ?>');

                jQuery('.delete_fieldset').on('click', function(e) {
                  e.preventDefault();
                  jQuery(this).closest('.repeater_wrap').remove();
                  return false;
                });
                jQuery('#add_<?php echo $field->id;?>').click(function(e) {
                  e.preventDefault();
                  jQuery('.additional_fieldsets_<?php echo $field->id; ?>').append('<div class="repeater_wrap"><?php ($j == 0 ? '<hr />' : ''); ?>'+ wrapper.html().split('field_<?php echo $field->id; ?>[<?php echo $i - 1; ?>').join('field_<?php echo $field->id; ?>['+ (i+1)) +'</div>');
                  i++;

                  if (jQuery(".opsi_date_month").length > 0) {
                    jQuery('.opsi_date_month, .opsi_date_year').on('change', function() {

                      var opsi_date_field_wrap = jQuery(this).parents('.opsi_date_field_wrap');
                      opsi_date_field_wrap.children('.opsi_date_field_value').val(opsi_date_field_wrap.children('.opsi_date_month').val()+'/'+opsi_date_field_wrap.children('.opsi_date_year').val());

                    });
                  }

                  return false;
                });

              });
            </script>
        <?php
        }

        public function admin_new_field_html (\BP_XProfile_Field $current_field, $control_type = '')
        {
            $type = array_search( get_class( $this ), bp_xprofile_get_field_types() );

            if ( false === $type ) {
                return;
            }

            $class            = $current_field->type != $type ? 'display: none;' : '';
            $current_type_obj = bp_xprofile_create_field_type( $type );

            $options = $current_field->get_children( true );
            $field_val = '';
            if ( ! $options ) {
                $options = array();
                $i       = 1;
                while ( isset( $_POST[$type . '_option'][$i] ) ) {
                    $is_default_option = true;

                    $options[] = (object) array(
                        'id'                => -1,
                        'is_default_option' => $is_default_option,
                        'name'              => sanitize_textarea_field( stripslashes( $_POST[$type . '_option'][$i] ) ),
                    );

                    ++$i;
                }

                if ( ! $options ) {
                    $options[] = (object) array(
                        'id'                => -1,
                        'is_default_option' => false,
                        'name'              => '',
                    );
                }
            } else {
                foreach ($options as $o) {
                  $field_val = $o->name;
                }
            }
        ?>
            <div id="<?php echo esc_attr( $type ); ?>" class="postbox bp-options-box" style="<?php echo esc_attr( $class ); ?> margin-top: 15px;">
                <div class="inside">
                    <p>
                        <label for="<?php echo esc_attr( "{$type}_option1" ); ?>">
                            <?php esc_html_e('Fields:', 'abp'); ?>
                        </label><br />
                        <textarea style="width: 100%; height: 250px;" name="<?php echo esc_attr( "{$type}_option[1]" ); ?>"
                            id="<?php echo esc_attr( "{$type}_option1" ); ?>"><?php echo $field_val; ?></textarea>
                    </p>
                    <small><?php echo __('Pipe (|) separated, and line seperated. Options should be comma separated.', 'opsi'); ?></small><br />
                    <small><?php echo __('Example:', 'opsi'); ?></small><br />
                    <small><?php echo __('field type|label|field name|options', 'opsi'); ?></small><br />
                    <small><?php echo __('Availale field types: text, textarea, select, date, URL', 'opsi'); ?></small>
                </div>
            </div>
        <?php
        }

        public function is_valid( $values ) {
            $validated = false;

            // Some types of field (e.g. multi-selectbox) may have multiple values to check
            foreach ( (array) $values as $value ) {

                // Validate the $value against the type's accepted format(s).
                foreach ( $this->validation_regex as $format ) {
                    if ( 1 === preg_match( $format, $value ) ) {
                        $validated = true;
                        continue;

                    } else {
                        $validated = false;
                    }
                }
            }

            // Handle field types with accepts_null_value set if $values is an empty array
            if ( ! $validated && is_array( $values ) && empty( $values ) && $this->accepts_null_value ) {
                $validated = true;
            }

            return (bool) apply_filters( 'bp_xprofile_field_type_is_valid', $validated, $values, $this );
        }

        /**
         * Modify the appearance of value. Apply autolink if enabled.
         *
         * @param  string   $value      Original value of field
         * @param  int      $field_id   Id of field
         * @return string   Value formatted
         */
        public static function display_filter($field_value, $field_id = '') {

            $new_field_value = $field_value;

            if (!empty($field_value)) {
                if (!empty($field_id)) {
                    $field = BP_XProfile_Field::get_instance($field_id);
                    if ($field) {
                        $do_autolink = apply_filters('abp_do_autolink',
                            $field->get_do_autolink());
                        if ($do_autolink) {
                            $query_arg = bp_core_get_component_search_query_arg( 'members' );
                            $search_url = add_query_arg( array(
                                    $query_arg => urlencode( $field_value )
                                ), bp_get_members_directory_permalink() );
                            $new_field_value = '<a href="' . esc_url( $search_url ) .
                                '" rel="nofollow">' . $new_field_value . '</a>';
                        }
                    }
                }
            }

            /**
             * abp_slider_display_filter
             *
             * Use this filter to modify the appearance of 'Slider'
             * field value.
             * @param  $new_field_value Value of field
             * @param  $field_id Id of field.
             * @return  Filtered value of field.
             */
            return apply_filters('abp_repeater_display_filter',
                print_r($new_field_value, true), $field_id);
        }
    }
}


add_filter('bp_xprofile_set_field_data_pre_validate', 'nitro_bp_xprofile_set_field_data_pre_validate', 10, 3);
function nitro_bp_xprofile_set_field_data_pre_validate($value, $field, $field_type_obj) {
  if ($field_type_obj->name == 'Repeater') {

    $options = $field->get_children( true );
    if ($options) {
      foreach ($options as $o) {
        $fields = explode("\n", $o->name);
      }
    }

    $fields_count = count($fields);

    if (!empty($value)) {
      $i = 0;
      $batch_data = '';
      $batch_keys = array();
      foreach($value as $k => $v) {

        $batch_data .= $v;
        $batch_keys[] = $k;

        $i++;

        if($fields_count == $i) {

          // if there is nothing in the batch, then remove the batch
          if (trim($batch_data) == '') {
            foreach($batch_keys as $bk) {
              unset($value[$bk]);
            }
          }

          $i = 0; // reset $i
          $batch_data = ''; // reset batch data
          $batch_keys = array(); // reset the keys
        }
      }

    }
  }

  return $value;
}

add_filter( 'bp_get_the_profile_field_value', 'nitro_bp_get_the_profile_field_value', 10, 3 );
function nitro_bp_get_the_profile_field_value($value, $type, $field_id) {

  if ($type == 'repeater') {
    $field = new BP_XProfile_Field( $field_id );
    $options = $field->get_children( true );
    if ($options) {
      foreach ($options as $o) {
        $fields = explode("\n", $o->name);
      }
    }

    if (!empty($fields)) {

      $labels = array();

      foreach($fields as $f) {
        $line = explode('|', $f);
        $labels[] = $line[1];
      }
    }

    $field_values_array = maybe_unserialize($field->data->value);



    if (!empty($field_values_array)) {
      $i = $j = 0;
      $value = '';
      foreach($field_values_array as $field_value) {

        if (trim($field_value) != '') {
          $value .= '
            <div class="repeater_field_wrap">
              <h4 class="repeater_title">'. $labels[$i] .'</h4>
              <p class="repeater_data '. sanitize_title($labels[$i]) .'">'. make_clickable($field_value) .'</p>
            </div>
          ';
        }

        $i++;
        $j++;
        if ($i == count( $labels ) && $j < count($field_values_array)) {
          $i = 0;
          $value .= '<hr class="repeater_batch_splitter" />';
        }
      }
    }
  }

  return $value;

}


add_filter( 'bp_get_the_profile_field_value', 'nitro_urls_bp_get_the_profile_field_value', 11, 3 );
function nitro_urls_bp_get_the_profile_field_value($value, $type, $field_id) {

  if ($type == 'repeater' && strpos($value, 'Additional URLs') !== false) {
    $value = str_replace('<h4 class="repeater_title">Additional URLs</h4>', '', $value);
    $value = str_replace('<hr class="repeater_batch_splitter" />', '', $value);
  }

  return $value;
}


/**
 * Multi-selectbox xprofile field type COPY for OPSI.
 *
 * @since 2.0.0
 */
class BP_XProfile_Field_Type_Multiselectbox_OPSI extends BP_XProfile_Field_Type {

	/**
	 * Constructor for the multi-selectbox field type.
	 *
	 * @since 2.0.0
	 */
	public function __construct() {
		parent::__construct();

		$this->category = _x( 'Multi Fields', 'xprofile field type category', 'buddypress' );
		$this->name     = _x( 'Multi Select Box OPSI', 'xprofile field type', 'buddypress' );

		$this->supports_multiple_defaults = true;
		$this->accepts_null_value         = true;
		$this->supports_options           = true;

		// $this->set_format( '/^.+$/', 'replace' );
    $this->set_format( '/.*?/', 'replace' );

		/**
		 * Fires inside __construct() method for BP_XProfile_Field_Type_Multiselectbox_OPSI class.
		 *
		 * @since 2.0.0
		 *
		 * @param BP_XProfile_Field_Type_Multiselectbox_OPSI $this Current instance of
		 *                                                    the field type multiple select box.
		 */
		do_action( 'bp_xprofile_field_type_multiselectbox_opsi', $this );
	}

	/**
	 * Output the edit field HTML for this field type.
	 *
	 * Must be used inside the {@link bp_profile_fields()} template loop.
	 *
	 * @since 2.0.0
	 *
	 * @param array $raw_properties Optional key/value array of
	 *                              {@link http://dev.w3.org/html5/markup/select.html permitted attributes}
	 *                              that you want to add.
	 */
	public function edit_field_html( array $raw_properties = array() ) {

		// User_id is a special optional parameter that we pass to
		// {@link bp_the_profile_field_options()}.
		if ( isset( $raw_properties['user_id'] ) ) {
			$user_id = (int) $raw_properties['user_id'];
			unset( $raw_properties['user_id'] );
		} else {
			$user_id = bp_displayed_user_id();
		}

    $field = new BP_XProfile_Field(bp_get_the_profile_field_id());

    if ($field && $field->data) {
      $values = maybe_unserialize($field->data->value);
      $full_options = $field->get_children( true );
      $options = array();
      $compare_entries = array();
      if (!empty($full_options)) {
        foreach($full_options as $fo) {
          $options[] = $fo->name;
        }
        $compared_entries = array_diff($values, $options);
      }
    }

		$r = bp_parse_args( $raw_properties, array(
			'multiple' => 'multiple',
			'id'       => bp_get_the_profile_field_input_name() . '[]',
			'name'     => bp_get_the_profile_field_input_name() . '[]',
		) ); ?>


		<legend id="<?php bp_the_profile_field_input_name(); ?>-1">
			<?php bp_the_profile_field_name(); ?>
			<?php bp_the_profile_field_required_label(); ?>
		</legend>

		<?php

		/** This action is documented in bp-xprofile/bp-xprofile-classes */
		do_action( bp_get_the_profile_field_errors_action() ); ?>

		<select <?php echo $this->get_edit_field_html_elements( $r ); ?> aria-labelledby="<?php bp_the_profile_field_input_name(); ?>-1" aria-describedby="<?php bp_the_profile_field_input_name(); ?>-3">
			<?php bp_the_profile_field_options( array(
				'user_id' => $user_id
			) ); ?>
      <?php
        if (!empty($compared_entries)) {
          foreach($compared_entries as $custom_tags) {
            echo '<option value="'. $custom_tags .'" selected="selected">'. $custom_tags .'</option>';
          }
        }
      ?>
		</select>

		<?php if ( bp_get_the_profile_field_description() ) : ?>
			<p class="description" id="<?php bp_the_profile_field_input_name(); ?>-3"><?php bp_the_profile_field_description(); ?></p>
		<?php endif; ?>

		<?php if ( ! bp_get_the_profile_field_is_required() ) : ?>

			<a class="clear-value" href="javascript:clear( '<?php echo esc_js( bp_get_the_profile_field_input_name() ); ?>[]' );">
				<?php esc_html_e( 'Clear', 'buddypress' ); ?>
			</a>

		<?php endif; ?>
	<?php
	}

	/**
	 * Output the edit field options HTML for this field type.
	 *
	 * BuddyPress considers a field's "options" to be, for example, the items in a selectbox.
	 * These are stored separately in the database, and their templating is handled separately.
	 *
	 * This templating is separate from {@link BP_XProfile_Field_Type::edit_field_html()} because
	 * it's also used in the wp-admin screens when creating new fields, and for backwards compatibility.
	 *
	 * Must be used inside the {@link bp_profile_fields()} template loop.
	 *
	 * @since 2.0.0
	 *
	 * @param array $args Optional. The arguments passed to {@link bp_the_profile_field_options()}.
	 */
	public function edit_field_options_html( array $args = array() ) {
		$original_option_values = maybe_unserialize( BP_XProfile_ProfileData::get_value_byid( $this->field_obj->id, $args['user_id'] ) );

		$options = $this->field_obj->get_children();
		$html    = '';

		if ( empty( $original_option_values ) && ! empty( $_POST['field_' . $this->field_obj->id] ) ) {
			$original_option_values = sanitize_text_field( $_POST['field_' . $this->field_obj->id] );
		}

		$option_values = ( $original_option_values ) ? (array) $original_option_values : array();
		for ( $k = 0, $count = count( $options ); $k < $count; ++$k ) {
			$selected = '';

			// Check for updated posted values, but errors preventing them from
			// being saved first time.
			foreach( $option_values as $i => $option_value ) {
				if ( isset( $_POST['field_' . $this->field_obj->id] ) && $_POST['field_' . $this->field_obj->id][$i] != $option_value ) {
					if ( ! empty( $_POST['field_' . $this->field_obj->id][$i] ) ) {
						$option_values[] = sanitize_text_field( $_POST['field_' . $this->field_obj->id][$i] );
					}
				}
			}

			// Run the allowed option name through the before_save filter, so
			// we'll be sure to get a match.
			$allowed_options = xprofile_sanitize_data_value_before_save( $options[$k]->name, false, false );

			// First, check to see whether the user-entered value matches.
			if ( in_array( $allowed_options, $option_values ) ) {
				$selected = ' selected="selected"';
			}

			// Then, if the user has not provided a value, check for defaults.
			if ( ! is_array( $original_option_values ) && empty( $option_values ) && ! empty( $options[$k]->is_default_option ) ) {
				$selected = ' selected="selected"';
			}

			/**
			 * Filters the HTML output for options in a multiselect input.
			 *
			 * @since 1.5.0
			 *
			 * @param string $value    Option tag for current value being rendered.
			 * @param object $value    Current option being rendered for.
			 * @param int    $id       ID of the field object being rendered.
			 * @param string $selected Current selected value.
			 * @param string $k        Current index in the foreach loop.
			 */
			$html .= apply_filters( 'bp_get_the_profile_field_options_multiselect', '<option' . $selected . ' value="' . esc_attr( stripslashes( $options[$k]->name ) ) . '">' . esc_html( stripslashes( $options[$k]->name ) ) . '</option>', $options[$k], $this->field_obj->id, $selected, $k );
		}

		echo $html;
	}

	/**
	 * Output HTML for this field type on the wp-admin Profile Fields screen.
	 *
	 * Must be used inside the {@link bp_profile_fields()} template loop.
	 *
	 * @since 2.0.0
	 *
	 * @param array $raw_properties Optional key/value array of permitted attributes that you want to add.
	 */
	public function admin_field_html( array $raw_properties = array() ) {
		$r = bp_parse_args( $raw_properties, array(
			'multiple' => 'multiple'
		) ); ?>

		<label for="<?php bp_the_profile_field_input_name(); ?>" class="screen-reader-text"><?php
			/* translators: accessibility text */
			esc_html_e( 'Select', 'buddypress' );
		?></label>
		<select <?php echo $this->get_edit_field_html_elements( $r ); ?>>
			<?php bp_the_profile_field_options(); ?>
		</select>

		<?php
	}

	/**
	 * Output HTML for this field type's children options on the wp-admin Profile Fields,
	 * "Add Field" and "Edit Field" screens.
	 *
	 * Must be used inside the {@link bp_profile_fields()} template loop.
	 *
	 * @since 2.0.0
	 *
	 * @param BP_XProfile_Field $current_field The current profile field on the add/edit screen.
	 * @param string            $control_type  Optional. HTML input type used to render the current
	 *                                         field's child options.
	 */
	public function admin_new_field_html( BP_XProfile_Field $current_field, $control_type = '' ) {
		parent::admin_new_field_html( $current_field, 'checkbox' );
	}

  public function is_valid( $values ) {
      $validated = false;

      // Some types of field (e.g. multi-selectbox) may have multiple values to check
      foreach ( (array) $values as $value ) {

          // Validate the $value against the type's accepted format(s).
          foreach ( $this->validation_regex as $format ) {
              if ( 1 === preg_match( $format, $value ) ) {
                  $validated = true;
                  continue;

              } else {
                  $validated = false;
              }
          }
      }

      // Handle field types with accepts_null_value set if $values is an empty array
      if ( ! $validated && is_array( $values ) && empty( $values ) && $this->accepts_null_value ) {
          $validated = true;
      }

      return (bool) apply_filters( 'bp_xprofile_field_type_is_valid', $validated, $values, $this );
  }
}


add_filter( 'widget_title', 'nitro_my_friends_widget_title', 10, 1 );
function nitro_my_friends_widget_title($title) {

  if(strpos($title, bp_get_displayed_user_fullname(). '&#8217;s Friend') !== FALSE) {
      $title = str_replace(bp_get_displayed_user_fullname(). '&#8217;s', '', $title);
  }


  return $title;
}

add_filter('bp_get_the_profile_field_required_label', 'nitro_bp_get_the_profile_field_required_label');
function nitro_bp_get_the_profile_field_required_label($label) {

  return '*';
}

// add_action('bp_profile_field_item', 'nitro_bp_profile_field_item');
function nitro_bp_profile_field_item() {
  global $profile_template;
  // $profile_template->current_field++;
  if (trim(bp_get_the_profile_field_name()) == 'Name') {
    $userdata = get_userdata( bp_displayed_user_id() );
    ?>
    <tr <?php bp_field_css_class(); ?>>

      <td class="label"><?php echo __('Email'); ?></td>

      <td class="data"><?php echo '<a href="mailto:'. $userdata->user_email .'">'. $userdata->user_email .'</a>'; ?></td>

    </tr>
    <?php
  }
}


add_action( 'xprofile_updated_profile', 'nitro_buddypress_profile_update', 10, 5 );
function nitro_buddypress_profile_update( $user_id, $posted_field_ids, $errors, $old_values, $new_values ) {

  if ($old_values[51]['value'] == $new_values[51]['value']) {
    return;
  }

   $admin_email = get_option( 'admin_email' );
   $message = sprintf( __( 'Member: %1$s', 'buddypress' ), bp_core_get_user_displayname( $user_id ) ) . "\r\n\r\n";
   $message .= get_edit_user_link($user_id)."\r\n\r\n";
   $message .= sprintf( __( 'NEW Organisation type: %s' ), bp_get_profile_field_data('field=Organisation type') ). "\r\n\r\n";
   $message .= sprintf( __( 'Old Organisation type: %s' ), $old_values[51]['value'] ). "\r\n\r\n";
   wp_mail( $admin_email, sprintf( __( '%1$s Member Profile Update' ), get_option('blogname') ), $message );
}


add_filter( 'bp_xprofile_set_field_data_pre_validate',  'nitro_xprofile_filter_pre_validate_value_by_field_type', 9, 3 );
function nitro_xprofile_filter_pre_validate_value_by_field_type( $value, $field, $field_type_obj ) {

  if ($field->name == 'Twitter' && filter_var($field->data->value, FILTER_VALIDATE_URL) === FALSE) {
    $value = str_replace('twitter.com', '', $value);
    $value = 'https://twitter.com/'. $value;
  }

	return $value;
}


/************** ADD Innovations / Case Study SubTab  START ************/

add_action( 'bp_setup_nav', 'profile_tab_innovations' );
function profile_tab_innovations() {
    global $bp;

	$user_id = bp_displayed_user_id();
	$current_user_id = bp_loggedin_user_id();

	$count_args_owner = array(
		'post_type' => array ( 'case' ),
		'post_status' => array( 'any', 'archive', 'pending_deletion', 'reviewed' )
	);
	$count_args_guest = array(
		'post_type' => array ( 'case' ),
		'post_status' => array( 'publish' )
	);

	$all_posts = nitro_get_user_posts_count( $user_id, $count_args_owner );
	$published_posts = nitro_get_user_posts_count( $user_id, $count_args_guest );

	$count_inno = 0;

	if ( $user_id == $current_user_id ) {
		$count_inno = $all_posts;
	} else {
		$count_inno = $published_posts;
	}

	$innonum = '';

	if ( $count_inno == 0 ) {
		$innonum = ' <span class="no-count">'. $count_inno .'</span>';
	} else {

		$innonum = ' <span class="count">'. $count_inno .'</span>';

	}

      bp_core_new_nav_item( array(
            'name' => __( 'Innovations', 'opsi' ).$innonum,
            'slug' => 'innovations',
            'screen_function' => 'nitro_my_innovations_screen',
            'position' => 70,
            'parent_url'      => bp_loggedin_user_domain() . '/innovations/',
            'parent_slug'     => $bp->profile->slug,
            'default_subnav_slug' => 'innovations'
      ) );
}


function nitro_my_innovations_screen(){
    global $bp;
    add_action( 'bp_template_title', 'bp_my_innovations_screen_title' );
    add_action( 'bp_template_content', 'bp_my_innovations_screen_content' );
	// add_filter( 'bp_get_template_part', 'bp_innovations_template_part_filter', 10, 3 );
    bp_core_load_template( array ( apply_filters( 'bp_core_template_plugin', 'members/single/plugins' ) ) );
}

function bp_innovations_template_part_filter( $templates, $slug, $name ) {

	if ( 'members/single/activity' != $slug ) {
		return $templates;
	}
	return bp_get_template_part( 'members/single/plugins' );
    // bp_core_load_template( array ( apply_filters( 'bp_core_template_plugin', 'members/single/plugins' ) ) );
}

function bp_my_innovations_screen_title() {
    // global $bp;
	// echo __( 'Innovations', 'opsi' );
	return;

}
function bp_my_innovations_screen_content() {
    global $bp;


	/**
	 * Fires before the display of the member activity post form.
	 *
	 * @since 1.2.0
	 */
	do_action( 'bp_before_member_activity_post_form' ); ?>

	<?php
	if ( is_user_logged_in() && bp_is_my_profile() && ( !bp_current_action() || bp_is_current_action( 'just-me' ) ) )
		bp_get_template_part( 'activity/post-form' );

	/**
	 * Fires after the display of the member activity post form.
	 *
	 * @since 1.2.0
	 */
	do_action( 'bp_after_member_activity_post_form' );

    echo bp_get_author_innovations_list();

}

// build author Innovations / Case Studies list
function bp_get_author_innovations_list( $user_id = 0 ) {

	if ($user_id == 0) {
      $user_id = bp_displayed_user_id();
    }
    if (!$user_id) {
      return false;
    }

    $current_user_id = bp_loggedin_user_id();

	if ( $user_id == $current_user_id ) {
		return bp_innovation_list_owner();
	} else {
		return bp_innovation_list_guest();
	}

}

function bp_innovation_list_owner() {

	// WP_Query arguments
	$args = array(
		'post_type'		=> array( 'case' ),
		'post_status'	=> array( 'any', 'pending_deletion', 'archive', 'reviewed' ),
		'author'		=> bp_loggedin_user_id(),
		'posts_per_page'=> -1

	);

	// The Query
	$query = new WP_Query( $args );

	$out  = '';

	ob_start();

	// The Loop
	if ( $query->have_posts() ) {

		?>
		<div class="table-responsive">
			<table class="table table-striped table-hover">
				<thead>
					<th><?php echo __( 'Title', 'opsi' ); ?></th>
					<th><?php echo __( 'Status', 'opsi' ); ?></th>
					<th class="text-center" colspan="4"><?php echo __( 'Actions', 'opsi' ); ?></th>
				</thead>
				<tbody>

		<?php

		while ( $query->have_posts() ) {
			$query->the_post();

			// get assigned users
			$no_existing_colabs = count_collaborators( get_the_ID() );
			$post_status_obj = get_post_status_object( get_post_status( get_the_ID() ) );
			// get primary type
			$primary_type = get_field('primary_case_type', get_the_ID());
			if( 'open-government' == $primary_type ) {
				$edit_url = get_field('case_study_form_page_open_gov', 'option');
			} else {
				$edit_url = get_field('case_study_form_page', 'option');
			}
			?>

			<tr <?php if ( get_post_status( get_the_ID() ) == 'archive' ) { echo ' class="warning archive"'; } ?> >
				<td>
					<?php
						// if ( get_post_status( get_the_ID() ) == 'publish' ) { $post_url = get_permalink(); }
						// else {
							$post_url = get_preview_post_link(get_the_ID());
							$post_url = str_replace( '&preview=true', '', $post_url );
							$post_url = str_replace( '?preview=true', '', $post_url );
						// }
					?>
					<a href="<?php echo $post_url ?>" title="<?php echo __( 'view', 'opsi' ); ?>">
						<?php the_title(); ?>
					</a>
				</td>
				<td>
					<?php

						if ( get_post_status( get_the_ID() ) == 'pending' ) {
							echo __( 'Submitted (pending review)', 'opsi' );
						} else {
							echo $post_status_obj->label;
						}
					?>
				</td>
				<td>
					<a href="<?php the_permalink(); ?>" title="<?php echo __( 'view', 'opsi' ); ?>">
						<i class="fa fa-search" aria-hidden="true"></i>
					</a>
				</td>
				<td>
					<a href="<?php echo get_the_permalink( $edit_url ).'?edit='. get_the_ID(); ?>" title="<?php echo __( 'edit', 'opsi' ); ?>">
						<i class="fa fa-pencil-square-o" aria-hidden="true"></i>
					</a>
				</td>
				<td>

					<a href="<?php echo get_the_permalink( get_field('case_study_add_collaborator_page', 'option') ).'?edit='. get_the_ID(); ?>" title="<?php echo __( 'Manage collaborators', 'opsi' ); ?>">
						<i class="fa fa-user-plus" aria-hidden="true"></i>
						<sup><?php echo $no_existing_colabs; ?></sup>
					</a>

				</td>
				<td>
				<?php
					$get_post_status = get_post_status();
					if ( can_delete_cs( get_the_ID(), bp_loggedin_user_id() ) ) { ?>
					<a href="<?php echo get_the_permalink( get_field('case_study_form_page', 'option') ).'?delete='. get_the_ID(); ?>" title="<?php echo __( 'remove', 'opsi' ); ?>" class="danger">
						<i class="fa fa-trash-o" aria-hidden="true"></i>
					</a>
				<?php } ?>
				</td>
			</tr>

			<?php

		}

		?>
				</tbody>
			</table>
		</div>
		<?php

	} else {

		?>
		<div id="message" class="info">
			<p><?php echo __( 'Sorry, there was no entries found.', 'opsi' ); ?></p>
		</div>
		<?php
	}

	// Restore original Post Data
	wp_reset_postdata();

	$out = ob_get_clean();

	return $out;

}

function bp_innovation_list_guest() {

	// WP_Query arguments
	$args = array(
		'post_type'		=> array( 'case' ),
		'post_status'	=> array( 'publish' ),
		'author'		=> bp_displayed_user_id(),
		'posts_per_page'=> -1
	);

	// The Query
	$query = new WP_Query( $args );

	$out  = '';

	ob_start();

	// The Loop
	if ( $query->have_posts() ) {

		?>
			<ul>

		<?php

		while ( $query->have_posts() ) {
			$query->the_post();
			?>

			<li><a href="<?php the_permalink(); ?>" title="<?php echo __( 'view', 'opsi' ); ?>"><?php the_title(); ?></a></li>

			<?php

		}

		?>
			</ul>
		<?php

	} else {

		?>
		<div id="message" class="info">
			<p><?php echo __( 'Sorry, there was no entries found.', 'opsi' ); ?></p>
		</div>
		<?php
	}

	// Restore original Post Data
	wp_reset_postdata();

	$out = ob_get_clean();

	return $out;

}


/************** ADD Innovations / Case Study SubTab  END ************/


/************** ADD Saved toolkits SubTab  START ************/
add_action( 'bp_setup_nav', 'bs_saved_toolkits_tab' );
function bs_saved_toolkits_tab() {
	global $bp;

	$toolkits = get_posts(
		array(
			'post_type' => 'toolkit',
			'posts_per_page' => -1,
			'orderby' => 'title',
			'order' => 'ASC',
			'meta_query' => array(
				array(
					'key' => 'saved',
					'value' => 'i:'.get_current_user_id().';',
					'compare' => 'LIKE'
				)
			)
		)
	);

	$count = count( $toolkits );
	$class = 'no-count';

	if ( $count ) {
		$class = 'count';
	}

	$toolkits_num = sprintf( '<span class="%s">%s</span>', $class, $count );

	bp_core_new_nav_item( array(
		'name' => __( 'Toolkits', 'opsi' ) . '&nbsp;' . $toolkits_num,
		'slug' => 'toolkits',
		'screen_function' => 'bs_toolkits_screen',
		'position' => 80,
		'parent_url' => bp_loggedin_user_domain() . '/toolkits/',
		'parent_slug' => $bp->profile->slug,
		'default_subnav_slug' => 'toolkits'
	) );
}

function bs_toolkits_screen() {
	add_action( 'bp_template_title', 'bs_toolkits_tab_title' );
	add_action( 'bp_template_content', 'bs_toolkits_tab_content' );
	bp_core_load_template( 'buddypress/members/single/plugins' );
}

function bs_toolkits_tab_title() {
	return;
}

function bs_toolkits_tab_content() {
	global $bp;


	/**
	 * Fires before the display of the member activity post form.
	 *
	 * @since 1.2.0
	 */
	do_action( 'bp_before_member_activity_post_form' );

	if ( is_user_logged_in() && bp_is_my_profile() && ( !bp_current_action() || bp_is_current_action( 'just-me' ) ) ) {
		bp_get_template_part( 'activity/post-form' );
	}

	/**
	 * Fires after the display of the member activity post form.
	 *
	 * @since 1.2.0
	 */
	do_action( 'bp_after_member_activity_post_form' );

	$posts = get_posts(
		array(
			'post_type' => 'toolkit',
			'posts_per_page' => -1,
			'orderby' => 'title',
			'order' => 'ASC',
			'meta_query' => array(
				array(
					'key' => 'saved',
					'value' => 'i:'.get_current_user_id().';',
					'compare' => 'LIKE'
				)
			)
		)
	);

	echo '<div class="saved-toolkits">';
	if ( !empty( $posts ) ) {
		foreach ( $posts as $post ) {
			$url = get_permalink( $post->ID );
			$publisher = wp_get_post_terms( $post->ID, 'toolkit-publisher' );
			$img = sprintf(
				'<a href="%s">%s</a>',
				$url,
				get_the_post_thumbnail( $post->ID, 'thumbnail' )
			);
			$content = sprintf(
				'<div class="title"><a href="%s">%s</a></div><div class="publisher">%s</div><div class="description">%s</div>',
				$url,
				$post->post_title,
				is_array( $publisher ) ? $publisher[0]->name : '',
				get_post_meta( $post->ID, 'description', true )
			);
			printf(
				'<div class="row toolkit"><div class="col-md-4 col-sm-4 img">%s</div><div class="col-md-8 col-sm-8 body">%s</div></div>',
				$img,
				$content
			);
		}
	} else {
		?>
		<div id="message" class="info">
			<p><?php echo __( 'Sorry, there was no entries found.', 'opsi' ); ?></p>
		</div>
		<?php
	}
	echo '</div>';
}

/************** ADD Saved toolkits SubTab  END ************/


  function bd_fetch_all_user_fields($user_id = 0) {
    if ($user_id == 0) {
      $user_id = bp_displayed_user_id();
    }
    if (!$user_id) {
      return false;
    }

    $result = array();
    $current_user_id = bp_loggedin_user_id();

    if ( bp_has_profile('user_id='. $user_id) ) {
      while ( bp_profile_groups() ) {
        bp_the_profile_group();

        if ( bp_profile_group_has_fields() ) {


          // bp_the_profile_group_name();
          while ( bp_profile_fields() ) {

            bp_the_profile_field();

            if ( bp_field_has_data() ) {

              $hidden_fields = bp_xprofile_get_hidden_fields_for_user($user_id, $current_user_id);
              $field_id = bp_get_the_profile_field_name();

              if(!in_array($field_id, $hidden_fields)){
                $args = array(
                'field' => $field_id, // Field ID or name.
                'user_id' => $user_id // Default -- It is profile owner id
                );
                $data = bp_get_profile_field_data($args);

                if (!is_array($data) && strip_tags($data) != '') {
                  $result[''.bp_get_the_profile_field_name().''] = bp_get_the_profile_field_value();
                }
              }

            }
          }
        }
      }

      return $result;
    } else {

      return false;

    }
  }

function bp_custom_get_send_private_message_link($to_id, $subject=false, $message=false) {

	//if user is not logged, do not prepare the link
	// if ( !is_user_logged_in() )
	// return false;

	$compose_url= bp_loggedin_user_domain() . bp_get_messages_slug() . '/compose/?';
	if($to_id) {
		$compose_url.=( 'r=' . bp_core_get_username( $to_id ) );
	}
	if($subject) {
		$compose_url.=( '&subject='.$subject );
	}
	if($message) {
		$compose_url.=( '&content='.$message );
	}

	return wp_nonce_url( $compose_url ) ;
}


add_action ( 'set_user_role', 'opsi_notify_pending_user', 10, 3 );
function opsi_notify_pending_user( $user_id, $role, $old_roles ) {

	if ( !in_array( 'pending', $old_roles ) ) {
		return;
	}

	if ( $role && $role != 'pending' ) {

		// notify the user
		$subject = get_field('approved_users_email_-_subject','option');
		$body    = get_field('approved_users_email_-_body','option').'<br><a href="'. bp_core_get_user_domain( $user_id ) .'">Visit your profile here</a>.';
		$headers = array('Content-Type: text/html; charset=UTF-8');
		wp_mail( get_the_author_meta( 'user_email', $user_id ), $subject, $body, $headers );

	}

}
