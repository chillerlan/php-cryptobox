<?php
/**
 * Interface CryptoBoxInterface
 *
 * @filesource   CryptoBoxInterface.php
 * @created      24.01.2018
 * @package      chillerlan\Cryptobox
 * @author       Smiley <smiley@chillerlan.net>
 * @copyright    2018 Smiley
 * @license      MIT
 */

namespace chillerlan\Cryptobox;

/**
 * @property \chillerlan\Cryptobox\CryptoKeypairInterface $keypair
 * @property string                                       $box
 * @property string                                       $nonce
 * @property string                                       $message
 */
interface CryptoBoxInterface{

	/**
	 * @param string $message
	 *
	 * @return \chillerlan\Cryptobox\CryptoBoxInterface
	 */
	public function create(string $message):CryptoBoxInterface;

}
