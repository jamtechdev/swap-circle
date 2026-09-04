<?php

namespace Tests\Unit;

use App\Support\EmailAddress;
use PHPUnit\Framework\TestCase;

class EmailAddressTest extends TestCase
{
    public function test_rejects_addresses_without_tld(): void
    {
        $this->assertFalse(EmailAddress::isValid('user@domain'));
        $this->assertFalse(EmailAddress::isValid('a@b'));
        $this->assertFalse(EmailAddress::isValid('test@localhost'));
    }

    public function test_accepts_normal_emails(): void
    {
        $this->assertTrue(EmailAddress::isValid('name@example.com'));
        $this->assertTrue(EmailAddress::isValid('user.name+tag@mail.co.uk'));
    }
}
