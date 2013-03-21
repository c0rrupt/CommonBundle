<?php

namespace Corrupt\CommonBundle\Controller;

use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\Validator\ConstraintViolationInterface;
use Symfony\Component\Validator\ConstraintViolationListInterface;

abstract class ApiController extends Controller
{
    /**
     * Convert form errors into array
     *
     * @param \Symfony\Component\Form\FormInterface $form
     * @return array
     */
    protected function getErrorMessages(FormInterface $form)
    {
        $errors = array();

        foreach ($form->getErrors() as $key => $error) {
            $template = $error->getMessageTemplate();
            $parameters = $error->getMessageParameters();

            foreach ($parameters as $var => $value) {
                $template = str_replace($var, $value, $template);
            }

            $errors[$key] = $template;
        }

        if (count($form->all()) > 0) {
            foreach ($form->all() as $child) {
                if (!$child->isValid()) {
                    $errors[$child->getName()] = $this->getErrorMessages($child);
                }
            }
        }

        return $errors;
    }

    /**
     * Create access denied exception
     *
     * @param string $message
     * @throws \Symfony\Component\HttpKernel\Exception\HttpException
     */
    protected function createAccessDenied($message = '')
    {
        throw new HttpException(403, $message);
    }

    /**
     * Convert constraint violations collection into array of errors
     *
     * @param ConstraintViolationListInterface $violations
     * @return array
     */
    protected function getConstraintViolationsMsgs(ConstraintViolationListInterface $violations)
    {
        if (count($violations) > 0) {
            $errors = array();

            /* @var ConstraintViolationInterface $violation */
            foreach ($violations as $violation) {
                $errors[] = sprintf('%s: %s', $violation->getPropertyPath(), $violation->getMessage());
            }

            return $errors;
        }

        return array();
    }
}
