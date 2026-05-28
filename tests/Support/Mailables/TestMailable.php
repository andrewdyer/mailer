<?php

declare(strict_types=1);

namespace AndrewDyer\Mailer\Tests\Support\Mailables;

use AndrewDyer\Mailer\Mailable;
use AndrewDyer\Mailer\Values\Address;
use AndrewDyer\Mailer\Values\Content;
use AndrewDyer\Mailer\Values\Envelope;

/**
 * Provides a concrete Mailable implementation for use in tests.
 */
final class TestMailable extends Mailable
{
    /**
     * Creates a new TestMailable.
     *
     * @param string $view The view template name.
     * @param array<string, mixed> $data The data to pass to the view template.
     */
    public function __construct(
        private readonly string $view = 'emails.welcome',
        private readonly array $data = [],
    ) {
    }

    /**
     * Returns the envelope for the message.
     *
     * @return Envelope The envelope containing addressing and routing metadata.
     */
    public function envelope(): Envelope
    {
        return new Envelope(new Address('recipient@example.com'), 'Hello');
    }

    /**
     * Returns the content for the message.
     *
     * @return Content The content containing the view template and data.
     */
    public function content(): Content
    {
        return new Content($this->view, $this->data);
    }
}
