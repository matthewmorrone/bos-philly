<?php
/**
 * Twenty Twenty-Four functions and definitions
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package Twenty Twenty-Four
 * @since Twenty Twenty-Four 1.0
 */

/**
 * Register block styles.
 */

if (! function_exists('twentytwentyfour_block_styles')) :
    /**
     * Register custom block styles
     *
     * @since Twenty Twenty-Four 1.0
     * @return void
     */
    function twentytwentyfour_block_styles() {

        register_block_style(
            'core/details',
            array(
                'name'         => 'arrow-icon-details',
                'label'        => __('Arrow icon', 'twentytwentyfour'),
                /*
                 * Styles for the custom Arrow icon style of the Details block
                 */
                'inline_style' => '
                .is-style-arrow-icon-details {
                    padding-top: var(--wp--preset--spacing--10);
                    padding-bottom: var(--wp--preset--spacing--10);
                }

                .is-style-arrow-icon-details summary {
                    list-style-type: "\2193\00a0\00a0\00a0";
                }

                .is-style-arrow-icon-details[open]>summary {
                    list-style-type: "\2192\00a0\00a0\00a0";
                }',
            )
        );
        register_block_style(
            'core/post-terms',
            array(
                'name'         => 'pill',
                'label'        => __('Pill', 'twentytwentyfour'),
                /*
                 * Styles variation for post terms
                 * https://github.com/WordPress/gutenberg/issues/24956
                 */
                'inline_style' => '
                .is-style-pill a,
                .is-style-pill span:not([class], [data-rich-text-placeholder]) {
                    display: inline-block;
                    background-color: var(--wp--preset--color--base-2);
                    padding: 0.375rem 0.875rem;
                    border-radius: var(--wp--preset--spacing--20);
                }

                .is-style-pill a:hover {
                    background-color: var(--wp--preset--color--contrast-3);
                }',
            )
        );
        register_block_style(
            'core/list',
            array(
                'name'         => 'checkmark-list',
                'label'        => __('Checkmark', 'twentytwentyfour'),
                /*
                 * Styles for the custom checkmark list block style
                 * https://github.com/WordPress/gutenberg/issues/51480
                 */
                'inline_style' => '
                ul.is-style-checkmark-list {
                    list-style-type: "\2713";
                }

                ul.is-style-checkmark-list li {
                    padding-inline-start: 1ch;
                }',
            )
        );
        register_block_style(
            'core/navigation-link',
            array(
                'name'         => 'arrow-link',
                'label'        => __('With arrow', 'twentytwentyfour'),
                /*
                 * Styles for the custom arrow nav link block style
                 */
                'inline_style' => '
                .is-style-arrow-link .wp-block-navigation-item__label:after {
                    content: "\2197";
                    padding-inline-start: 0.25rem;
                    vertical-align: middle;
                    text-decoration: none;
                    display: inline-block;
                }',
            )
        );
        register_block_style(
            'core/heading',
            array(
                'name'         => 'asterisk',
                'label'        => __('With asterisk', 'twentytwentyfour'),
                'inline_style' => "
                .is-style-asterisk:before {
                    content: '';
                    width: 1.5rem;
                    height: 3rem;
                    background: var(--wp--preset--color--contrast-2, currentColor);
                    clip-path: path('M11.93.684v8.039l5.633-5.633 1.216 1.23-5.66 5.66h8.04v1.737H13.2l5.701 5.701-1.23 1.23-5.742-5.742V21h-1.737v-8.094l-5.77 5.77-1.23-1.217 5.743-5.742H.842V9.98h8.162l-5.701-5.7 1.23-1.231 5.66 5.66V.684h1.737Z');
                    display: block;
                }

                /* Hide the asterisk if the heading has no content, to avoid using empty headings to display the asterisk only, which is an A11Y issue */
                .is-style-asterisk:empty:before {
                    content: none;
                }

                .is-style-asterisk:-moz-only-whitespace:before {
                    content: none;
                }

                .is-style-asterisk.has-text-align-center:before {
                    margin: 0 auto;
                }

                .is-style-asterisk.has-text-align-right:before {
                    margin-left: auto;
                }

                .rtl .is-style-asterisk.has-text-align-left:before {
                    margin-right: auto;
                }",
            )
        );
    }
endif;

add_action('init', 'twentytwentyfour_block_styles');

/**
 * Enqueue block stylesheets.
 */

if (! function_exists('twentytwentyfour_block_stylesheets')) :
    /**
     * Enqueue custom block stylesheets
     *
     * @since Twenty Twenty-Four 1.0
     * @return void
     */
    function twentytwentyfour_block_stylesheets() {
        /**
         * The wp_enqueue_block_style() function allows us to enqueue a stylesheet
         * for a specific block. These will only get loaded when the block is rendered
         * (both in the editor and on the front end), improving performance
         * and reducing the amount of data requested by visitors.
         *
         * See https://make.wordpress.org/core/2021/12/15/using-multiple-stylesheets-per-block/ for more info.
         */
        wp_enqueue_block_style(
            'core/button',
            array(
                'handle' => 'twentytwentyfour-button-style-outline',
                'src'    => get_parent_theme_file_uri('assets/css/button-outline.css'),
                'ver'    => wp_get_theme(get_template())->get('Version'),
                'path'   => get_parent_theme_file_path('assets/css/button-outline.css'),
            )
        );
    }
endif;

add_action('init', 'twentytwentyfour_block_stylesheets');

/**
 * Register pattern categories.
 */

if (! function_exists('twentytwentyfour_pattern_categories')) :
    /**
     * Register pattern categories
     *
     * @since Twenty Twenty-Four 1.0
     * @return void
     */
    function twentytwentyfour_pattern_categories() {

        register_block_pattern_category(
            'twentytwentyfour_page',
            array(
                'label'       => _x('Pages', 'Block pattern category', 'twentytwentyfour'),
                'description' => __('A collection of full page layouts.', 'twentytwentyfour'),
            )
        );
    }
endif;

add_action('init', 'twentytwentyfour_pattern_categories');

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