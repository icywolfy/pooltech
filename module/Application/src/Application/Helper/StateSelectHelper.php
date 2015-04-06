<?php

namespace Application\Helper;

use Zend\Form\Element\Select;
use Zend\View\Helper\AbstractHelper;

class StateSelectHelper extends AbstractHelper {
  public function __invoke() {
    return $this->getSelectElement();
  }


  private function getSelectElement() {
    $select = new Select();
    $select->setUseHiddenElement(false);
    $select->setValueOptions($this->getStateList());
    $select->setName('state');
    $select->setAttributes(array(
      'id' => 'payment-state',
      'class' => 'form-control',
    ));

    return $select;
  }


  private function getStateList() {
    $states = array(
      'AL' => "Alabama",
      'AK' => "Alaska",
      'AZ' => "Arizona",
      'AR' => "Arkansas",
      'CA' => "California",
      'CO' => "Colorado",
      'CT' => "Connecticut",
      'DE' => "Delaware",
      'DC' => "District Of Columbia",
      'FL' => "Florida",
      'GA' => "Georgia",
      'HI' => "Hawaii",
      'ID' => "Idaho",
      'IL' => "Illinois",
      'IN' => "Indiana",
      'IA' => "Iowa",
      'KS' => "Kansas",
      'KY' => "Kentucky",
      'LA' => "Louisiana",
      'ME' => "Maine",
      'MD' => "Maryland",
      'MA' => "Massachusetts",
      'MI' => "Michigan",
      'MN' => "Minnesota",
      'MS' => "Mississippi",
      'MO' => "Missouri",
      'MT' => "Montana",
      'NE' => "Nebraska",
      'NV' => "Nevada",
      'NH' => "New Hampshire",
      'NJ' => "New Jersey",
      'NM' => "New Mexico",
      'NY' => "New York",
      'NC' => "North Carolina",
      'ND' => "North Dakota",
      'OH' => "Ohio",
      'OK' => "Oklahoma",
      'OR' => "Oregon",
      'PA' => "Pennsylvania",
      'RI' => "Rhode Island",
      'SC' => "South Carolina",
      'SD' => "South Dakota",
      'TN' => "Tennessee",
      'TX' => "Texas",
      'UT' => "Utah",
      'VT' => "Vermont",
      'VA' => "Virginia",
      'WA' => "Washington",
      'WV' => "West Virginia",
      'WI' => "Wisconsin",
      'WY' => "Wyoming"
    );

    // We only want abbreviations
    $states = array_combine(array_keys($states), array_keys($states));
    return $states;
  }
}
