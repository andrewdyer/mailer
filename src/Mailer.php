<?php

declare(strict_types=1);

namespace AndrewDyer\Mailer;

use AndrewDyer\Mailer\Contracts\AttachableInterface;
use AndrewDyer\Mailer\Contracts\MailableInterface;
use AndrewDyer\Mailer\Contracts\TransportInterface;
use AndrewDyer\Mailer\Values\Address;
use AndrewDyer\Mailer\Values\Envelope;
use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

/**
 * Handles sending mailable messages via the configured transport.
 */
final readonly class Mailer
{
    /**
     * Creates a new Mailer with the required dependencies.
     *
     * @param Environment $twig The Twig template engine.
     * @param TransportInterface $transport The mail transport to use.
     * @param Address|null $defaultFrom The default sender address.
     */
    public function __construct(
        private Environment $twig,
        private TransportInterface $transport,
        private ?Address $defaultFrom = null,
    ) {
    }

    /**
     * Handles sending a mailable message via the configured transport.
     *
     * @param MailableInterface $mailable The mailable to send.
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
    public function send(MailableInterface $mailable): void
    {
        $envelope = $this->resolveFrom($mailable->envelope());

        $html = $this->twig->render(
            $mailable->content()->view,
            $mailable->content()->data,
        );

        $attachments = $mailable instanceof AttachableInterface
            ? $mailable->attachments()
            : [];

        $this->transport->send(
            new PreparedMessage($envelope, $html, $attachments),
        );
    }

    /**
     * Resolves the sender address for the given envelope.
     *
     * @internal
     *
     * @param Envelope $envelope The envelope to resolve.
     * @return Envelope The envelope with the resolved sender address.
     */
    private function resolveFrom(Envelope $envelope): Envelope
    {
        if ($envelope->from->email !== '' || $this->defaultFrom === null) {
            return $envelope;
        }

        return new Envelope(
            to: $envelope->to,
            subject: $envelope->subject,
            from: $this->defaultFrom,
            cc: $envelope->cc,
            bcc: $envelope->bcc,
            replyTo: $envelope->replyTo,
            priority: $envelope->priority,
        );
    }
}
