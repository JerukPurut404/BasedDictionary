<?php
session_start();
$word = $_POST['wordChecker'];
$api_url = 'https://api.dictionaryapi.dev/api/v2/entries/en/';

function getData($url)
{
    $ch = curl_init();
    curl_setopt($ch, CURLINFO_HEADER_OUT, true);
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HEADER, "Content-type: application/json; charset=utf-8\r
");
    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
        'Content-type: application/json; charset=utf-8',
        'Accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,*/*;q=0.8',
        'Accept-Language: en-US,en;q=0.5',
        'Accept-Encoding: gzip, deflate, br',
        'DNT: 1',
        'Connection: keep-alive',
        'Upgrade-Insecure-Requests: 1',
        'Sec-Fetch-Dest: document',
        'Sec-Fetch-Mode: navigate',
        'Sec-Fetch-Site: cross-site'
    ));
    curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 10.0; rv:120.0) Gecko/20100101 Firefox/120.0');
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($ch, CURLOPT_MAXREDIRS, 3);
    curl_setopt($ch, CURLOPT_TIMEOUT, 30); 
    curl_setopt($ch, CURLOPT_ENCODING, '');

    $result = curl_exec($ch);
    if ($result === false) {
        echo 'Error: '. curl_error($ch);
        curl_close($ch);
        return false;
    }

    $information = curl_getinfo($ch);
    curl_close($ch);

    $data = json_decode($result, true);
    return $data;
};

function find__word($api_url, $word){
  $url = $api_url . $word;
  $response = getData($url);

  $_SESSION['word'] = $response[0]['word'];
  $_SESSION['definitions'] = [];
  $_SESSION['audio'] = [];
  foreach($response[0]['meanings'] as $meaning) {
      $partOfSpeech = $meaning['partOfSpeech'];
      
      foreach($meaning['definitions'] as $definition) {
          $data = [
              'partOfSpeech' => $partOfSpeech,
              'definition' => $definition['definition'],
              'example' => isset($definition['example']) ? $definition['example'] : "",
              "synonyms" => !empty($definition['synonyms']) ? implode(", ", $definition['synonyms']) : "",
              "antonyms" => !empty($definition['antonyms']) ? implode(", ", $definition['antonyms']) : ""
          ];
          $_SESSION['definitions'][] = $data;        
      }
  }

  foreach($response[0]['phonetics'] ?? [] as $phonetic){
    if(isset($phonetic['audio']) && !empty($phonetic['audio'])){
        $_SESSION['audio'][] = $phonetic['audio'];
    }
}

  header("Location: result.php");
}

find__word($api_url, $word);
?>
