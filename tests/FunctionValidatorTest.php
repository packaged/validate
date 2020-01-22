<?php

namespace Packaged\Validate\Tests;

use Packaged\Validate\ValidationException;
use Packaged\Validate\Validators\FunctionValidator;
use PHPUnit\Framework\TestCase;

class FunctionValidatorTest extends TestCase
{
  public function provider()
  {
    $bool = function ($test) {
      return function ($value) use ($test) {
        return $value === $test;
      };
    };
    $exception = function ($test) {
      return function ($value) use ($test) {
        return $value === $test ? null : new ValidationException('failed');
      };
    };
    $generator = function ($test) {
      return function ($value) use ($test) {
        if($value !== $test)
        {
          yield new ValidationException('failed');
        }
      };
    };
    $string = function ($test) {
      return function ($value) use ($test) {
        return $value === $test ? null : 'failed';
      };
    };
    return [
      [
        'test',
        $bool('test'),
        true,
      ],
      [
        'test',
        $bool('wrong'),
        false,
      ],
      [
        'test',
        $exception('test'),
        true,
      ],
      [
        'test',
        $exception('wrong'),
        false,
      ],
      [
        'test',
        $generator('test'),
        true,
      ],
      [
        'test',
        $generator('wrong'),
        false,
      ],
      [
        'test',
        $string('test'),
        true,
      ],
      [
        'test',
        $string('wrong'),
        false,
      ],
    ];
  }

  /**
   * @dataProvider provider
   *
   * @param $testValue
   * @param $fn
   * @param $expectedValid
   */
  public function testFunctionValidator($testValue, $fn, $expectedValid)
  {
    $validator = new FunctionValidator($fn);
    $this->assertEquals($expectedValid, $validator->isValid($testValue));
  }
}
