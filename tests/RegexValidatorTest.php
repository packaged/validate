<?php
namespace Packaged\Validate\Tests;

use Packaged\Validate\Validators\RegexValidator;
use PHPUnit\Framework\TestCase;

class RegexValidatorTest extends TestCase
{
  public function testRegexValidatorMessage()
  {
    $v1 = new RegexValidator('/^[0-9]{6}$/');
    $v2 = new RegexValidator('/^[0-9]{6}$/', 'test failure message');
    $v1err = $v1->validate('123');
    $this->assertNotEmpty($v1err);
    $this->assertEquals('does not match regular expression', $v1err[0]->getMessage());
    $v2err = $v2->validate('123');
    $this->assertNotEmpty($v2err);
    $this->assertEquals('test failure message', $v2err[0]->getMessage());
  }
}
