# ext-direct
A base component to integrate Sencha Ext JS Ext.direct into a PHP application

[![Build Status](https://travis-ci.org/teqneers/ext-direct.svg?branch=master)](https://travis-ci.org/teqneers/ext-direct)
[![Code Coverage](https://scrutinizer-ci.com/g/teqneers/ext-direct/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/teqneers/ext-direct/?branch=master)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/teqneers/ext-direct/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/teqneers/ext-direct/?branch=master)
[![Dependency Status](https://www.versioneye.com/user/projects/55b4ba61643533001b00072d/badge.svg?style=flat)](https://www.versioneye.com/user/projects/55b4ba61643533001b00072d)

## Introduction

This library provides a server-side implementation for Sencha *Ext.direct* an RPC-style communication
component that is part of Sencha's *Ext JS* and *Sencha Touch*.

>Ext Direct is a platform and language agnostic remote procedure call (RPC) protocol. Ext Direct allows
>for seamless communication between the client side of an Ext JS application and any server platform that
>conforms to the specification. Ext Direct is stateless and lightweight, supporting features like API discovery,
>call batching, and server to client events.

Currently this library is only used as the foundation of [teqneers/ext-direct-bundle](https://github.com/teqneers/ext-direct-bundle),
a Symfony 2 bundle that integrates *Ext.direct* into a Symfony 2 based application. We have not tried to use the library
as a stand-alone component or in any other context than a Symfony 2 environment, so the following is only how it should
work theoretically without the bundle. We'd appreciate any help and contribution to make the library more useful outside
the bundle.

## Installation

You can install this library using composer

    composer require teqneers/ext-direct

or add the package to your composer.json file directly.

## Example

The service locator uses a metadata factory from the [`jms/metadata`](https://github.com/schmittjoh/metadata) library and
an associated annotation driver (which in turn uses a [`doctrine/annotations`](https://github.com/doctrine/annotations.git)
annotation reader) to read meta information about possible annotated service
classes from a given set of paths.

```php
$serviceLocator = new TQ\ExtDirect\Service\MetadataServiceLocator(
    new Metadata\MetadataFactory(
        new TQ\ExtDirect\Metadata\Driver\AnnotationDriver(
            new Doctrine\Common\Annotations\AnnotationReader(),
            [
                __DIR__.'/services'
            ]
        )
    )
);
```

The naming strategy determins how PHP class names and namespaces are translated
into Javascript-compatible *Ext.direct* action names. The default naming strategy
translates the `\` namspapce separator into a `.`. So `My\Namespace\Service` is
translated into `My.namespace.Service`. Please note that the transformation
must be reversible (`My.namespace.Service` => `My\Namespace\Service`).

```php
$namingStrategy = new TQ\ExtDirect\Service\DefaultNamingStrategy();
```

The event dispatcher is optional but is required to use features like
argument conversion and validation, result conversion of the profiling listener.

```php
$eventDispatcher = new Symfony\Component\EventDispatcher\EventDispatcher();
```

The router is used to translate incoming *Ext.direct* requests into PHP method calls
to the correct service class. The `ContainerServiceFactory` supports retrieving
services from a Symfony dependency injection container or instantiating simple
services which take no constructor arguments at all. Static service calls bypass the
service factory.

```php
$router = new TQ\ExtDirect\Router\Router(
    new TQ\ExtDirect\Router\ServiceResolver(
        $serviceLocator,
        $namingStrategy,
        new TQ\ExtDirect\Service\ContainerServiceFactory(
            /* a Symfony\Component\DependencyInjection\ContainerInterface */
        )
    ),
    $eventDispatcher
);
```

The endpoint object is a facade in front of all the *Ext.direct* server-side components.
With its `createServiceDescription()` method one can obtain a standard-compliant API-
description while `handleRequest()` takes a `Symfony\Component\HttpFoundation\Request`
and returns a `Symfony\Component\HttpFoundation\Response` which contains the *Ext.direct*
response for the service calls received.

```php
$endpoint = TQ\ExtDirect\Service\Endpoint(
    'default', // endpoint id
    new TQ\ExtDirect\Description\ServiceDescriptionFactory(
        $serviceLocator,
        $namingStrategy,
        'My.api',
        $router,
        new TQ\ExtDirect\Router\RequestFactory(),
        'My.api.REMOTING_API'
    )
);
```

The endpoint manager is just a simple collection of endpoints which allow retrieval using
the endpoint id. This allows easy exposure of multiple independent APIs.

```php
$manager = new TQ\ExtDirect\Service\EndpointManager();
$manager->addEndpoint($endpoint);
$defaultEndpoint = $manager->getEndpoint('default');

$apiResponse = $defaultEndpoint->createServiceDescription('/path/to/router');
$apiResponse->send();

$request = Symfony\Component\HttpFoundation\Request::createFromGlobals();
$response = $defaultEndpoint->handleRequest($request);
$response->send();
```

The routing process can be manipulated and augmented by using event listeners on the event
dispatcher passed into the router. The library provides four event subscribers that allow
- converting arguments prior to calling the service method
- validation of arguments prior to calling the service method
- converting the service method call result before sending it back to the client
- instrumenting the router to gain timing information (used to augment the Symfony profiler timeline)

The shipped argument and result converters use the [`jms/serializer`](https://github.com/schmittjoh/serializer) library to provide extended
(de-)serialization capabilities, while the default argument validator makes use of the [`symfony/validator`](https://github.com/symfony/Validator)
library.

```php
$eventDispatcher->addSubscriber(
    new TQ\ExtDirect\Router\EventListener\ArgumentConversionListener(
        new TQ\ExtDirect\Router\ArgumentConverter(/* a JMS\Serializer\Serializer */)
    )
);
$eventDispatcher->addSubscriber(
    new TQ\ExtDirect\Router\EventListener\ArgumentValidationListener(
        new TQ\ExtDirect\Router\ArgumentValidator(/* a Symfony\Component\Validator\Validator\ValidatorInterface */)
    )
);
$eventDispatcher->addSubscriber(
    new TQ\ExtDirect\Router\EventListener\ResultConversionListener(
        new TQ\ExtDirect\Router\ResultConverter(/* a JMS\Serializer\Serializer */)
    )
);
$eventDispatcher->addSubscriber(
    new TQ\ExtDirect\Router\EventListener\StopwatchListener(
        /* a Symfony\Component\Stopwatch\Stopwatch */
    )
);
```

## Service Annotations

Services to be exposed via the *Ext.direct* API must be decorated with appropriate meta information. Currently this
is only possible using annotations (like the ones known from Doctrine, Symfony or other modern PHP libraries).

Each service class that will be exposed as an *Ext.direct* action is required to be annotated with `TQ\ExtDirect\Annotation\Action`.
The `Action` annotation optionally takes a service id parameter for services that are neither static nor can be
 instantiated with a parameter-less constructor.

```php
use TQ\ExtDirect\Annotation as Direct;

/**
 * @Direct\Action()
 */
class Service1
{
    // service will be instantiated using the parameter-less constructor if called method is not static
}

/**
 * @Direct\Action("app.direct.service2")
 */
class Service2
{
    // service will be retrieved from the dependency injection container using id "app.direct.service2" if called method is not static
}
```

Additionally each method that ill be exposed on an *Ext.direct* action is required to be annotated with `TQ\ExtDirect\Annotation\Method`.
The `Method` annotation optionally takes either `true` to designate the method as being a form handler ([taking regular form
posts](http://docs.sencha.com/extjs/6.0/direct/specification.html#Remoting_form_submission)) or `false` to designate the
method as being a regular *Ext.direct* method (this is the default).

```php
/**
 * @Direct\Action("app.direct.service3")
 */
class Service3
{
    /**
     * @Direct\Method()
     */
    public function methodA()
    {
        // regular method
    }

    /**
     * @Direct\Method(true)
     */
    public function methodB()
    {
        // form handler method
    }
}
```

**Extended features such as named parameters and strict named parameters described the in the *Ext.direct* specification are
 currently not exposed through the annotation system.**

Parameters that go into a method that is being called via an *Ext.direct* request can be annotated as well to apply
 parameter validation. This requires that the `TQ\ExtDirect\Router\EventListener\ArgumentValidationListener` is
 registered with the appropriate event dispatcher.

```php
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @Direct\Action("app.direct.service4")
 */
class Service4
{
    /**
     * @Direct\Method()
     * @Direct\Parameter("a", { @Assert\NotNull(), @Assert\Type("int") })
     *
     * @param int $a
     */
    public function methodA($a)
    {
    }
}
```

If the signature of the method being called exposes parameter(s) with a type-hint for `Symfony\Component\HttpFoundation\Request`
and/or `TQ\ExtDirect\Router\Request`, the incoming Symfony HTTP request and/or the raw *Ext.direct* request are
 injected into the method call automatically. This is especially important form form handling methods because there
 is no other way to access the incoming HTTP request parameters (form post).

As soon as the `TQ\ExtDirect\Router\EventListener\ArgumentConversionListener` is enabled, one can use strictly-typed
object parameters on service methods. These arguments will be automatically deserialized from the incoming JSON request and
will be injected into the method call.

The same is true for returning objects from a service method call. If the `TQ\ExtDirect\Router\EventListener\ResultConversionListener`
is enabled, return values are automatically serialized to JSON even if they are non-trivial objects.

Both the argument as well as the return value conversion is based on the excellent [`jms/serializer`](https://github.com/schmittjoh/serializer)
library by Johannes Schmitt. See the [documentation](http://jmsyst.com/libs/serializer) for more information.

## Specification

The Ext Direct Specification can be found on [Sencha's documentation website](http://docs.sencha.com/extjs/6.0/direct/specification.html).

## License

The MIT License (MIT)

Copyright (c) 2015 TEQneers GmbH & Co. KG

Permission is hereby granted, free of charge, to any person obtaining a copy
of this software and associated documentation files (the "Software"), to deal
in the Software without restriction, including without limitation the rights
to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
copies of the Software, and to permit persons to whom the Software is
furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in all
copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
SOFTWARE.
