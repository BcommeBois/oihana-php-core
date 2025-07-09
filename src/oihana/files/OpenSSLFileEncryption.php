<?php

namespace oihana\files;

use Exception;
use RuntimeException;

/**
 * Class OpenSSLFileEncryption
 * This class provides methods to encrypt and decrypt files using OpenSSL.
 * @package oihana\files
 */
class OpenSSLFileEncryption
{
    /**
     * Creates a new OpenSSLFileEncryption instance.
     * @param string $passphrase The key to encrypt the file.
     * @param string $cipher The cipher method. For a list of available cipher methods, use {@see openssl_get_cipher_methods()}.
     */
    public function __construct( string $passphrase, string $cipher = 'aes-256-cbc' )
    {
        $this->cipher     = $cipher;
        $this->passphrase = $passphrase;
        $this->ivLength   = openssl_cipher_iv_length( $cipher ) ;
    }

    /**
     * @var string The cipher method used for encryption and decryption.
     */
    private string $cipher;

    /**
     * @var string The passphrase used for encryption and decryption.
     */
    private string $passphrase;

    /**
     * @var string The length of the initialization vector (IV) used for encryption and decryption.
     */
    private string $ivLength;

    /**
     * Encrypt a file with the OpenSSL tool.
     *
     * This method reads the input file, encrypts its contents using OpenSSL, and writes the encrypted data to the output file.
     * The encrypted data includes the initialization vector (IV) prepended to the encrypted data.
     *
     * @param string $inputFile The path to the file to be encrypted.
     * @param string $outputFile The path where the encrypted file will be written.
     * @return true Returns true on successful encryption.
     * @throws RuntimeException If the input file cannot be read, encryption fails, or the output file cannot be written.
     */
    public function encrypt( string $inputFile , string $outputFile ):bool
    {
        if( !file_exists( $inputFile ) )
        {
            throw new RuntimeException( 'Failed to encrypt, the input file not exist.' ) ;
        }

        $data = file_get_contents( $inputFile ) ;
        if ( $data === false )
        {
            throw new RuntimeException( 'Failed to encrypt, unable to read the file.' ) ;
        }

        $iv = openssl_random_pseudo_bytes( $this->ivLength ) ;

        $encrypted = openssl_encrypt( $data, $this->cipher, $this->passphrase, OPENSSL_RAW_DATA, $iv );

        if ( $encrypted === false )
        {
            throw new RuntimeException("Failed to encrypt the file'." ) ;
        }

        try
        {
            file_put_contents( $outputFile , $iv . $encrypted );
        }
        catch ( Exception )
        {
            throw new RuntimeException("Encryption failed, file write failed." ) ;
        }

        return true;
    }

    /**
     * Decrypt a file with the OpenSSL tool.
     *
     * This method reads the input file, extracts the initialization vector (IV) from the beginning of the file,
     * decrypts the remaining data using OpenSSL, and writes the decrypted data to the output file.
     *
     * @param string $inputFile The path to the file to be decrypted.
     * @param string $outputFile The path where the decrypted file will be written.
     * @return true Returns true on successful decryption.
     * @throws RuntimeException If the input file cannot be read, decryption fails (due to incorrect passphrase or corrupted data), or the output file cannot be written.
     */
    public function decrypt( string $inputFile , string $outputFile ) :true
    {
        $data = file_get_contents($inputFile) ;

        if ($data === false)
        {
            throw new RuntimeException("Failed to decrypt, unable to read the file '%inputFile'.");
        }

        $iv = substr( $data , 0 , $this->ivLength ) ;
        $encryptedData = substr( $data , $this->ivLength ) ;

        $decrypted = openssl_decrypt( $encryptedData , $this->cipher , $this->passphrase , OPENSSL_RAW_DATA , $iv ) ;

        if ($decrypted === false)
        {
            throw new RuntimeException("Decryption failed due to incorrect passphrase or corrupted data.");
        }

        $result = file_put_contents( $outputFile , $decrypted ) ;

        if ( $result === false )
        {
            throw new RuntimeException("Decryption failed, file write failed." );
        }

        return true;
    }
}