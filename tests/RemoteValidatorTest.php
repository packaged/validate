<?php
namespace Packaged\Validate\Tests;

use Packaged\Validate\ValidationException;
use Packaged\Validate\Validators\EqualValidator;
use Packaged\Validate\Validators\NumberValidator;
use Packaged\Validate\Validators\RemoteValidator;
use PHPUnit\Framework\TestCase;

class RemoteValidatorTest extends TestCase
{
  public function testRemoteValidator()
  {
    $validator = new RemoteValidator('testField');
    $validator->addValidator(new NumberValidator(0, 5), 'prereqField', new EqualValidator('abc'));
    $this->assertTrue($validator->isValid([]));
    $this->assertTrue($validator->isValid(['testField' => 10]));
    $this->assertTrue($validator->isValid(['prereqField' => '']));
    $this->assertTrue($validator->isValid(['prereqField' => '']));
    $this->assertFalse($validator->isValid(['prereqField' => 'abc']));
    $this->assertFalse($validator->isValid(['prereqField' => 'abc', 'testField' => 10]));
    $this->assertTrue($validator->isValid(['prereqField' => 'abc', 'testField' => 3]));

    $this->expectException(ValidationException::class);
    $this->expectExceptionMessage('not a valid value for a dataset validator');
    $validator->assert('invalid');
  }
}
