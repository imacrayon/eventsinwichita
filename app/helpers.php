<?php

if (! function_exists('next_week_url')) {

    function next_week_url() {
        if (Request::has('start_time') && Request::has('end_time')) {
            $nextWeekStart = Carbon\Carbon::createFromFormat('Y-m-d H:i:s', Request::query('start_time'))
                ->addDays(6)
                ->toDateTimeString();

            $nextWeekEnd = Carbon\Carbon::createFromFormat('Y-m-d H:i:s', Request::query('end_time'))
                ->addDays(6)
                ->toDateTimeString();

            $query = array_merge(Request::query(), [
                'start_time' => $nextWeekStart,
                'end_time'   => $nextWeekEnd
            ]);

            return Request::url() . '?' . http_build_query($query);
        }
    }

}

if (! function_exists('format_calendar_day')) {

    function format_calendar_day($date) {
        $carbon = Carbon\Carbon::createFromFormat('Y-m-d', $date);

        if ($carbon->isToday()) {
            return 'Today &middot; ' . $carbon->format('D F j');
        }

        return $carbon->format('D F j');
    }

}

if (! function_exists('parse_url_host')) {

    function parse_url_host($url) {
        return array_get(parse_url($url), 'host');
    }

}

if (! function_exists('safe_html')) {

    function safe_html($value) {
        // Escape HTML tags
        $value = htmlspecialchars($value);
        // The Regular Expression filter
        $rex = '/(http|https)\:\/\/[a-zA-Z0-9\-\.]+\.[a-zA-Z]{2,3}(\/\S*)?/';
        // Check if there is a url in the text
        if (preg_match($rex, $value, $url)) {
            $url = htmlspecialchars($url[0]);
            $value = preg_replace($rex, '<a target="_blank" href="' . $url . '">' . $url . '</a> ', $value);
        }

        return nl2br($value);
    }
}
