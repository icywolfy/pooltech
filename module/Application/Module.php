<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2015 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Application;

use Application\Service\ThemeManager;
use Zend\Mvc\ModuleRouteListener;
use Zend\Mvc\MvcEvent;

class Module {
  public function onBootstrap(MvcEvent $e) {
    $eventManager = $e->getApplication()->getEventManager();
    $moduleRouteListener = new ModuleRouteListener();
    $moduleRouteListener->attach($eventManager);

    // Theme Handling
    $eventManager->attach(MvcEvent::EVENT_RENDER, array($this, 'prepareTheme'), 100);
  }


  public function getConfig() {
    return include __DIR__ . '/config/module.config.php';
  }


  public function getAutoloaderConfig() {
    return array(
      'Zend\Loader\StandardAutoloader' => array(
        'namespaces' => array(
          __NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__,
        ),
      ),
    );
  }


  public function prepareTheme(MvcEvent $event) {
    $services = $event->getApplication()->getServiceManager();
    /** @var ThemeManager $themes */
    $themeManager = $services->get('theme');
    $themeManager->registerActiveTheme($services);
  }
}
