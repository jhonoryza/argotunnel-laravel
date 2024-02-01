# Integrate Laravel with ArgoTunnel Cloudflare

<p align="center">
    <a href="https://packagist.org/packages/jhonoryza/argotunnel-laravel">
    <img src="https://poser.pugx.org/jhonoryza/argotunnel-laravel/d/total.svg" alt="Total Downloads">
    </a>
    <a href="https://packagist.org/packages/jhonoryza/argotunnel-laravel">
        <img src="https://poser.pugx.org/jhonoryza/argotunnel-laravel/v/stable.svg" alt="Latest Stable Version">
    </a>
    <a href="https://packagist.org/packages/jhonoryza/argotunnel-laravel">
        <img src="https://poser.pugx.org/jhonoryza/argotunnel-laravel/license.svg" alt="License">
    </a>
</p>

This package allows you to connect to remote database that using argo tunnel from cloudflare, this assume that you have create a tunnel from your remote server with tcp connection.

## Installation

You can install the package via composer:

```bash
composer require jhonoryza/argotunnel-laravel
```

## Basic usage
### Configuration in .env file

```
; Process used to verify connection
; Use bash if your distro uses nmap-ncat (RHEL/CentOS 7.x)
TUNNELER_VERIFY_PROCESS=nc

; Path to the nc executable
TUNNELER_NC_PATH=/usr/bin/nc
; Path to the bash executable
TUNNELER_BASH_PATH=/usr/bin/bash
; Path to the ssh executable
TUNNELER_SSH_PATH=/usr/bin/ssh
; Path to the nohup executable
TUNNELER_NOHUP_PATH=/usr/bin/nohup

; Log messages for troubleshooting
SSH_VERBOSITY=
NOHUP_LOG=/dev/null

; The identity file you want to use for ssh auth
TUNNELER_IDENTITY_FILE=/home/user/.ssh/id_rsa

; The local address and port for the tunnel
TUNNELER_LOCAL_PORT=13306
TUNNELER_LOCAL_ADDRESS=127.0.0.1

; The remote address and port for the tunnel
TUNNELER_BIND_PORT=3306
TUNNELER_BIND_ADDRESS=127.0.0.1

; The ssh connection: sshuser@sshhost:sshport
TUNNELER_USER=sshuser
TUNNELER_HOSTNAME=sshhost
TUNNELER_PORT=sshport

; How long to wait, in microseconds, before testing to see if the tunnel is created.
; Depending on your network speeds you will want to modify the default of 1 seconds
TUNNELER_CONN_WAIT=1000000

; How often it is checked if the tunnel is created. Useful if the tunnel creation is sometimes slow,
; and you want to minimize waiting times
TUNNELER_CONN_TRIES=1

; Do you want to ensure you have the Tunnel in place for each bootstrap of the framework?
TUNNELER_ON_BOOT=false

; Do you want to use additional SSH options when the tunnel is created?
TUNNELER_SSH_OPTIONS="-o StrictHostKeyChecking=no"

```

### How it works
It first uses netcat (nc) via exec to check the local port to see if the tunnel is open. If the port is there, it does nothing else.

If the port isn't there, it then creates the ssh tunnel connection command and executes that via exec after execution we wait the defined TUNNELER_CONN_WAIT time before running netcat again to verify that the connection is in place.

That's it. The tunnel will stay up until it times out, if it times out.

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
