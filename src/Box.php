<?php
/**
 * Class Box
 *
 * @filesource   Box.php
 * @created      25.01.2018
 * @package      chillerlan\Cryptobox
 * @author       Smiley <smiley@chillerlan.net>
 * @copyright    2018 Smiley
 * @license      MIT
 */

namespace chillerlan\Cryptobox;

use function random_bytes, sodium_crypto_box, sodium_crypto_box_keypair_from_secretkey_and_publickey,
	sodium_crypto_box_open, sodium_memzero;

use const SODIUM_CRYPTO_BOX_NONCEBYTES, SODIUM_CRYPTO_BOX_PUBLICKEYBYTES, SODIUM_CRYPTO_BOX_SECRETKEYBYTES;

class Box extends CryptoBoxAbstract{

	/**
	 * @param string      $message
	 * @param string|null $nonce_bin
	 *
	 * @return \chillerlan\Cryptobox\CryptoBoxInterface
	 */
	public function create(string $message, string $nonce_bin = null):CryptoBoxInterface{
		$this->checkKeypair(SODIUM_CRYPTO_BOX_SECRETKEYBYTES, SODIUM_CRYPTO_BOX_PUBLICKEYBYTES);

		$message     = $this->checkMessage($message);
		$keypair     = sodium_crypto_box_keypair_from_secretkey_and_publickey($this->keypair->secret, $this->keypair->public);
		$this->nonce = $nonce_bin ?? random_bytes(SODIUM_CRYPTO_BOX_NONCEBYTES);
		$this->box   = sodium_crypto_box($message, $this->nonce, $keypair);

		sodium_memzero($keypair);
		sodium_memzero($message);

		if($nonce_bin !== null){
			sodium_memzero($nonce_bin);
		}

		return $this;
	}

	/**
	 * @param string $box_bin
	 * @param string $nonce_bin
	 *
	 * @return \chillerlan\Cryptobox\CryptoBoxInterface
	 * @throws \chillerlan\Cryptobox\CryptoException
	 */
	public function open(string $box_bin, string $nonce_bin):CryptoBoxInterface{
		$this->checkKeypair(SODIUM_CRYPTO_BOX_SECRETKEYBYTES, SODIUM_CRYPTO_BOX_PUBLICKEYBYTES);

		$keypair       = sodium_crypto_box_keypair_from_secretkey_and_publickey($this->keypair->secret, $this->keypair->public);
		$this->message = sodium_crypto_box_open($box_bin, $nonce_bin, $keypair);

		sodium_memzero($keypair);
		sodium_memzero($box_bin);
		sodium_memzero($nonce_bin);

		if($this->message !== false){
			return $this;
		}

		throw new CryptoException('invalid box'); // @codeCoverageIgnore
	}

}
