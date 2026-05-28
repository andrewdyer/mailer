<?php

declare(strict_types=1);

namespace AndrewDyer\Mailer\Values;

/**
 * Carries the view template and associated data for a mailable.
 */
final readonly class Content
{
    /**
     * Creates a new Content.
     *
     * @param string $view The view template name.
     * @param array<string, mixed> $data The data to pass to the view template.
     */
    public function __construct(
        public string $view,
        public array $data = [],
    ) {
    }
}
