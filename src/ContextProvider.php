<?php

namespace Drupal\terrific;

use Deniaz\Terrific\Provider\ContextProviderInterface;
use Twig_Compiler;
use Twig_Node;
use Twig_Node_Expression_Array;
use Twig_Node_Expression_Constant;

class ContextProvider implements ContextProviderInterface
{
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
     * @var bool $only
     */
    private $only;

    /**
     * {@inheritdoc}
     */
    public function compile(Twig_Compiler $compiler, Twig_Node $component, Twig_Node $dataVariant = null, $only = false)
    {
        $this->compiler = $compiler;
        $this->component = $component;
        $this->dataVariant = $dataVariant;
        $this->only = (bool)$only;

        if (false === $this->only) {
            $this->compiler->raw('$tContext = $context;');
        } else {
            $this->compiler->raw('$tContext = [];');
        }

        $this->createContext();
    }

//    private function createDefaultContext()
//    {
//        $componentName = $this->component->getAttribute('value');
//
//        $this->compiler
//            ->raw("\n")
//            ->raw('if (')
//            ->raw('isset($context["$terrific"]) && ')
//            ->raw('isset($context["$terrific"]["' . $componentName . '"])')
//            ->raw(') {')
//            ->raw("\n\t")
//            ->raw('$tContext = array_merge($tContext, ')
//            ->raw('$context]["$terrific"]["' . $componentName . '"]')
//            ->raw(');')
//            ->raw("\n")
//            ->raw('} else {')
//            ->raw('throw new \Twig_Error("')
//            ->raw("Data Variant {$componentName} not mapped. Check your preprocess hooks.")
//            ->raw('");')
//            ->raw('}');
//    }

    private function createContext()
    {
        if ($this->dataVariant instanceof Twig_Node_Expression_Array) {
            $this->compiler
                ->raw('$tContext = array_merge($tContext, ')
                ->subcompile($this->dataVariant)
                ->raw(');');
            return;
        }

        $dataKey = ($this->dataVariant instanceof Twig_Node_Expression_Constant)
            ? $this->dataVariant->getAttribute('value')
            : $this->component->getAttribute('value');

        $this->compiler
            ->raw("\n")
            ->raw('if (')
            ->raw('isset($context["#terrific"]) && ')
            ->raw('isset($context["#terrific"]["' . $dataKey . '"])')
            ->raw(') {')
            ->raw("\n\t")
            ->raw('$tContext = array_merge($tContext, ')
            ->raw('$context["#terrific"]["' . $dataKey . '"]')
            ->raw(');')
            ->raw("\n")
            ->raw('} else {')
            ->raw('throw new \Twig_Error("')
            ->raw("Data Variant {$dataKey} not mapped. Check your preprocess hooks.")
            ->raw('");')
            ->raw('}')
        ;

        return;

//        elseif ($this->dataVariant instanceof Twig_Node_Expression_Constant) {
//            $variantName = $this->dataVariant->getAttribute('value');
//
//            $this->compiler
//                ->raw('if (')
//                ->raw('isset($context["element"]) && ')
//                ->raw('isset($context["element"]["terrific"]) && ')
//                ->raw('isset($context["element"]["terrific"]["' . $variantName . '"])')
//                ->raw(') {')
//                ->raw('$tContext = array_merge($tContext, ')
//                ->raw('$context["element"]["terrific"]["' . $variantName . '"]')
//                ->raw(');')
//                ->raw('} else {')
//                ->raw('throw new \Twig_Error("')
//                ->raw("Data Variant {$variantName} not mapped. Check your preprocess hooks.")
//                ->raw('");')
//                ->raw('}');
//        }
    }
}