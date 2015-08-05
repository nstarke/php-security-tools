# PHP Security Tools

This repository serves as a collection of command line PHP scripts useful for penetration testers operating in a PHP environment.

## Examples:

### Netcat.php

Listen on port 8000 and return a command shell.

```
php netcat.php --listen-port 8000 -c
```

Likewise, this script can be access via a browser, with query string parameters instead of commandline arguments.
