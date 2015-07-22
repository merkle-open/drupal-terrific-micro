<?php
require 'utils.php';

// Define mimetype
$mime = isset($_GET['mime']) ? $_GET['mime'] : '';
$mimetype = 'text/' . $mime;
header( 'Content-Type: ' . $mimetype );

if (isset($_REQUEST['min'])) {
  header('Cache-Control: public, max-age='. 2 * 7 * 24 * 60 * 60); // 2 Weeks in seconds
}

$cachekey = isset($_GET['cachekey']) ? $_GET['cachekey'] : '';

// Get asset name to render
$asset_name = isset($_GET['name']) ? $_GET['name'] : '';

foreach ( $config->assets as $asset => $value ) {
  if ($asset == $asset_name) {
    dump( $asset, $mimetype, $cachekey );
    exit();
  }
}
