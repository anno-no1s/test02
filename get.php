<?php

/**************************************************

	2018年月テスト 問2プログラム

**************************************************/

require 'vendor/autoload.php';

use meCab\meCab;

$mecab = new meCab();
$lines = file('./data.txt');
$result = [];
foreach ($lines as $line) {
  $analysis = $mecab->analysis($line);
  foreach ($analysis as $word) {
      if ($word->getSpeech() !== '名詞') {
          continue;
      }
      if (!isset($result[$word->getText()])) {
          $result[$word->getText()] = 0;
      }
      $result[$word->getText()]++;
  }
}
arsort($result);

foreach ($result as $word => $count) {
    echo $word . "\t" . $count . "\n";
}
