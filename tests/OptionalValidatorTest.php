<?php
namespace Packaged\Validate\Tests;

use Packaged\Validate\Validators\EqualValidator;
use Packaged\Validate\Validators\OptionalValidator;
use PHPUnit\Framework\TestCase;

class OptionalValidatorTest extends TestCase
{
  public function testEmptyValues()
  {
    $eValidator = new EqualValidator('correct');
    $this->assertFalse($eValidator->isValid(null));
    $this->assertFalse($eValidator->isValid(''));
    $this->assertFalse($eValidator->isValid('wrong'));
    $this->assertTrue($eValidator->isValid('correct'));

    $oValidator = new OptionalValidator($eValidator);
    $this->assertTrue($oValidator->isValid(null));
    $this->assertFalse($oValidator->isValid(''));
    $this->assertFalse($oValidator->isValid('wrong'));
    $this->assertTrue($oValidator->isValid('correct'));
  }
}
