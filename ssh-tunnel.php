<?php
require('vendor/autoload.php');

$options = getopt('r:p:k:u:h', [
  'remote-host:',
  'remote-port:',
  'key-path:',
  'username:',
  'help'
  ]);

  $remoteHost = '';
  $remotePort = 0;
  $keyPath = '';
  $username = '';
  $password = '';

  if (array_key_exists('h', $options) || array_key_exists('help', $options)) {
      echo "PHP SSH Tunnel";
      echo "\n";
      echo "Usage: php ssh-tunnel.php --remote-host [remote-host] --remote-port [remote-port] --key-path [key-path] --username [username] --help";
      echo "\n";
      echo "-r, --remote-host: Remote host to connect back to.\n";
      echo "-p, --remote-port: Remote post to connect back to.\n";
      echo "-k, --key-path: Local path for ssh key.\n";
      echo "-u, --username: SSH Username.\n";
      echo "-h, --help: Show this menu.\n";
      echo "\n";
      exit();
  }

  // Password can be overridden with HTTP GET Query String parameter.
  if (array_key_exists('password', $_GET)) {
      $password = urldecode($_GET['password']);
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
  } else if (array_key_exists('remote-port', $options)) {
      $remotePort = $options['remote-port'];
  }

  if (array_key_exists('key-path', $_GET)) {
      $keyPath = $_GET['key-path'];
  } else if (array_key_exists('k', $options)) {
      $keyPath = $options['k'];
  } else if (array_key_exists('key-path', $options)) {
      $keyPath = $options['key-path'];
  }

  if (array_key_exists('username', $_GET)) {
      $username = $_GET['username'];
  } else if (array_key_exists('u', $options)) {
      $username = $options['u'];
  } else if (array_key_exists('username', $options)) {
      $username = $options['username'];
  }

  $startErrors = [];

  if (strlen($remoteHost) == 0) {
      $startErrors[] = "Remote Host is required.";
  }

  if ($remotePort == 0) {
      $startErrors[] = "Remote Port is required.";
  }

  if (count($startErrors) > 0) {
    echo join(' ', $startErrors);
    exit();
  }

  $ssh = new Net_SSH2($remoteHost, $remotePort);
  if (strlen($keyPath) > 0) {
    echo 'Using Key: ' . $keyPath . "\n";
    $privateKey = file_get_contents($keyPath);
    $key = new Crypt_RSA();
    if (strlen($password) == 0) {
      echo "Please enter key password:\n";
      system('stty -echo');
      $password = trim(fgets(STDIN));
      system('stty echo');
    }

    if (strlen($password) > 0) {
      $key->setPassword($password);
    }

    $key->loadKey($privateKey);

    if ($ssh->login($username, $key)) {
        shellOut($ssh);
    } else {
        echo "Login Failed!\n";
    }
  } else {

    if (strlen($password) == 0) {
      echo "Please enter password:\n";
      system('stty -echo');
      $password = trim(fgets(STDIN));
      system('stty echo');
    }

    if ($ssh->login($username, $password)) {
        shellOut($ssh);
    } else {
        echo "Login Failed!\n";
    }
  }

  function shellOut($ssh) {
    while (true) {
      echo "$ ";
      $input = fgets(STDIN);
      echo $ssh->exec($input);
    }
  }
?>
