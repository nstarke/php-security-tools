<?php
  $options = getopt('b:w:r:v:o:h', [
    'base-url:',
    'wordlist:',
    'response-codes:',
    'verb:',
    'option:',
    'help',
    ]);

  if (array_key_exists('h', $options) || array_key_exists('help', $options)) {
    echo "PHP URL Fuzzer\n";
    echo "\n";
    echo "Usage: php url-fuzzer.php --base-url [base-url] --wordlist [path-to-wordlist] --response-codes [response-codes] --help\n";
    echo "-b, --base-url: Base URL to fuzz against.  Use the string 'FUZZ' where words from the word list need to be inserted.\n";
    echo "-w, --word-list: Word list to fuzz.\n";
    echo "-r, --response-codes: Response codes to return results for.\n";
    echo "-v, --verb: Which HTTP Verb to use.  Defaults to GET.\n";
    echo "-o, --body: Request body to use.\n";
    echo "-h, --help: Show this menu.\n";
    echo "\n";

    exit();
  }

  $baseUrl = '';
  $wordListPath = '';
  $responseCodes = [];
  $verb = 'GET';
  $body = '';

  if (array_key_exists('base-url', $_GET)) {
    $baseUrl = $_GET['base-url'];
  } else if (array_key_exists('b', $options)) {
    $baseUrl = $options['b'];
  } else if (array_key_exists('base-url', $options)) {
    $baseUrl = $options['base-url'];
  }

  if (array_key_exists('wordlist', $_GET)) {
      $wordListPath = $_GET['wordlist'];
  } else if (array_key_exists('w', $options)) {
      $wordListPath = $options['w'];
  } else if (array_key_exists('wordlist', $options)) {
      $wordListPath = $options['wordlist'];
  }

  if (array_key_exists('response-codes', $_GET)) {
      $responseCodes = split(',', $_GET['response-codes']);
  } else if (array_key_exists('r', $options)) {
      $responseCodes = split(',', $options['r']);
  } else if (array_key_exists('response-codes', $options)) {
      $responseCodes = split(',', $options['response-codes']);
  }

  if (array_key_exists('verb', $_GET)) {
      $verb = $_GET['verb'];
  } else if (array_key_exists('v', $options)) {
    $verb = $options['v'];
  } else if (array_key_exists('verb', $options)) {
      $verb = $options['verb'];
  }

  if (array_key_exists('body', $_GET)) {
    $body = $_GET['body'];
  } else if (array_key_exists('o', $options)) {
    $body = $options['o'];
  } else if (array_key_exists('body', $options)){
    $body = $options['body'];
  }

  $startErrors = [];

  if (strlen($baseUrl) == 0) {
      $startErrors[] = "Base Url must be set.";
  }

  if (strlen($wordListPath) == 0) {
      $startErrors[] = "Word List path must be set.";
  }

  if (count($responseCodes) == 0) {
      $startErrors[] = "At least one response code must be set.";
  }

  if (count($startErrors) > 0) {
    echo join("\n", $startErrors);
    exit(1);
  }

  stream_context_set_default(
    array(
        'http' => array(
            'method' => $verb
        )
    )
  );

  $handle = fopen($wordListPath, 'r');

  if ($handle) {
    while (($line = fgets($handle)) !== false) {
      if (strpos($line, '#') === false) {
        $full = str_replace('FUZZ', urlencode(trim($line)), $baseUrl);
        $code = 0;
        if (strlen($body) == 0) {
          $code = get_headers($full, 1)[0];
        } else {
          $reqOptions = array('http' => array('content' => $body, 'ignore_errors' => true, 'method' => $verb));
          $context = stream_context_create($reqOptions);
          $code = @file_get_contents($full, false, $context);
        }
        foreach ($responseCodes as $responseCode) {
          if (strpos($code, $responseCode) != false) {
            echo $full . "\n";
            break;
          }
        }
      }
    }
    fclose($handle);
  } else {
    echo "Word list file not found\n";
  }
?>
