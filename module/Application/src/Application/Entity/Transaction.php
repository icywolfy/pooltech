<?php
namespace Application\Entity;

class Transaction {
  private $txnTime;
  private $productCode;
  private $promoCode;
  private $chargeAmount;
  private $chargeCurrency;
  private $chargeResult;
  private $testTransaction = false;
  private $data;
  private $description;


  /**
   * @return mixed
   */
  public function getDescription() {
    return $this->description;
  }


  /**
   * @param mixed $description
   */
  public function setDescription($description) {
    $this->description = $description;
  }

  /**
   * @return mixed
   */
  public function getTxnTime() {
    return $this->txnTime;
  }


  /**
   * @param mixed $txnTime
   */
  public function setTxnTime($txnTime) {
    $this->txnTime = $txnTime;
  }


  /**
   * @return mixed
   */
  public function getProductCode() {
    return $this->productCode;
  }


  /**
   * @param mixed $productCode
   */
  public function setProductCode($productCode) {
    $this->productCode = $productCode;
  }


  /**
   * @return mixed
   */
  public function getPromoCode() {
    return $this->promoCode;
  }


  /**
   * @param mixed $promoCode
   */
  public function setPromoCode($promoCode) {
    $this->promoCode = $promoCode;
  }


  /**
   * @return mixed
   */
  public function getChargeAmount() {
    return $this->chargeAmount;
  }


  /**
   * @param mixed $chargeAmount
   */
  public function setChargeAmount($chargeAmount) {
    $this->chargeAmount = $chargeAmount;
  }


  /**
   * @return mixed
   */
  public function getChargeCurrency() {
    return $this->chargeCurrency;
  }


  /**
   * @param mixed $chargeCurrency
   */
  public function setChargeCurrency($chargeCurrency) {
    $this->chargeCurrency = $chargeCurrency;
  }


  /**
   * @return mixed
   */
  public function getChargeResult() {
    return $this->chargeResult;
  }


  /**
   * @param mixed $chargeResult
   */
  public function setChargeResult($chargeResult) {
    $this->chargeResult = $chargeResult;
  }


  /**
   * @return boolean
   */
  public function isTestTransaction() {
    return $this->testTransaction;
  }


  /**
   * @param boolean $testTransaction
   */
  public function setTestTransaction($testTransaction) {
    $this->testTransaction = $testTransaction;
  }


  /**
   * @return mixed
   */
  public function getData() {
    return $this->data;
  }


  /**
   * @param mixed $data
   */
  public function setData($data) {
    $this->data = $data;
  }

}
