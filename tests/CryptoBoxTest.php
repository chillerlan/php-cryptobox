<?php
/**
 * Class CryptoBoxTest
 *
 * @filesource   CryptoBoxTest.php
 * @created      24.01.2018
 * @package      chillerlan\TraitTest\Crypto
 * @author       Smiley <smiley@chillerlan.net>
 * @copyright    2018 Smiley
 * @license      MIT
 */

namespace chillerlan\TraitTest\Crypto;

use chillerlan\Cryptobox\{
	Box, BoxKeypair, CryptoException, CryptoKeypairInterface, SealedBox, SecretBox, SignedMessage, SignKeypair
};
use PHPUnit\Framework\TestCase;
use SodiumException;

use function extension_loaded, function_exists, sodium_hex2bin, strlen;

use const SODIUM_CRYPTO_BOX_KEYPAIRBYTES, SODIUM_CRYPTO_BOX_PUBLICKEYBYTES, SODIUM_CRYPTO_BOX_SECRETKEYBYTES,
	SODIUM_CRYPTO_SIGN_PUBLICKEYBYTES, SODIUM_CRYPTO_SIGN_SECRETKEYBYTES;

class CryptoBoxTest extends TestCase{

	protected const TESTKEY_BIN                    = "\x00\x01\x02\x03\x04\x05\x06\x07\x08\x09\x0a\x0b\x0c\x0d\x0e\x0f\x10\x11\x12\x13\x14\x15\x16\x17\x18\x19\x1a\x1b\x1c\x1d\x1e\x1f";
	protected const TESTNONCE_BIN                  = "\x00\x00\x00\x00\x00\x00\x00\x00\x00\x00\x00\x00\x00\x00\x00\x00\x00\x00\x00\x00\x00\x00\x00\x01";
	protected const TESTKEY_BOX_SECRET_FROM_SEED   = '3d94eea49c580aef816935762be049559d6d1440dede12e6a125f1841fff8e6f';
	protected const TESTKEY_BOX_PUBLIC_FROM_SEED   = '4701d08488451f545a409fb58ae3e58581ca40ac3f7f114698cd71deac73ca01';
	protected const TESTKEY_BOX_PUBLIC_FROM_SECRET = '8f40c5adb68f25624ae5b214ea767a6ec94d829d3d7b5e1ad1ba6f3e2138285f';
	protected const TESTKEY_SIGN_SECRET_FROM_SEED  = '000102030405060708090a0b0c0d0e0f101112131415161718191a1b1c1d1e1f03a107bff3ce10be1d70dd18e74bc09967e4d6309ba50d5f1ddc8664125531b8';
	protected const TESTKEY_SIGN_PUBLIC_FROM_SEED  = '03a107bff3ce10be1d70dd18e74bc09967e4d6309ba50d5f1ddc8664125531b8';
	protected const TESTMESSAGE                    = 'likes are now florps';

	/**
	 * @var \chillerlan\Cryptobox\CryptoKeypairInterface
	 */
	protected $keypair;

	public function setUp():void{

		if(!extension_loaded('sodium') || !function_exists('sodium_memzero')){
			$this->markTestSkipped('sodium extension (PHP 7.2+) required!');
		}

		$this->keypair = (new BoxKeypair)->create();
	}

	public function testCreateBoxKeypair(){
		$keypair = (new BoxKeypair)->create();

		$this->assertInstanceOf(CryptoKeypairInterface::class, $keypair);
		$this->assertSame($keypair->keypair, $keypair->secret.$keypair->public);
		$this->assertSame(SODIUM_CRYPTO_BOX_KEYPAIRBYTES, strlen($keypair->keypair));
		$this->assertSame(SODIUM_CRYPTO_BOX_SECRETKEYBYTES, strlen($keypair->secret));
		$this->assertSame(SODIUM_CRYPTO_BOX_PUBLICKEYBYTES, strlen($keypair->public));
	}

	public function testCreateBoxKeypairFromSeed(){
		$keypair = (new BoxKeypair)->create($this::TESTKEY_BIN);

		$this->assertSame(sodium_hex2bin($this::TESTKEY_BOX_SECRET_FROM_SEED), $keypair->secret);
		$this->assertSame(sodium_hex2bin($this::TESTKEY_BOX_PUBLIC_FROM_SEED), $keypair->public);
	}

	public function testCreateBoxKeypairInvalidSeed(){
		$this->expectException(CryptoException::class);
		$this->expectExceptionMessage('invalid seed length');

		(new BoxKeypair)->create('0');
	}

	public function testCreateBoxKeypairFromSecret(){
		$keypair = (new BoxKeypair)->createFromSecret($this::TESTKEY_BIN);

		$this->assertSame($this::TESTKEY_BIN, $keypair->secret);
		$this->assertSame(sodium_hex2bin($this::TESTKEY_BOX_PUBLIC_FROM_SECRET), $keypair->public);
	}

	public function testCreateBoxKeypairFromSecretInvalidLength(){
		$this->expectException(CryptoException::class);
		$this->expectExceptionMessage('invalid secret key length');

		(new BoxKeypair)->createFromSecret('0');
	}

	public function testCreateSignKeypair(){
		$keypair = (new SignKeypair)->create();

		$this->assertInstanceOf(CryptoKeypairInterface::class, $keypair);
		$this->assertSame($keypair->keypair, $keypair->secret.$keypair->public);
		$this->assertSame(SODIUM_CRYPTO_SIGN_SECRETKEYBYTES, strlen($keypair->secret));
		$this->assertSame(SODIUM_CRYPTO_SIGN_PUBLICKEYBYTES, strlen($keypair->public));

		unset($keypair);
	}

	public function testCreateSignKeypairFromSeed(){
		$keypair = (new SignKeypair)->create($this::TESTKEY_BIN);

		$this->assertSame(sodium_hex2bin($this::TESTKEY_SIGN_SECRET_FROM_SEED), $keypair->secret);
		$this->assertSame(sodium_hex2bin($this::TESTKEY_SIGN_PUBLIC_FROM_SEED), $keypair->public);
	}

	public function testCreateSignKeypairInvalidSeed(){
		$this->expectException(CryptoException::class);
		$this->expectExceptionMessage('invalid seed length');

		(new SignKeypair)->create('0');
	}

	public function testBox(){
		$e = (new Box($this->keypair))->create($this::TESTMESSAGE, null);
		$d = (new Box($this->keypair))->open($e->box, $e->nonce);

		$this->assertSame($this::TESTMESSAGE, $d->message);
	}

	public function testBoxWithFixedNonce(){
		$e = (new Box($this->keypair))->create($this::TESTMESSAGE, $this::TESTNONCE_BIN);
		$d = (new Box($this->keypair))->open($e->box, $this::TESTNONCE_BIN);

		$this->assertSame($this::TESTMESSAGE, $d->message);
	}

	public function testCreateBoxInvalidMessage(){
		$this->expectException(CryptoException::class);
		$this->expectExceptionMessage('invalid message');

		(new Box($this->keypair))->create('', null);
	}

	public function testCreateBoxInvalidSecret(){
		$this->expectException(CryptoException::class);
		$this->expectExceptionMessage('invalid secret key');

		$keypair = new BoxKeypair(sodium_hex2bin('DEADBEEF'), $this::TESTKEY_BIN);

		(new Box($keypair))->create($this::TESTMESSAGE, null);
	}

	public function testCreateBoxInvalidPublic(){
		$this->expectException(CryptoException::class);
		$this->expectExceptionMessage('invalid public key');

		$keypair = new BoxKeypair($this::TESTKEY_BIN, sodium_hex2bin('DEADBEEF'));

		(new Box($keypair))->create($this::TESTMESSAGE, null);
	}

	public function testSecretBox(){
		$e = (new SecretBox($this->keypair))->create($this::TESTMESSAGE, null);
		$d = (new SecretBox($this->keypair))->open($e->box, $e->nonce);

		$this->assertSame($this::TESTMESSAGE, $d->message);
	}

	public function testSecretBoxWithFixedNonce(){
		$e = (new SecretBox($this->keypair))->create($this::TESTMESSAGE, $this::TESTNONCE_BIN);
		$d = (new SecretBox($this->keypair))->open($e->box, $this::TESTNONCE_BIN);

		$this->assertSame($this::TESTMESSAGE, $d->message);
	}

	public function testCreateSecretBoxInvalidNonce(){
		$this->expectException(SodiumException::class);
		$this->expectExceptionMessage('nonce size should be SODIUM_CRYPTO_SECRETBOX_NONCEBYTES bytes');

		(new SecretBox($this->keypair))->create($this::TESTMESSAGE, 'foo');
	}

	public function testSealedBox(){
		$e = (new SealedBox($this->keypair))->create($this::TESTMESSAGE);
		$d = (new SealedBox($this->keypair))->open($e->box);

		$this->assertSame($this::TESTMESSAGE, $d->message);
	}

	public function testSignMessage(){
		$keypair1 = (new SignKeypair)->create();

		$e1 = (new SignedMessage($keypair1))->create($this::TESTMESSAGE);
		$d1 = (new SignedMessage($keypair1))->open($e1->box);

		$this->assertSame($this::TESTMESSAGE, $d1->message);

		$keypair2 = new SignKeypair(
			sodium_hex2bin($this::TESTKEY_SIGN_SECRET_FROM_SEED),
			sodium_hex2bin($this::TESTKEY_SIGN_PUBLIC_FROM_SEED)
		);

		$e2 = (new SignedMessage($keypair2))->create($this::TESTMESSAGE);
		$d2 = (new SignedMessage($keypair2))->open($e2->box);

		$this->assertSame($this::TESTMESSAGE, $d2->message);
	}

}
