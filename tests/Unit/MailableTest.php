<?php

declare(strict_types=1);

namespace AndrewDyer\Mailer\Tests\Unit;

use AndrewDyer\Mailer\Tests\Support\Mailables\TestMailable;
use PHPUnit\Framework\TestCase;

/**
 * Unit tests for Mailable.
 */
final class MailableTest extends TestCase
{
    /**
     * Asserts that getView returns the view template name from the mailable's content.
     */
    public function testGetViewReturnsViewFromContent(): void
    {
        $this->assertSame('emails.welcome', (new TestMailable())->getView());
    }

    /**
     * Asserts that getData returns the data array from the mailable's content.
     */
    public function testGetDataReturnsDataFromContent(): void
    {
        $data = ['name' => 'John', 'token' => 'abc123'];

        $this->assertSame($data, (new TestMailable(data: $data))->getData());
    }

    /**
     * Asserts that getData returns an empty array when no data is provided to the content.
     */
    public function testGetDataReturnsEmptyArrayWhenContentHasNoData(): void
    {
        $this->assertSame([], (new TestMailable())->getData());
    }
}
