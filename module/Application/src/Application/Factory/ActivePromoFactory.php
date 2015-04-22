<?php
namespace Application\Factory;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class ActivePromoFactory implements  FactoryInterface {
  /**
   * Create service
   *
   * @param ServiceLocatorInterface $serviceLocator
   * @throws \RuntimeException
   * @return mixed
   */
  public function createService(ServiceLocatorInterface $serviceLocator)
  {
    $config = $serviceLocator->get('Config');
    $activeService = isset($config['active_promo']) ? $config['active_promo'] : null;
    if ($activeService === null) {
      throw new \RuntimeException('Active Promotion not defined.');
    }
    return $activeService;
  }

}
