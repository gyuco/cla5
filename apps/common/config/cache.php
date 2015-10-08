<?php

return array(
    'DiskCache' => array(
        'identifier' => 'cla',
        'path' => '/var/www/cantine/web_cantine/app/system/cache'
    ),
    'Apc' => array(
        'identifier' => 'cla'
    ),
    'Memcache' => array(
        'identifier' => 'cla',
        'compress_data' => true,
        'timeout' => 0,
        'servers' => array(
            'server1' => array(
                'server' => '127.0.0.1',
                'port' => 11211,
                'persistent_connection' => true,
                'weight' => 1
            )
        ),
        'expire'=>60000
    )
);
