<?php
/**
 * Class SecretBox
 *
 * @filesource   SecretBox.php
 * @created      25.01.2018
 * @package      chillerlan\Cryptobox
 * @author       Smiley <smiley@chillerlan.net>
 * @copyright    2018 Smiley
 * @license      MIT
 */

namespace chillerlan\Cryptobox;

class SecretBox extends CryptoBoxAbstract{

	/**
	 * @param string      $message
	 * @param string|null $nonce_bin
	 *
	 * @return \chillerlan\Cryptobox\CryptoBoxInterface
	 */
	public function create(string $message, string $nonce_bin = null):CryptoBoxInterface{
		$this->checkKeypair(SODIUM_CRYPTO_BOX_SECRETKEYBYTES);

		$message     = $this->checkMessage($message);
		$this->nonce = $nonce_bin ?? random_bytes(SODIUM_CRYPTO_BOX_NONCEBYTES);
		$this->box   = sodium_crypto_secretbox($message, $this->nonce, $this->keypair->secret);

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
		$this->checkKeypair(SODIUM_CRYPTO_BOX_SECRETKEYBYTES);

		$this->message = sodium_crypto_secretbox_open($box_bin, $nonce_bin, $this->keypair->secret);

		if($this->message !== false){
			return $this;
		}

		throw new CryptoException('invalid box'); // @codeCoverageIgnore
	}

}
