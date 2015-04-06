<?php

namespace Application\Helper;

use Zend\Form\Element\Select;
use Zend\View\Helper\AbstractHelper;

class NumberRangeSelectHelper extends AbstractHelper {
  private $format = '%d';
  private $rangeStart = 0;
  private $rangeEnd = 0;
  private $attributes = array();
  public function __invoke() {
    return $this;
  }
  public function getSelect() {
    $select = new Select();
    $select->setUseHiddenElement(false);
    $rawRange = range($this->rangeStart, $this->rangeEnd);
    $numberFormat = $this->format;
    $options = array_combine($rawRange, $rawRange);
    $range = array_map(function ($v) use ($numberFormat) {
      return sprintf($numberFormat, $v);
    }, $rawRange);
    $select->setValueOptions($range);
    $select->setAttributes($this->attributes);
    return $select;
  }


  /**
   * @param string $format
   */
  public function setFormat($format) {
    $this->format = $format;
    return $this;
  }


  /**
   * @param int $rangeStart
   */
  public function setRangeStart($rangeStart) {
    $this->rangeStart = $rangeStart;
    return $this;
  }


  public function setRange($start, $end) {
    $this->setRangeStart($start);
    $this->setRangeEnd($end);
    return $this;
  }
  /**
   * @param int $rangeEnd
   */
  public function setRangeEnd($rangeEnd) {
    $this->rangeEnd = $rangeEnd;
    return $this;
  }


  /**
   * @param array $attributes
   */
  public function setAttributes($attributes) {
    $this->attributes = $attributes;
    return $this;
  }

  public function setAttribute($name, $value) {
    $this->attributes[$name] = $value;
    return $this;
  }


}
