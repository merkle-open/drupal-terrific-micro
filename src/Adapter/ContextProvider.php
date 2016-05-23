<?php

/**
 * This file is part of the Terrific Twig package.
 *
 * (c) Robert Vogt <robert.vogt@namics.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Drupal\terrific\Adapter;

use Deniaz\Terrific\Provider\ContextProviderInterface;
use Twig_Compiler;
use Twig_Node;
use Twig_Node_Expression_Array;
use Twig_Node_Expression_Constant;

/**
 * Class ContextProvider
 * @package Drupal\terrific\Adapter
 */
class ContextProvider implements ContextProviderInterface {
  /**
   * @const string TERRIFIC_ARRAY_KEY Array Key where data is stored.
   */
  const TERRIFIC_ARRAY_KEY = '#terrific';
  /**
   * @var Twig_Compiler $compiler
   */
  private $compiler;

  /**
   * @var Twig_Node $component
   */
  private $component;

  /**
   * @var Twig_Node $dataVariant
   */
  private $dataVariant;

  /**
   * @var bool $only If true a new context is created.
   */
  private $only;

  /**
   * {@inheritdoc}
   */
  public function compile(
    Twig_Compiler $compiler,
    Twig_Node $component,
    Twig_Node $dataVariant = NULL,
    $only = FALSE
  ) {
    $this->compiler = $compiler;
    $this->component = $component;
    $this->dataVariant = $dataVariant;
    $this->only = (bool) $only;

    if ($this->only) {
      $this->compiler
        ->raw("\t")
        ->raw('$tContext = [];');
    }

    $this->createContext();
  }

  /**
   * Creates a new context or merges the variant with the existing context.
   */
  private function createContext() {
    if ($this->dataVariant instanceof Twig_Node_Expression_Array) {
      $this->compiler
        ->raw('$tContext = array_merge($tContext, ')
        ->subcompile($this->dataVariant)
        ->raw(');');
    }
    else {
      $dataKey = ($this->dataVariant instanceof Twig_Node_Expression_Constant)
        ? $this->dataVariant->getAttribute('value')
        : $this->component->getAttribute('value');

      $this->compiler
        ->raw("\n")->addIndentation()
        ->raw('if (')
        ->raw('isset($context["' . self::TERRIFIC_ARRAY_KEY . '"]) && ')
        ->raw('isset($context["' . self::TERRIFIC_ARRAY_KEY . '"]["' . $dataKey . '"])')
        ->raw(') {')
        ->raw("\n")->addIndentation()->addIndentation()
        ->raw('$tContext = array_merge($tContext, ')
        ->raw('$context["' . self::TERRIFIC_ARRAY_KEY . '"]["' . $dataKey . '"]')
        ->raw(');')
        ->raw("\n")->addIndentation()
        ->raw('} else {')
        ->raw("\n")->addIndentation()->addIndentation()
        ->raw('throw new \Twig_Error("')
        ->raw("Data Variant {$dataKey} not mapped. Check your preprocess hooks.")
        ->raw('");')
        ->raw("\n")->addIndentation()
        ->raw('}')
        ->raw("\n\n");
    }
  }
}