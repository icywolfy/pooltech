<?php
namespace Application\Service;

use Zend\Mvc\Service\ViewTemplateMapResolverFactory;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class ThemeManager implements ServiceLocatorAwareInterface {
  /**
   * @var ServiceLocatorInterface
   */
  private $serviceLocator;

  /**
   * @var string
   */
  private $activeTheme;

  /**
   * @var array
   */
  private $config;


  public function getActiveTheme() {
    if (null === $this->activeTheme) {
      $this->discoverTheme();
    }

    return $this->activeTheme;
  }


  public function setActiveTheme($activeTheme) {
    $this->activeTheme = $activeTheme;
  }


  private function discoverTheme() {
    $this->setActiveTheme('default');
  }


  private function getConfig() {
    if (null === $this->config) {
      $config = $this->serviceLocator->get('config');
      $this->config = $config['themes'];
    }
    return $this->config;
  }


  private function getActiveThemeConfig() {
    $config = $this->getConfig();
    $activeTheme = $this->getActiveTheme();
    $themeConfig = $config[$activeTheme];
    return $themeConfig;
  }

  public function registerActiveTheme(ServiceLocatorInterface $services) {
    $config = $this->getActiveThemeConfig();
    if (isset($config['template_map'])) {
      $map = $services->get('ViewTemplateMapResolver');
      $map->merge($config['template_map']);
    }

    if (isset($config['template_path_stack'])) {
      $stack = $services->get('ViewTemplatePathStack');
      $stack->addPaths($config['template_path_stack']);
    }

  }


  public function setServiceLocator(ServiceLocatorInterface $serviceLocator) {
    $this->serviceLocator = $serviceLocator;
  }


  public function getServiceLocator() {
    return $this->serviceLocator;
  }
}
