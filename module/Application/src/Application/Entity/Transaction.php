<?php
namespace Application\Entity;

use DateTime;

class Transaction
{
  private $id;
  private $customerId;
  private $stripeCustomerId;
  private $stripeTransactionId;
  const STATUS_SUCCESS = 'succeeded';
  /** @var DateTime */
  private $timestamp;
  private $email;
  private $status;
  private $data;
  private $chargeAmount;
  private $chargeCurrency;
  private $chargeResult;
  private $chargeDescription;
  private $testTransaction = false;


  /**
   * @return mixed
   */
  public function getId()
  {
    return $this->id;
  }


  /**
   * @param mixed $id
   */
  public function setId($id)
  {
    $this->id = $id;
  }


  /**
   * @return mixed
   */
  public function getCustomerId()
  {
    return $this->customerId;
  }


  /**
   * @param mixed $customerId
   */
  public function setCustomerId($customerId)
  {
    $this->customerId = $customerId;
  }


  /**
   * @return mixed
   */
  public function getStripeCustomerId()
  {
    return $this->stripeCustomerId;
  }


  /**
   * @param mixed $stripeCustomerId
   */
  public function setStripeCustomerId($stripeCustomerId)
  {
    $this->stripeCustomerId = $stripeCustomerId;
  }


  /**
   * @return mixed
   */
  public function getStripeTransactionId()
  {
    return $this->stripeTransactionId;
  }


  /**
   * @param mixed $stripeTransactionId
   */
  public function setStripeTransactionId($stripeTransactionId)
  {
    $this->stripeTransactionId = $stripeTransactionId;
  }


  /**
   * @return DateTime
   *
   */
  public function getTimestamp()
  {
    return $this->timestamp;
  }


  /**
   * @param DateTime $timestamp
   */
  public function setTimestamp($timestamp=null)
  {
    $this->timestamp = $timestamp;
  }


  /**
   * @return mixed
   */
  public function getEmail()
  {
    return $this->email;
  }


  /**
   * @param mixed $email
   */
  public function setEmail($email)
  {
    $this->email = $email;
  }


  /**
   * @return mixed
   */
  public function getStatus()
  {
    return $this->status;
  }


  /**
   * @param mixed $status
   */
  public function setStatus($status)
  {
    $this->status = $status;
  }


  /**
   * @return mixed
   */
  public function getData()
  {
    return $this->data;
  }


  /**
   * @param mixed $data
   */
  public function setData($data)
  {
    $this->data = $data;
  }


  /**
   * @return mixed
   */
  public function getChargeAmount()
  {
    return $this->chargeAmount;
  }


  /**
   * @param mixed $chargeAmount
   */
  public function setChargeAmount($chargeAmount)
  {
    $this->chargeAmount = $chargeAmount;
  }


  /**
   * @return mixed
   */
  public function getChargeCurrency()
  {
    return $this->chargeCurrency;
  }


  /**
   * @param mixed $chargeCurrency
   */
  public function setChargeCurrency($chargeCurrency)
  {
    $this->chargeCurrency = $chargeCurrency;
  }


  /**
   * @return mixed
   */
  public function getChargeResult()
  {
    return $this->chargeResult;
  }


  /**
   * @param mixed $chargeResult
   */
  public function setChargeResult($chargeResult)
  {
    $this->chargeResult = $chargeResult;
  }


  /**
   * @return mixed
   */
  public function getChargeDescription()
  {
    return $this->chargeDescription;
  }


  /**
   * @param mixed $chargeDescription
   */
  public function setChargeDescription($chargeDescription)
  {
    $this->chargeDescription = $chargeDescription;
  }


  /**
   * @return boolean
   */
  public function isTestTransaction()
  {
    return $this->testTransaction;
  }


  /**
   * @param boolean $testTransaction
   */
  public function setTestTransaction($testTransaction)
  {
    $this->testTransaction = $testTransaction;
  }
  public function isSuccess() {
    return $this->getStatus() === self::STATUS_SUCCESS;
  }
}
