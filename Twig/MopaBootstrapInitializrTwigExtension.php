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
        $google = $this->parameters['google'];

        // TODO: think about setting this default as kernel debug,
        // what about PROD env which does not need diagnostic mode and test
        $diagnostic_mode = $this->parameters['diagnostic_mode'];

        return array(
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
            new \Twig_SimpleFunction('add_dns_prefetch_hosts', array($this, 'addDnsPrefetchHosts')),
            new \Twig_SimpleFunction('add_preconnect_hosts', array($this, 'addPreconnectHosts')),
            new \Twig_SimpleFunction('add_prefetch_hosts', array($this, 'addPrefetchHosts')),
            new \Twig_SimpleFunction('get_dns_prefetch_hosts', array($this, 'getDnsPrefetchHosts')),
            new \Twig_SimpleFunction('get_preconnect_hosts', array($this, 'getPreconnectHosts')),
            new \Twig_SimpleFunction('get_prefetch_hosts', array($this, 'getPrefetchHosts')),
        );
    }

    /**
     * @param array $urls
     */
    public function addDnsPrefetchHosts(array $urls)
    {
        $this->custom_config['dns_prefetch'] = array_merge(
            $this->custom_config['dns_prefetch'],
            $urls
        );
    }

    /**
     * @param array $urls
     */
    public function addPreconnectHosts(array $urls)
    {
        $this->custom_config['preconnect'] = array_merge(
            $this->custom_config['preconnect'],
            $urls
        );
    }

    /**
     * @param array $urls
     */
    public function addPrefetchHosts(array $urls)
    {
        $this->custom_config['prefetch'] = array_merge(
            $this->custom_config['prefetch'],
            $urls
        );
    }

    /**
     * @return array
     */
    public function getDnsPrefetchHosts()
    {
        return array_unique(array_merge($this->parameters['dns_prefetch'], $this->custom_config['dns_prefetch']));
    }

    /**
     * @return array
     */
    public function getPreconnectHosts()
    {
        return array_unique(array_merge($this->parameters['preconnect'], $this->custom_config['preconnect']));
    }

    /**
     * @return array
     */
    public function getPrefetchHosts()
    {
        return array_unique(array_merge($this->parameters['prefetch'], $this->custom_config['prefetch']));
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
