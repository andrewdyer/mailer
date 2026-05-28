<?php

declare(strict_types=1);

namespace AndrewDyer\Mailer\Tests\Unit;

use AndrewDyer\Mailer\Values\Address;
use PHPUnit\Framework\TestCase;

/**
 * Unit tests for Address.
 */
final class AddressTest extends TestCase
{
    /**
     * Asserts that the email property is set correctly on construction.
     */
    public function testEmailIsSetOnConstruction(): void
    {
        $address = new Address('user@example.com');

        $this->assertSame('user@example.com', $address->email);
    }

    /**
     * Asserts that the name defaults to an empty string when not provided.
     */
    public function testNameDefaultsToEmptyString(): void
    {
        $address = new Address('user@example.com');

        $this->assertSame('', $address->name);
    }

    /**
     * Asserts that the name property is set correctly when provided.
     */
    public function testNameIsSetOnConstruction(): void
    {
        $address = new Address('user@example.com', 'John Doe');

        $this->assertSame('John Doe', $address->name);
    }

    /**
     * Asserts that casting to string returns only the email when no name is provided.
     */
    public function testToStringReturnsEmailOnlyWhenNameIsEmpty(): void
    {
        $address = new Address('user@example.com');

        $this->assertSame('user@example.com', (string) $address);
    }

    /**
     * Asserts that casting to string returns the formatted name and email when a name is provided.
     */
    public function testToStringReturnsFormattedStringWhenNameIsProvided(): void
    {
        $address = new Address('user@example.com', 'John Doe');

        $this->assertSame('"John Doe" <user@example.com>', (string) $address);
    }
}
