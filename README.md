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

### SSH-Tunnel.php

Connect to remote host via username:
```
php ssh-tunnel.php --remote-host 192.168.0.1 --remote-port 22 --username test
```

Connect to remote host via key:
```
php ssh-tunnel.php --remote-host 192.168.0.1 --remote-port 22 --key-path /path/to/private/key
```

### URL-Fuzzer.php

Fuzz base url with word list and check for response codes 200 and 201:
```
php url-fuzzer.php --base-url http://localhost:3000/FUZZ.php --wordlist /path/to/wordlist --response-codes 200,201
```

Likewise, these scripts can be access via a browser, with query string parameters instead of command line arguments.

