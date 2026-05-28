<?php

declare(strict_types=1);

namespace AndrewDyer\Mailer\Drivers;

use AndrewDyer\Mailer\Contracts\TransportInterface;
use AndrewDyer\Mailer\PreparedMessage;
use AndrewDyer\Mailer\Values\Address;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\Mailer as SymfonyMailer;
use Symfony\Component\Mailer\Transport;
use Symfony\Component\Mime\Address as SymfonyAddress;
use Symfony\Component\Mime\Email;

/**
 * Handles sending mail messages via the Symfony Mailer transport.
 */
final class SymfonyTransport implements TransportInterface
{
    /**
     * The underlying Symfony Mailer instance.
     */
    private SymfonyMailer $mailer;

    /**
     * Creates a new SymfonyTransport with the required dependencies.
     *
     * @param string $dsn The Symfony Mailer DSN string.
     */
    public function __construct(string $dsn)
    {
        $this->mailer = new SymfonyMailer(Transport::fromDsn($dsn));
    }

    /**
     * Sends the prepared message via the Symfony Mailer transport.
     *
     * @param PreparedMessage $message The prepared message to send.
     * @throws TransportExceptionInterface When the message fails to send.
     */
    public function send(PreparedMessage $message): void
    {
        $email = (new Email())
            ->from($this->toSymfony($message->envelope->from))
            ->to($this->toSymfony($message->envelope->to))
            ->subject($message->envelope->subject)
            ->priority($message->envelope->priority->value)
            ->html($message->html);

        foreach ($message->envelope->cc as $cc) {
            $email->addCc($this->toSymfony($cc));
        }

        foreach ($message->envelope->bcc as $bcc) {
            $email->addBcc($this->toSymfony($bcc));
        }

        if ($message->envelope->replyTo !== null) {
            $email->replyTo($this->toSymfony($message->envelope->replyTo));
        }

        foreach ($message->attachments as $path) {
            $email->attachFromPath($path);
        }

        $this->mailer->send($email);
    }

    /**
     * Converts an Address to a Symfony Address instance.
     *
     * @internal
     *
     * @param Address $address The address to convert.
     * @return SymfonyAddress The equivalent Symfony address.
     */
    private function toSymfony(Address $address): SymfonyAddress
    {
        return new SymfonyAddress($address->email, $address->name);
    }
}
