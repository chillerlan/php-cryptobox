<?php
/**
 * Class CryptoBoxAbstract
 *
 * @filesource   CryptoBoxAbstract.php
 * @created      25.01.2018
 * @package      chillerlan\Cryptobox
 * @author       Smiley <smiley@chillerlan.net>
 * @copyright    2018 Smiley
 * @license      MIT
 */

namespace chillerlan\Cryptobox;

use function property_exists, strlen, trim;

/**
 * @link https://paragonie.com/book/pecl-libsodium/read/00-intro.md
 * @link https://paragonie.com/book/pecl-libsodium/read/01-quick-start.md
 */
abstract class CryptoBoxAbstract implements CryptoBoxInterface{

	/**
	 * @var \chillerlan\Cryptobox\CryptoKeypairInterface
	 */
	protected $keypair;

	/**
	 * @var string
	 */
	protected $box;

	/**
	 * @var string
	 */
	protected $nonce;

	/**
	 * @var string
	 */
	protected $message;

	/**
	 * CryptoBoxAbstract constructor.
	 *
	 * @param \chillerlan\Cryptobox\CryptoKeypairInterface|null $keypair
	 */
	public function __construct(CryptoKeypairInterface $keypair = null){
		$this->keypair = $keypair;
	}

	/**
	 * @param string $property
	 *
	 * @return mixed|null
	 */
	public function __get(string $property){
		return property_exists($this, $property) ? $this->{$property} : null;
	}

	/**
	 * @param int $secretLength
	 * @param int $publicLength
	 *
	 * @return void
	 * @throws \chillerlan\Cryptobox\CryptoException
	 */
	protected function checkKeypair(int $secretLength = null, int $publicLength = null):void{

		if($secretLength !== null && (!$this->keypair->secret || strlen($this->keypair->secret) !== $secretLength)){
			throw new CryptoException('invalid secret key');
		}

		if($publicLength !== null && (!$this->keypair->public || strlen($this->keypair->public) !== $publicLength)){
			throw new CryptoException('invalid public key');
		}

	}

	/**
	 * @param string $message
	 *
	 * @return string
	 * @throws \chillerlan\Cryptobox\CryptoException
	 */
	protected function checkMessage(string $message):string{
		$message = trim($message);

		if(empty($message)){
			throw new CryptoException('invalid message');
		}

		// @todo: padding?
		return $message;
	}

}
