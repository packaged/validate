<?php
namespace Packaged\Validate\Tests;

use Packaged\Validate\Validation;
use Packaged\Validate\Validators\EmailValidator;
use Packaged\Validate\Validators\MultiValidator;
use Packaged\Validate\Validators\StringValidator;
use PHPUnit\Framework\TestCase;

class MultiValidatorTest extends TestCase
{
  public function testMultiValidator()
  {
    $validator = new MultiValidator(new StringValidator(10), new EmailValidator());
    $test1 = $validator->validate('t@jdiio');
    $this->assertNotEmpty($test1);
    $this->assertEquals('Must be at least 10 characters', $test1[0]->getMessage());
    $this->assertEquals('Invalid email address', $test1[1]->getMessage());

    $test2 = $validator->validate('t@jdi.io');
    $this->assertNotEmpty($test2);
    $this->assertEquals('Must be at least 10 characters', $test2[0]->getMessage());

    $test3 = $validator->validate('tom.kay@jdi.io');
    $this->assertEmpty($test3);
  }

  public function testSerialize()
  {
    $validator = new MultiValidator(new StringValidator(10), new EmailValidator());
    $this->assertTrue($validator->isValid('tom.kay@jdi.io'));

    $jsn = json_encode($validator);
    $unsValidator = Validation::fromJsonObject(json_decode($jsn));
    $this->assertInstanceOf(get_class($validator), $unsValidator);
    $this->assertTrue($unsValidator->isValid('tom.kay@jdi.io'));
    $this->assertEquals(json_encode($validator), json_encode($unsValidator));
  }
}
