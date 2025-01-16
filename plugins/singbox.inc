<?php

/*
 * Copyright (C) 2024  https://pfchina.org/
 * All rights reserved.
 *
 */


function singbox_services()
{
    global $config;

    $services = array();

        $services[] = array(
            'description' => gettext('sing-box'),
            'configd' => array(
                'restart' => array('singbox restart'),
                'start' => array('singbox start'),
                'stop' => array('singbox stop'),
            ),
            'name' => 'sing-box',
            'pidfile' => '/var/run/singbox.pid',
        );
    

    return $services;
}
function singbox_syslog()
{
    $logfacilities = array();
    $logfacilities['sing-box'] = array(
        'facility' => array('sing-box'),
    );
    return $logfacilities;
}