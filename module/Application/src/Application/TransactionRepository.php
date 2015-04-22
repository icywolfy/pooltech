<?php
namespace Application;

use Application\Entity\Transaction;
use Zend\Db\Sql\Where;

class TransactionRepository extends AbstractDbClient
{

  const DB_SCHEMA = 'transaction_data';


  public function create(Transaction $transaction)
  {
    $sql = $this->getSql();
    $insert = $sql->insert(self::DB_SCHEMA);
    $params = $this->extract($transaction);
    $insert->values($params);
    $result = $this->dispatchDataStatement($insert);
    $transaction->setId($this->getGeneratedId($result));
    return $transaction;
  }


  protected function extract(Transaction $transaction)
  {
    return array(
      'tid' => $transaction->getId(),
      'cid' => $transaction->getCustomerId(),
      'ts' => $transaction->getTimestamp()->format('Ymd\THisO'),
      'email' => $transaction->getEmail(),
      'stripe_id' => $transaction->getStripeCustomerId(),
      'stripe_txn' => $transaction->getStripeTransactionId(),
      'amount' => $transaction->getChargeAmount(),
      'currency' => $transaction->getChargeCurrency(),
      'status' => $transaction->getStatus(),
      'detail' => json_encode($transaction->getData()),
    );
  }


  public function update(Transaction $transaction)
  {
    $sql = $this->getSql();
    $update = $sql->update(self::DB_SCHEMA);
    $params = $this->extract($transaction);
    unset($params['tid']);
    $update->set($params);

    $where = new Where();
    $where->equalTo('tid', $transaction->getId());
    $update->where($where);
    $this->dispatchDataStatement($update);
  }


  /**
   * @param $email
   * @return Transaction[]
   */
  public function fetchByEmail($email) {

  }
  /**
   * @TODO Create if needed
   * @param array $data
   * @return Transaction[]
   */
  protected function hydrate(array $data)
  {
    return parent::hydrate($data);
  }
}
