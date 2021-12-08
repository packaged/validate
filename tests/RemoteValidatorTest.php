<?php
namespace Packaged\Validate\Tests;

use Packaged\Validate\Validators\EqualValidator;
use Packaged\Validate\Validators\NumberValidator;
use Packaged\Validate\Validators\RemoteValidator;
use PHPUnit\Framework\TestCase;

class RemoteValidatorTest extends TestCase
{
  /**
   * @dataProvider provider
   */
  public function testRemoteValidator(array $data, bool $expect)
  {
    $validator = new RemoteValidator('testField');
    $validator->addValidator(new NumberValidator(0, 5), 'prereqField', new EqualValidator('abc'));
    $validator->setData($data);

    $this->assertEquals($expect, $validator->isValid($data['testField'] ?? null));
  }

  public function provider()
  {
    return [
      [['testField' => 10], true],
      [['prereqField' => ''], true],
      [['prereqField' => ''], true],
      [['prereqField' => 'abc'], false],
      [['prereqField' => 'abc', 'testField' => 10], false],
      [['prereqField' => 'abc', 'testField' => 3], true],
    ];
  }
}
