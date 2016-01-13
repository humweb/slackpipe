<?php

namespace Humweb\SlackPipe\Support;

use phpseclib\Crypt\AES;

/**
 * A class to handle secure encryption and decryption of arbitrary data
 *
 * Note that this is not just straight encryption.  It also has a few other
 *  features in it to make the encrypted data far more secure.  Note that any
 *  other implementations used to decrypt data will have to do the same exact
 *  operations.
 *
 * Security Benefits:
 *
 * - Uses Key stretching
 * - Hides the Initialization Vector
 * - Does HMAC verification of source data
 *
 */
class Encryption
{

    protected $key;

    /**
     * @var string $cipher The cipher instance
     */
    protected $cipher = '';

    /**
     * Constructor!
     *
     * @param string $key
     */
    public function __construct($key)
    {
        $this->key = $key;

        $this->cipher = new AES();
        $this->cipher->setKeyLength(256);
        $this->cipher->setKey($this->key);
    }

    /**
     * Decrypt the data with the provided key
     *
     * @param string $data The encrypted data to decrypt
     *
     * @returns string|false The returned string if decryption is successful
     *                           false if it is not
     */
    public function decrypt($data)
    {
        return $this->cipher->decrypt(base64_decode($data));
    }

    /**
     * Encrypt the supplied data using the supplied key
     *
     * @param string $data The data to encrypt
     *
     * @returns string The encrypted data base64 encoded
     */
    public function encrypt($data)
    {
        return base64_encode($this->cipher->encrypt($data));
    }

}