<?php

declare(strict_types=1);

namespace AndrewDyer\Mailer\Values;

/**
 * Carries the email address and display name of a mail recipient or sender.
 */
final readonly class Address
{
    /**
     * Creates a new Address.
     *
     * @param string $email The email address.
     * @param string $name  The display name.
     */
    public function __construct(
        public string $email,
        public string $name = '',
    ) {
    }

    /**
     * Returns the address as a formatted string.
     *
     * @return string The email address, optionally wrapped with the display name.
     */
    public function __toString(): string
    {
        return $this->name !== ''
            ? sprintf('"%s" <%s>', $this->name, $this->email)
            : $this->email;
    }
}
