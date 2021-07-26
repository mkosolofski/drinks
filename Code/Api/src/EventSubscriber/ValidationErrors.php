<?php
/**
 * Contains App\EventSubscriber\ValidationErrors
 *
 * @package App
 * @subpackage EventSubscriber
 * @author Matthew Kosolofski <matthew.kosolofski@gmail.com>
 */

declare(strict_types=1);

namespace App\EventSubscriber;

use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\HttpKernel\Event\ControllerEvent;
use Symfony\Component\Validator\ConstraintViolationList;
use Symfony\Component\HttpFoundation\Response;
use FOS\RestBundle\View\ViewHandlerInterface;
use FOS\RestBundle\View\View;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;

/**
 * @package App
 * @subpackage EventSubscriber
 * @author Matthew Kosolofski <matthew.kosolofski@gmail.com>
 */
class ValidationErrors implements EventSubscriberInterface
{
    /**
     * @var ViewHandlerInterface
     */
    protected $viewHandler;

    /**
     * @param ViewHandlerInterface $viewHandler
     */
    public function __construct(ViewHandlerInterface $viewHandler)
    {
        $this->viewHandler = $viewHandler;
    }
    
    /**
     * {@inheritdoc}
     */
    public static function getSubscribedEvents()
    {
        return [
            // Must be executed after FOSREST's Body listener
            KernelEvents::CONTROLLER => ['handleValidationErrors', -100],
            
            // Symfony subscirber priorities range from -255 to 255.
            KernelEvents::EXCEPTION => array('onValidationErrorsException', 550)
        ];
    }

    /**
     * Handles validation errors exception thrown by the api
     *
     * @param ExceptionEvent $event
     *
     * @return void
     */
    public function onValidationErrorsException(ExceptionEvent $event)
    {
        $response = $event->getResponse();
        
        $exception = $event->getThrowable();

        if (!($this->viewHandler instanceof ViewHandlerInterface)) {
            return;
        }
        
        $validationErrors = $exception;
        if (!($validationErrors instanceof ConstraintViolationList)) return;

        $errors = $this->constraintViolationListToResponseData($validationErrors);
        if (empty($errors)) return;

        $response = $this->viewHandler->handle($errors);

        // Change status code to 400 Bad Request. 
        $response->headers->set('X-Status-Code', Response::HTTP_BAD_REQUEST);
        $response->setStatusCode(Response::HTTP_BAD_REQUEST);

        $event->setResponse($response);
    }

    /**
     * If validation errors are present from FOSREST's body listener return a reponse.
     *
     * @param ControllerEvent $event
     */
    public function handleValidationErrors(ControllerEvent $event)
    {
        $request = $event->getRequest();
        if (!$request->attributes->has('validationErrors')) return;

        $validationErrors = $request->attributes->get('validationErrors');
        if (!($validationErrors instanceof ConstraintViolationList)) return;

        $errors = $this->constraintViolationListToResponseData($validationErrors);
        if (empty($errors)) return;

        $response = $this->viewHandler->handle(View::create($errors));
        
        // Change status code to 400 Bad Request. 
        $response->headers->set('X-Status-Code', Response::HTTP_BAD_REQUEST);
        $response->setStatusCode(Response::HTTP_BAD_REQUEST);

        $event->stopPropagation();

        $event->setController(
            function() use ($response) {
                return $response;
            }
        );
    }

    /**
     * Returns the components of a given property name.
     *
     * @param string $propertyName
     *
     * @return array The property name components. Ex: [propertyName, subPropertyName, subPropertyIndex].
     */
    private function getPropertyNameComponents($propertyName): array
    {
        if (strpos($propertyName, '.') === false) {
            return [$propertyName, null, null];
        }

       // Parse collection property names.
       if (preg_match('/^(([^\[]*)\[(\d+)])\.([^\.]*)/', $propertyName, $matches) === false
            || count($matches) < 3
        ) {
            
            list($propertyName, $subPropertyName) = explode('.', $propertyName);
            return [$propertyName, $subPropertyName, null];
        }
        
        list($fullMatch, $groupOne, $propertyName, $subPropertyIndex, $subPropertyName) = $matches;

        // Prepending the sub property index maintains the output as an object when all indexes are present from
        // index 0.        
        $subPropertyIndex = "_${subPropertyIndex}";
        return [$propertyName, $subPropertyName, $subPropertyIndex];
    }

    /**
     * Explands the property based on its name.
     *
     * @param string $propertyName
     * @param mixed $propertyValue
     * @param bool $isCollection If the given propertyName is a collection.
     *
     * @return array The expanded property.
     */
    private function expandProperty(string $propertyName, $propertyValue, &$isCollection = false): array
    {
        // If we are at the root node.
        if (strpos($propertyName, '.') === false) {
            return [$propertyName => $propertyValue];
        }

        list($propertyName, $subPropertyName, $subPropertyIndex) = $this->getPropertyNameComponents($propertyName);

        $isCollection = !is_null($subPropertyIndex) ? true : false;

        $result = [$propertyName => []];

        do {
            $convertedProperty = $this->expandProperty($subPropertyName, $propertyValue, $isSubPropertyCollection);

            if (!$isCollection) {
                $result = [
                    $propertyName => array_replace_recursive(
                        $result[$propertyName],
                        $convertedProperty
                    )
                ];
                continue;
            }
            
            if (!isset($result[$propertyName][$subPropertyIndex])) {
                $result[$propertyName][$subPropertyIndex] = [];
            }

            $result[$propertyName][$subPropertyIndex] = array_replace_recursive(
                $result[$propertyName][$subPropertyIndex],
                $convertedProperty
            );

        } while($isSubPropertyCollection);

        return $result;
    }

    /**
     * Formats a constraint violation list to response data.
     *
     * @param ConstraintViolationList $constraintViolationList
     *
     * @return array
     */
    private function constraintViolationListToResponseData(ConstraintViolationList $constraintViolationList): array
    {
        $errors = [];
        foreach($constraintViolationList as $constraintViolation) {

            // Camel case to snake case.
            $propertyName = strtolower(preg_replace('/(?<!^)[A-Z]/', '_$0', $constraintViolation->getPropertyPath()));

            $errors = array_replace_recursive(
                $errors,
                $this->expandProperty($propertyName, $constraintViolation->getMessage())
            );
        }   

        return $errors;
    }
}
