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
use Zend\Http\PhpEnvironment\Request;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\JsonModel;
use Zend\View\Model\ViewModel;

class IndexController extends AbstractActionController {
  public function indexAction() {
    return new ViewModel();
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
