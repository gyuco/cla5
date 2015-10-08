<?php

return array(
    'environment' => 'DEV',
    'default_language' => 'en_US',
    'routes_path' => APPS_PATH.'%s/mux.php',
    'backup_dir' => '/tmp/',
    'gettext_path' => SYSTEM_PATH.'locale/',
    'gettext_path_lang' => SYSTEM_PATH.'locale/%s/LC_MESSAGES/',
    'gettext_domain' => 'messages'
);
