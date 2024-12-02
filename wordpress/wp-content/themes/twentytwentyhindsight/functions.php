<?php
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