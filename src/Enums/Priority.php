<?php

declare(strict_types=1);

namespace AndrewDyer\Mailer\Enums;

/**
 * Represents the priority level of a mail message.
 */
enum Priority: int
{
    /**
     * The highest priority level.
     */
    case Highest = 1;

    /**
     * A high priority level.
     */
    case High = 2;

    /**
     * The default, normal priority level.
     */
    case Normal = 3;

    /**
     * A low priority level.
     */
    case Low = 4;

    /**
     * The lowest priority level.
     */
    case Lowest = 5;
}
