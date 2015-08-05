# PHP Security Tools

This repository serves as a collection of command line PHP scripts useful for penetration testers operating in a PHP environment.

## Examples:

### Netcat.php

Listen on port 8000 and return a command shell.

```
php netcat.php --listen-port 8000 -c
```

### TCP-Proxy.php

Listen on port 8000 locally and proxy requests made to this port to the remote host at 192.168.0.1:80.
```
php tcp-proxy.php --listen-port 8000 --remote-host 192.168.0.1 --remote-port 80
```

Likewise, these scripts can be access via a browser, with query string parameters instead of commandline arguments.

