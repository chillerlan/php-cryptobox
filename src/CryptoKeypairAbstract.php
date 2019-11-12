<?php
/**
 * Class CryptoKeypairAbstract
 *
 * @filesource   CryptoKeypairAbstract.php
 * @created      24.01.2018
 * @package      chillerlan\Cryptobox
 * @author       Smiley <smiley@chillerlan.net>
 * @copyright    2018 Smiley
 * @license      MIT
 */

namespace chillerlan\Cryptobox;

use function property_exists;

/**
 * @link https://paragonie.com/book/pecl-libsodium/read/00-intro.md
 * @link https://paragonie.com/book/pecl-libsodium/read/01-quick-start.md
 */
abstract class CryptoKeypairAbstract implements CryptoKeypairInterface{

	/**
	 * @var string
	 */
	protected $keypair;

	/**
	 * @var string
	 */
	protected $secret;

	/**
	 * @var string
	 */
	protected $public;

	/**
	 * CryptoKeypairAbstract constructor.
	 *
	 * @param string|null $secret_bin
	 * @param string|null $public_bin
	 */
	public function __construct(string $secret_bin = null, string $public_bin = null){
		$this->secret = $secret_bin;
		$this->public = $public_bin;
	}

	/**
	 * @param string $property
	 *
	 * @return mixed|null
	 */
	public function __get(string $property){
		return property_exists($this, $property) ? $this->{$property} : null;
	}

}
