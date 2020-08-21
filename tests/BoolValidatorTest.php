<?php
namespace Packaged\Validate\Tests;

use Packaged\Validate\Validation;
use Packaged\Validate\ValidationException;
use Packaged\Validate\Validators\BoolValidator;
use PHPUnit\Framework\TestCase;

class BoolValidatorTest extends TestCase
{
  public function testValidation()
  {
    $validator = new BoolValidator();
    $okValues = [
      'true',
      'True',
      'TRUE',
      '1',
      1,
      true,
      'false',
      'False',
      'FALSE',
      '0',
      0,
      false,
    ];

    $notOkValues = [
      'abcdef',
      'ABC',
      ' true ',
      ' false',
      10,
      '10',
      new \stdClass(),
      1.1,
      '1.1',
      '',
    ];

    foreach($okValues as $value)
    {
      $this->assertTrue($validator->isValid($value));
    }
    foreach($notOkValues as $value)
    {
      $errors = $validator->validate($value);
      $this->assertNotEmpty($errors);
      $this->assertEquals(1, count($errors));
      $this->assertContainsOnly(ValidationException::class, $errors);
      $this->assertEquals('Invalid boolean value', $errors[0]->getMessage());
    }
  }

  public function testAssertion()
  {
    $val = new BoolValidator();
    $val->assert(true);
    $val->assert(false);
    $this->expectException(ValidationException::class);
    $val->assert('nope');
  }

  public function testSerialize()
  {
    $validator = new BoolValidator();
    $this->assertEquals(true, $validator->isValid(true));

    $jsn = json_encode($validator);
    $unsValidator = Validation::fromJsonObject(json_decode($jsn));
    $this->assertInstanceOf(get_class($validator), $unsValidator);
    $this->assertEquals(true, $validator->isValid(true));
    $this->assertEquals(json_encode($validator), json_encode($unsValidator));
  }
}
