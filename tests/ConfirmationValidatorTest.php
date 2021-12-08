<?php
namespace Packaged\Validate\Tests;

use Packaged\Validate\Validators\ConfirmationValidator;
use PHPUnit\Framework\TestCase;

class ConfirmationValidatorTest extends TestCase
{
  /**
   * @dataProvider provider
   */
  public function testConfirmationValidator(array $data, bool $expect)
  {
    $validator = new ConfirmationValidator('field2');
    $validator->setData($data);

    $this->assertEquals($expect, $validator->isValid($data['field1'] ?? null));
  }

  public function provider()
  {
    return [
      [['field1' => 10], false],
      [['field2' => ''], false],
      [['field1' => 'yes', 'field2' => 'no'], false],
      [['field1' => 'no', 'field2' => 'yes'], false],
      [['field1' => 'test', 'field2' => 'test'], true],
      [['field1' => 123, 'field2' => 123], true],
    ];
  }
}
