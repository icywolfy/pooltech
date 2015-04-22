<?php
namespace Application;

use Zend\Db\Sql;

class Auditor extends AbstractDbClient {
  const DB_SCHEMA = 'auditlog';

  public function createEvent($name, $shortDesc, $detail=array()) {
    $sql = $this->getSql();
    $insert = $sql->insert(self::DB_SCHEMA);
    $params = array(
      'ts' => date('Ymd\THisO', time()),
      'module' => $name,
      'desc' => $shortDesc,
      'details' => json_encode($detail),
    );
    $insert->values($params);

    $result = $this->dispatchDataStatement($insert);
    return $result;
  }
}
