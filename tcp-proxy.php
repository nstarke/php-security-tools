<?php
$options = getopt('l:p:r:q:h', [
  'local-host:',
  'local-port:',
  'remote-host:',
  'remote-port:',
  'help'
]);

$localHost = '0.0.0.0';
$localPort = 0;
$remoteHost = '';
$remotePort = 0;

if (array_key_exists('h', $options) || array_key_exists('help', $options)) {
    echo "PHP TCP Proxy Tool\n";
    echo "\n";
    echo "Usage: php tcp-proxy.php --local-host <local-host> --local-port <local-port> --remote-host <remote-host> --remote-port <remote-post> --help\n";
    echo "\n";
    echo "-l, --local-host: The local host ip to listen for connections on\n";
    echo "-p, --local-port: The local port to listen for connections on\n";
    echo "-r, --remote-host: The remote host to forward connections to\n";
    echo "-q, --remote-port: The remote port to forward connectiosn to\n";
    echo "-h, --help: Show this help menu\n";
    echo "\n";
    exit();
}

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

$localSocket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);

socket_bind($localSocket, $localHost, $localPort) or die('Could not bind to address (is it in use?)');

socket_listen($localSocket, 5);


while (true) {
  $localClient = socket_accept($localSocket);
  $localBuffer = '';
  while (true) {
    $localInput = socket_read($localClient, 4096);
    $localBuffer .= $localInput;
    if ($localInput < 4096) {
        break;
    }
  }

  $remoteSocket = fsockopen($remoteHost, $remotePort, $errno, $errstr, 30);
  echo "[==>] Local request heading out.\n";
  hex_dump($localBuffer);
  fwrite($remoteSocket, $localBuffer);

  $remoteBuffer = '';
  while (true) {
    $remoteResponse = fgets($remoteSocket, 4096);
    $remoteBuffer .= $remoteResponse;
    if ($remoteResponse < 4096) {
        break;
    }
  }

  echo "[<==] Remote response coming in.\n";
    hex_dump($remoteBuffer);
    socket_write($localClient, $remoteBuffer);
    fclose($remoteSocket);
    socket_close($localClient);
}

# from http://stackoverflow.com/questions/1057572/how-can-i-get-a-hex-dump-of-a-string-in-php
function hex_dump($data, $newline="\n")
{
  static $from = '';
  static $to = '';

  static $width = 16; # number of bytes per line

  static $pad = '.'; # padding for non-visible characters

  if ($from==='')
  {
      for ($i=0; $i<=0xFF; $i++)
            {
            $from .= chr($i);
            $to .= ($i >= 0x20 && $i <= 0x7E) ? chr($i) : $pad;
                }
        }

    $hex = str_split(bin2hex($data), $width*2);
    $chars = str_split(strtr($data, $from, $to), $width);

      $offset = 0;
      foreach ($hex as $i => $line)
          {
          echo sprintf('%6X',$offset).' : '.implode(' ', str_split($line,2)) . ' [' . $chars[$i] . ']' . $newline;
          $offset += $width;
        }
}

?>
