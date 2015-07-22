# Drupal theme for Terrific Micro

This theme provides a theme to load a [Terrific Micro](https://github.com/namics/terrific-micro) frontend in your Drupal 7 setup.

## Setup
* Place terrific into your themes folder, as usual.
* Enable and set the theme _Terrific_ as default.
* You will see an example front page with two demo blocks, if Terrific could be loaded successfully. You can remove those by editing html.tpl.php.
* Replace the folder terrific-micro/frontend with your existing Terrific Micro frontend or use the provided skeleton as a starting point to developer your frontend.

## General usage
* Note the $nocache variable in the terrific-micro/utils.php. It's recommended to set it to FALSE on production.
* The integration of assets is shown into the templates/html.tpl.php file and should be self-explanatory.

## Credits
* This Drupal theme is maintained by Namics AG

## Licence
* See LICENSE.txt