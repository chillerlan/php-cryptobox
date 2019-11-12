# chillerlan/php-cryptobox

A stupid simple wrapper around the [sodium](https://www.php.net/manual/en/ref.sodium.php) *_box functions.

[![version][packagist-badge]][packagist]
[![license][license-badge]][license]
[![Travis][travis-badge]][travis]
[![Coverage][coverage-badge]][coverage]
[![Scrunitizer][scrutinizer-badge]][scrutinizer]
[![Packagist downloads][downloads-badge]][downloads]
[![PayPal donate][donate-badge]][donate]

[packagist-badge]: https://img.shields.io/packagist/v/chillerlan/php-cryptobox.svg?style=flat-square
[packagist]: https://packagist.org/packages/chillerlan/php-cryptobox
[license-badge]: https://img.shields.io/github/license/chillerlan/php-cryptobox.svg?style=flat-square
[license]: https://github.com/chillerlan/php-cryptobox/blob/master/LICENSE
[travis-badge]: https://img.shields.io/travis/chillerlan/php-cryptobox.svg?style=flat-square
[travis]: https://travis-ci.org/chillerlan/php-cryptobox
[coverage-badge]: https://img.shields.io/codecov/c/github/chillerlan/php-cryptobox.svg?style=flat-square
[coverage]: https://codecov.io/github/chillerlan/php-cryptobox
[scrutinizer-badge]: https://img.shields.io/scrutinizer/g/chillerlan/php-cryptobox.svg?style=flat-square
[scrutinizer]: https://scrutinizer-ci.com/g/chillerlan/php-cryptobox
[downloads-badge]: https://img.shields.io/packagist/dt/chillerlan/php-cryptobox.svg?style=flat-square
[downloads]: https://packagist.org/packages/chillerlan/php-cryptobox/stats
[donate-badge]: https://img.shields.io/badge/donate-paypal-ff33aa.svg?style=flat-square
[donate]: https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=WLYUNAT9ZTJZ4

## Requirements
- PHP 7.2+
  - the [Sodium](http://php.net/manual/book.sodium.php) extension


- `CryptoBox`
  - `CryptoBoxInterface`: `Box`, `SecretBox`, `SealedBox`, `SignedMessage`
  - `CryptoKeypairInterface`: `BoxKeypair`, `SignKeypair`
