<?php
namespace Application;

use Application\Entity\Transaction;
use Stripe\Charge;
use Stripe\Customer;

class PaymentProcessor {

  private function createCustomerFromToken($cardToken, $email) {
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
