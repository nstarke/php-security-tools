<?php

$options = getopt('r:p:f:k:u:', [
  'remote-host:',
  'remote-port:',
  'forward-port:',
  'key-path:',
  'username:'
  ]);

  $remoteHost = '';
  $remotePort = 0;
  $forwardPort = 0;
  $keyPath = '';
  $username = '';

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
