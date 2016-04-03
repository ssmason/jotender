jQuery(document).ready(function($) {

	var g_feed_base_url = '';
	var params = [];
	var xhr = '';

	var template_actions = $('#templates_list + .description');

	var validator = $('form[name=post]').validate({
		errorElement: "div",
		errorPlacement: function(error, element) {
			error.insertAfter( element.closest('label') );
		},
	});

	// Geocomplete.
	var geo_options = {
        details: "form",
        detailsAttribute: "data-geo",
	};

	if ( $('.geocomplete').length ) {
		$('.geocomplete').geocomplete( geo_options );
	}

	// Date picker.
	$('#from_date').datepicker({
		dateFormat: 'yy-mm-dd',
		changeMonth: true,
		onClose: function( selectedDate ) {
			$( "#to_date" ).datepicker( "option", "minDate", selectedDate );
			if ( ! $( "#to_date" ).val() ) {
				$( "#to_date" ).val( $( "#from_date" ).val() );
			}
		}
	});

	$('#to_date').datepicker({
		dateFormat: 'yy-mm-dd',
		changeMonth: true,
		onClose: function( selectedDate ) {
			$( "#from_date" ).datepicker( "option", "maxDate", selectedDate );
		}
	});


	init();


	// __Init.

	function init() {
		update_hidden_sections( '[section=taxonomies], [section=meta], [section=source]' );

		// Hide rows with hidden inputs.
		$('input.hidden').closest('tr').hide();

		$('#templates_list').val('');
		$('.rss-load-images').prop( 'checked', false );
	}


	// __Core Events.

	// On refresh templates.
	$(document).on( 'click', '.refresh-templates', function(e) {

		$('#templates_list option').filter( function() {
			return this.value;
		}).remove();

		if ( ! $('.goft_wpjm.processing.templates').length ) {

			template_actions.hide().after('<div class="goft_wpjm processing templates">&nbsp;</div>');

			$('.import-feed').prop( 'disabled', true );
		}

		var data = {
			action: 'goft_wpjm_update_templates_list',
			_ajax_nonce: goft_wpjm_admin_l18n.ajax_nonce,
		};

		$.post( goft_wpjm_admin_l18n.ajaxurl, data, function( response ) {

			if ( undefined !== response && undefined !== response.templates ) {

				$.each( response.templates, function( key, value ) {

					if ( $("#templates_list option[value='" + value + "']").length ) {
						return;
					}

					$('#templates_list').append( $( '<option>', { value : value } ).text( value ) );
				});

				$('#templates_list').trigger('change');
			}

			$('.goft_wpjm.processing.templates').remove();
			$('.import-feed').prop( 'disabled', false );

			template_actions.show();

		}, 'json' );


		e.preventDefault();
		return false;
	});

	// On load feed.
	$(document).on( 'click', '.import-feed', function(e) {

		//show_hide_setup( false );
		var url = $('#rss_feed_import').val();

		if ( ! url ) {
			alert( goft_wpjm_admin_l18n.msg_specify_valid_url );
			return;
		}

		enable_import( false );

		$('.goft_wpjm.no-jobs-found').remove();

		if ( ! $('.goft_wpjm.processing.feed').length ) {
			$('input[name=import_feed]').hide();
			$('input[name=import_feed]').after('<span class="goft_wpjm processing feed">&nbsp;</span>').after('<a class="button-secondary cancel-feed-load">' + goft_wpjm_admin_l18n.cancel_feed_load + '</a>');
			$('input', template_actions).prop( 'disabled', true );
		}

		var data = {
			action: 'goft_wpjm_import_feed',
			url: url,
			load_images: $('input[name=load_images]').is(':checked'),
			force_feed: $('input[name=force_feed]').is(':checked'),
			_ajax_nonce: goft_wpjm_admin_l18n.ajax_nonce,
		};

		xhr = $.post( goft_wpjm_admin_l18n.ajaxurl, data, function( response ) {

			if ( undefined !== response ) {

				enable_import( false );

				if ( undefined !== response.error ) {

					$('.force-feed').show();

					$('input[name=load_images]').closest('p').after('<span class="goft_wpjm no-jobs-found feed"> ' + goft_wpjm_admin_l18n.msg_invalid_feed + ': ' + response.error );

				} else if ( response.total_items > 0 ) {

					$('.goft_wpjm_jobs_found').remove();

					$('.goft_wpjm_table').html( response.sample_item );

					$('.goft_wpjm_table').after( '<h4 class="goft_wpjm_jobs_found code">' + goft_wpjm_admin_l18n.msg_jobs_found + ': ' + response.total_items + '</h4>');

					var load_only_defaults = $('#templates_list').val() && ! is_template_new_provider();

					if ( response.provider ) {
						auto_fill_provider_details( response.provider, load_only_defaults );
						auto_fill_custom_fields_defaults( response.provider );
					}


					if ( ! $('#templates_list').val() ) {
						$('#template_name').val( generate_default_template_name( url ) );
					}

					toggle_settings_groups( true );

					enable_import( true );

					$.event.trigger({
						type: "goftj_rss_content_loaded",
					});

				} else {
					$('input[name=load_images]').closest('p').after('<span class="goft_wpjm no-jobs-found feed"> ' + goft_wpjm_admin_l18n.msg_no_jobs_found + '</span>');
				}

			}

			$('.goft_wpjm.processing').remove();
			$('.cancel-feed-load').remove();

			$('input[name=import_feed]').show();
			$('input', template_actions).prop( 'disabled', false );

			update_hidden_sections( '[section=taxonomies], [section=meta], [section=source]' );

		}, 'json' );

		e.preventDefault();
		return false;
	});

	// On cancel load feed.
	$(document).on( 'click', '.cancel-feed-load', function(e) {

		xhr.abort();

		$(this).remove();

		$('.goft_wpjm.processing').remove();
		$('input[name=import_feed]').show();
		$('input', template_actions).prop( 'disabled', false );

		e.preventDefault();
		return false;

	});

	// On save template.
	$(document).on( 'click', '.save-template', function(e) {
		var template_name = $('#template_name').val();

		if ( ! template_name ) {
			alert( goft_wpjm_admin_l18n.msg_template_missing );
			return;
		}

		if ( ! $('.goft_wpjm.processing.save').length ) {
			$('.save-template').hide();
			$('.save-template').after('<span class="goft_wpjm processing save">&nbsp;</span>');
		}

		var data = {
			action: 'goft_wpjm_save_template',
			template: template_name,
			fetch_images: Number( $('input[name=load_images]').is(':checked') ),
			force_feed: Number( $('input[name=force_feed]').is(':checked') ),
			rss_feed_import: $('#rss_feed_import').val(),
			_wpnonce: goft_wpjm_admin_l18n.ajax_save_nonce,
		};

		// Dynamically get all meta and taxonomy input data.
		$.each( [ 'tax_input', 'meta', 'source' ], function( idx, element ) {

			$('[name*="' + element + '"]').each( function( i, el ) {

				if ( $(el).is(':checkbox') ) {
					data[ $(this).prop('name') ] = $(el).is(':checked') ? $(this).attr('data-default') : '';
				} else {
					data[ $(this).prop('name') ] = $(this).val();
				}
			});

		});

		$.post( goft_wpjm_admin_l18n.ajaxurl, data, function( response ) {

			if ( undefined !== response ) {
				$('.save-template').show();
				$('.save-template').after('<span class="template-saved-msg"> ' + goft_wpjm_admin_l18n.msg_template_saved + '</span>');

				$('.template-saved-msg').delay(2000).fadeOut();

				if ( ! $("#templates_list option[value='" + template_name + "']").length ) {
					$('#templates_list').append( $( '<option>', { value : template_name } ).text( template_name ) );
				}

				$('#templates_list').val( template_name );
			}

			$('.goft_wpjm.processing.save').remove();

		});

		e.preventDefault();
		return false;
	});

	// On template change.
	$(document).on( 'change', '#templates_list', function() {

		var template_obj = $(this);

		// Hide any visible sections.
		toggle_settings_groups( false );

		enable_import( false );

		if ( ! $('.goft_wpjm.processing.templates').length ) {
			template_actions.hide().after('<div class="goft_wpjm processing templates">&nbsp;</div>');
		}

		var data = {
			action: 'goft_wpjm_load_template_content',
			template: $(this).val(),
			_ajax_nonce: goft_wpjm_admin_l18n.ajax_nonce,
		};

		$.post( goft_wpjm_admin_l18n.ajaxurl, data, function( response ) {

			if ( ! response ) {

				$('#rss_feed_import').val('');

			} else {

				var feed_url = response['rss_feed_import'];

				// Load settings from the template.

				$('#rss_feed_import').val( feed_url );
				$('#template_name').val( template_obj.val() );

				$('input[name=load_images]').prop( 'checked', Boolean( response['fetch_images'] ) );
				$('input[name=force_feed]').prop( 'checked', Boolean( response['force_feed'] ) );
				$('#rss_feed_import').attr( 'data-saved-url', feed_url );

				$.each( [ 'tax_input', 'meta', 'source' ], function( idx, element ) {

					$.each( response[ element ], function( index, value ) {

						var object = '[name="' + element + '[' + index + ']"]';

						if ( $( object ).is(':checkbox') ) {

							if ( ! value ) {
								$( object ).val( $( object ).attr('data-default') );
								$( object ).prop( 'checked', false );
							} else {
								$( object ).prop( 'checked', true );
							}
							return;
						}

						$( object ).val( value ).prop( 'selected', true ).change();

						if ( $( object ).hasClass('goft-image') ) {

							if ( value ) {
				               var td = $( object ).closest('td');
				               $( 'img', td ).attr( 'src', value );
				           }
						}

					});

				});

				// Load the feed from the template.
				$('.import-feed').click();

				$('.force-feed').toggle( $('input[name=force_feed]').is(':checked')  );
			}

			$('.goft_wpjm.processing.templates').remove();

			template_actions.show();

		}, 'json' );

		// Clear the providers selection.
		$('#providers_list').val('').change();
	});

	// On sections values change.
	$('[section=taxonomies],[section=meta], [section=source]').on( 'change', function() {
		update_hidden_sections( '[section=' + $(this).attr('section') + ']' );
	});


	// __Providers Events.

	// On provider change.
	$(document).on( 'change', '#providers_list', function() {

		$('.goft_wpjm.processing.providers').remove();

		if ( ! $(this).val() ) {
			$('.providers-placeholder').closest('tr').fadeOut();
			return;
		}

		if ( ! $('.goft_wpjm.processing.templates').length ) {
			$('#providers_list').after('<div class="goft_wpjm processing providers">&nbsp;</div>');
		}

		var data = {
			action: 'goft_wpjm_load_provider_info',
			provider: $(this).val(),
			_ajax_nonce: goft_wpjm_admin_l18n.ajax_nonce,
		};

		$.post( goft_wpjm_admin_l18n.ajaxurl, data, function( response ) {

			if ( response ) {
				$('.providers-placeholder-content').html( response['setup'] ).closest('tr').fadeIn();
				$('.providers-placeholder').slideDown();

				pre_fill_provider_rss_builder( response['provider'] );
			}

			$('.goft_wpjm.processing.providers').remove();

		}, 'json' );

		g_feed_base_url = '';
		params = [];

	});

	// On provider click.
	$(document).on( 'click', 'a.provider-rss, a.provider-rss-custom', function(e) {

		$('.rss-copied-msg').remove();

		$('input[name=rss_feed_import]').val( $(this).attr('href') );

		$(this).after('<span class="rss-copied-msg"> ' + goft_wpjm_admin_l18n.msg_rss_copied + '</span>').fadeIn();
		$('.rss-copied-msg').delay(1000).fadeOut();

		e.preventDefault();
		return false;
	});

	// On provider section expand.
	$(document).on( 'click', '[class*=provider-expand]', function(e) {

		var oclick = $(this);
		var ochild = $( '.' + oclick.attr('data-child') );

		ochild.slideToggle( update_provider_help_links( oclick, ! ochild.is(':visible') ) );

		e.preventDefault();
		return false;
	});


	// __Provider RSS builder events.

	// On any RSS builder field keyup.
	$(document).on( 'keyup', '.feed-builder input[name*=feed]', function() {
		var query_arg       = $(this).attr('data-qarg');
		var param           = $('input[name=' + query_arg + ']').val();
		var value           = $(this).val();
		var feed_params_sep = $('.feed-builder input[name=feed-params-sep]').val();
		var is_param_prefix = $('input[name=' + query_arg + ']').attr('data-prefix');

		params[ query_arg ] = [];

		if ( ! value.length ) {
			value = '';
		}

		value = encodeURIComponent( value );

		if ( '&' === feed_params_sep ) {
			param += '=' + value;
		} else {
			param += ( ! is_param_prefix ? feed_params_sep : '' ) + value;
		}

		params[ query_arg ] = param;

		build_provider_rss_feed( params );
	});

	// On RSS builder feed URL field keyup.
	$(document).on( 'keyup', '.feed-builder input[name=feed-url]', function() {

		g_feed_base_url = get_provider_feed_base_url();

		var is_domain_location = 'domain_l' === $('.feed-builder input[name=feed-param-location]').val();

		// Check for the special 'domain_l' parameter name for locations in the feed URL.
		if ( is_domain_location ) {
			var regex = new RegExp( $('.feed-builder input[name=feed-param-location]').attr('data-regex') );

			if ( ( m = regex.exec( g_feed_base_url ) ) !== null ) {
			    $('.feed-builder input[name=feed-location]').val( m[1] );
			}
		}

		$('.feed-builder .params input[name*=feed-]:visible').keyup();
	});

	// On reset feed URL.
	$(document).on( 'click', '.reset-feed-url', function(e) {
		$('.feed-builder input[name=feed-url]').val( $('.feed-builder input[name=feed-url]').attr('data-default') ).keyup();
		e.preventDefault();
		return false;
	});


	// __Date events.


	// On clear date.
	$(document).on( 'click', '.clear_span_dates', function() {
		$( '.' + $(this).attr('data-goft_parent') ).val('');
	});


	// __Other events.


	// On reset values.
	$(document).on( 'click', '.reset-val', function(e) {

		var parent = $( 'input[name="' + $(this).attr('data-parent') + '"' );

		$( parent ).val( $(parent).attr('data-original') ).change();
		e.preventDefault();
		return false;
	});

	// On 'Advanced' fields toggle.
	$('.section-expand').on( 'click', function(e) {

		var section = $(this).attr('expand');
		var context = '[section=' + section + ']';
		var section_a = this;

		$( '.section-' + section + '-values' ).toggle().addClass('temp-tr-hide');

		$( context ).closest('tr').toggle( function() {

			$(this).addClass('temp-tr-hide');

			if ( $(this).is(':visible') ) {
				$( section_a ).text( goft_wpjm_admin_l18n.simple );
			} else {
				$( section_a ).text( goft_wpjm_admin_l18n.advanced );
			}

		});

		update_hidden_sections( '[section=taxonomies], [section=meta], [section=source]' );

		e.preventDefault;
		return false;
	});


	// __Providers callbacks.

	/**
	 * Update the manual/builder link names considering their child visibility.
	 */
	function update_provider_help_links( oclick, visible ) {

		var oclick = oclick ? oclick : $('[class*=provider-expand]');

		oclick.each( function() {

			var ochild = $( '.' + $(this).attr('data-child') );
			var text = $(this).attr('data-default');
			var is_visible = typeof( visible ) === 'undefined' ? ochild.is(':visible') : visible;

			text = ( is_visible ?  '- ' : '+ ' ) + text;

			$(this).text( text );
		});

	}

	/**
	 * Pre fill the provider RSS builder fields.
	 */
	function pre_fill_provider_rss_builder( provider ) {

		if ( $('.provider-expand-feed-builder').length ) {
			$('.provider-expand-feed-builder').click();
		} else {
			$('.provider-expand-feed-manual-setup').click();
		}

		update_provider_help_links();

		if ( undefined !== provider.feed.query_args_sep ) {
			$('.feed-builder input[name=feed-params-sep]').val( provider.feed.query_args_sep );
		}

		if ( undefined !== provider.feed.query_args ) {

			$('[class*=opt-param]').hide();

			$.each( provider.feed.query_args, function( key, value ) {

				$('.feed-builder .opt-param-' + key).show();

				var param           = Object.keys(value)[0];
				var def_val         = undefined != value[ param ].default_value ? value[ param ].default_value : '';
				var placeholder     = undefined != value[ param ].placeholder ? value[ param ].placeholder : '';
				var required        = undefined != value[ param ].required ? value[ param ].required : '';
				var is_param_prefix = undefined != value[ param ].is_prefix ? value[ param ].is_prefix : '';

				// Check for the special 'domain_l' parameter name for locations in the feed URL.
				if ( param === 'domain_l' ) {
					$('.feed-builder input[name=feed-param-' + key + ']').attr( 'data-regex', value[ param ].regex );
				}

				if ( required ) {
					$('.feed-builder .opt-param-' + key + ' label').append( ' ' + required );
				}

				$('.feed-builder input[name=feed-param-' + key + ']').val( param );
				$('.feed-builder input[name=feed-param-' + key + ']').attr( 'data-default', def_val );

				if ( placeholder ) {
					$('.feed-builder input[name=feed-' + key + ']').attr( 'placeholder', placeholder );
				}

				if ( is_param_prefix ) {
					$('.feed-builder input[name=feed-param-' + key + ']').attr( 'data-prefix', is_param_prefix );
				}
			});

		}

		$('.feed-builder input[name=feed-url]').val( provider.feed.base_url ).keyup();
		$('.feed-builder input[name=feed-url]').attr( 'data-default', provider.feed.base_url );
	}

	/**
	 * Get the base feed URL.
	 */
	function get_provider_feed_base_url() {
		var feed_url        = $('input[name=feed-url]').val();
		var feed_params_sep = $('.feed-builder input[name=feed-params-sep]').val();

		if ( 'undefined' === typeof( feed_url ) ) {
			return feed_url;
		}

		if ( '&' === feed_params_sep ) {

			if ( feed_url.indexOf('?') < 0 ) {
				feed_url += '?';
			} else {
				feed_url += '&';
			}

		} else {

			if ( feed_url.indexOf( feed_params_sep ) < 0 ) {
				feed_url += feed_params_sep;
			}

		}
		return feed_url;
	}

	/**
	 * RSS feed builder.
	 */
	function build_provider_rss_feed( params ) {
		var feed_url        = g_feed_base_url;
		var feed_params_sep = $('.feed-builder input[name=feed-params-sep]').val();
		var sorted_params   = [];

		// Make sure all the available parameters are present, sorted correctly and in some cases assigned a default value.
		$('.feed-builder .params input[name*=feed-]').each( function() {

			var qarg = $(this).attr('data-qarg');
			var def_value = $('input[name=' + qarg + ']').attr('data-default');

			def_value = undefined != def_value ? def_value : '';
			value = undefined != params[ qarg ] ? params[ qarg ] : '';
			//console.log('param = '+qarg+'; value = '+value+'; def value = '+def_value);
			sorted_params[ qarg ] = value ? value : def_value;

		});

		params = sorted_params;

		for( var param in params ) {
			if ( ! params.hasOwnProperty( param ) || undefined == params[ param ]  ) {
				continue;
			}

			if ( undefined != params[ param ] ) {

				if ( ( '&' === feed_params_sep && '?' !== feed_url.slice(-1) ) || '&' !== feed_params_sep ) {

					if ( feed_params_sep !== feed_url.slice(-1) ) {
						feed_url += feed_params_sep;
					}

				}

				feed_url += params[param];
			}

		}

		$('.provider-rss-custom').text( feed_url );
		$('.provider-rss-custom').prop( 'href', feed_url );
	}

	/**
	 * Check if user is changing this saved feed.
	 */
	function is_template_new_provider( $url ) {

		var new_feed   = $('#rss_feed_import').val().split('?')[0];
		if ( undefined !== $('#rss_feed_import').attr('data-saved-url') ) {
			var saved_feed = $('#rss_feed_import').attr('data-saved-url').split('?')[0];
		}

		return new_feed !== saved_feed;
	}

	/**
	 * Auto fill the provider details given the provider data.
	 */
	function auto_fill_provider_details( provider, only_defaults ) {

		$.each( provider, function( key, value ) {
			if ( ! only_defaults ) {
				$('input[name="source[' + key + ']"]').val( value ).change();
			}
			$('input[name="source[' + key + ']"]').attr( 'data-original', value ).change();
		})

		update_hidden_sections( '[section=source]' );
	}


	// __Custom fields callbacks.

	/**
	 * Auto fill custom fields default values based on the feed query string.
	 */
	function auto_fill_custom_fields_defaults( provider ) {

		var all_query_args = [];

		if ( undefined !== goft_wpjm_admin_l18n.default_query_args ) {
			all_query_args.push( goft_wpjm_admin_l18n.default_query_args );
		}

		if ( undefined !== provider.feed ) {
			all_query_args.push( provider.feed.query_args );
		}

		// Iterate through the default query args and specific provider query args, if any.
		$.each( all_query_args, function( k, query_args ) {

			if ( undefined === query_args ) {
				return;
			}

			$.each( query_args, function( key, value ) {

				var param = '';

				// Defaults don't use key/value pairs, hack the key/value.
				if ( '' !== Object.keys(value)[0] && 0 === Object.keys(value)[0] ) {
					param = value;
					key = param;
				} else {
					param = Object.keys(value)[0];
				}

				param_value = get_parameter_by_name( $('#rss_feed_import').val(), param );

				$('input[data-core-name=' + key + ']').val( param_value ).change();

			});

		});

	}


	// __Core callbacks.

	/**
	 * Toggle sections and import related elements.
	 */
	function enable_import( visible ) {

		if ( visible ) {
			$('.import-notes').closest('tr').hide();
			$('.import-posts').fadeIn();
		} else {
			$('.import-notes').closest('tr').fadeIn();
			$('.import-posts').hide();
		}

		toggle_settings_groups( visible );
	}

	 /**
	  * Dynamically update user changes when options are minimized.
	  */
	function update_hidden_sections( context ) {

		var curr_section = '';

		$( context ).each( function() {

			var section = $(this).attr('section');
			var selector = 'section-' + section + '-values';

	 		var tr = $(this).closest('tr');
			var label = $( 'th', tr ).text();

			var default_value = '-';

			$( 'select, input[type!="button"], textarea', tr ).each( function() {

				value = '';

				if ( $(this).is('select') ) {
					value = $(this).find(':selected').text();
				} else if ( $(this).is(':checkbox') ) {
					value = $(this).is(':checked') ? goft_wpjm_admin_l18n.label_yes : goft_wpjm_admin_l18n.label_no;
				} else if ( $(this).val() ) {
					value = $(this).val();


					// For URL values, output the last part or an image if that's the case.
					if ( is_url( value ) ) {

						var parser = document.createElement('a');
						parser.href = value;

						if ( parser.pathname.match(/\.(jpeg|jpg|gif|png|ico)$/) !== null ) {
							value = '<img src="' + value + '" class = "goft-image-thumb">';
						} else {
							value = '<a href="' + value + '" class = "goft-link">' + value + '</a>';
						}

					}
				} else {

					if ( '' !== $(this).attr('data-default') ) {
						value = $(this).attr('data-default');
					}

				}

			});

			if ( section !== curr_section ) {

				if ( ! $( '.' + selector ).length ) {

					var sel_values_placeholder =
						'<tr class="section-advanced temp-tr-hide">' +
							'<td><a href="#" class="section-expand" expand="' + section + '">' + goft_wpjm_admin_l18n.msg_advanced + '</a></td>' +
							'<td class="tip">&nbsp;</td>' +
							'<td class="section-values ' + selector + '" colspan=5></td>' +
						'</tr>';

					$( '.section-' + section ).parents('tr').after( sel_values_placeholder );
				} else {
					$( '.' + selector ).html('');
				}

			}

			if ( ! value ) {
				value = default_value;
			}

			var sel_values = '<p class="goft-basic"><strong>' + label  + ': </strong>' + value + '</p> ' ;
			$( '.' + selector ).append( sel_values );

			curr_section = section;
		});

	}

	/**
	 * Show/hide the hidden settings groups.
	 */
	function toggle_settings_groups( show ) {

		var group = $('.temp-tr-hide');
		var fixedgroup = $('.temp-tr-hide.tr-hide');

		if ( show ) {
			group.fadeIn();
 		} else {
			group.fadeOut();
			fixedgroup.removeClass('temp-tr-hide');
 		}

	}


	// __Helpers.

	/**
	 * Generates a template name given the RSS feed url.
	 */
	function generate_default_template_name( feed_url ) {
		//var re = /\/\/www\.(.*?)\.|\/\/(.*?[\.][^.][a-zA-z]*)[\/|?]/;
		var re = /\/\/www\.(.*?)[\/\?]$/;
		var m;

		if ( ( m = re.exec( feed_url ) ) !== null ) {
		    var index = m.length;

		    for (var i = m.length - 1; i >= 0; i--) {
		    	if ( undefined !== m[ i ] && '' !== m[ i ] ) {
			 		return 'rss-' + m[ i ];
		    	}
		    }

		}
		return 'my-rss-feed';
	}

	/**
	 * Checks if string is an URL.
	 */
	function is_url( str ) {
		var urlRegEx = /((([A-Za-z]{3,9}:(?:\/\/)?)(?:[\-;:&=\+\$,\w]+@)?[A-Za-z0-9\.\-]+|(?:www\.|[\-;:&=\+\$,\w]+@)[A-Za-z0-9\.\-]+)((?:\/[\+~%\/\.\w\-]*)?\??(?:[\-\+=&;%@\.\w]*)#?(?:[\.\!\/\\\w]*))?)/g;
	  	var pattern = new RegExp( urlRegEx );
	  	return pattern.test(str);
	}

	/**
	 * Retrieves the value from a given query string parameter.
	 */
	function get_parameter_by_name( url, name ) {

		name = name.replace( new RegExp( "]", 'gm' ), /[\[]/, '\\[').replace( new RegExp( "]" , 'gm'),  /[\]]/, '\\]' );

	    var regex = new RegExp("[\\?&\/]" + name + ".([^&#\/]*)");
	    var results = regex.exec( url );
    	return results === null ? "" : decodeURIComponent( results[1].replace(new RegExp( ']', 'gm' ),  /\+/g, " ") );
	}


	// Single page schedules.

	$('#_goft_wpjm_period_custom').closest('tr').hide();

	$(document).on( 'change', '#_goft_wpjm_period', function() {

		if ( 'custom' == $(this).val() ) {
			$('#_goft_wpjm_period_custom' ).closest('tr').fadeIn();
		} else {
			$('#_goft_wpjm_period_custom' ).closest('tr').hide();
		}

	});

	$('#_goft_wpjm_period').trigger('change');

})
