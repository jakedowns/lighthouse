<?php

namespace Nuwave\Relay\Tests\Schema\Registrars;

use GraphQL\Type\Definition\ObjectType;
use Nuwave\Relay\Tests\TestCase;
use Nuwave\Relay\Tests\Support\GraphQL\Types\UserType;
use Nuwave\Relay\Schema\Registrars\TypeRegistrar;
use Nuwave\Relay\Schema\SchemaBuilder;
use Nuwave\Relay\Support\Exceptions\GraphQLTypeInstanceNotFound;

class TypeRegistrarTest extends TestCase
{
    /**
     * Type registrar.
     *
     * @var TypeRegistrar
     */
    protected $registrar;

    /**
     * Set up test environment.
     */
    public function setUp()
    {
        parent::setUp();

        $schema = new SchemaBuilder;

        $this->registrar = new TypeRegistrar;
        $this->registrar->setSchema($schema);
    }

    /**
     * @test
     */
    public function itCanRegisterType()
    {
        $field = $this->registrar->register('foo', 'FooClass');

        $this->assertCount(1, $this->registrar->all());
        $this->assertEquals('foo', $field->name);
        $this->assertEquals('FooClass', $field->namespace);
        $this->assertSame($field, $this->registrar->get('foo'));
    }

    /**
     * @test
     */
    public function itCanRetrieveTypeInstanceFromRegistrar()
    {
        $this->registrar->register('user', UserType::class);

        $instance = $this->registrar->instance('user');
        $this->assertInstanceOf(ObjectType::class, $instance);
        $this->assertEquals('User', $instance->name);
    }

    /**
     * @test
     */
    public function itThrowsExceptionWhenUnregisteredTypeIsRequested()
    {
        $this->registrar->register('user', UserType::class);

        $this->setExpectedException(GraphQLTypeInstanceNotFound::class);

        $this->registrar->instance('foo');
    }
}