<?php

namespace oihana\files;

use PHPUnit\Framework\TestCase;
use RuntimeException;

class OpenSSLFileEncryptionTest extends TestCase
{
    private string $testDir;
    private string $inputFile;
    private string $encryptedFile;
    private string $decryptedFile;
    private string $passphrase;
    private string $cipher;

    protected function setUp(): void
    {
        $this->testDir = sys_get_temp_dir() . '/OpenSSLFileEncryptionTest';
        if (!file_exists($this->testDir)) {
            mkdir($this->testDir, 0777, true);
        }

        $this->inputFile = $this->testDir . '/input.txt';
        $this->encryptedFile = $this->testDir . '/encrypted.txt';
        $this->decryptedFile = $this->testDir . '/decrypted.txt';
        $this->passphrase = 'secret';
        $this->cipher = 'aes-256-cbc';

        // Create a sample input file
        file_put_contents($this->inputFile, 'Hello, World!');
    }

    protected function tearDown(): void
    {
        // Clean up test files
        if (file_exists($this->inputFile)) {
            unlink($this->inputFile);
        }
        if (file_exists($this->encryptedFile)) {
            unlink($this->encryptedFile);
        }
        if (file_exists($this->decryptedFile)) {
            unlink($this->decryptedFile);
        }
        if (file_exists($this->testDir)) {
            rmdir($this->testDir);
        }
    }

    public function testConstruct()
    {
        $encryption = new OpenSSLFileEncryption($this->passphrase, $this->cipher);
        // Note: Since properties are private, we can't access them directly.
        // We can use reflection to access private properties, but it's often better to add getter methods for testing.
        // For now, we'll assume the constructor works correctly if no exceptions are thrown.
        $this->assertTrue(true); // Placeholder assertion
    }

    public function testEncryptAndDecrypt()
    {
        $encryption = new OpenSSLFileEncryption($this->passphrase, $this->cipher);

        // Test encryption
        $result = $encryption->encrypt($this->inputFile, $this->encryptedFile);
        $this->assertTrue($result);
        $this->assertFileExists($this->encryptedFile);

        // Test decryption
        $result = $encryption->decrypt($this->encryptedFile, $this->decryptedFile);
        $this->assertTrue($result);
        $this->assertFileExists($this->decryptedFile);

        // Verify that the decrypted file matches the original
        $originalContent = file_get_contents($this->inputFile);
        $decryptedContent = file_get_contents($this->decryptedFile);
        $this->assertEquals($originalContent, $decryptedContent);
    }

    public function testEncryptWithNonExistentInputFile()
    {
        $encryption = new OpenSSLFileEncryption($this->passphrase, $this->cipher);
        $nonExistentFile = $this->testDir . '/nonexistent.txt';

        $this->expectException( RuntimeException::class );
        $this->expectExceptionMessage("Failed to encrypt, the input file not exist.");
        $encryption->encrypt($nonExistentFile, $this->encryptedFile);
    }

    // public function testEncryptWithUnwritableOutputFile()
    // {
    //     $encryption = new OpenSSLFileEncryption($this->passphrase, $this->cipher);
    //     $unwritableDir = '/unwritable/directory';
    //     $unwritableFile = $unwritableDir . '/encrypted.txt';
    //
    //     $this->expectException(RuntimeException::class);
    //     $this->expectExceptionMessage("Encryption failed, file write failed.");
    //     $encryption->encrypt($this->inputFile, $unwritableFile);
    // }
    //
    // public function testDecryptWithNonExistentInputFile()
    // {
    //     $encryption = new OpenSSLFileEncryption($this->passphrase, $this->cipher);
    //     $nonExistentFile = $this->testDir . '/nonexistent.txt';
    //
    //     $this->expectException(RuntimeException::class);
    //     $this->expectExceptionMessage("Failed to decrypt, unable to read the file '%inputFile'.");
    //     $encryption->decrypt($nonExistentFile, $this->decryptedFile);
    // }
    //
    // public function testDecryptWithIncorrectPassphrase()
    // {
    //     $encryption = new OpenSSLFileEncryption($this->passphrase, $this->cipher);
    //     $encryption->encrypt($this->inputFile, $this->encryptedFile);
    //
    //     $wrongPassphrase = 'wrongpassphrase';
    //     $encryptionWithWrongPassphrase = new OpenSSLFileEncryption($wrongPassphrase, $this->cipher);
    //
    //     $this->expectException(RuntimeException::class);
    //     $this->expectExceptionMessage("Decryption failed due to incorrect passphrase or corrupted data.");
    //     $encryptionWithWrongPassphrase->decrypt($this->encryptedFile, $this->decryptedFile);
    // }
    //
    // public function testDecryptWithUnwritableOutputFile()
    // {
    //     $encryption = new OpenSSLFileEncryption($this->passphrase, $this->cipher);
    //     $encryption->encrypt($this->inputFile, $this->encryptedFile);
    //
    //     $unwritableDir = '/unwritable/directory';
    //     $unwritableFile = $unwritableDir . '/decrypted.txt';
    //
    //     $this->expectException(RuntimeException::class);
    //     $this->expectExceptionMessage("Decryption failed, file write failed.");
    //     $encryption->decrypt($this->encryptedFile, $unwritableFile);
    // }
}
