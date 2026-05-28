<?php

declare(strict_types=1);

namespace AndrewDyer\Mailer\Contracts;

/**
 * Defines the contract for a mailable that supports file attachments.
 */
interface AttachableInterface
{
    /**
     * Returns the list of file paths to attach to the message.
     *
     * @return list<string> The file paths to attach.
     */
    public function attachments(): array;
}
