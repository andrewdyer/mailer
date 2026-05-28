<?php

declare(strict_types=1);

namespace AndrewDyer\Mailer\Contracts;

use AndrewDyer\Mailer\PreparedMessage;

/**
 * Defines the contract for sending a prepared mail message.
 */
interface TransportInterface
{
    /**
     * Sends the prepared message via the transport.
     *
     * @param PreparedMessage $message The prepared message to send.
     */
    public function send(PreparedMessage $message): void;
}
