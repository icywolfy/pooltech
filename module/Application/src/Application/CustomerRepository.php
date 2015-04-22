<?php
namespace Application;

use Application\Entity\CustomerData;
use Zend\Db\Sql\Where;

class CustomerRepository extends AbstractDbClient
{
  const DB_SCHEMA = 'customer_data';


  /**
   * @param $email
   * @return CustomerData[]
   * @throws \Zend\Db\Sql\Exception\InvalidArgumentException
   */
  public function fetchByEmail($email)
  {
    $whereClause = new Where();
    $whereClause->equalTo('email', $email);
    $select = $this->getSql()->select(self::DB_SCHEMA);
    $select->where($whereClause);
    $result = $this->getObjectQuery($select);
    return $result;
  }


  public function create(CustomerData $customerData)
  {
    $sql = $this->getSql();
    $insert = $sql->insert(self::DB_SCHEMA);
    $params = $this->extract($customerData);
    $insert->values($params);
    $result = $this->dispatchDataStatement($insert);
    $customerData->setId($this->getGeneratedId($result));
    return $customerData;
  }


  public function update(CustomerData $customerData)
  {
    $sql = $this->getSql();
    $update = $sql->update(self::DB_SCHEMA);
    $params = $this->extract($customerData);
    unset($params['cid']);
    $update->set($params);

    $where = new Where();
    $where->equalTo('cid', $customerData->getId());
    $update->where($where);
    $this->dispatchDataStatement($update);
  }


  private function extract(CustomerData $customerData)
  {
    return array(
      'cid' => $customerData->getId(),
      'email' => $customerData->getEmail(),
      'name' => $customerData->getFullName(),
      'address_1' => $customerData->getAddress1(),
      'address_2' => $customerData->getAddress2(),
      'city' => $customerData->getCity(),
      'state' => $customerData->getState(),
      'zip' => $customerData->getZip(),
      'phone' => $customerData->getPhone(),
      'promo' => $customerData->getPromo(),
      'product' => $customerData->getProduct(),
      'stripe_id' => $customerData->getStripeId(),
    );
  }


  /**
   * @param array $data
   * @return CustomerData
   */
  protected function hydrate(array $data)
  {
    $customerData = new CustomerData;
    $customerData->setId($data['cid']);
    $customerData->setEmail($data['email']);
    $customerData->setFullName($data['name']);
    $customerData->setAddress1($data['address_1']);
    $customerData->setAddress2($data['address_2']);
    $customerData->setCity($data['city']);
    $customerData->setState($data['state']);
    $customerData->setZip($data['zip']);
    $customerData->setPhone($data['phone']);
    $customerData->setPromo($data['promo']);
    $customerData->setProduct($data['product']);
    $customerData->setStripeId($data['stripe_id']);
    return $customerData;
  }

}
