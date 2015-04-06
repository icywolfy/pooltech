<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/zf2 for the canonical source repository
 * @copyright Copyright (c) 2005-2015 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Application\Helper;

use Zend\Form\Element\Hidden;
use Zend\Form\ElementInterface;
use Zend\Form\Element\Select as SelectElement;
use Zend\Form\Exception;
use Zend\Stdlib\ArrayUtils;
use Zend\Form\View\Helper\FormSelect as BaseFormSelect;

class FormSelectHelper extends BaseFormSelect
{
  /**
   * Render a form <select> element from the provided $element
   *
   * @param  ElementInterface $element
   * @throws Exception\InvalidArgumentException
   * @throws Exception\DomainException
   * @return string
   */
  public function render(ElementInterface $element)
  {
    if (!$element instanceof SelectElement) {
      throw new Exception\InvalidArgumentException(sprintf(
        '%s requires that the element is of type Zend\Form\Element\Select',
        __METHOD__
      ));
    }

    $name   = $element->getName();
    if (empty($name) && $name !== 0) {
      $name = null;
    }

    $options = $element->getValueOptions();

    if (($emptyOption = $element->getEmptyOption()) !== null) {
      $options = array('' => $emptyOption) + $options;
    }

    $attributes = $element->getAttributes();
    $value      = $this->validateMultiValue($element->getValue(), $attributes);

    if (null !== $name) {
      $attributes['name'] = $name;
      if (array_key_exists('multiple', $attributes) && $attributes['multiple']) {
        $attributes['name'] .= '[]';
      }
    }
    $this->validTagAttributes = $this->validSelectAttributes;

    $rendered = sprintf(
      '<select %s>%s</select>',
      $this->createAttributesString($attributes),
      $this->renderOptions($options, $value)
    );

    // Render hidden element
    $useHiddenElement = method_exists($element, 'useHiddenElement')
      && method_exists($element, 'getUnselectedValue')
      && $element->useHiddenElement();

    if ($useHiddenElement) {
      $rendered = $this->renderHiddenElement($element) . $rendered;
    }

    return $rendered;
  }
}
