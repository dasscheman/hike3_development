<?php
return [
    'routeopstarta' => [
        'route_ID' => 1,
        'route_name' => 'Opstart introductie event 1',
        'event_ID' => 1,
        'day_date' => NULL,
        'route_volgorde' => 1,
        'create_time' => '2015-07-10 07:05:13',
        'create_user_ID' => 2,
        'update_time' => '2015-07-10 07:05:13',
        'update_user_ID' => 2,
        'start_datetime' => date_format(
            date_sub(
                date("php:Y-m-d H:i:s"),
                date_interval_create_from_date_string("2 hours")),
            "php:Y-m-d H:i:s"),
        'end_datetime' => date_format(
            date_add(
                date("php:Y-m-d H:i:s"),
                date_interval_create_from_date_string("2 hours")),
            "php:Y-m-d H:i:s")
    ],
    'routeopstartb' => [
        'route_ID' => 2,
        'route_name' => 'Opstart dag 1 event 1',
        'event_ID' => 1,
        'day_date' => '2015-02-25',
        'route_volgorde' => 1,
        'create_time' => '2015-07-10 07:05:13',
        'create_user_ID' => 2,
        'update_time' => '2015-07-10 07:05:13',
        'update_user_ID' => 2,
        'start_datetime' => date_format(
            date_sub(
                date("php:Y-m-d H:i:s"),
                date_interval_create_from_date_string("2 hours")),
            "php:Y-m-d H:i:s"),
        'end_datetime' => date_format(
            date_add(
                date("php:Y-m-d H:i:s"),
                date_interval_create_from_date_string("2 hours")),
            "php:Y-m-d H:i:s")
    ],
    'routeopstartc' => [
        'route_ID' => 3,
        'route_name' => 'Opstart dag 2 event 1',
        'event_ID' => 1,
        'day_date' => '2015-02-26',
        'route_volgorde' => 1,
        'create_time' => '2015-07-10 07:05:13',
        'create_user_ID' => 2,
        'update_time' => '2015-07-10 07:05:13',
        'update_user_ID' => 2,
        'start_datetime' => date_format(
            date_sub(
                date("php:Y-m-d H:i:s"),
                date_interval_create_from_date_string("2 hours")),
            "php:Y-m-d H:i:s"),
        'end_datetime' => date_format(
            date_add(
                date("php:Y-m-d H:i:s"),
                date_interval_create_from_date_string("2 hours")),
            "php:Y-m-d H:i:s")
    ],

    'routeopintroa' => [
        'route_ID' => 4,
        'route_name' => 'intro introductie event 2',
        'event_ID' => 2,
        'day_date' => NULL,
        'route_volgorde' => 1,
        'create_time' => '2015-07-10 07:05:13',
        'create_user_ID' => 2,
        'update_time' => '2015-07-10 07:05:13',
        'update_user_ID' => 2,
        'start_datetime' => date_format(
            date_sub(
                date("php:Y-m-d H:i:s"),
                date_interval_create_from_date_string("2 hours")),
            "php:Y-m-d H:i:s"),
        'end_datetime' => date_format(
            date_add(
                date("php:Y-m-d H:i:s"),
                date_interval_create_from_date_string("2 hours")),
            "php:Y-m-d H:i:s")
    ],
    'routeopintrob' => [
        'route_ID' => 5,
        'route_name' => 'intro dag 1 event 2',
        'event_ID' => 2,
        'day_date' => '2015-02-25',
        'route_volgorde' => 1,
        'create_time' => '2015-07-10 07:05:13',
        'create_user_ID' => 2,
        'update_time' => '2015-07-10 07:05:13',
        'update_user_ID' => 2,
        'start_datetime' => date_format(
            date_sub(
                date("php:Y-m-d H:i:s"),
                date_interval_create_from_date_string("2 hours")),
            "php:Y-m-d H:i:s"),
        'end_datetime' => date_format(
            date_add(
                date("php:Y-m-d H:i:s"),
                date_interval_create_from_date_string("2 hours")),
            "php:Y-m-d H:i:s")
    ],
    'routeopintroc' => [
        'route_ID' => 6,
        'route_name' => 'intro dag 2 event 2',
        'event_ID' => 2,
        'day_date' => '2015-02-26',
        'route_volgorde' => 1,
        'create_time' => '2015-07-10 07:05:13',
        'create_user_ID' => 2,
        'update_time' => '2015-07-10 07:05:13',
        'update_user_ID' => 2,
        'start_datetime' => date_format(
            date_sub(
                date("php:Y-m-d H:i:s"),
                date_interval_create_from_date_string("2 hours")),
            "php:Y-m-d H:i:s"),
        'end_datetime' => date_format(
            date_add(
                date("php:Y-m-d H:i:s"),
                date_interval_create_from_date_string("2 hours")),
            "php:Y-m-d H:i:s")
    ],
    'routegestarta' => [
        'route_ID' => 7,
        'route_name' => 'gestart introductie event 3',
        'event_ID' => 3,
        'day_date' => NULL,
        'route_volgorde' => 1,
        'create_time' => '2015-07-10 07:05:13',
        'create_user_ID' => 2,
        'update_time' => '2015-07-10 07:05:13',
        'update_user_ID' => 2,
        'start_datetime' => date_format(
            date_sub(
                date("php:Y-m-d H:i:s"),
                date_interval_create_from_date_string("2 hours")),
            "php:Y-m-d H:i:s"),
        'end_datetime' => date_format(
            date_add(
                date("php:Y-m-d H:i:s"),
                date_interval_create_from_date_string("2 hours")),
            "php:Y-m-d H:i:s")
    ],
    'routeopgestartb' => [
        'route_ID' => 8,
        'route_name' => 'gestart dag 1 event 3',
        'event_ID' => 3,
        'day_date' => '2015-02-25',
        'route_volgorde' => 1,
        'create_time' => '2015-07-10 07:05:13',
        'create_user_ID' => 2,
        'update_time' => '2015-07-10 07:05:13',
        'update_user_ID' => 2,
        'start_datetime' => date_format(
            date_sub(
                date("php:Y-m-d H:i:s"),
                date_interval_create_from_date_string("2 hours")),
            "php:Y-m-d H:i:s"),
        'end_datetime' => date_format(
            date_add(
                date("php:Y-m-d H:i:s"),
                date_interval_create_from_date_string("2 hours")),
            "php:Y-m-d H:i:s")
    ],
    'routeopgestartc' => [
        'route_ID' => 9,
        'route_name' => 'gestart dag 2 event 3',
        'event_ID' => 3,
        'day_date' => '2015-02-26',
        'route_volgorde' => 1,
        'create_time' => '2015-07-10 07:05:13',
        'create_user_ID' => 2,
        'update_time' => '2015-07-10 07:05:13',
        'update_user_ID' => 2,
        'start_datetime' => date_format(
            date_sub(
                date("php:Y-m-d H:i:s"),
                date_interval_create_from_date_string("2 hours")),
            "php:Y-m-d H:i:s"),
        'end_datetime' => date_format(
            date_add(
                date("php:Y-m-d H:i:s"),
                date_interval_create_from_date_string("2 hours")),
            "php:Y-m-d H:i:s")
    ],
    'routeopeinda' => [
        'route_ID' => 10,
        'route_name' => 'Beindigd introductie event 4',
        'event_ID' => 4,
        'day_date' => NULL,
        'route_volgorde' => 1,
        'create_time' => '2015-07-10 07:05:13',
        'create_user_ID' => 2,
        'update_time' => '2015-07-10 07:05:13',
        'update_user_ID' => 2,
        'start_datetime' => date_format(
            date_sub(
                date("php:Y-m-d H:i:s"),
                date_interval_create_from_date_string("2 hours")),
            "php:Y-m-d H:i:s"),
        'end_datetime' => date_format(
            date_add(
                date("php:Y-m-d H:i:s"),
                date_interval_create_from_date_string("2 hours")),
            "php:Y-m-d H:i:s")
    ],
    'routeopeindb' => [
        'route_ID' => 11,
        'route_name' => 'Beindigd dag 1 event 4',
        'event_ID' => 4,
        'day_date' => '2015-02-25',
        'route_volgorde' => 1,
        'create_time' => '2015-07-10 07:05:13',
        'create_user_ID' => 2,
        'update_time' => '2015-07-10 07:05:13',
        'update_user_ID' => 2,
        'start_datetime' => date_format(
            date_sub(
                date("php:Y-m-d H:i:s"),
                date_interval_create_from_date_string("2 hours")),
            "php:Y-m-d H:i:s"),
        'end_datetime' => date_format(
            date_add(
                date("php:Y-m-d H:i:s"),
                date_interval_create_from_date_string("2 hours")),
            "php:Y-m-d H:i:s")
    ],
    'routeopeindc' => [
        'route_ID' => 12,
        'route_name' => 'Beindigd dag 2 event 4',
        'event_ID' => 4,
        'day_date' => '2015-02-26',
        'route_volgorde' => 1,
        'create_time' => '2015-07-10 07:05:13',
        'create_user_ID' => 2,
        'update_time' => '2015-07-10 07:05:13',
        'update_user_ID' => 2,
        'start_datetime' => date_format(
            date_sub(
                date("php:Y-m-d H:i:s"),
                date_interval_create_from_date_string("2 hours")),
            "php:Y-m-d H:i:s"),
        'end_datetime' => date_format(
            date_add(
                date("php:Y-m-d H:i:s"),
                date_interval_create_from_date_string("2 hours")),
            "php:Y-m-d H:i:s")
    ],
];
