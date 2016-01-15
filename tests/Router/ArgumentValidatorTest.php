<?php
/**
 * Created by PhpStorm.
 * User: stefan
 * Date: 27.07.15
 * Time: 10:17
 */

namespace TQ\ExtDirect\Tests\Router;

use Symfony\Component\Validator\Constraints\NotNull;
use Symfony\Component\Validator\ConstraintViolation;
use Symfony\Component\Validator\ConstraintViolationList;
use TQ\ExtDirect\Router\ArgumentValidator;
use TQ\ExtDirect\Router\Exception\ArgumentValidationException;

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

    public function testValidatorFailsWhenValidationFails()
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
                  ->with($this->equalTo(null), $this->equalTo($constraints))
                  ->willReturn(
                      new ConstraintViolationList(
                          array(
                              new ConstraintViolation('not null', 'not null', array(), '', '', null)
                          )
                      )
                  );

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

        $this->setExpectedException(
            'TQ\ExtDirect\Router\Exception\ArgumentValidationException',
            'Argument validation failed: {"a":["not null []"]}'
        );

        $argValidator->validate($service, array(
            'a' => null
        ));
    }

    public function testParameterConstraintsWithValidationGroupsAreValidated()
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
        $groups      = array(
            'myGroup'
        );

        $validator->expects($this->once())
                  ->method('validate')
                  ->with($this->equalTo(1), $this->equalTo($constraints), $this->equalTo($groups))
                  ->willReturn(new ConstraintViolationList());

        /** @var \TQ\ExtDirect\Router\ServiceReference|\PHPUnit_Framework_MockObject_MockObject $service */
        $service = $this->getMock(
            'TQ\ExtDirect\Router\ServiceReference',
            array('getParameterConstraints', 'getParameterValidationGroups'),
            array(),
            '',
            false
        );
        $service->expects($this->once())
                ->method('getParameterConstraints')
                ->with($this->equalTo('a'))
                ->willReturn($constraints);
        $service->expects($this->once())
                ->method('getParameterValidationGroups')
                ->with($this->equalTo('a'))
                ->willReturn($groups);

        $argValidator = new ArgumentValidator($validator, false);

        $argValidator->validate($service, array(
            'a' => 1
        ));
    }

    public function testStrictParameterConstraintAreValidated()
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
                  ->with($this->equalTo(null), $this->equalTo($constraints), $this->equalTo(array()))
                  ->willReturn(
                      new ConstraintViolationList(
                          array(
                              new ConstraintViolation('not null', 'not null', array(), '', '', null)
                          )
                      )
                  );

        /** @var \TQ\ExtDirect\Router\ServiceReference|\PHPUnit_Framework_MockObject_MockObject $service */
        $service = $this->getMock(
            'TQ\ExtDirect\Router\ServiceReference',
            array('getParameterConstraints', 'getParameterValidationGroups', 'isStrictParameterValidation'),
            array(),
            '',
            false
        );
        $service->expects($this->once())
                ->method('getParameterConstraints')
                ->with($this->equalTo('a'))
                ->willReturn($constraints);
        $service->expects($this->once())
                ->method('getParameterValidationGroups')
                ->with($this->equalTo('a'))
                ->willReturn(array());
        $service->expects($this->once())
                ->method('isStrictParameterValidation')
                ->with($this->equalTo('a'))
                ->willReturn(true);

        $argValidator = new ArgumentValidator($validator, false);

        try {
            $argValidator->validate($service, array(
                'a' => null
            ));
            $this->fail('Expected TQ\ExtDirect\Router\Exception\ArgumentValidationException not called');
        } catch (ArgumentValidationException $e) {
            $this->assertEquals('Argument validation failed: {"a":["not null []"]}', $e->getMessage());
            $this->assertTrue($e->isStrictFailure());
        }
    }
}
