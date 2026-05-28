<?php

declare(strict_types=1);

namespace AndrewDyer\Mailer;

use AndrewDyer\Mailer\Values\Envelope;

/**
 * Carries the envelope, rendered HTML, and attachments of a mail message ready for sending.
 */
final readonly class PreparedMessage
{
    /**
     * Creates a new PreparedMessage.
     *
     * @param Envelope $envelope The envelope containing addressing and routing metadata.
     * @param string $html The rendered HTML body of the message.
     * @param list<mixed> $attachments The file attachments to include with the message.
     */
    public function __construct(
        public Envelope $envelope,
        public string $html,
        public array $attachments = [],
    ) {
    }
}
