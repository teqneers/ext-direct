<?php
/**
 * Created by PhpStorm.
 * User: stefan
 * Date: 27.07.15
 * Time: 10:17
 */

namespace TQ\ExtDirect\Tests\Router;

use Symfony\Component\Validator\Constraints\NotNull;
use Symfony\Component\Validator\ConstraintViolationList;
use TQ\ExtDirect\Router\ArgumentValidator;

/**
 * Class ArgumentValidatorTest
 *
 * @package TQ\ExtDirect\Tests\Router
 */
class ArgumentValidatorTest extends \PHPUnit_Framework_TestCase
{
    public function testInternalParameterIsNotValidated()
    {
        /** @var \Symfony\Component\Validator\Validator\ValidatorInterface|\PHPUnit_Framework_MockObject_MockObject $validator */
        $validator = $this->getMock(
            'Symfony\Component\Validator\Validator\ValidatorInterface',
            array(
                'validate',
                'validateProperty',
                'validatePropertyValue',
                'startContext',
                'inContext',
                'getMetadataFor',
                'hasMetadataFor'
            )
        );
        $validator->expects($this->never())
                  ->method('validate');

        /** @var \TQ\ExtDirect\Router\ServiceReference|\PHPUnit_Framework_MockObject_MockObject $service */
        $service = $this->getMock(
            'TQ\ExtDirect\Router\ServiceReference',
            array('getParameterConstraints'),
            array(),
            '',
            false
        );
        $service->expects($this->never())
                ->method('getParameterConstraints');

        $argValidator = new ArgumentValidator($validator, true);

        $argValidator->validate($service, array(
            '__internal__a' => 1
        ));
    }

    public function testParameterWithoutConstraintsIsNotValidated()
    {
        /** @var \Symfony\Component\Validator\Validator\ValidatorInterface|\PHPUnit_Framework_MockObject_MockObject $validator */
        $validator = $this->getMock(
            'Symfony\Component\Validator\Validator\ValidatorInterface',
            array(
                'validate',
                'validateProperty',
                'validatePropertyValue',
                'startContext',
                'inContext',
                'getMetadataFor',
                'hasMetadataFor'
            )
        );
        $validator->expects($this->never())
                  ->method('validate');

        /** @var \TQ\ExtDirect\Router\ServiceReference|\PHPUnit_Framework_MockObject_MockObject $service */
        $service = $this->getMock(
            'TQ\ExtDirect\Router\ServiceReference',
            array('getParameterConstraints'),
            array(),
            '',
            false
        );
        $service->expects($this->once())
                ->method('getParameterConstraints')
                ->with($this->equalTo('a'))
                ->willReturn(array());

        $argValidator = new ArgumentValidator($validator, false);

        $argValidator->validate($service, array(
            'a' => 1
        ));
    }

    public function testParameterWithoutConstraintsIsNotValidatedAndFailsInStrictMode()
    {
        /** @var \Symfony\Component\Validator\Validator\ValidatorInterface|\PHPUnit_Framework_MockObject_MockObject $validator */
        $validator = $this->getMock(
            'Symfony\Component\Validator\Validator\ValidatorInterface',
            array(
                'validate',
                'validateProperty',
                'validatePropertyValue',
                'startContext',
                'inContext',
                'getMetadataFor',
                'hasMetadataFor'
            )
        );
        $validator->expects($this->never())
                  ->method('validate');

        /** @var \TQ\ExtDirect\Router\ServiceReference|\PHPUnit_Framework_MockObject_MockObject $service */
        $service = $this->getMock(
            'TQ\ExtDirect\Router\ServiceReference',
            array('getParameterConstraints'),
            array(),
            '',
            false
        );
        $service->expects($this->once())
                ->method('getParameterConstraints')
                ->with($this->equalTo('a'))
                ->willReturn(array());

        $this->setExpectedException(
            'TQ\ExtDirect\Router\Exception\StrictArgumentValidationException',
            'Strict argument validation failed: not all parameters could be validated'
        );

        $argValidator = new ArgumentValidator($validator, true);

        $argValidator->validate($service, array(
            'a' => 1
        ));
    }

    public function testParameterConstraintsAreValidated()
    {
        /** @var \Symfony\Component\Validator\Validator\ValidatorInterface|\PHPUnit_Framework_MockObject_MockObject $validator */
        $validator = $this->getMock(
            'Symfony\Component\Validator\Validator\ValidatorInterface',
            array(
                'validate',
                'validateProperty',
                'validatePropertyValue',
                'startContext',
                'inContext',
                'getMetadataFor',
                'hasMetadataFor'
            )
        );

        $constraints = array(
            new NotNull()
        );

        $validator->expects($this->once())
                  ->method('validate')
                  ->with($this->equalTo(1), $this->equalTo($constraints))
                  ->willReturn(new ConstraintViolationList());

        /** @var \TQ\ExtDirect\Router\ServiceReference|\PHPUnit_Framework_MockObject_MockObject $service */
        $service = $this->getMock(
            'TQ\ExtDirect\Router\ServiceReference',
            array('getParameterConstraints'),
            array(),
            '',
            false
        );
        $service->expects($this->once())
                ->method('getParameterConstraints')
                ->with($this->equalTo('a'))
                ->willReturn($constraints);

        $argValidator = new ArgumentValidator($validator, false);

        $argValidator->validate($service, array(
            'a' => 1
        ));
    }
}
