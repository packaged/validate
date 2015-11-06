<?php
namespace Packaged\Validate\Tests;

use Packaged\Validate\BoolValidator;

class BoolValidatorTest extends \PHPUnit_Framework_TestCase
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
      false
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
      ''
    ];

    foreach($okValues as $value)
    {
      $this->assertTrue($validator->validate($value));
    }
    foreach($notOkValues as $value)
    {
      $this->assertFalse($validator->validate($value));
    }
  }

  public function testTidy()
  {
    $validator = new BoolValidator();
    $trueValues = [
      'true',
      'True',
      'TRUE',
      '1',
      1,
      true,
      ];
    $falseValues =[
      'false',
      'False',
      'FALSE',
      '0',
      0,
      false
    ];

    foreach($trueValues as $value)
    {
      $this->assertTrue($validator->tidy($value));
    }
    foreach($falseValues as $value)
    {
      $this->assertFalse($validator->tidy($value));
    }
  }

  public function testFailedTidy()
  {
    $validator = new BoolValidator();
    $this->setExpectedException(\Exception::class, 'Unable to tidy the value');
    $validator->tidy('abc');
  }
}
