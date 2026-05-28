<?php

declare(strict_types=1);

namespace AndrewDyer\Mailer;

use AndrewDyer\Mailer\Contracts\MailableInterface;
use AndrewDyer\Mailer\Values\Content;
use AndrewDyer\Mailer\Values\Envelope;

/**
 * Provides the base implementation for a mailable message.
 */
abstract class Mailable implements MailableInterface
{
    /**
     * Returns the envelope for the message.
     *
     * @return Envelope The envelope containing addressing and routing metadata.
     */
    abstract public function envelope(): Envelope;

    /**
     * Returns the content for the message.
     *
     * @return Content The content containing the view template and data.
     */
    abstract public function content(): Content;

    /**
     * Returns the view template name from the mailable's content.
     *
     * @return string The view template name.
     */
    public function getView(): string
    {
        return $this->content()->view;
    }

    /**
     * Returns the data array from the mailable's content.
     *
     * @return array<string, mixed> The data to pass to the view template.
     */
    public function getData(): array
    {
        return $this->content()->data;
    }
}
