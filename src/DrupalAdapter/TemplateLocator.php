<?php

namespace Drupal\terrific\DrupalAdapter;

use Deniaz\Terrific\Config\ConfigReader;
use Deniaz\Terrific\TemplateLocatorInterface;
use Drupal\Core\Config\ConfigFactory as DrupalConfigFactory;
use Drupal\Core\File\FileSystemInterface;
use \DomainException;

class TemplateLocator implements TemplateLocatorInterface {

  private $paths = [];

  private $base_path = null;

  private $terrific_config = null;

  public function __construct(DrupalConfigFactory $config_factory, FileSystemInterface $filesystem) {
    $this->base_path = $filesystem->realpath($config_factory->get('terrific.settings')->get('frontend_dir'));
    $this->terrific_config = (new ConfigReader($this->base_path))->read();
  }

  public function getPaths() {
    if (empty($this->paths)) {
      $this->generatePaths();
    }

    return $this->paths;
  }

  private function generatePaths() {

    foreach ($this->terrific_config['nitro']['components'] as $name => $component) {
      $this->paths[$name] = $this->base_path . '/' . $component['path'];
    }
  }

  public function getFileExtension() {
    if (!isset($this->terrific_config['nitro']['view_file_extension'])) {
      throw new DomainException('Frontend template file extension not defined in config.json');
    }

    return $this->terrific_config['nitro']['view_file_extension'];
  }
}