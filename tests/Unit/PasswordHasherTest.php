<?php

namespace Tests\Unit;

use App\Support\PasswordHasher;
use PHPUnit\Framework\TestCase;

class PasswordHasherTest extends TestCase
{
    public function test_bcrypt_round_trip(): void
    {
        $hash = PasswordHasher::hash('Secret123!');
        $this->assertTrue(PasswordHasher::isBcrypt($hash));
        $this->assertTrue(PasswordHasher::check('Secret123!', $hash));
        $this->assertFalse(PasswordHasher::check('wrong', $hash));
    }

    public function test_legacy_md5_and_rehash_flag(): void
    {
        $md5 = md5('legacy-pass');
        $this->assertTrue(PasswordHasher::check('legacy-pass', $md5));
        $this->assertTrue(PasswordHasher::needsRehash($md5));
        $this->assertFalse(PasswordHasher::isBcrypt($md5));
    }
}
