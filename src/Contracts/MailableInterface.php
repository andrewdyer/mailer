<?php

declare(strict_types=1);

namespace AndrewDyer\Mailer\Contracts;

use AndrewDyer\Mailer\Values\Content;
use AndrewDyer\Mailer\Values\Envelope;

/**
 * Defines the contract for a mailable message.
 */
interface MailableInterface
{
    /**
     * Returns the envelope for the message.
     *
     * @return Envelope The envelope containing addressing and routing metadata.
     */
    public function envelope(): Envelope;

    /**
     * Returns the content for the message.
     *
     * @return Content The content containing the view template and data.
     */
    public function content(): Content;
}
