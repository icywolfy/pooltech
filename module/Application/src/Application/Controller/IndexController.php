<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2015 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Application\Controller;

use Application\CustomerRepository;
use Application\Entity\CustomerData;
use Application\Entity\Transaction;
use Application\PaymentProcessor;
use Stripe\Error\InvalidRequest;
use Zend\Http\PhpEnvironment\Request;
use Zend\Json\Json;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\JsonModel;
use Zend\View\Model\ViewModel;

class IndexController extends AbstractActionController
{
  public function indexAction()
  {
    return new ViewModel();
  }


  public function chargeCustomerAction()
  {
    /** @var Request $request */
    $request = $this->getRequest();
    $cardToken = $request->getQuery('customer');
    \Stripe\Stripe::setApiKey('sk_test_4x3c30DKpLyURBmzsTq0hfCi');
    try {
      $customer = \Stripe\Charge::create(array(
        'amount' => 100 + rand(0, 100),
        'currency' => 'USD',
        'customer' => $cardToken,
        'description' => 'Charge from PoolTech',
        'capture' => true,
        'statement_descriptor' => 'pHin Preorder POOL',
        'receipt_email' => 'stripe-test@deep-freeze.ca',

      ));
    } catch (\Stripe\Error\Card $e) {
      echo '<pre>';
      var_export($e->getMessage());
      var_export($e->getHttpBody());
      var_export($e->getJsonBody());
      var_export($e->getHttpStatus());
      var_export($e->getCode());
    }
    echo '<pre>';
    var_export($customer);
    if (is_object($customer) && method_exists($customer, '__toArray')) {
      $result = $customer->__toArray();
    } else {
      $result = $customer;
    }
    return new JsonModel($result);
  }

  /** @var Request $request */
  public function retrieveCustomerAction() {
    $request = $this->getRequest();
    $customerId = $request->getQuery('customer');
    \Stripe\Stripe::setApiKey('sk_test_4x3c30DKpLyURBmzsTq0hfCi');
    try {
      $customer = \Stripe\Customer::retrieve($customerId);
    } catch (InvalidRequest $e) {
      var_export($e->getJsonBody());
      var_export($e->param);
    }
    echo '<pre>';
    var_export($customer);
    return new JsonModel($customer->__toArray());
  }

  public function createCustomerAction() {
    /** @var Request $request */
    $request = $this->getRequest();
    $cardToken = $request->getQuery('token');
    \Stripe\Stripe::setApiKey('sk_test_4x3c30DKpLyURBmzsTq0hfCi');
    $customer = \Stripe\Customer::create(array(
      'source' => $cardToken,
      'description' => '',
      'email' => 'test@example.company',
    ));
    echo '<pre>';
    var_export($customer);
    if (is_object($customer) && method_exists($customer, '__toArray')) {
      $result = $customer->__toArray();
    } else {
      $result = $customer;
    }
    return new JsonModel($result);
  }

  public function processCardAction() {
    /** @var Request $request */
    $request = $this->getRequest();
    $post = $request->getPost();
    $customerData = $this->createCustomerData($post->toArray());
    $paymentToken = $post->get('token');
    $promoCode = $post->getActivePromo();
    $product = $post->get('product');
    $chargeAmount = 100;
    /** @var CustomerRepository $customerRepo */
    $customerRepo = $this->getServiceLocator()->get('CustomerRepository');

    // Create the customer if required.
    try {
      $existingCustomer = $customerRepo->getByEmail($customerData->getEmail());
      if (!$existingCustomer) {
        $existingCustomer = $customerRepo->create($customerData);
      }
    } catch (\Exception $e) {
      $response = new JsonModel(array(
        'status' => 'error',
        'message' => 'There was an error creating the customer data.',
        'detail' => $e->getMessage(),
      ));
      return $response;
    }

    // Create the transaction
    /** @var PaymentProcessor $paymentProcessor */
    try {
      $paymentProcessor = $this->getServiceLocator()->get('PaymentProcessor');
      $transaction = $this->createTransaction($post);
      $paymentProcessor->chargeCard($paymentToken, $customerData->getEmail(), $transaction);
    } catch (\Exception $e) {
      $response = new JsonModel(array(
        'status' => 'error',
        'message' => 'There was an error charging your card.',
        'detail' => $e->getMessage(),
      ));
      return $response;
    }
    return new JsonModel(array(
      'status' => 'ok',
    ));
  }


  private function createTransaction($formData) {
    $transaction = new Transaction();
    $transaction->setTxnTime(time());
    $transaction->setPromoCode();
    $transaction->setDescription();
    $transaction->setProductCode();
    $transaction->setData();
    $transaction->setChargeResult();
    $transaction->setChargeCurrency('usd');
    $transaction->setChargeAmount();
    return $transaction;
  }

  private function createCustomerData($formData) {
    $customerData = new CustomerData();
    $customerData->setFullName($formData['fullName']);
    $customerData->setAddress1($formData['address1']);
    $customerData->setAddress2($formData['address2']);
    $customerData->setCity($formData['city']);
    $customerData->setState($formData['state']);
    $customerData->setZip($formData['zip']);
    $customerData->setPhone($formData['phone']);
    $customerData->setEmail($formData['email']);

    return $customerData;
  }
}
