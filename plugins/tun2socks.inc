<?php

/*
 * Copyright (C) 2024  https://pfchina.org/
 * All rights reserved.
 *
 */

function tun2socks_services()
{
    global $config;

    $services = array();

        $services[] = array(
            'description' => gettext('tun2socks'),
            'configd' => array(
                'restart' => array('tun2socks restart'),
                'start' => array('tun2socks start'),
                'stop' => array('tun2socks stop'),
            ),
            'name' => 'tun2socks',
            'pidfile' => '/var/run/tun2socks.pid',
        );
    

    return $services;
}
function tuntosocks_syslog()
{
    $logfacilities = array();
    $logfacilities['tun2socks'] = array(
        'facility' => array('tun2socks'),
    );
    return $logfacilities;
}