<?php

namespace Corrupt\CommonBundle\Twig\Extension;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Request;

class RouteExtension extends \Twig_Extension
{
    private $container;

    public function __construct(ContainerInterface $serviceContainer)
    {
        $this->container = $serviceContainer;
    }

    /**
     * Get current request params
     *
     * @return mixed
     */
    public function getRequestQueryParams()
    {
        /* @var Request $request */
        $request = $this->container->get('request');

        return array_merge($request->attributes->get('_route_params'), $request->query->all());
    }

    /**
     * Check contains data in request query parameters
     *
     * @param $key
     * @param null $value
     * @return bool
     */
    public function isRequestQueryContains($key, $value = null)
    {
        /* @var Request $request */
        $request = $this->container->get('request');

        if (null === $value) {
            return $request->query->has($key);
        }

        return $request->query->has($key) && ($request->query->get($key) === $value);
    }

    public function getFunctions()
    {
        return array(
            'requestQueryContains' => new \Twig_Function_Method($this, 'isRequestQueryContains'),
        );
    }

    public function getGlobals()
    {
        return array(
            'requestQueryParams' => $this->getRequestQueryParams(),
        );
    }

    /**
     * Returns the name of the extension.
     *
     * @return string The extension name
     */
    public function getName()
    {
        return 'corrupt_route_extension';
    }
}
