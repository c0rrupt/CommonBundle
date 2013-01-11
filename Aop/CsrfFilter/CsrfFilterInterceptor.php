<?php

namespace Corrupt\CommonBundle\Aop\CsrfFilter;

use CG\Proxy\MethodInterceptorInterface;
use Symfony\Component\Form\Extension\Csrf\CsrfProvider\CsrfProviderInterface;
use CG\Proxy\MethodInvocation;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Doctrine\Common\Annotations\Reader;
use Corrupt\CommonBundle\Annotation\CsrfFilter;

class CsrfFilterInterceptor implements MethodInterceptorInterface
{
    private $annotationReader;
    private $csrfProvider;
    private $request;

    public function __construct(Reader $reader, CsrfProviderInterface $csrfProvider, Request $request)
    {
        $this->annotationReader = $reader;
        $this->csrfProvider = $csrfProvider;
        $this->request = $request;
    }

    public function intercept(MethodInvocation $invocation)
    {
        foreach ($this->annotationReader->getMethodAnnotations($invocation->reflection) as $annotationClass) {
            if ($annotationClass instanceof CsrfFilter) {
                $csrfTokenValue = $this->request->query->get($annotationClass->getParameterName());
                if (!$this->csrfProvider->isCsrfTokenValid('', $csrfTokenValue)) {
                    throw new AccessDeniedHttpException($annotationClass->getErrorMessage());
                }
            }
        }

        return $invocation->proceed();
    }
}