<?php
/**
 * Twenty Twenty-Five functions and definitions.
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package WordPress
 * @subpackage Twenty_Twenty_Five
 * @since Twenty Twenty-Five 1.0
 */

// Adds theme support for post formats.
if ( ! function_exists( 'twentytwentyfive_post_format_setup' ) ) :
	/**
	 * Adds theme support for post formats.
	 *
	 * @since Twenty Twenty-Five 1.0
	 *
	 * @return void
	 */
	function twentytwentyfive_post_format_setup() {
		add_theme_support( 'post-formats', array( 'aside', 'audio', 'chat', 'gallery', 'image', 'link', 'quote', 'status', 'video' ) );
	}
endif;
add_action( 'after_setup_theme', 'twentytwentyfive_post_format_setup' );

// Enqueues editor-style.css in the editors.
if ( ! function_exists( 'twentytwentyfive_editor_style' ) ) :
	/**
	 * Enqueues editor-style.css in the editors.
	 *
	 * @since Twenty Twenty-Five 1.0
	 *
	 * @return void
	 */
	function twentytwentyfive_editor_style() {
		add_editor_style( get_parent_theme_file_uri( 'assets/css/editor-style.css' ) );
	}
endif;
add_action( 'after_setup_theme', 'twentytwentyfive_editor_style' );

// Enqueues style.css on the front.
if ( ! function_exists( 'twentytwentyfive_enqueue_styles' ) ) :
	/**
	 * Enqueues style.css on the front.
	 *
	 * @since Twenty Twenty-Five 1.0
	 *
	 * @return void
	 */
	function twentytwentyfive_enqueue_styles() {
		wp_enqueue_style(
			'twentytwentyfive-style',
			get_parent_theme_file_uri( 'style.css' ),
			array(),
			wp_get_theme()->get( 'Version' )
		);
	}
endif;
add_action( 'wp_enqueue_scripts', 'twentytwentyfive_enqueue_styles' );

// Registers custom block styles.
if ( ! function_exists( 'twentytwentyfive_block_styles' ) ) :
	/**
	 * Registers custom block styles.
	 *
	 * @since Twenty Twenty-Five 1.0
	 *
	 * @return void
	 */
	function twentytwentyfive_block_styles() {
		register_block_style(
			'core/list',
			array(
				'name'         => 'checkmark-list',
				'label'        => __( 'Checkmark', 'twentytwentyfive' ),
				'inline_style' => '
				ul.is-style-checkmark-list {
					list-style-type: "\2713";
				}

				ul.is-style-checkmark-list li {
					padding-inline-start: 1ch;
				}',
			)
		);
	}
endif;
add_action( 'init', 'twentytwentyfive_block_styles' );

// Registers pattern categories.
if ( ! function_exists( 'twentytwentyfive_pattern_categories' ) ) :
	/**
	 * Registers pattern categories.
	 *
	 * @since Twenty Twenty-Five 1.0
	 *
	 * @return void
	 */
	function twentytwentyfive_pattern_categories() {

		register_block_pattern_category(
			'twentytwentyfive_page',
			array(
				'label'       => __( 'Pages', 'twentytwentyfive' ),
				'description' => __( 'A collection of full page layouts.', 'twentytwentyfive' ),
			)
		);

		register_block_pattern_category(
			'twentytwentyfive_post-format',
			array(
				'label'       => __( 'Post formats', 'twentytwentyfive' ),
				'description' => __( 'A collection of post format patterns.', 'twentytwentyfive' ),
			)
		);
	}
endif;
add_action( 'init', 'twentytwentyfive_pattern_categories' );

// Registers block binding sources.
if ( ! function_exists( 'twentytwentyfive_register_block_bindings' ) ) :
	/**
	 * Registers the post format block binding source.
	 *
	 * @since Twenty Twenty-Five 1.0
	 *
	 * @return void
	 */
	function twentytwentyfive_register_block_bindings() {
		register_block_bindings_source(
			'twentytwentyfive/format',
			array(
				'label'              => _x( 'Post format name', 'Label for the block binding placeholder in the editor', 'twentytwentyfive' ),
				'get_value_callback' => 'twentytwentyfive_format_binding',
			)
		);
	}
endif;
add_action( 'init', 'twentytwentyfive_register_block_bindings' );

// Registers block binding callback function for the post format name.
if ( ! function_exists( 'twentytwentyfive_format_binding' ) ) :
	/**
	 * Callback function for the post format name block binding source.
	 *
	 * @since Twenty Twenty-Five 1.0
	 *
	 * @return string|void Post format name, or nothing if the format is 'standard'.
	 */
	function twentytwentyfive_format_binding() {
		$post_format_slug = get_post_format();

		if ( $post_format_slug && 'standard' !== $post_format_slug ) {
			return get_post_format_string( $post_format_slug );
		}
	}
endif;

function my_connection_types() {
    p2p_register_connection_type(array(
        'name' => 'primary_dj',
        'from' => 'dj',
        'to' => 'event',
        'title' => array(
            'from' => 'Primary Events',
            'to' => 'Primary DJ'
        ),
        'cardinality' => 'one-to-many'
    ));
    p2p_register_connection_type(array(
        'name' => 'secondary_dj',
        'from' => 'dj',
        'to' => 'event',
        'title' => array(
            'from' => 'Secondary Events',
            'to' => 'Secondary DJs'
        ),
        'cardinality' => 'many-to-many'
    ));
    p2p_register_connection_type(array(
        'name' => 'models_to_events',
        'from' => 'model',
        'to' => 'event',
        'title' => array(
            'from' => 'Events',
            'to' => 'Models'
        ),
        'cardinality' => 'many-to-many'
    ));
}
add_action('p2p_init', 'my_connection_types');

add_action('after_setup_theme', function() {
    add_theme_support('post-thumbnails');
});
add_theme_support('post-thumbnails');


function google_calendar_button_for_edit_post() {
    $screen = get_current_screen();
    if ($screen->base === 'post' && $screen->id === 'event') {
        ?>
        <script src="https://apis.google.com/js/api.js"></script>
        <script>
(function($) {
    document.addEventListener("DOMContentLoaded", function() {
        const CLIENT_ID = 'YOUR_CLIENT_ID';
        const SCOPES = 'https://www.googleapis.com/auth/calendar';

        const calendarButton = document.createElement('button');
        calendarButton.textContent = 'Add Event to Google Calendar';
        
        function addCalendarButton() {
            const settingsElement = document.querySelector('.editor-header__settings');
            if (settingsElement) {
                settingsElement.insertAdjacentElement('afterbegin', calendarButton);
                observer.disconnect();
            }
        }

        const observer = new MutationObserver(() => {
            addCalendarButton();
        });
        observer.observe(document.body, {childList: true, subtree: true});

        calendarButton.addEventListener('click', function() {
            authenticateAndSubmitEvent();
        });

        function authenticateAndSubmitEvent() {
            gapi.load('client:auth2', () => {
                gapi.auth2.init({client_id: CLIENT_ID});
                gapi.auth2.getAuthInstance().signIn().then(() => {
                    gapi.client.load('calendar', 'v3', submitEvent);
                });
            });
        }

        function formatDateString(dateString) {
            if (typeof dateString !== 'string' || dateString.length !== 8) {
                throw new Error('Invalid date string format. Expected "YYYYMMDD".');
            }

            const year = parseInt(dateString.substring(0, 4), 10);
            const month = parseInt(dateString.substring(4, 6), 10) - 1; // Months are zero-indexed
            const day = parseInt(dateString.substring(6, 8), 10);
            
            const date = new Date(year, month, day);

            const formattedDate = date.toISOString().split('T')[0];
            return formattedDate;
        }

        function adjustTimestampsIfNextDay(startDateStr, endDateStr) {
            const startDate = new Date(startDateStr);
            const endDate = new Date(endDateStr);

            if (endDate < startDate) {
                endDate.setDate(endDate.getDate() + 1);
            }

            const year = endDate.getFullYear();
            const month = String(endDate.getMonth() + 1).padStart(2, '0'); // Months are zero-based
            const day = String(endDate.getDate()).padStart(2, '0');
            const hours = String(endDate.getHours()).padStart(2, '0');
            const minutes = String(endDate.getMinutes()).padStart(2, '0');
            const seconds = String(endDate.getSeconds()).padStart(2, '0');

            return `${year}-${month}-${day}T${hours}:${minutes}:${seconds}`;
        }

        function submitEvent() {
            let summary = $(`.editor-visual-editor__post-title-wrapper`).text();
            let description = $(`.wp-block-post-content`).text();
            let location = $(`[data-name="venue_address"] input`).val();
            let dateOfEvent = formatDateString($(`[data-name="date_of_event"] input`).val());
            let startTime = $(`[data-name="start_time"] input`).val();
            let endTime = $(`[data-name="end_time"] input`).val();
            let startTimeStamp = `${dateOfEvent}T${startTime}`;
            let endTimeStamp = `${dateOfEvent}T${endTime}`;
            endTimeStamp = adjustTimestampsIfNextDay(startTimeStamp, endTimeStamp);

            const event = {
                'summary': summary,
                'description': description,
                'location': location,
                'start': {
                    'dateTime': startTimeStamp,
                    'timeZone': 'America/New_York'
                },
                'end': {
                    'dateTime': endTimeStamp,
                    'timeZone': 'America/New_York'
                }
            };
            
            gapi.client.calendar.events.insert({
                'calendarId': 'primary',
                'resource': event
            }).then(response => {
                if (response.status === 200) {
                    alert('Event created: ' + response.result.htmlLink);
                }
                else {
                    alert('Error: ' + response.result.error.message);
                }
            });
        }
    });
})(jQuery);
        </script>
        <?php
    }
}
add_action('admin_footer', 'google_calendar_button_for_edit_post');

function my_enqueue_styles() {
    wp_enqueue_style('theme-style', get_stylesheet_uri());
}
add_action('wp_enqueue_scripts', 'my_enqueue_styles');

/**
 * Customize ACF WYSIWYG editor to include font size controls
 */
function acf_wysiwyg_customize_toolbar($toolbars) {
    // Add font size select to the toolbar
    $toolbars['Full'][1][] = 'fontsizeselect';
    $toolbars['Basic'][1][] = 'fontsizeselect';
    
    return $toolbars;
}
add_filter('acf/fields/wysiwyg/toolbars', 'acf_wysiwyg_customize_toolbar');

/**
 * Add TinyMCE plugin and button configuration for font sizes
 */
function acf_wysiwyg_add_font_sizes($settings, $editor_id) {
    // Check if this is an ACF editor
    if (strpos($editor_id, 'acf-editor-') !== false) {
        // Add font size select
        $settings['fontsize_formats'] = '8pt 9pt 10pt 11pt 12pt 14pt 16pt 18pt 20pt 22pt 24pt 26pt 28pt 36pt 48pt 72pt';
        
        // Ensure the button is in the toolbar
        if (isset($settings['toolbar1'])) {
            $settings['toolbar1'] .= ',fontsizeselect';
        }
    }
    
    return $settings;
}
add_filter('tiny_mce_before_init', 'acf_wysiwyg_add_font_sizes', 10, 2);

/**
 * Alternative approach - Add font size controls via TinyMCE filters
 */
function acf_wysiwyg_mce_buttons($buttons, $editor_id) {
    // Only for ACF editors
    if (strpos($editor_id, 'acf-editor-') !== false || strpos($editor_id, 'acf_') !== false) {
        // Add font size select if not already present
        if (!in_array('fontsizeselect', $buttons)) {
            array_splice($buttons, 1, 0, 'fontsizeselect');
        }
    }
    
    return $buttons;
}
add_filter('mce_buttons', 'acf_wysiwyg_mce_buttons', 10, 2);

/**
 * Configure font sizes for TinyMCE
 */
function acf_wysiwyg_mce_init($settings) {
    // Set available font sizes
    $settings['fontsize_formats'] = '8px 9px 10px 11px 12px 13px 14px 15px 16px 17px 18px 19px 20px 21px 22px 24px 26px 28px 30px 32px 36px 42px 48px 54px 60px 66px 72px';
    
    return $settings;
}
add_filter('tiny_mce_before_init', 'acf_wysiwyg_mce_init');

/**
 * Add inline styles support for font sizes in content
 */
function acf_wysiwyg_allow_font_sizes($init) {
    // Allow style attribute and font-size in span tags
    $init['valid_elements'] = '*[*]';
    $init['extended_valid_elements'] = 'span[style|class]';
    
    return $init;
}
add_filter('tiny_mce_before_init', 'acf_wysiwyg_allow_font_sizes');

/**
 * Allow font-size styles in the content filter
 */
function acf_wysiwyg_allow_style_attributes($tags, $context) {
    if ($context === 'post') {
        $tags['span']['style'] = true;
    }
    
    return $tags;
}
add_filter('wp_kses_allowed_html', 'acf_wysiwyg_allow_style_attributes', 10, 2);

/**
 * Add custom JavaScript to enhance ACF WYSIWYG font size functionality
 */
function acf_wysiwyg_admin_scripts() {
    ?>
    <script type="text/javascript">
    (function($) {
        // Enhance ACF WYSIWYG editors with better font size support
        if (typeof acf !== 'undefined') {
            acf.add_action('wysiwyg_tinymce_init', function(ed, id, mceInit, field) {
                // Ensure font size select is available
                if (ed.controlManager && ed.controlManager.get('fontsizeselect')) {
                    // Font size select is already there
                } else {
                    // Add font size button to toolbar if not present
                    var toolbar = ed.getParam('toolbar1', '');
                    if (toolbar.indexOf('fontsizeselect') === -1) {
                        ed.settings.toolbar1 = toolbar + ',fontsizeselect';
                    }
                }
                
                // Set font size formats
                ed.settings.fontsize_formats = '8px 9px 10px 11px 12px 13px 14px 15px 16px 17px 18px 19px 20px 21px 22px 24px 26px 28px 30px 32px 36px 42px 48px 54px 60px 66px 72px';
                
                // Ensure styles are preserved
                ed.on('init', function() {
                    // Allow style attributes
                    ed.schema.addValidElements('span[style]');
                    ed.schema.addValidElements('*[style]');
                });
            });
        }
        
        // For WordPress 5.0+ (Gutenberg era) compatibility
        $(document).ready(function() {
            // Hook into TinyMCE initialization for ACF fields
            $(document).on('tinymce-editor-init', function(event, editor) {
                if (editor.id && (editor.id.indexOf('acf') !== -1 || editor.id.indexOf('acf-editor') !== -1)) {
                    // Configure font sizes for ACF editors
                    editor.settings.fontsize_formats = '8px 9px 10px 11px 12px 13px 14px 15px 16px 17px 18px 19px 20px 21px 22px 24px 26px 28px 30px 32px 36px 42px 48px 54px 60px 66px 72px';
                    
                    // Add font size select to toolbar if not present
                    var toolbar1 = editor.settings.toolbar1 || '';
                    if (toolbar1.indexOf('fontsizeselect') === -1) {
                        editor.settings.toolbar1 = toolbar1 + (toolbar1 ? ',' : '') + 'fontsizeselect';
                    }
                }
            });
        });
    })(jQuery);
    </script>
    <?php
}
add_action('admin_footer', 'acf_wysiwyg_admin_scripts');

/**
 * Add custom CSS for admin to ensure font size controls are visible
 */
function acf_wysiwyg_admin_styles() {
    ?>
    <style type="text/css">
    /* Ensure font size dropdown is visible in ACF WYSIWYG */
    .acf-editor-wrap .mce-listbox.mce-fontsizeselect {
        display: inline-block !important;
    }
    
    /* Style the font size dropdown */
    .acf-editor-wrap .wp-editor-tools .mce-toolbar .mce-btn-group .mce-listbox {
        margin: 0 1px;
    }
    
    /* Ensure ACF WYSIWYG content preserves font sizes */
    .acf-editor-wrap iframe[id*="acf-editor"] {
        /* TinyMCE iframe styles will be handled by the editor itself */
    }
    </style>
    <?php
}
add_action('admin_head', 'acf_wysiwyg_admin_styles');

/**
 * Enhanced ACF WYSIWYG customization with ACF Extended compatibility
 */
function acf_wysiwyg_enhanced_init() {
    // Enhanced toolbar configuration
    add_filter('acf/fields/wysiwyg/toolbars', function($toolbars) {
        // Create a custom toolbar with font controls
        $toolbars['Font Enhanced'] = array(
            1 => array(
                'formatselect',
                'fontsizeselect', 
                'bold', 
                'italic', 
                'underline',
                'strikethrough',
                'forecolor',
                'backcolor',
                'link',
                'unlink'
            ),
            2 => array(
                'bullist',
                'numlist',
                'blockquote',
                'alignleft',
                'aligncenter',
                'alignright',
                'alignjustify',
                'removeformat',
                'undo',
                'redo'
            )
        );
        
        // Add font size to existing toolbars
        if (isset($toolbars['Full'][1])) {
            if (!in_array('fontsizeselect', $toolbars['Full'][1])) {
                array_splice($toolbars['Full'][1], 1, 0, 'fontsizeselect');
            }
        }
        
        if (isset($toolbars['Basic'][1])) {
            if (!in_array('fontsizeselect', $toolbars['Basic'][1])) {
                array_splice($toolbars['Basic'][1], 1, 0, 'fontsizeselect');
            }
        }
        
        return $toolbars;
    });
    
    // Enhanced TinyMCE configuration
    add_filter('tiny_mce_before_init', function($init) {
        // Font size formats (more comprehensive list)
        $init['fontsize_formats'] = '8px 9px 10px 11px 12px 13px 14px 15px 16px 17px 18px 19px 20px 21px 22px 24px 26px 28px 30px 32px 36px 42px 48px 54px 60px 66px 72px';
        
        // Format select options
        $init['block_formats'] = 'Paragraph=p;Heading 1=h1;Heading 2=h2;Heading 3=h3;Heading 4=h4;Heading 5=h5;Heading 6=h6;Preformatted=pre;Address=address';
        
        // Allow more HTML elements with style attributes
        $init['valid_elements'] = '*[*]';
        $init['extended_valid_elements'] = 'span[style|class|id],div[style|class|id],p[style|class|id]';
        
        // Preserve styles
        $init['verify_html'] = false;
        $init['cleanup'] = false;
        $init['cleanup_on_startup'] = false;
        
        return $init;
    });
}
add_action('init', 'acf_wysiwyg_enhanced_init');

/**
 * Preserve font styles in ACF content output
 */
function acf_preserve_wysiwyg_styles($value, $post_id, $field) {
    if ($field['type'] == 'wysiwyg') {
        // Remove WordPress default formatting that might strip styles
        remove_filter('acf_the_content', 'wpautop');
        
        // Apply minimal formatting while preserving styles
        $value = do_shortcode($value);
        
        // Re-add wpautop after processing
        add_filter('acf_the_content', 'wpautop');
    }
    
    return $value;
}
add_filter('acf/format_value/type=wysiwyg', 'acf_preserve_wysiwyg_styles', 10, 3);