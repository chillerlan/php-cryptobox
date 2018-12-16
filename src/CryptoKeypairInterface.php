<?php
/**
 * Interface CryptoKeypairInterface
 *
 * @filesource   CryptoKeypairInterface.php
 * @created      24.01.2018
 * @package      chillerlan\Cryptobox
 * @author       Smiley <smiley@chillerlan.net>
 * @copyright    2018 Smiley
 * @license      MIT
 */

namespace chillerlan\Cryptobox;

/**
 * @property string $keypair
 * @property string $secret
 * @property string $public
 */
interface CryptoKeypairInterface{

	/**
	 * @param string|null $seed_bin
	 *
	 * @return \chillerlan\Cryptobox\CryptoKeypairInterface
	 */
	public function create(string $seed_bin = null):CryptoKeypairInterface;

}
