<?php

namespace Corrupt\CommonBundle\Annotation;

/**
 * @Annotation
 */
class CsrfFilter
{
    private $parameterName = '_token';
    private $errorMessage = 'Wrong csrf token';

    public function __construct($data)
    {
        if (isset($data['value'])) {
            $data['parameterName'] = $data['value'];
            unset($data['value']);
        }

        foreach ($data as $key => $value) {
            $method = 'set' . $key;
            if (!method_exists($this, $method)) {
                throw new \BadMethodCallException(sprintf("Unknown property '%s' on annotation '%s'.", $key,
                    get_class($this)));
            }
            $this->$method($value);
        }
    }

    public function getParameterName()
    {
        return $this->parameterName;
    }

    public function setParameterName($parameterName)
    {
        $this->parameterName = $parameterName;
    }

    public function getErrorMessage()
    {
        return $this->errorMessage;
    }

    public function setErrorMessage($message)
    {
        $this->errorMessage = $message;
    }
}