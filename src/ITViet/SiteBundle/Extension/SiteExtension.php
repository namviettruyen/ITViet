<?php

namespace ITViet\SiteBundle\Extension;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Routing\Exception\RouteNotFoundException;

class SiteExtension extends \Twig_Extension {
    private $container;
    private $tagSet;

    public function __construct(ContainerInterface $container) {
        $this->container = $container;
    }

    public function getFunctions() {
        return array(
          'getPathsForLocales' => new \Twig_Function_Method($this, 'getPathsForLocales'),
          'mlpath' => new \Twig_Function_Method($this, 'mlpath'),
          'mlurl' => new \Twig_Function_Method($this, 'mlurl'),
        );
    }

    public function getFilters() {
        return array(
          'truncate' => new \Twig_Filter_Method($this, 'truncate'),
          'nbsp2sp' => new \Twig_Filter_Method($this, 'nbsp2sp'),
        );
    }

    public function getName() {
        return 'site_extension';
    }

    public function getPathsForLocales($params) {
        if(preg_match('/^_[a-z]{2}_/', $params['route']) > 0)
          $params['route'] = substr($params['route'], 3);

        $parameters = isset($params['args']) ? $params['args'] : array();
        unset(
          $parameters['_controller'],
          $parameters['_route'],
          $parameters['_template_default_vars']
        );

        $list = array(
          'vi' => array(
            'title' => 'Change language to Vietnamese',
            'label' => 'Vietnamese',
          ),
          'en' => array(
            'title' => 'Change language to English',
            'label' => 'English',
          ),
        );

        if (isset($params['except']) && $params['except']) {
            foreach($params['except'] as $lang)
                unset($list[$lang]);
        }

        foreach ($list as $key => &$item) {
            $item['locale'] = $key;
            $parameters = array_merge($parameters, array('_locale' => $key));

            if (isset($params['langAwareParams'])) {
                foreach ($params['langAwareParams'] as $paramName => &$langAwareParam)
                    if (isset($langAwareParam[$key])) $parameters[$paramName] = $langAwareParam[$key];
            }
            $item['path'] = $this->mlpath($params['route'], $parameters, false);
        }

        return $list;
    }

    public function mlpath($name, $parameters = array(), $absolute = false) {
        $router = $this->container->get('router');
        $session = $this->container->get('session');
        $locale = isset($parameters['_locale']) ? $parameters['_locale'] : $session->getLocale();
        if ($locale) {
            try {
                return $this->urlencodeNonAsciiChars($router->generate('_'.$locale.$name, $parameters, $absolute));
            } catch (RouteNotFoundException $e) {}
        }
        return $this->urlencodeNonAsciiChars($router->generate($name, $parameters, $absolute));
    }

    private function urlencodeNonAsciiChars($str) {
        return preg_replace_callback('/[^(\x20-\x7F)]*/', function($matches){
          return urlencode($matches[0]);
        }, $str);
    }
    public function truncate($str, $len, $abb) {
        if (mb_strlen($str, 'UTF-8') > $len)
            return mb_substr($str, 0, $len, 'UTF-8').$abb;
        else
            return $str;
    }
    public function nbsp2sp($value) {
        return str_replace("&nbsp;", " ", $value);
    }

    public function mlurl($name, $parameters = array()) {
        return $this->mlpath($name, $parameters, true);
    }

}
