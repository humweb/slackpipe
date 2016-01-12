<?php

namespace Humweb\SlackPipe\Support;

use phpseclib\Crypt\AES;

/**
 * Crypt
 *
 * @package Humweb\SlackPipe\Support
 */
class Crypt
{
    protected $key = '4bcd3fgh1j|<1mn0pq?st\/wxyz.!:>-';
    protected $cipher;

    /**
     *
     */
    public function encrypt($plainText)
    {
        return $this->getInstance()->encrypt($plainText);
    }

    public function decrypt($encryptedText)
    {
        return $this->getInstance()->decrypt($encryptedText);
    }

    /**
     * @return \phpseclib\Crypt\AES
     */
    protected function getInstance()
    {
        if ( ! $this->cipher) {
            $cipher = new AES();
            $cipher->setKeyLength(256);
            $cipher->setKey($this->key);

            $this->cipher = $cipher;
        }

        return $this->cipher;
    }
}