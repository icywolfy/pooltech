<?php
namespace Application;

use Zend\Db\Adapter\Adapter;
use Zend\Db\Adapter\Driver\ResultInterface;
use Zend\Db\ResultSet\ResultSetInterface;
use Zend\Db\Sql;

class AbstractDbClient
{
  /**
   * @var Adapter
   */
  private $dbAdapter;

  /** @var Sql\Sql */
  private $abstractSql;


  protected function getGeneratedId(ResultInterface $result)
  {
    return $result->getGeneratedValue();
  }


  protected function dispatchDataStatement($statement)
  {
    $sql = $this->getSql();
    $adapter = $this->getDbAdapter();
    $result = $adapter->query($sql->buildSqlString($statement), $adapter::QUERY_MODE_EXECUTE);
    return $result;
  }


  protected function getSql()
  {
    if (null === $this->abstractSql) {
      $this->abstractSql = new Sql\Sql($this->getDbAdapter());
    }

    return $this->abstractSql;
  }


  /**
   * @return Adapter
   */
  public function getDbAdapter()
  {
    return $this->dbAdapter;
  }


  /**
   * @param Adapter $storage
   */
  public function setDbAdapter($storage)
  {
    $this->dbAdapter = $storage;
  }

  protected function hydrate(array $data) {
    return $data;
  }

  protected function getObjectQuery($select) {
    $statementResult = $this->dispatchDataStatement($select);
    $results = array();

    foreach ($statementResult as $resultSet) {
      $results[] = $this->hydrate($resultSet);
    }
    return $results;
  }
}
