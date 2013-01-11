<?php

namespace Corrupt\CommonBundle\Aop\CsrfFilter;

use JMS\AopBundle\Aop\PointcutInterface;
use Doctrine\Common\Annotations\Reader;

class CsrfFilterPointcut implements PointcutInterface
{
    private $reader;

    public function __construct(Reader $reader)
    {
        $this->reader = $reader;
    }

    public function matchesClass(\ReflectionClass $class)
    {
        return true;
    }

    public function matchesMethod(\ReflectionMethod $method)
    {
        return null !== $this->reader->getMethodAnnotation($method, 'Corrupt\CommonBundle\Annotation\CsrfFilter');
    }
}