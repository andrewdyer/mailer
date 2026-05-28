<?php

declare(strict_types=1);

namespace AndrewDyer\Mailer\Values;

use AndrewDyer\Mailer\Enums\Priority;

/**
 * Carries the addressing and routing metadata for a mail message.
 */
final readonly class Envelope
{
    /**
     * Creates a new Envelope.
     *
     * @param Address $to The recipient address.
     * @param string $subject The message subject.
     * @param Address $from The sender address.
     * @param array<Address> $cc The CC recipient addresses.
     * @param array<Address> $bcc The BCC recipient addresses.
     * @param Address|null $replyTo The reply-to address.
     * @param Priority $priority The message priority.
     */
    public function __construct(
        public Address $to,
        public string $subject,
        public Address $from = new Address(''),
        public array $cc = [],
        public array $bcc = [],
        public ?Address $replyTo = null,
        public Priority $priority = Priority::Normal,
    ) {
    }
}
