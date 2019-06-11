jQuery(document).ready(function(){

	var uploader_btn = '';

	if (jQuery(".image_uploader .acf-gallery-add").length > 0) {
		jQuery(".image_uploader .acf-gallery-add").on('click', function() {
			uploader_btn = 'Add images to gallery';
		});
	}

	if (jQuery(".file_uploader .acf-gallery-add").length > 0) {
		jQuery(".file_uploader .acf-gallery-add").on('click', function() {
			uploader_btn = 'Add supporting files';
		});
	}


	// fix year first value
	if (jQuery("#acf-field_5ae73ec09f20d-field_5ae741469f211").length > 0) {
		var initialValue = jQuery(".acf-field-5ae741469f211 input").attr('max');
		var spinner = jQuery("#acf-field_5ae73ec09f20d-field_5ae741469f211");

		function setDefaultValue() {
		  jQuery(this).val(initialValue);
		  jQuery(this).off("input", setDefaultValue);
		}

		spinner.on("input", setDefaultValue);
	}


	if (typeof wp.Uploader === 'function') {
		jQuery.extend( wp.Uploader.prototype, {
			init : function() { // plupload 'PostInit'

				console.log( this.uploader );

				jQuery( '.media-frame-title h1' ).html( uploader_btn + '<span class="dashicons dashicons-arrow-down"></span>' );
			}
		});
	}

	if (jQuery("#archivetoggle").length > 0) {
		jQuery('#archivetoggle').change(function() {
			var url = window.location.href;

			if( jQuery( this ).is( ":checked" ) ) {

				if ( url.indexOf('?') > -1){
				   url += '&archive=1'
				}else{
				   url += '?archive=1'
				}
				window.location.href = url;

			} else {

				url = removeParam( 'archive', url );
				window.location.href = url;


			}

		});
	}

	if (jQuery(".sidewrap_csfilters h2.widget-title").length > 0) {
		jQuery(".sidewrap_csfilters h2.widget-title").on('click', function() {

			jQuery( this ).closest('aside.sidebar-box').find('.widget_content').toggle();

		});
	}
	if (jQuery(".hidemaptoggle").length > 0) {
		jQuery(".hidemaptoggle").on('click', function() {

			jQuery( '.cs_top_map' ).toggle();

		});
	}

	jQuery(document).on('facetwp-loaded', function() {
        // Scroll to the top of the page after the page is refreshed
        jQuery('.facetwp-per-page-select>option:first-child, .facetwp-sort-select>option:first-child').text('---');
    });

	jQuery( '.file_uploader a.acf-gallery-add' ).text( 'Add files' );
	jQuery( '.acf-field-gallery a.acf-gallery-add' ).addClass( 'btn btn-primary' );

	if (jQuery(".sliding_panel>h2").length > 0) {

		jQuery(".sliding_panel>h2").on( 'click', function() {
			jQuery( this ).toggleClass( 'active' );
			jQuery( this ).closest( '.sliding_panel' ).find( '.panel_content' ).slideToggle(200);
			jQuery( this ).closest( '.sliding_panel' ).toggleClass( 'open' );
		});
	}
	if (jQuery("#case_form").length > 0) {

		if ( jQuery( '.updatedalert' ).length > 0 ) {

			setTimeout(function () {
				jQuery( '.updatedalert' ).fadeOut();
			}, 5000);
		}

		// reset step navigation status
		jQuery("#case_form #acf-form .acf-form-fields>div.acf-field-group.stepgroup").on( 'click', function() {

			var stepindex = jQuery(this).index( '.stepgroup' );

			jQuery( '#acf_steps li.step_'+stepindex ).removeClass( 'haserror' );

		});

		jQuery("#case_form #acf-form .acf-form-fields>div.acf-field-group.stepgroup").each(function(index) {

			var groupthis = jQuery(this);

			groupthis.addClass('step-'+index);

			jQuery('#acf_steps').append('<li class="form-step step_'+ index +'" data-step="'+ index +'"><a href="#step-'+ index +'"><span class="stepliwrap"><span class="step_button_text">' + groupthis.find('>.acf-label>label').text() + '</span> <span class="stepicon"><i class="fa fa-times-circle-o fa-stack-2x fai"></i><span class="fa-stack fa-lg fai ellipsis"><i class="fa fa-circle-o fa-stack-2x"></i><i class="fa fa-ellipsis-h fa-stack-1x"></i></span><i class="fa fai fa-check-circle-o fa-stack-2x"></i><i class="fa fai fa-clock-o fa-stack-2x"></i></span></span><span class="nextdown"><i class="fa fa-chevron-down" aria-hidden="true"></i></span></a></li>');

		});

		// jQuery('.form-step.step_0 .step_button_text').text('Start');
		jQuery('#acf_steps').append('<li class="form-step last_step"><span class="stepliwrap"><span class="step_button_text">Finish</span> <span class="stepicon"><span class="fa-stack fa-lg fai flag"><i class="fa fa-circle-o fa-stack-2x"></i><i class="fa fa-flag-checkered fa-stack-1x"></i></span></span></span><span class="nextdown"><i class="fa fa-chevron-down" aria-hidden="true"></i></span></li>');

		jQuery('#acf_steps>li:first-child').addClass('active');
		jQuery('.stepform .acf-field-group.stepgroup.step-0').addClass('active');

		jQuery('#acf_steps li').on('click', function() {

			if ( jQuery( this ).hasClass( 'last_step' ) ) {
				return false;
			}

			var stepnum = jQuery(this).data('step');

			jQuery('#acf_steps li').removeClass('active');
			jQuery(this).addClass('active');
			jQuery('.stepform .acf-field-group.stepgroup').addClass('inactive').removeClass('active');
			jQuery('.stepform .step-'+ stepnum).removeClass('inactive').addClass('active');
		});


		// if there is hashtag
		if( window.location.hash ) {
			gotostep();
		}

		jQuery(window).on('hashchange', function() {
			gotostep();
		});

		// reset the website url field
		function reset_website_url() {

			if ( jQuery( '.acf-field-url[data-name="innovation_website"] input' ).val() == 'http://' ) {
				jQuery( '.acf-field-url[data-name="innovation_website"] input' ).val( '' );
			}

		}


		// Case Study form SUBMIT for review
		jQuery( 'a.submitform' ).on( 'click', function(e) {

			e.preventDefault();
			reset_website_url();
			jQuery( 'a.saveform' ).addClass( 'disabled' );
			jQuery( 'a.submitform' ).addClass( 'disabled' );
			jQuery( '#csf_action' ).val( 'submit' );
			jQuery ( '.acf-form-submit input.acf-button' ).trigger( 'click' );

			if (jQuery( '.stepform .layout_hero_block .acf-spinner' ).length > 0) {
				jQuery( '.stepform .layout_hero_block .acf-spinner' ).addClass( 'is-active' );
			}

			return false;

		});

		// Case Study form SAVE as draft
		jQuery( 'a.saveform' ).on( 'click', function(e) {

			e.preventDefault();
			reset_website_url();
			jQuery( 'a.saveform' ).addClass( 'disabled' );
			jQuery( 'a.submitform' ).addClass( 'disabled' );
			jQuery('input').removeAttr( 'required' );
			jQuery('textarea').removeAttr( 'required' );
			jQuery( '#csf_action' ).val( 'save' );
			jQuery ( '.acf-form-submit input.acf-button' ).trigger( 'click' );

			if (jQuery( '.stepform .layout_hero_block .acf-spinner' ).length > 0) {
				jQuery( '.stepform .layout_hero_block .acf-spinner' ).addClass( 'is-active' );
			}

			return false;

		});

		// Case Study form SAVE as draft
		jQuery( 'a.saveandpreview' ).on( 'click', function(e) {

			e.preventDefault();
			reset_website_url();
			jQuery( 'a.saveform' ).addClass( 'disabled' );
			jQuery( 'a.submitform' ).addClass( 'disabled' );
			jQuery('input').removeAttr( 'required' );
			jQuery('textarea').removeAttr( 'required' );
			jQuery( '#csf_action' ).val( 'saveandpreview' );
			jQuery ( '.acf-form-submit input.acf-button' ).trigger( 'click' );

			return false;

		});


		acf.add_filter('validation_complete', function( json, $form ){

			// if errors?
			// if( json.errors ) {

			setTimeout(function () {
				if( jQuery( '.acf-notice.acf-error-message' ).length > 0 ) {


					if (jQuery( '.stepform .formbuttons .acf-spinner' ).length > 0) {
						jQuery( '.stepform .formbuttons .acf-spinner' ).removeClass( 'is-active' );
					}


					jQuery( '.stepgroup' ).each( function(index) {

						console.log(jQuery( '.stepgroup.step-'+index ).find( '.acf-error' ).text());

						jQuery( '.form-step.step_'+index ).removeClass( 'noerror' );
						jQuery( '.form-step.step_'+index ).removeClass( 'haserror' );

						if ( jQuery( '.stepgroup.step-'+index ).find( '.acf-error' ).length > 0 ) {
							jQuery( '.form-step.step_'+index ).addClass( 'haserror' );
						} else {
							jQuery( '.form-step.step_'+index ).addClass( 'noerror' );
						}

					});

					// get first step to have error
					if ( jQuery( '.form-step.haserror' ).length > 0 ) {
						var thefirstgroup = jQuery( '.form-step.haserror' ).first().attr( 'data-step' );
						jQuery( '.form-step.step_'+thefirstgroup ).trigger( 'click' );
						window.location.hash = '#step-'+thefirstgroup;
					}

				}

			}, 500);


			jQuery( 'a.saveform' ).removeClass( 'disabled' );
			jQuery( 'a.submitform' ).removeClass( 'disabled' );


			// return
			return json;

		});


	}

	function gotostep() {

		var thehash = window.location.hash;

		// if ( thehash.includes( 'step-' ) ) {
		if ( thehash.indexOf( 'step-' ) >= 0 ) {
			var stepparts = thehash.split( '-' );

			if ( jQuery( '.form-step.step_'+stepparts[1] ).length > 0 ) {

				jQuery( '.form-step.step_'+stepparts[1] ).trigger( 'click' );

				jQuery( '#form_step_field' ).val( stepparts[1] );
			}
		}

	}

  if (jQuery('body.logged-in .mc4wp-checkbox.mc4wp-checkbox-wp-comment-form').length > 0) {
    jQuery('body.logged-in .mc4wp-checkbox.mc4wp-checkbox-wp-comment-form input[type="checkbox"]').attr('checked', false); // Unchecks it for logged in users
  }
  if (jQuery(".input-options.datebox-selects>select:first-of-type").length > 0) {
    jQuery(".input-options.datebox-selects>select:first-of-type").val(1);
  }
  if (jQuery(".opsi_date_month").length > 0) {
    jQuery('.opsi_date_month, .opsi_date_year').on('change', function() {

      var opsi_date_field_wrap = jQuery(this).parents('.opsi_date_field_wrap');
      opsi_date_field_wrap.children('.opsi_date_field_value').val(opsi_date_field_wrap.children('.opsi_date_month').val()+'/'+opsi_date_field_wrap.children('.opsi_date_year').val());

    });
  }

  if (jQuery(".field_type_multiselectbox_opsi select").length > 0) {
    jQuery(".field_type_multiselectbox_opsi select").select2({
      tags: true
    });
  }

  if (jQuery('#groups-list-options #alphabetical-groups').length > 0) {
    jQuery('#groups-list-options #alphabetical-groups').text('A-Z');
  }

  if (jQuery('#groups-list-options #newest-groups').length > 0) {
    jQuery('#groups-list-options #newest-groups').text('New');
  }

  jQuery('li.archive-accordion-year').click(function() {
			// Change CSS of current year
      jQuery('li.archive-accordion-year').not(this).find('.fa').removeClass('fa-chevron-up').addClass('fa-chevron-down');
			jQuery('li.archive-accordion-year').not(this).children('ul').slideUp(250);
			jQuery(this).find('.fa').toggleClass('fa-chevron-down').toggleClass('fa-chevron-up');
			jQuery(this).children('ul').slideToggle(250);

	});


  jQuery('article table').addClass('table table-striped table-hover table-responsive');
  jQuery('article table thead tr td').attr('data-sortable', 'true');

  var attrs = { };

  if (jQuery("article table thead tr td").length > 0) {
    jQuery.each(jQuery("article table thead tr td")[0].attributes, function(idx, attr) {
        attrs[attr.nodeName] = attr.nodeValue;
    });
  }


  jQuery("article table thead tr td").replaceWith(function () {
      return jQuery("<th />", attrs).append(jQuery(this).contents());
  });

  toggleCollapse();
  function toggleCollapse() {
    if (jQuery(window).width() < 768) {
      jQuery('.layout_header h2').on('click', function(e) {
        e.preventDefault();
        jQuery(this).toggleClass('open');
        jQuery(this).closest('.layout_posts_block').find('.collapse-xs').toggleClass('in');
        return false;
      });
      jQuery('.sidewrap h2.widget-title').on('click', function(e) {
        e.preventDefault();
        jQuery(this).toggleClass('open');
        jQuery(this).closest('aside').find('.collapse-xs').toggleClass('in');
        return false;
      });
      jQuery('.search_mobile').on('click', function(e) {
        e.preventDefault();
        jQuery('.mobile_search_form').toggleClass('in');
        return false;
      });
    }
  }

  jQuery('.droptoggle').on('click', function(e) {
    e.preventDefault();

    jQuery(this).closest('li').addClass('clicked');
    jQuery('#primary-menu li:not(.clicked)').removeClass('open');
    jQuery(this).closest('li').toggleClass('open').removeClass('clicked');

    return false;
  });

  jQuery('.primarymenu li').hover(
    function() {
      jQuery( this ).addClass('parentactive');
    }, function() {
      jQuery( this ).removeClass('parentactive');
    }
  );


  jQuery(function () {
    setTimeout(function() {
      jQuery('select.goog-te-combo > option:first-child').text('English');
    }, 1000);
  });


  var ie_version  =  getIEVersion();
  // var is_ie10     = ie_version.major == 10;

  //build a selector that targets all links ending in an image extension
  var thumbnails = 'a[href$=".gif"],a[href$=".jpg"],a[href$=".jpeg"],a[href$=".png"],a[href$=".GIF"],a[href$=".JPG"],a[href$=".JPEG"],a[href$=".PNG"]';

  //add "fancybox" class to those (this step could be skipped if you want) and group them under a same "rel" to enable previous/next buttons once in Fancybox
  jQuery(thumbnails).addClass("fancybox");

  jQuery(".fancybox").fancybox({
		openEffect	: 'none',
		closeEffect	: 'none',
    helpers : {
      title : {
        type : 'inside'
      },
      overlay: {
        locked: false
      }
    }
	});

  jQuery('.layout_accordion .panel-title a').on('click', function() {
    var panelgroup = jQuery(this).closest('.panel-group');

    setTimeout(function(){
      panelgroup.find('div.panel').each(function() {
        if (jQuery(this).find('.panel-collapse').hasClass('in')) {
          jQuery(this).find('.panel-title a').addClass('open');
        } else {
          jQuery(this).find('.panel-title a').removeClass('open');
        }
      });
    }, 400);
  });

  jQuery('.social-wrap a').attr('target', '_blank');
  jQuery('.blank').attr('target', '_blank');

  function getIEVersion(){
    var agent = navigator.userAgent;
    var reg = /MSIE\s?(\d+)(?:\.(\d+))?/i;
    var matches = agent.match(reg);
    if (matches != null) {
        return { major: matches[1], minor: matches[2] };
    }
    return { major: "-1", minor: "-1" };
  }

  // Open youtube links in a new window

  jQuery(".container a[href^='http://www.youtube.com']").attr("target","_blank");
  jQuery(".container a[href^='https://www.youtube.com']").attr("target","_blank");



  // full_width_image START

  fwi_resize();

  jQuery( window ).resize(function() {
    fwi_resize();
    toggleCollapse();
  });

  function fwi_resize() {

    var windowh = jQuery( window ).height();

    jQuery('.percenth').each(function(){

      if (jQuery(this).hasClass('height25')) {
        jQuery(this).height(windowh * 0.25);
        jQuery(this).parent().find('.overlay_color').css({'height': '300%'});
      }
      if (jQuery(this).hasClass('height50')) {
        jQuery(this).height(windowh * 0.5);
        jQuery(this).parent().find('.overlay_color').css({'height': '250%'});
      }
      if (jQuery(this).hasClass('height75')) {
        jQuery(this).height(windowh * 0.75);
        jQuery(this).parent().find('.overlay_color').css({'height': '250%'});
      }
      if (jQuery(this).hasClass('height100')) {
        jQuery(this).height(windowh);
        jQuery(this).parent().find('.overlay_color').css({'height': '250%'});
      }

      var overheight = jQuery(this).parent().find('.overlay_color').height();

      jQuery(this).parent().find('.overlay_color').css({'margin-top': -(overheight / 3) + 'px'});

    });
  }

  // full_width_image END


  jQuery(function() {
    jQuery('.row .pb_title').matchHeight();
    jQuery('.row .post_col').matchHeight();
    jQuery('.row .pb_excerpt').matchHeight();
  });



	jQuery('a.gobackjs').on('click', function(e) {
		e.preventDefault();
		window.history.back();
		return false;
	});

	jQuery('a.gonext, a.goback').on('click', function(e) {
		goToByScroll('formtop');
	});

	jQuery('a').on('click', function(e) {
		var attr = jQuery(this).attr('data-scroll-to');
		if (typeof attr !== typeof undefined && attr !== false && jQuery('#'+attr).length > 0) {
		  e.preventDefault();
		  jQuery('html, body').animate({
			  scrollTop: (jQuery('#'+attr).offset().top - 150)
		  }, 1000);
		  return false;
		}
	});


	function goToByScroll(id){
		// Remove "link" from the ID
		// id = id.replace("link", "");
		jQuery('html,body').animate({scrollTop: (jQuery("#"+id).offset().top - 50)},'fast');
	}


	function removeParam(key, sourceURL) {
		var rtn = sourceURL.split("?")[0],
			param,
			params_arr = [],
			queryString = (sourceURL.indexOf("?") !== -1) ? sourceURL.split("?")[1] : "";
		if (queryString !== "") {
			params_arr = queryString.split("&");
			for (var i = params_arr.length - 1; i >= 0; i -= 1) {
				param = params_arr[i].split("=")[0];
				if (param === key) {
					params_arr.splice(i, 1);
				}
			}
			rtn = rtn + "?" + params_arr.join("&");
		}
		return rtn;
	}


	function goBack() {
		window.history.back();
	}


});
