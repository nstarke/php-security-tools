<?php

$options = getopt('r:p:f:k:u:h', [
  'remote-host:',
  'remote-port:',
  'forward-port:',
  'key-path:',
  'username:',
  'help'
  ]);

  $remoteHost = '';
  $remotePort = 0;
  $forwardPort = 0;
  $keyPath = '';
  $username = '';

  if (array_key_exists('h', $options) || array_key_exists('help', $options)) {
      echo "PHP SSH Tunnel";
      echo "\n";
      echo "Usage: php ssh-tunnel.php --remote-host [remote-host] --remote-port [remote-port] --forward-port [forward-port] --key-path [key-path] --username [username] --help";
      echo "\n";
      echo "-r, --remote-host: Remote host to connect back to.\n";
      echo "-p, --remote-port: Remote post to connect back to.\n";
      echo "-f, --forward-port: Port to forward on on remote host.\n";
      echo "-k, --key-path: Local path for ssh key.\n";
      echo "-u, --username: SSH Username.\n";
      echo "-h, --help: Show this menu.\n";
      echo "\n";
      exit();
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
  } else if (array_key_exists('p', $options)) {
      $remotePort = $options['p'];
  } else if (array_key_exists('forward-port', $options)) {
      $remotePort = $options['forward-port'];
  }

  if (array_key_exists('forward-port', $_GET)) {
      $forwardPort = $_GET['forward-port'];
  } else if (array_key_exists('f', $options)) {
      $forwardPort = $options['f'];
  } else if (array_key_exists('forward-port', $options)) {
      $forwardPort = $options['forward-port'];
  }

  if (array_key_exists('key-path', $_GET)) {
      $keyPath = $_GET['key-path'];
  } else if (array_key_exists('k', $options)) {
      $keyPath = $options['k'];
  } else if (array_key_exists('key-path', $options)) {
      $keyPath = $options['key-path'];
  }

  if (array_key_exists('username', $_GET)) {
      $keyPath = $_GET['username'];
  } else if (array_key_exists('u', $options)) {
      $keyPath = $options['u'];
  } else if (array_key_exists('username', $options)) {
      $keyPath = $options['username'];
  }

  $startErrors = [];

  if (strlen($remoteHost) > 0) {
      $startErrors[] = "Remote Host is required";
  }

  if ($remotePort == 0) {
      $startErrors[] = "Remote Port is required";
  }

  if ($forwardPort == 0) {
      $startErrors[] = "Forward Port is required";
  }

  if (count($startErrors) > 0) {
    echo join(' ', $startErrors);
    exit();
  }

  // Write SSH Tunnel Logic
?>
