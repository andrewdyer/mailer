<?php

declare(strict_types=1);

namespace AndrewDyer\Mailer\Tests\Unit\Values;

use AndrewDyer\Mailer\Enums\Priority;
use AndrewDyer\Mailer\Values\Address;
use AndrewDyer\Mailer\Values\Envelope;
use PHPUnit\Framework\TestCase;

/**
 * Unit tests for Envelope.
 */
final class EnvelopeTest extends TestCase
{
    /**
     * Asserts that the to property is set correctly on construction.
     */
    public function testToIsSetOnConstruction(): void
    {
        $to = new Address('recipient@example.com', 'Recipient');
        $envelope = new Envelope($to, 'Hello');

        $this->assertSame($to, $envelope->to);
    }

    /**
     * Asserts that the subject property is set correctly on construction.
     */
    public function testSubjectIsSetOnConstruction(): void
    {
        $envelope = new Envelope(new Address('recipient@example.com'), 'Hello World');

        $this->assertSame('Hello World', $envelope->subject);
    }

    /**
     * Asserts that the from property defaults to an empty Address when not provided.
     */
    public function testFromDefaultsToEmptyAddress(): void
    {
        $envelope = new Envelope(new Address('recipient@example.com'), 'Hello');

        $this->assertSame('', $envelope->from->email);
    }

    /**
     * Asserts that the from property is set correctly when provided.
     */
    public function testFromIsSetOnConstruction(): void
    {
        $from = new Address('sender@example.com', 'Sender');
        $envelope = new Envelope(new Address('recipient@example.com'), 'Hello', $from);

        $this->assertSame($from, $envelope->from);
    }

    /**
     * Asserts that the cc property defaults to an empty array when not provided.
     */
    public function testCcDefaultsToEmptyArray(): void
    {
        $envelope = new Envelope(new Address('recipient@example.com'), 'Hello');

        $this->assertSame([], $envelope->cc);
    }

    /**
     * Asserts that the cc property is set correctly when provided.
     */
    public function testCcIsSetOnConstruction(): void
    {
        $cc = [new Address('cc@example.com', 'CC User')];
        $envelope = new Envelope(new Address('recipient@example.com'), 'Hello', cc: $cc);

        $this->assertSame($cc, $envelope->cc);
    }

    /**
     * Asserts that the bcc property defaults to an empty array when not provided.
     */
    public function testBccDefaultsToEmptyArray(): void
    {
        $envelope = new Envelope(new Address('recipient@example.com'), 'Hello');

        $this->assertSame([], $envelope->bcc);
    }

    /**
     * Asserts that the bcc property is set correctly when provided.
     */
    public function testBccIsSetOnConstruction(): void
    {
        $bcc = [new Address('bcc@example.com', 'BCC User')];
        $envelope = new Envelope(new Address('recipient@example.com'), 'Hello', bcc: $bcc);

        $this->assertSame($bcc, $envelope->bcc);
    }

    /**
     * Asserts that the replyTo property defaults to null when not provided.
     */
    public function testReplyToDefaultsToNull(): void
    {
        $envelope = new Envelope(new Address('recipient@example.com'), 'Hello');

        $this->assertNull($envelope->replyTo);
    }

    /**
     * Asserts that the replyTo property is set correctly when provided.
     */
    public function testReplyToIsSetOnConstruction(): void
    {
        $replyTo = new Address('reply@example.com', 'Reply To');
        $envelope = new Envelope(new Address('recipient@example.com'), 'Hello', replyTo: $replyTo);

        $this->assertSame($replyTo, $envelope->replyTo);
    }

    /**
     * Asserts that the priority defaults to Priority::Normal when not provided.
     */
    public function testPriorityDefaultsToNormal(): void
    {
        $envelope = new Envelope(new Address('recipient@example.com'), 'Hello');

        $this->assertSame(Priority::Normal, $envelope->priority);
    }

    /**
     * Asserts that the priority is set correctly when provided.
     */
    public function testPriorityIsSetOnConstruction(): void
    {
        $envelope = new Envelope(new Address('recipient@example.com'), 'Hello', priority: Priority::High);

        $this->assertSame(Priority::High, $envelope->priority);
    }
}
