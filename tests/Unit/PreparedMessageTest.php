<?php

declare(strict_types=1);

namespace AndrewDyer\Mailer\Tests\Unit;

use AndrewDyer\Mailer\PreparedMessage;
use AndrewDyer\Mailer\Values\Address;
use AndrewDyer\Mailer\Values\Envelope;
use PHPUnit\Framework\TestCase;

/**
 * Unit tests for PreparedMessage.
 */
final class PreparedMessageTest extends TestCase
{
    /**
     * Asserts that the envelope property is set correctly on construction.
     */
    public function testEnvelopeIsSetOnConstruction(): void
    {
        $envelope = new Envelope(new Address('recipient@example.com'), 'Hello, world');
        $message = new PreparedMessage($envelope, '<p>Hello, world</p>');

        $this->assertSame($envelope, $message->envelope);
    }

    /**
     * Asserts that the html property is set correctly on construction.
     */
    public function testHtmlIsSetOnConstruction(): void
    {
        $envelope = new Envelope(new Address('recipient@example.com'), 'Hello, world');
        $message = new PreparedMessage($envelope, '<p>Hello, world</p>');

        $this->assertSame('<p>Hello, world</p>', $message->html);
    }

    /**
     * Asserts that the attachments property defaults to an empty array when not provided.
     */
    public function testAttachmentsDefaultsToEmptyArray(): void
    {
        $envelope = new Envelope(new Address('recipient@example.com'), 'Hello, world');
        $message = new PreparedMessage($envelope, '<p>Hello, world</p>');

        $this->assertSame([], $message->attachments);
    }

    /**
     * Asserts that the attachments property is set correctly when provided.
     */
    public function testAttachmentsIsSetOnConstruction(): void
    {
        $attachments = ['/path/to/file.pdf', '/path/to/image.png'];
        $envelope = new Envelope(new Address('recipient@example.com'), 'Hello, world');
        $message = new PreparedMessage($envelope, '<p>Hello, world</p>', $attachments);

        $this->assertSame($attachments, $message->attachments);
    }
}
