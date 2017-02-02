<?php

/*
 * This file is part of the MopaBootstrapBundle.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Mopa\Bundle\BootstrapBundle\Twig;

/**
 * Reads initializr configuration file and generates corresponding Twig Globals
 *
 * @author Paweł Madej (nysander) <pawel.madej@profarmaceuta.pl>
 */
class MopaBootstrapInitializrTwigExtension extends \Twig_Extension implements \Twig_Extension_GlobalsInterface
{
    /**
     * @var array
     */
    protected $parameters;

    /**
     * @var array
     */
    protected $custom_config;

    /**
     *
     * @param array
     */
    public function __construct(array $parameters)
    {
        $this->parameters = $parameters;
        $this->custom_config = [
            'dns_prefetch' => [],
            'preconnect' => [],
            'prefetch' => [],
        ];
    }

    /**
     * Returns array of Twig Global Variables
     *
     * @return array Twig Globals
     */
    public function getGlobals()
    {
        $meta = $this->parameters['meta'];
        $dns_prefetch = $this->parameters['dns_prefetch'];
        $preconnect = $this->parameters['preconnect'];
        $prefetch = $this->parameters['prefetch'];
        $google = $this->parameters['google'];

        // TODO: think about setting this default as kernel debug,
        // what about PROD env which does not need diagnostic mode and test
        $diagnostic_mode = $this->parameters['diagnostic_mode'];

        return array(
            'dns_prefetch'      => $dns_prefetch,
            'preconnect'        => $preconnect,
            'prefetch'          => $prefetch,
            'meta'              => $meta,
            'google'            => $google,
            'diagnostic_mode'   => $diagnostic_mode
        );
    }

    /**
     * Returns a list of functions to add to the existing list.
     *
     * @return array An array of functions
     */
    public function getFunctions()
    {
        return array(
            new \Twig_SimpleFunction('form_help', null, array(
                'node_class' => 'Symfony\Bridge\Twig\Node\SearchAndRenderBlockNode',
                'is_safe' => array('html'),
            )),
            new \Twig_SimpleFunction('add_dns_prefetch', array($this, 'addDnsPrefetchResource')),
            new \Twig_SimpleFunction('add_preconnect', array($this, 'addPreconnectResource')),
            new \Twig_SimpleFunction('add_prefetch', array($this, 'addPrefetchResource')),
            new \Twig_SimpleFunction('get_dns_prefetch', array($this, 'getDnsPrefetchResources')),
            new \Twig_SimpleFunction('get_preconnect', array($this, 'getPreconnectResources')),
            new \Twig_SimpleFunction('get_prefetch', array($this, 'getPrefetchResources')),
        );
    }

    /**
     * @param string $url
     */
    public function addDnsPrefetchResource($url)
    {
        $this->custom_config['dns_prefetch'][] = $url;
    }

    /**
     * @param string $url
     */
    public function addPreconnectResource($url)
    {
        $this->custom_config['preconnect'][] = $url;
    }

    /**
     * @param string $url
     */
    public function addPrefetchResource($url)
    {
        $this->custom_config['prefetch'][] = $url;
    }

    /**
     * @return array
     */
    public function getDnsPrefetchResources()
    {
        return array_merge($this->parameters['dns_prefetch'], $this->custom_config['dns_prefetch']);
    }

    /**
     * @return array
     */
    public function getPreconnectResources()
    {
        return array_merge($this->parameters['preconnect'], $this->custom_config['preconnect']);
    }

    /**
     * @return array
     */
    public function getPrefetchResources()
    {
        return array_merge($this->parameters['prefetch'], $this->custom_config['prefetch']);
    }

    /**
     * Returns the name of the extension.
     *
     * @return string The extension name
     */
    public function getName()
    {
        return 'initializr';
    }
}
