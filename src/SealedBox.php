<?php
/**
 * Class SealedBox
 *
 * @filesource   SealedBox.php
 * @created      25.01.2018
 * @package      chillerlan\Cryptobox
 * @author       Smiley <smiley@chillerlan.net>
 * @copyright    2018 Smiley
 * @license      MIT
 */

namespace chillerlan\Cryptobox;

use function sodium_crypto_box_keypair_from_secretkey_and_publickey, sodium_crypto_box_seal,
	sodium_crypto_box_seal_open, sodium_memzero;

use const SODIUM_CRYPTO_BOX_PUBLICKEYBYTES, SODIUM_CRYPTO_BOX_SECRETKEYBYTES;

class SealedBox extends CryptoBoxAbstract{

	/** @inheritdoc */
	public function create(string $message):CryptoBoxInterface{
		$this->checkKeypair(null, SODIUM_CRYPTO_BOX_PUBLICKEYBYTES);

		$this->box = sodium_crypto_box_seal($this->checkMessage($message), $this->keypair->public);

		sodium_memzero($message);

		return $this;
	}

	/**
	 * @inheritdoc
	 *
	 * @throws \chillerlan\Cryptobox\CryptoException
	 */
	public function open(string $box_bin):CryptoBoxInterface{
		$this->checkKeypair(SODIUM_CRYPTO_BOX_SECRETKEYBYTES, SODIUM_CRYPTO_BOX_PUBLICKEYBYTES);

		$keypair       = sodium_crypto_box_keypair_from_secretkey_and_publickey($this->keypair->secret, $this->keypair->public);
		$this->message = sodium_crypto_box_seal_open($box_bin, $keypair);

		sodium_memzero($keypair);
		sodium_memzero($box_bin);

		if($this->message !== false){
			return $this;
		}

		throw new CryptoException('invalid box'); // @codeCoverageIgnore
	}

}
