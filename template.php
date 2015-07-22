<?php

/**
 * Terrific Helper function to render an asset from terrific-micro.
 */
function terrific_render_asset($asset) {
  $filetype = substr( strrchr( $asset, '.' ), 1 );
  $do_min = variable_get('preprocess_js', FALSE);
  $cache_key = variable_get('css_js_query_string', '0');

  // Generate path to asset, including params for caching and minification
  $asset_path = '/' . path_to_theme();
  $asset_path .= '/terrific-micro/terrific-asset.php';
  $asset_path .= '?cachekey=' . $cache_key;

  if ($do_min) {
    $asset_path .= '&min';
  }

  $asset_path .= '&name=' . $asset;

  switch ( $filetype ) {
    case 'css':
      $asset_path .= '&mime=css';
      return '<link href="' . $asset_path . '" type="text/css" rel="stylesheet" />';
      break;
    case 'js':
      $asset_path .= '&mime=javascript';
      return '<script src="' . $asset_path . '" type="text/javascript" /></script>';
      break;
    default:
      return '';
  }
}