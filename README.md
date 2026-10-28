# Wilson Godfirst

A responsive, multi-page website for a fictional cleaning business, built as a PHP 8 learning project. It demonstrates reusable page includes, data-driven services, safe form handling, and a simple password-protected local messages page.

## Features

- Shared site configuration, header, footer, and navigation across four main pages
- Responsive home, about, services, and contact pages
- Six data-driven cleaning services with individual detail pages
- Team profiles rendered from a PHP data array
- Validated contact form with sticky values, field-specific errors, and a session-backed CSRF token
- Contact submissions saved as timestamped JSON Lines in `storage/messages.jsonl`
- Password-protected messages page with a newest-first table and count badge
- HTML output escaping for dynamic content
- Apache access denied for the storage folder through `.htaccess`

## Tech stack

- PHP 8.0 or later (developed and checked with PHP 8.2)
- Apache through XAMPP
- HTML5 and CSS3
- JSON Lines for local contact message storage

## Run with XAMPP

1. Install and open XAMPP Control Panel.
2. Start **Apache**.
3. Keep the project in this exact folder:

   `C:\xampp\htdocs\swd\04-php-site`

4. Open this URL in your browser:

   [http://localhost/swd/04-php-site/](http://localhost/swd/04-php-site/)

5. To view locally stored contact submissions, visit:

   [http://localhost/swd/04-php-site/messages.php](http://localhost/swd/04-php-site/messages.php)

   The local learning password is set in `includes/config.php` as `$messagesPassword`. Change it before using the page, even on a shared development machine.

Contact submissions are appended to `storage/messages.jsonl`. Apache is configured to deny direct web access to the storage directory. The web server must have permission to create and write to `storage`.

## Project structure

```text
04-php-site/
├── about.php
├── contact.php
├── index.php
├── messages.php
├── README.md
├── service.php
├── services.php
├── assets/
│   └── css/
│       └── style.css
├── includes/
│   ├── config.php
│   ├── data.php
│   ├── footer.php
│   ├── functions.php
│   └── header.php
└── storage/
    ├── .htaccess
    └── messages.jsonl  (created when the first contact form is submitted)
```

## What I learned

- **PHP includes and reusable layouts:** [Write what you learned here.]
- **Arrays, loops, and data-driven pages:** [Write what you learned here.]
- **Form validation, sessions, and CSRF tokens:** [Write what you learned here.]
- **Escaping output and handling user input safely:** [Write what you learned here.]
- **Writing and reading JSON Lines files:** [Write what you learned here.]
- **Running and testing PHP with XAMPP:** [Write what you learned here.]

## Learning-only security note

The messages page uses a hard-coded password in `includes/config.php` to keep the example easy to understand on a local machine. This is not production-grade authentication: the password is stored in source code, is shared by every user, and has no account management, throttling, or password reset. A deployed site should use securely stored password hashes, individual accounts, HTTPS, and a proper authentication/session security design. Keep this demo local and never use real customer data with the example password.
