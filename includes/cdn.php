<?php
//error_reporting(E_ALL);
//ini_set( 'display_errors', 1 );
// phpcs:ignoreFile WordPress.Security.NonceVerification.Recommended

use WpOrg\Requests\Requests;

if (isset($_SERVER['HTTP_IF_MODIFIED_SINCE'])) {
    header('HTTP/1.1 304 Not Modified');
    header('Last-Modified: ' . $_SERVER['HTTP_IF_MODIFIED_SINCE'], true, 304);
    exit;
}

function a2wl_mbstring_binary_safe_encoding($reset = false) {
    static $encodings = [];
    static $overloaded = null;

    if (is_null($overloaded))
        $overloaded = function_exists('mb_internal_encoding') && ( ini_get('mbstring.func_overload') & 2 );

    if (false === $overloaded)
        return;

    if (!$reset) {
        $encoding = mb_internal_encoding();
        array_push($encodings, $encoding);
        mb_internal_encoding('ISO-8859-1');
    }

    if ($reset && $encodings) {
        $encoding = array_pop($encodings);
        mb_internal_encoding($encoding);
    }
}

try {
    include('functions.php');

    $key = "";
    if (file_exists("../../../uploads/ali2woo/pk.php")) {
        include ("../../../uploads/ali2woo/pk.php");
        $key = a2wl_plugin_key();
    }
    
    if (empty($key) || !a2wl_verify_request($_REQUEST['_sign'], ['url'=> $_REQUEST['url'] ?? ''], $key)) {
        header('HTTP/1.1 401 Unauthorized');
        exit;
    }
    
    if (!class_exists('WpOrg\Requests\Requests')) {
        include_once './../../../../wp-includes/Requests/src/Autoload.php';
        WpOrg\Requests\Autoload::register();
    }

    // Avoid issues where mbstring.func_overload is enabled.
    a2wl_mbstring_binary_safe_encoding();

    $request_url = !empty($_REQUEST['url']) ? $_REQUEST['url'] : '';
    if (base64_encode(base64_decode($request_url, true)) === $request_url) {
        $request_url = base64_decode($request_url, true);
    }
    
    if ($request_url){
        if (substr($request_url, 0, 4) !== "http") {
            $request_url = "https:" . $request_url;
        }
    }

    $requests_response = Requests::get(
        $request_url,
        ['Accept-Encoding' => ''],
        [
            'timeout' => 30,
            'useragent' => 'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/55.0.2883.87 Safari/537.36',
            'verify' => false,
            'sslverify' => false,
            'verifyname' => false
        ]
    );

    foreach ($requests_response->headers->getAll() as $name => $values) {
        if (in_array(strtolower($name), ['content-length', 'content-type', 'cache-control', 'last-modified', 'expires', 'date'])) {
            foreach ($values as $value) {
                header("$name: $value");
            }
        }
    }

    echo $requests_response->body;
} catch (Exception $e) {
    header('HTTP/1.1 400 Bad Request');
    exit;
}
