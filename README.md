# Mailer

A framework-agnostic PHP library for sending emails from Twig templates, with support for a swappable transport interface.

[![Latest Stable Version](http://poser.pugx.org/andrewdyer/mailer/v?style=flat-square)](https://packagist.org/packages/andrewdyer/mailer)
[![Total Downloads](http://poser.pugx.org/andrewdyer/mailer/downloads?style=flat-square)](https://packagist.org/packages/andrewdyer/mailer)
[![License](http://poser.pugx.org/andrewdyer/mailer/license?style=flat-square)](https://packagist.org/packages/andrewdyer/mailer)
[![PHP Version Require](http://poser.pugx.org/andrewdyer/mailer/require/php?style=flat-square)](https://packagist.org/packages/andrewdyer/mailer)

## Introduction

This library provides an email delivery pipeline for PHP applications, rendering Twig templates to HTML and dispatching them through an extensible transport interface. A Symfony Mailer transport is included, with support for custom transports via a simple contract.

## Prerequisites

- **[PHP](https://www.php.net/)**: Version 8.3 or higher is required.
- **[Composer](https://getcomposer.org/)**: Dependency management tool for PHP.
- **[Twig](https://twig.symfony.com/)**: Version ^3.27 is required.

## Installation

```bash
composer require andrewdyer/mailer
```

## Getting Started

### 1. Create a Twig template

Create an HTML template in the application's templates directory:

```html
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <style>
      body {
        font-family: Arial, sans-serif;
        font-size: 14px;
        color: #333;
        padding: 40px;
      }
      h1 {
        font-size: 24px;
        color: #1a1a2e;
      }
    </style>
  </head>
  <body>
    <h1>Welcome, {{ user.name }}</h1>
    <p>Thanks for signing up.</p>
  </body>
</html>
```

### 2. Create a mailable

Extend `Mailable` and implement `envelope()` to configure the routing and `content()` to define the template and data:

```php
use AndrewDyer\Mailer\Mailable;
use AndrewDyer\Mailer\Values\Address;
use AndrewDyer\Mailer\Values\Content;
use AndrewDyer\Mailer\Values\Envelope;

class WelcomeMail extends Mailable
{
    public function __construct(private readonly User $user) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            to:      new Address($this->user->email, $this->user->name),
            subject: 'Welcome to the platform!',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails/welcome.html.twig',
            data: ['user' => $this->user],
        );
    }
}
```

### 3. Set up the mailer

Instantiate `Mailer` with a `Twig\Environment`, a transport, and an optional default from address:

```php
use AndrewDyer\Mailer\Mailer;
use AndrewDyer\Mailer\Drivers\SymfonyTransport;
use AndrewDyer\Mailer\Values\Address;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;

$twig = new Environment(new FilesystemLoader('/path/to/templates'));

$mailer = new Mailer(
    twig:        $twig,
    transport:   new SymfonyTransport('smtp://user:pass@smtp.example.com:587'),
    defaultFrom: new Address('hello@example.com', 'My App'),
);
```

## Usage

### Sending a mailable

Dispatch any `Mailable` instance via the `send()` method:

```php
$mailer->send(new WelcomeMail($user));
```

### Attaching files

Implement `AttachableInterface` on the mailable and return an array of absolute file paths:

```php
use AndrewDyer\Mailer\Contracts\AttachableInterface;
use AndrewDyer\Mailer\Mailable;

class InvoiceMail extends Mailable implements AttachableInterface
{
    public function __construct(private readonly Order $order) {}

    public function envelope(): Envelope { /* ... */ }

    public function content(): Content { /* ... */ }

    public function attachments(): array
    {
        return [
            '/storage/invoices/invoice-' . $this->order->id . '.pdf',
        ];
    }
}
```

## Envelope

A value object defining the routing and metadata for a message. Only `to` and `subject` are required — all other properties are optional and can be passed in any order using named arguments:

```php
use AndrewDyer\Mailer\Values\Envelope;
use AndrewDyer\Mailer\Values\Address;
use AndrewDyer\Mailer\Enums\Priority;

new Envelope(
    to:       new Address('recipient@example.com', 'Recipient'),
    subject:  'Hello!',
    from:     new Address('sender@example.com', 'Sender'),
    cc:       [new Address('cc@example.com')],
    bcc:      [new Address('bcc@example.com')],
    replyTo:  new Address('reply@example.com'),
    priority: Priority::Normal,
);
```

### Setting a from address

The `defaultFrom` address set on `Mailer` is used when `from` is omitted. Set it explicitly on the `Envelope` to override it for a specific mailable:

```php
public function envelope(): Envelope
{
    return new Envelope(
        to:      new Address($this->user->email, $this->user->name),
        subject: 'Welcome to the platform!',
        from:    new Address('support@example.com', 'Support Team'),
    );
}
```

### Adding CC and BCC recipients

Pass arrays of `Address` instances to `cc` and `bcc` on the `Envelope`:

```php
public function envelope(): Envelope
{
    return new Envelope(
        to:      new Address($this->user->email, $this->user->name),
        subject: 'Welcome to the platform!',
        cc:      [new Address('manager@example.com', 'Manager')],
        bcc:     [new Address('archive@example.com')],
    );
}
```

### Setting priority

Pass a `Priority` enum value to `priority` on the `Envelope`:

```php
use AndrewDyer\Mailer\Enums\Priority;

public function envelope(): Envelope
{
    return new Envelope(
        to:       new Address($this->user->email),
        subject:  'Urgent: action required',
        priority: Priority::High,
    );
}
```

## Transports

### Symfony Mailer

A transport backed by [Symfony Mailer](https://symfony.com/doc/current/mailer.html), supporting SMTP and a wide range of third-party providers via DSN strings.

```bash
composer require symfony/mailer
```

Then instantiate the transport with a DSN string:

```php
use AndrewDyer\Mailer\Drivers\SymfonyTransport;

$transport = new SymfonyTransport('smtp://user:pass@smtp.example.com:587');
```

Common DSN formats:

```
smtp://user:pass@smtp.example.com:587
sendmail://default
null://null
```

### Custom transports

Any class implementing `TransportInterface` can be used as a transport, accepting a `PreparedMessage` and dispatching it:

```php
use AndrewDyer\Mailer\Contracts\TransportInterface;
use AndrewDyer\Mailer\PreparedMessage;

class CustomTransport implements TransportInterface
{
    public function send(PreparedMessage $message): void
    {
        // Dispatch the message...
    }
}
```

## License

Licensed under the [MIT licence](https://opensource.org/licenses/MIT) and is free for private or commercial projects.
