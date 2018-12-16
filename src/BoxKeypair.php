<?php
/**
 * Class BoxKeypair
 *
 * @filesource   BoxKeypair.php
 * @created      24.01.2018
 * @package      chillerlan\Cryptobox
 * @author       Smiley <smiley@chillerlan.net>
 * @copyright    2018 Smiley
 * @license      MIT
 */

namespace chillerlan\Cryptobox;

class BoxKeypair extends CryptoKeypairAbstract{

	/**
	 * @inheritdoc
	 *
	 * @throws \chillerlan\Cryptobox\CryptoException
	 */
	public function create(string $seed_bin = null):CryptoKeypairInterface{

		if($seed_bin !== null && strlen($seed_bin) !== SODIUM_CRYPTO_BOX_SEEDBYTES){
			throw new CryptoException('invalid seed length');
		}

		$this->keypair = $seed_bin
			? sodium_crypto_box_seed_keypair($seed_bin)
			: sodium_crypto_box_keypair();

		$this->secret  = sodium_crypto_box_secretkey($this->keypair);
		$this->public  = sodium_crypto_box_publickey($this->keypair);

		if($seed_bin !== null){
			sodium_memzero($seed_bin);
		}

		return $this;
	}

	/**
	 * @param string $secret_bin
	 *
	 * @return \chillerlan\Cryptobox\CryptoKeypairInterface
	 * @throws \chillerlan\Cryptobox\CryptoException
	 */
	public function createFromSecret(string $secret_bin):CryptoKeypairInterface{

		if(strlen($secret_bin) !== SODIUM_CRYPTO_BOX_SECRETKEYBYTES){
			throw new CryptoException('invalid secret key length');
		}

		$this->secret  = $secret_bin;
		$this->public  = sodium_crypto_box_publickey_from_secretkey($this->secret);
		$this->keypair = $this->secret.$this->public;

		sodium_memzero($secret_bin);

		return $this;
	}

}
