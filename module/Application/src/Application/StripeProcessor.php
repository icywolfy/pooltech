<?php
namespace Application;

use Application\Entity\CustomerData;
use Application\Entity\Transaction;
use Stripe\Charge;
use Stripe\Customer;

class StripeProcessor {
  public function fetchCustomer($customerId) {
    try {
      $customer = Customer::retrieve($customerId);
    } catch(\Exception $e) {
      return null;
    }

    return $customer;
  }
  private function createCustomerFromToken($cardToken, CustomerData $customer) {
    $customer = Customer::create(array(
      'source' => $cardToken,
      'description' => '',
      'email' => $email,
    ));
    return $customer;
  }
  public function chargeCard($token, $email, Transaction $transaction) {
    $customer = $this->createCustomerFromToken($token, $email);
    $this->executeTransaction($customer, $transaction);

  }
  private function executeTransation(Customer $customer, Transaction $transaction) {
    $charge = Charge::create(array(
      'amount' => $transaction->getChargeAmount(),
      'currency' => $transaction->getChargeCurrency(),
      'customer' => $customer->id,
      'description' => $transaction->getDescription(),
    ));

  }
}
