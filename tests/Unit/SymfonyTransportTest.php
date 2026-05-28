<?php

declare(strict_types=1);

namespace AndrewDyer\Mailer\Tests\Unit;

use AndrewDyer\Mailer\Drivers\SymfonyTransport;
use AndrewDyer\Mailer\PreparedMessage;
use AndrewDyer\Mailer\Values\Address;
use AndrewDyer\Mailer\Values\Envelope;
use PHPUnit\Framework\TestCase;

/**
 * Unit tests for SymfonyTransport.
 */
final class SymfonyTransportTest extends TestCase
{
    /**
     * Asserts that send completes without error for a basic message.
     */
    public function testSendDispatchesBasicMessage(): void
    {
        $transport = new SymfonyTransport('null://null');
        $envelope = new Envelope(
            new Address('recipient@example.com', 'Recipient'),
            'Hello, world',
            new Address('sender@example.com', 'Sender'),
        );
        $message = new PreparedMessage($envelope, '<p>Hello, world</p>');

        $transport->send($message);

        $this->expectNotToPerformAssertions();
    }

    /**
     * Asserts that send completes without error when the envelope includes CC addresses.
     */
    public function testSendDispatchesMessageWithCc(): void
    {
        $transport = new SymfonyTransport('null://null');
        $envelope = new Envelope(
            new Address('recipient@example.com'),
            'Hello, world',
            new Address('sender@example.com'),
            cc: [new Address('cc@example.com', 'CC User')],
        );
        $message = new PreparedMessage($envelope, '<p>Hello, world</p>');

        $transport->send($message);

        $this->expectNotToPerformAssertions();
    }

    /**
     * Asserts that send completes without error when the envelope includes BCC addresses.
     */
    public function testSendDispatchesMessageWithBcc(): void
    {
        $transport = new SymfonyTransport('null://null');
        $envelope = new Envelope(
            new Address('recipient@example.com'),
            'Hello, world',
            new Address('sender@example.com'),
            bcc: [new Address('bcc@example.com', 'BCC User')],
        );
        $message = new PreparedMessage($envelope, '<p>Hello, world</p>');

        $transport->send($message);

        $this->expectNotToPerformAssertions();
    }

    /**
     * Asserts that send completes without error when the envelope includes a reply-to address.
     */
    public function testSendDispatchesMessageWithReplyTo(): void
    {
        $transport = new SymfonyTransport('null://null');
        $envelope = new Envelope(
            new Address('recipient@example.com'),
            'Hello, world',
            new Address('sender@example.com'),
            replyTo: new Address('reply@example.com', 'Reply To'),
        );
        $message = new PreparedMessage($envelope, '<p>Hello, world</p>');

        $transport->send($message);

        $this->expectNotToPerformAssertions();
    }
}
