<?php
namespace Packaged\Validate\Tests;

use Packaged\Validate\ValidationException;
use Packaged\Validate\Validators\StringValidator;
use PHPUnit\Framework\TestCase;

class StringValidatorTest extends TestCase
{
  public function dataProvider()
  {
    return [
      [0, 0],
      [0, 1],
      [0, 2],
      [0, 9],
      [0, 10],
      [0, 99],
      [0, 100],
      [1, 0],
      [10, 0],
      [11, 0],
      [100, 0],
      [101, 0],
      [5, 15],
      [15, 5],
    ];
  }

  /**
   * @param $minLen
   * @param $maxLen
   *
   * @dataProvider dataProvider
   */
  public function testStringValidator($minLen, $maxLen)
  {
    if(($maxLen > 0) && ($minLen > $maxLen))
    {
      $this->expectException(\InvalidArgumentException::class);
    }
    $validator = new StringValidator($minLen, $maxLen);

    $strings = [
      '',
      'a',
      str_repeat('a', 10),
      str_repeat('a', 100),
    ];

    foreach($strings as $string)
    {
      $errors = $validator->validate($string);

      $expected = (strlen($string) >= $minLen) && (($maxLen <= 0) || (strlen($string) <= $maxLen));
      if($expected)
      {
        $this->assertEmpty($errors);
      }
      else
      {
        $this->assertNotEmpty($errors);
        $this->assertEquals(1, count($errors));
        $this->assertContainsOnly(ValidationException::class, $errors);
      }
    }
  }
}
