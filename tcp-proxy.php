<?php
$options = getopt('l:p:r:q', [
  'local-host:',
  'local-port:',
  'remote-host:',
  'remote-port:'
]);

$localHost = '0.0.0.0';
$localPort = 0;
$remoteHost = '';
$remotePort = 0;

if (array_key_exists('local-host', $_GET)) {
   $localHost = $_GET['local-host'];
} else if (array_key_exists('l', $options)) {
    $localHost = $options['l'];
} else if (array_key_exists('local-host', $options)) {
    $localHost = $options['local-host'];
}

if (array_key_exists('local-port', $_GET)) {
    $localPort = $_GET['local-port'];
} else if (array_key_exists('p', $options)) {
    $localPort = $options['p'];
} else if (array_key_exists('local-port', $options)) {
    $localPort = $options['local-port'];
}

if (array_key_exists('remote-host', $_GET)) {
    $remoteHost = $_GET['remote-host'];
} else if (array_key_exists('r', $options)) {
    $remoteHost = $options['r'];
} else if (array_key_exists('remote-host', $options)) {
    $remoteHost = $options['remote-host'];
}

if (array_key_exists('remote-port', $_GET)) {
    $remotePort = $_GET['remote-port'];
} else if (array_key_exists('q', $options)) {
    $remotePort = $options['q'];
} else if (array_key_exists('remote-port' ,$options)) {
    $remotePort = $options['remote-port'];
}

$startErrors = [];

if ($localPort == 0) {
    $startErrors[] = "Local Port must be set.\n";
}

if (strlen($remoteHost) == 0) {
    $startErrors[] = "Remote Host must be set.\n";
}

if ($remotePort == 0) {
    $startErrors[] = "Remote Port must be set.\n";
}

if (count($startErrors) > 0) {
    echo join(' ', $startErrors);
    exit(1);
}
// TODO: write proxy logic
?>
