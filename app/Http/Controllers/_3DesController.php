<?php

namespace App\Http\Controllers;

class _3DesController extends Controller
{
    protected $cipher_algorithm;
    protected $digest_method;
    protected $ivector_length;
    protected $key;

    public function __construct($cipher_algorithm = 'aes-256-ctr', $digest_method = 'sha256')
    {
        $this->cipher_algorithm = $cipher_algorithm;
        $this->digest_method = $digest_method;

        if (!in_array($cipher_algorithm, openssl_get_cipher_methods(TRUE))) {
            throw new \Exception(__METHOD__ . " Unknown cipher $cipher_algorithm");
        }

        if (!in_array($digest_method, openssl_get_md_methods(TRUE))) {
            throw new \Exception(__METHOD__ . " Unknown digest $digest_method");
        }

        $this->ivector_length = openssl_cipher_iv_length($cipher_algorithm);
        $this->key = getenv('CRYPTO_KEY');
    }

    public function encrypt($text)
    {
        $key = $this->key;
        $keyhash = openssl_digest($key, $this->digest_method, TRUE);
        $ivector = random_bytes($this->ivector_length);
        $crypted = openssl_encrypt($text, $this->cipher_algorithm, $keyhash, OPENSSL_RAW_DATA, $ivector);
        
        if ($crypted === FALSE) {
            throw new \Exception(__METHOD__ . ' Failed: ' . openssl_error_string());
        }

        // RETURN THE IV AND THE ENCRYPTED DATA
        return base64_encode($ivector . $crypted);
    }

    public function decrypt($text)
    {
        $key = $this->key;
        $keyhash = openssl_digest($key, $this->digest_method, TRUE);
        $rawdata = base64_decode($text);
        if (strlen($rawdata) < $this->ivector_length) {
            throw new \Exception(__METHOD__ . ' Data is too short');
        }

        // SEPARATE THE IV AND THE ENCRYPTED DATA
        $ivector = substr($rawdata, 0, $this->ivector_length);
        $rawtext = substr($rawdata, $this->ivector_length);
        $decrypt = openssl_decrypt($rawtext, $this->cipher_algorithm, $keyhash, OPENSSL_RAW_DATA, $ivector);

        if ($decrypt === FALSE) {
            throw new \Exception(__METHOD__ . ' Failed: ' . openssl_error_string());
        }

        return $decrypt;
    }
}
