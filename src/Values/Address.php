<?php

declare(strict_types=1);

namespace AndrewDyer\Mailer\Values;

final readonly class Address
{
    public function __construct(
        public string $email,
        public string $name = '',
    ) {
    }

    public function __toString(): string
    {
        return $this->name !== ''
            ? sprintf('"%s" <%s>', $this->name, $this->email)
            : $this->email;
    }
}
