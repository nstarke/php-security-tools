<?php
  $options = getopt('b:w:r:h', [
    'base-url:',
    'wordlist:',
    'response-codes:',
    'help'
    ]);

  if (array_key_exists('h', $options) || array_key_exists('help', $options)) {
    echo "PHP URL Fuzzer\n";
    echo "\n";
    echo "Usage: php url-fuzzer.php --base-url [base-url] --wordlist [path-to-wordlist] --response-codes [response-codes] --help\n";
    echo "-b, --base-url: Base URL to fuzz against.  Use the string 'FUZZ' where words from the word list need to be inserted.\n";
    echo "-w, --word-list: Word list to fuzz.\n";
    echo "-r, --response-codes: Response codes to return results for.\n";
    echo "-h, --help: Show this menu.\n";
    echo "\n";

    exit();
  }

  $baseUrl = '';
  $wordListPath = '';
  $responseCodes = [];

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
    exit();
  }
  var_dump($responseCodes);
  /* $handle = fopen($wordListPath, 'r'); */
  /* if ($handle) { */
  /*   while (($line = fgets($handle)) !== false) { */
  /*     if (strpos($line, '#') === false) { */
  /*       $full = str_replace('FUZZ', urlencode(trim($line)), $baseUrl); */
  /*       $code = get_headers($full, 1)[0]; */
  /*       foreach ($responseCodes as $responseCode) { */
  /*         echo $code . ' ' . var_dump($responseCodes); */
  /*         if (strpos($code, $responseCode) != false) { */
  /*           echo $full . "\n"; */
  /*           break; */
  /*         } */
  /*       } */
  /*     } */
  /*   } */
  /*   fclose($handle); */
  /* } else { */
  /*   echo "Word list file not found\n"; */
  /* } */
?>
