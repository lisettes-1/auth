<?php
declare (strict_types=1);
/**
 * @copyright
 * @version 1.0.0
 * @link
 */

namespace Lisettes\Auth\Listener;

use Hyperf\Event\Contract\ListenerInterface;
use Hyperf\Validation\Contract\ValidatorFactoryInterface;
use Hyperf\Validation\Event\ValidatorFactoryResolved;
use Hyperf\Validation\Validator;

/**
 * ValidatorPhoneOrEmailRuleListener
 *
 * @package Lisettes\Auth\Listener
 */
class ValidatorPhoneOrEmailRuleListener implements ListenerInterface
{
    public function listen(): array
    {
        return [
            ValidatorFactoryResolved::class,
        ];
    }

    public function process(object $event)
    {
        /**  @var ValidatorFactoryInterface $validatorFactory */
        $validatorFactory = $event->validatorFactory;
        $validatorFactory->extend('phoneOrEmail', function ($attribute, $value, $parameters, $validator) {
            /** @var Validator $validator */
            return $validator->validateRegex($attribute, $value, ['/^[0-9]*$/']) || $validator->validateEmail($attribute, $value);
        });
    }
}