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