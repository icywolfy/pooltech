<?php
namespace Application\Entity;

class CostMatrix {
  private $productCost = array(
    'pool_year' => 3500,
    'pool_month' => 5000,
    'spa_year' => 2500,
    'spa_month' => 4000,
    'combo_year' => 5500,
    'combo_month' => 8500,
  );

  public function getCost($product) {
    $targetProduct = strtolower($product);
    return isset($this->productCost[$targetProduct]) ? $this->productCost[$targetProduct] : null;
  }

}
