<?php

declare(strict_types=1);

namespace AndrewDyer\Mailer\Tests\Unit;

use AndrewDyer\Mailer\Values\Content;
use PHPUnit\Framework\TestCase;

/**
 * Unit tests for Content.
 */
final class ContentTest extends TestCase
{
    /**
     * Asserts that the view property is set correctly on construction.
     */
    public function testViewIsSetOnConstruction(): void
    {
        $content = new Content('emails.welcome');

        $this->assertSame('emails.welcome', $content->view);
    }

    /**
     * Asserts that the data property defaults to an empty array when not provided.
     */
    public function testDataDefaultsToEmptyArray(): void
    {
        $content = new Content('emails.welcome');

        $this->assertSame([], $content->data);
    }

    /**
     * Asserts that the data property is set correctly when provided.
     */
    public function testDataIsSetOnConstruction(): void
    {
        $data = ['name' => 'John', 'token' => 'abc123'];
        $content = new Content('emails.welcome', $data);

        $this->assertSame($data, $content->data);
    }
}
