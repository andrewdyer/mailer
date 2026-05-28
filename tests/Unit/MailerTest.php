<?php

declare(strict_types=1);

namespace AndrewDyer\Mailer\Tests\Unit;

use AndrewDyer\Mailer\Contracts\AttachableInterface;
use AndrewDyer\Mailer\Contracts\TransportInterface;
use AndrewDyer\Mailer\Mailer;
use AndrewDyer\Mailer\PreparedMessage;
use AndrewDyer\Mailer\Tests\Support\Mailables\TestMailable;
use AndrewDyer\Mailer\Values\Address;
use AndrewDyer\Mailer\Values\Content;
use AndrewDyer\Mailer\Values\Envelope;
use PHPUnit\Framework\TestCase;
use Twig\Environment;

/**
 * Unit tests for Mailer.
 */
final class MailerTest extends TestCase
{
    /**
     * Asserts that send renders the view with the correct template name and data.
     */
    public function testSendRendersViewWithCorrectTemplateAndData(): void
    {
        $twig = $this->createMock(Environment::class);
        $twig->expects($this->once())
            ->method('render')
            ->with('emails.welcome', ['name' => 'John'])
            ->willReturn('<p>Hello</p>');

        $transport = $this->createMock(TransportInterface::class);
        $transport->expects($this->once())->method('send');

        $mailer = new Mailer($twig, $transport);
        $mailer->send(new TestMailable('emails.welcome', ['name' => 'John']));
    }

    /**
     * Asserts that send passes the rendered HTML to the transport.
     */
    public function testSendPassesRenderedHtmlToTransport(): void
    {
        $twig = $this->createMock(Environment::class);
        $twig->method('render')->willReturn('<p>Hello, world</p>');

        $transport = $this->createMock(TransportInterface::class);
        $transport->expects($this->once())
            ->method('send')
            ->with($this->callback(
                fn (PreparedMessage $message) => $message->html === '<p>Hello, world</p>',
            ));

        $mailer = new Mailer($twig, $transport);
        $mailer->send(new TestMailable());
    }

    /**
     * Asserts that send uses the default from address when the envelope from is empty.
     */
    public function testSendUsesDefaultFromWhenEnvelopeFromIsEmpty(): void
    {
        $defaultFrom = new Address('default@example.com', 'Default Sender');

        $twig = $this->createMock(Environment::class);
        $twig->method('render')->willReturn('<p>Hello</p>');

        $transport = $this->createMock(TransportInterface::class);
        $transport->expects($this->once())
            ->method('send')
            ->with($this->callback(
                fn (PreparedMessage $message) => $message->envelope->from === $defaultFrom,
            ));

        $mailer = new Mailer($twig, $transport, $defaultFrom);
        $mailer->send(new TestMailable());
    }

    /**
     * Asserts that send keeps the envelope from address when it is explicitly set.
     */
    public function testSendKeepsEnvelopeFromWhenExplicitlySet(): void
    {
        $explicitFrom = new Address('sender@example.com', 'Sender');

        $twig = $this->createMock(Environment::class);
        $twig->method('render')->willReturn('<p>Hello</p>');

        $transport = $this->createMock(TransportInterface::class);
        $transport->expects($this->once())
            ->method('send')
            ->with($this->callback(
                fn (PreparedMessage $message) => $message->envelope->from === $explicitFrom,
            ));

        $mailable = new class ($explicitFrom) extends \AndrewDyer\Mailer\Mailable {
            public function __construct(private readonly Address $from)
            {
            }

            public function envelope(): Envelope
            {
                return new Envelope(new Address('recipient@example.com'), 'Hello', $this->from);
            }

            public function content(): Content
            {
                return new Content('emails.welcome');
            }
        };

        $mailer = new Mailer($twig, $transport, new Address('default@example.com'));
        $mailer->send($mailable);
    }

    /**
     * Asserts that send includes attachments when the mailable implements AttachableInterface.
     */
    public function testSendIncludesAttachmentsForAttachableMailable(): void
    {
        $twig = $this->createMock(Environment::class);
        $twig->method('render')->willReturn('<p>Hello</p>');

        $transport = $this->createMock(TransportInterface::class);
        $transport->expects($this->once())
            ->method('send')
            ->with($this->callback(
                fn (PreparedMessage $message) => $message->attachments === ['/path/to/file.pdf'],
            ));

        $mailable = new class () extends \AndrewDyer\Mailer\Mailable implements AttachableInterface {
            public function envelope(): Envelope
            {
                return new Envelope(new Address('recipient@example.com'), 'Hello');
            }

            public function content(): Content
            {
                return new Content('emails.welcome');
            }

            public function attachments(): array
            {
                return ['/path/to/file.pdf'];
            }
        };

        $mailer = new Mailer($twig, $transport);
        $mailer->send($mailable);
    }

    /**
     * Asserts that send uses an empty attachments array when the mailable is not attachable.
     */
    public function testSendUsesEmptyAttachmentsForNonAttachableMailable(): void
    {
        $twig = $this->createMock(Environment::class);
        $twig->method('render')->willReturn('<p>Hello</p>');

        $transport = $this->createMock(TransportInterface::class);
        $transport->expects($this->once())
            ->method('send')
            ->with($this->callback(
                fn (PreparedMessage $message) => $message->attachments === [],
            ));

        $mailer = new Mailer($twig, $transport);
        $mailer->send(new TestMailable());
    }
}
