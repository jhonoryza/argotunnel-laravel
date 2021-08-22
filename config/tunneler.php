<?php

return [

    'verify_process' => env('TUNNELER_VERIFY_PROCESS', 'nc'),

    'nc_path' => env('TUNNELER_NC_PATH', '/bin/nc'),
    'bash_path' => env('TUNNELER_BASH_PATH', '/usr/bin/bash'),
    'ssh_path' => env('TUNNELER_SSH_PATH', 'vendor/bin/cloudflared-linux-amd64'),
    'nohup_path' => env('TUNNELER_NOHUP_PATH', '/usr/bin/nohup'),
    
    'local_address' => env('TUNNELER_LOCAL_ADDRESS', '127.0.0.1'),
    'local_port' => env('TUNNELER_LOCAL_PORT', '4000'),
    'identity_file' => env('TUNNELER_IDENTITY_FILE'),
    
    'bind_address' => env('TUNNELER_BIND_ADDRESS', '127.0.0.1'),
    'bind_port' => env('TUNNELER_BIND_PORT'),
    
    'user' => env('TUNNELER_USER'),
    'hostname' => env('TUNNELER_HOSTNAME'),
    'port' => env('TUNNELER_PORT'),
    'wait' => env('TUNNELER_CONN_WAIT', '1000000'),
    'tries' => env('TUNNELER_CONN_TRIES', 1),

    'on_boot' => filter_var(env('TUNNELER_ON_BOOT', true), FILTER_VALIDATE_BOOLEAN),
    'ssh_verbosity' => env('SSH_VERBOSITY',''),
    'ssh_options' => env('TUNNELER_SSH_OPTIONS', ''),
    'nohup_log' => env('NOHUP_LOG', '/dev/null'),

];