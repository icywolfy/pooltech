<?php

namespace Application;

use Application\Entity\CostMatrix;
use Application\Entity\CustomerData;
use Application\Entity\Transaction;
use Application\Exception\ProcessingException;
use Stripe\Card;
use Stripe\Customer;

class PreOrder
{
  /** @var  CustomerRepository */
  private $customerRepository;
  /** @var  TransactionRepository */
  private $transactionRepository;
  /** @var  StripeProcessor */
  private $stripeProcessor;
  /** @var  string */
  private $activePromoCode = '';


  public function chargeCard($token, CustomerData $customer, Transaction $transaction)
  {
    // Data Validation
    $this->validateCustomerProduct($customer);
    $this->populateTransaction($customer, $transaction);

    // Customer Storage
    if ($this->requiresNewLocalCustomer($customer)) {
      $this->createLocalCustomer($customer);
    } else {
      $storedCustomer = $this->getUnchargedLocalCustomerObject($customer);
      $customer->setId($storedCustomer->getId());
    }

    // Stripe Customer Storage
    if (!$customer->getStripeId()) {
      $this->createStripeCustomer($token, $customer);
      $this->updateLocalCustomer($customer);
    } else {
      // Add the replace card on the customer object
      $this->replacePaymentSource($token, $customer);
    }

    $this->requireCustomerStripeId($customer);

    // Transaction Log
    $this->createLocalTransaction($transaction);

    // Charge
    $this->chargeStripeCustomer($customer, $transaction);

    // Update local detail
    $this->updateLocalTransaction($transaction);
  }


  private function replacePaymentSource($token, CustomerData $customer) {
    try {
      /** @var Customer $stripeCustomer */
      $stripeCustomer = $this->getStripeProcessor()->fetchCustomer($customer->getStripeId());
    } catch (\Exception $e) {
      // Try to fallback to default state then.
      $this->createLocalCustomer($customer);
      $this->createStripeCustomer($token, $customer);
      $this->updateLocalCustomer($customer);
      return;
    }

    try {

      /** @noinspection PhpUndefinedFieldInspection */
      $stripeCustomer->sources->create(array('source' => $token));
      /** @var Card $default_source */
      /** @noinspection PhpUndefinedFieldInspection */
      $default_source = $stripeCustomer->sources->retrieve($stripeCustomer->default_source);
      $default_source->delete();
    } catch (\Exception $e) {
      throw new ProcessingException('update_card_error', $e->getMessage(), 0, $e);
    }
  }

  private function validateCustomerProduct(CustomerData $customer)
  {
    $product = strtoupper($customer->getProduct());
    $promo = strtoupper($customer->getPromo());
    if (!$this->getChargeAmount($product)) {
      throw new ProcessingException('invalid_product', "Customer product has no associated cost: product=" . json_encode($product));
    }

    if ($promo !== $this->getActivePromoCode()) {
      throw new ProcessingException('invalid_promo', "Invalid promotion code provided: promo=" . json_encode($product));
    }
  }

  private function getChargeAmount($product) {
    $chargeMatrix = new CostMatrix();
    $chargeAmount = $chargeMatrix->getCost($product);
    return $chargeAmount;
  }

  private function populateTransaction(CustomerData $customer, Transaction $transaction)
  {
    $product = strtoupper($customer->getProduct());
    $promo = strtoupper($customer->getPromo());
    $amount = $this->getChargeAmount($product, $promo);
    $chargeMessage = "pHin pre-order {$product}_{$promo}";
    $transaction->setChargeAmount($amount);
    $transaction->setChargeCurrency('USD');
    $transaction->setEmail($customer->getEmail());
    $transaction->setChargeDescription($chargeMessage);
    $transaction->setStripeCustomerId($customer->getStripeId());
    $transaction->setTimestamp(new \DateTime());
    $transaction->setData(array('customer' => $customer));
  }


  private function requiresNewLocalCustomer(CustomerData $customer)
  {
    try {
      $hasFreeCustomer = $this->getUnchargedLocalCustomerObject($customer);
      return !$hasFreeCustomer;
    } catch (\Exception $e) {
      throw new ProcessingException('internal_error', $e->getMessage(), 0, $e);
    }
  }


  private function createLocalCustomer(CustomerData $customer)
  {
    try {
      $this->getCustomerRepository()->create($customer);
    } catch (\Exception $e) {
      throw new ProcessingException('create_cust_fail', $e->getMessage(), 0, $e);
    }
  }


  private function createStripeCustomer($token, CustomerData $customer)
  {
    try {
      $this->getStripeProcessor()->createCustomer($token, $customer);
    } catch (\Stripe\Error\Card $e) {
      throw new ProcessingException('stripe_card_error', $e->getMessage(), 0, $e);
    } catch (\Exception $e) {
      throw new ProcessingException('stripe_cust_fail', $e->getMessage(), 0, $e);
    }
  }


  private function updateLocalCustomer(CustomerData $customer)
  {
    try {
      $this->getCustomerRepository()->update($customer);
    } catch (\Exception $e) {
      throw new ProcessingException('update_cust_fail', $e->getMessage(), 0, $e);
    }
  }


  private function requireCustomerStripeId(CustomerData $customer)
  {
    if (!$customer->getStripeId()) {
      throw new ProcessingException('no_stripe_cust', 'Customer has no Stripe ID.');
    }

  }


  private function createLocalTransaction(Transaction $transaction)
  {
    try {
      $this->getTransactionRepository()->create($transaction);
    } catch (\Exception $e) {
      throw new ProcessingException('create_txn_fail', $e->getMessage(), 0, $e);
    }
  }


  private function chargeStripeCustomer(CustomerData $customerData, Transaction $transaction)
  {
    try {

    } catch (\Stripe\Error\Card $e) {
      throw new ProcessingException('stripe_card_error', $e->getMessage(), 0, $e);
    } catch (\Exception $e) {
      throw new ProcessingException('stripe_charge_fail', $e->getMessage(), 0, $e);
    }
  }


  private function updateLocalTransaction(Transaction $transaction)
  {
    try {
      $this->getTransactionRepository()->update($transaction);
    } catch (\Exception $e) {
      throw new ProcessingException('update_txn_fail', $e->getMessage(), 0, $e);
    }
  }


  private function getActivePromoCode()
  {
    return $this->activePromoCode;
  }


  /**
   * @param $customer
   * @return CustomerData
   */
  private function getUnchargedLocalCustomerObject($customer)
  {
    $existingCustomers = $this->getCustomerRepository()->fetchByEmail($customer->getEmail());
    $transactions = $this->getTransactionRepository()->fetchByEmail($customer->getEmail());
    $freeCustomer = null;
    // Filter out any customers tied to a successful transaction
    foreach ($transactions as $transaction) {
      if ($transaction->isSuccess()) {
        unset($existingCustomers[$transaction->getCustomerId()]);
      }
    }
    if (empty($existingCustomers)) {
      return null;
    }
    // Return the first customer value.
    $existingCustomers = array_values($existingCustomers);
    return $existingCustomers[0];
  }


  /**
   * @return CustomerRepository
   */
  public function getCustomerRepository()
  {
    return $this->customerRepository;
  }


  /**
   * @param CustomerRepository $customerRepository
   */
  public function setCustomerRepository($customerRepository)
  {
    $this->customerRepository = $customerRepository;
  }


  /**
   * @return StripeProcessor
   */
  public function getStripeProcessor()
  {
    return $this->stripeProcessor;
  }


  /**
   * @param StripeProcessor $stripeProcessor
   */
  public function setStripeProcessor($stripeProcessor)
  {
    $this->stripeProcessor = $stripeProcessor;
  }


  /**
   * @return TransactionRepository
   */
  public function getTransactionRepository()
  {
    return $this->transactionRepository;
  }


  /**
   * @param TransactionRepository $transactionRepository
   */
  public function setTransactionRepository($transactionRepository)
  {
    $this->transactionRepository = $transactionRepository;
  }


  private function setActivePromoCode($activePromoCode)
  {
    $this->activePromoCode = $activePromoCode;
  }


}
