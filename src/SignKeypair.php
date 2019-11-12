<?php
/**
 * Class SignKeypair
 *
 * @filesource   SignKeypair.php
 * @created      24.01.2018
 * @package      chillerlan\Cryptobox
 * @author       Smiley <smiley@chillerlan.net>
 * @copyright    2018 Smiley
 * @license      MIT
 */

namespace chillerlan\Cryptobox;

use function sodium_crypto_sign_keypair, sodium_crypto_sign_publickey, sodium_crypto_sign_secretkey,
	sodium_crypto_sign_seed_keypair, sodium_memzero, strlen;

use const SODIUM_CRYPTO_SIGN_SEEDBYTES;

class SignKeypair extends CryptoKeypairAbstract{

	/**
	 * @inheritdoc
	 *
	 * @throws \chillerlan\Cryptobox\CryptoException
	 */
	public function create(string $seed_bin = null):CryptoKeypairInterface{

		if($seed_bin !== null && strlen($seed_bin) !== SODIUM_CRYPTO_SIGN_SEEDBYTES){
			throw new CryptoException('invalid seed length');
		}

		$keypair = $seed_bin
			? sodium_crypto_sign_seed_keypair($seed_bin)
			: sodium_crypto_sign_keypair();

		$this->keypair = $keypair;
		$this->secret  = sodium_crypto_sign_secretkey($keypair);
		$this->public  = sodium_crypto_sign_publickey($keypair);

		sodium_memzero($keypair);

		if($seed_bin !== null){
			sodium_memzero($seed_bin);
		}

		return $this;
	}

}
