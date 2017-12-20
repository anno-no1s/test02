<?php

/**************************************************

	2017年12月テスト 問2プログラム

**************************************************/

$requestUrl = 'https://www.google.co.jp/search?q=%E6%B2%96%E7%B8%84%E3%80%80%E9%AB%98%E7%B4%9A%E3%83%9B%E3%83%86%E3%83%AB';
$requestMethod = 'GET';

$curl = curl_init() ;
curl_setopt($curl, CURLOPT_URL, $requestUrl);
curl_setopt($curl, CURLOPT_HEADER, true);
curl_setopt($curl, CURLOPT_CUSTOMREQUEST, $requestMethod);
curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
curl_setopt($curl, CURLOPT_TIMEOUT, 30);
$response1 = curl_exec($curl);
$response2 = curl_getinfo($curl);
$errno = curl_errno($curl);
curl_close($curl);

if (CURLE_OK !== $errno) {
    echo 'Curl error: ' . $errno;
    exit;
}

$html = substr($response1, $response2['header_size']);

$html = mb_convert_encoding($html, 'utf-8', 'sjis');
// テキスト中のヒットした検索ワードに付加されているbタグを除去
$html = preg_replace('/<\/?b>/i', '', $html);
$domDocument = new DOMDocument();
@$domDocument->loadHTML($html);
$xmlString = $domDocument->saveXML();
$xmlObject = simplexml_load_string($xmlString);

$targetTagList = [
    ['body'],
    ['table'],
    ['tbody'],
    ['tr'],
    ['td',  ['id' => null]],
    ['div', ['id' => 'center_col']],
    ['div', ['id' => 'res']],
    ['div', ['id' => 'search']],
    ['div', ['id' => 'ires']],
    ['ol'],
    ['div', ['class' => 'g']],
];
$searchList = searchAttribute($xmlObject, $targetTagList);

$view = [];
foreach ($searchList as $list) {
    if (isset($list->h3->a) && isset($list->div->div->cite)) {
        $view[] = [
            'title' => $list->h3->a,
            'url'   => urlFormat($list->div->div->cite),
        ];
    }
    if (isset($list->table->tr[0]->td->h3->a) && isset($list->table->tr[1]->td[1]->cite)) {
        $view[] = [
            'title' => $list->table->tr[0]->td->h3->a,
            'url'   => urlFormat($list->table->tr[1]->td[1]->cite),
        ];
    }
}

// 表示
foreach ($view as $v) {
    echo '<<< ' . $v['title'] . ' >>>', PHP_EOL;
    echo $v['url'], PHP_EOL;
    echo '----------------------------------------------------------------------', PHP_EOL;
}

function urlFormat($url) {
    $url = preg_replace('/ /', '', $url);

    $urlParts = @parse_url($url);
    $scheme = array_key_exists('scheme', $urlParts) ? $urlParts['scheme'] : 'http';
    $host = array_key_exists('host', $urlParts) ? $urlParts['host']  : '';
    $path = array_key_exists('path', $urlParts) ? $urlParts['path']  : '';
    $query = array_key_exists('query', $urlParts) ? $urlParts['query']  : '';

    $scheme .= '://';
    $path = implode("/", array_map("urlencode", explode("/", $path)));
    if ($query) {
        parse_str($query, $parms);
        $query = '?' . http_build_query($parms);
    }

    return implode('', [$scheme, $host, $path, $query]);
}

function searchAttribute($xmlObject, $targetTagList) {
    $result = [];
    if (!$tag = array_shift($targetTagList)) {
        return $xmlObject;
    }
    if (!array_key_exists(1, $tag)) {
        $result = searchAttribute($xmlObject->{$tag[0]}, $targetTagList);
    } else {
        foreach ($xmlObject->{$tag[0]} as $obj) {
            $attribute = key($tag[1]);
            if ((string)$obj->attributes()->{$attribute} == $tag[1][$attribute]) {
                $result[] = searchAttribute($obj, $targetTagList);
            }
        }
        if (count($result) == 1) {
            $result = $result[0];
        }
    }
    return $result;
}
