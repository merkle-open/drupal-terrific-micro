<?php

namespace Drupal\terrific\DrupalAdapter;

use Deniaz\Terrific\Config\ConfigReader;
use Deniaz\Terrific\TemplateLocatorInterface;
use Drupal\Core\Config\ConfigFactory as DrupalConfigFactory;
use Drupal\Core\File\FileSystemInterface;
use \DomainException;

/**
 * TemplateLocator provides a number of properties on how to load terrific
 * components.
 *
 * Class TemplateLocator
 * @package Drupal\terrific\DrupalAdapter
 *
 * @author Robert Vogt <robert.vogt@namics.com>
 */
class TemplateLocator implements TemplateLocatorInterface {

  /**
   * @var array $paths List of paths where templates are stored.
   */
  private $paths = [];

  /**
   * @var string $base_path Path to Frontend Directory.
   */
  private $base_path = '';

  /**
   * @var array $terrific_config Terrific's config.json Content.
   */
  private $terrific_config = [];

  /**
   * TemplateLocator constructor.
   * @param DrupalConfigFactory $config_factory
   * @param FileSystemInterface $filesystem
   */
  public function __construct(
    DrupalConfigFactory $config_factory,
    FileSystemInterface $filesystem
  ) {
    $this->base_path = $filesystem
      ->realpath(
        $config_factory->get('terrific.settings')->get('frontend_dir')
      );

    $this->terrific_config = (new ConfigReader($this->base_path))->read();
  }

  /**
   * Returns a list of paths where templates can be found.
   * @return array
   */
  public function getPaths() {
    if (empty($this->paths)) {
      $this->generatePaths();
    }

    return $this->paths;
  }

  /**
   * Generate Paths array from Terrific Configuration.
   */
  private function generatePaths() {
    $components = $this->terrific_config['nitro']['components'];
    foreach ($components as $name => $component) {
      $this->paths[$name] = $this->base_path . '/' . $component['path'];
    }
  }

  /**
   * @return string Template File Extension.
   */
  public function getFileExtension() {
    $fileExtension = $this->terrific_config['nitro']['view_file_extension'];
    if (!isset($fileExtension)) {
      throw new DomainException(
        "Frontend Template File Extension not defined in Terrific's Configuration File."
      );
    }

    return $fileExtension;
  }
}