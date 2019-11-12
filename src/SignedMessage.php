<?php
/**
 * Class SignedMessage
 *
 * @filesource   SignedMessage.php
 * @created      25.01.2018
 * @package      chillerlan\Cryptobox
 * @author       Smiley <smiley@chillerlan.net>
 * @copyright    2018 Smiley
 * @license      MIT
 */

namespace chillerlan\Cryptobox;

use function sodium_crypto_sign, sodium_crypto_sign_open, sodium_memzero;

use const SODIUM_CRYPTO_SIGN_PUBLICKEYBYTES, SODIUM_CRYPTO_SIGN_SECRETKEYBYTES;

class SignedMessage extends CryptoBoxAbstract{

	/** @inheritdoc */
	public function create(string $message):CryptoBoxInterface{
		$this->checkKeypair(SODIUM_CRYPTO_SIGN_SECRETKEYBYTES);

		$this->box = sodium_crypto_sign($this->checkMessage($message), $this->keypair->secret);

		sodium_memzero($message);

		return $this;
	}

	/**
	 * @inheritdoc
	 *
	 * @throws \chillerlan\Cryptobox\CryptoException
	 */
	public function open(string $box_bin):CryptoBoxInterface{
		$this->checkKeypair(null, SODIUM_CRYPTO_SIGN_PUBLICKEYBYTES);

		$this->message = sodium_crypto_sign_open($box_bin, $this->keypair->public);

		sodium_memzero($box_bin);

		if($this->message !== false){
			return $this;
		}

		throw new CryptoException('invalid box'); // @codeCoverageIgnore
	}

}
