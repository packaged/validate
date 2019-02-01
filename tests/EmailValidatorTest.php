<?php
namespace Packaged\Validate\Tests;

use Packaged\Validate\Validators\EmailValidator;
use PHPUnit\Framework\TestCase;

class EmailValidatorTest extends TestCase
{
  public function emailProvider()
  {
    return [
      ['test@here', false],
      ['test@test.com', true],
      ['TeSt@TeSt.CoM', true],
      ['123-test.user.one@test.domain.com', true],
      ['-_@-.co', true],
      ['.test@test.com', false],
    ];
  }

  /**
   * @dataProvider emailProvider
   *
   * @param string $emailAddress
   * @param bool   $isValid
   */
  public function testEmailValidator($emailAddress, $isValid)
  {
    $validator = new EmailValidator();
    $errors = $validator->validate($emailAddress);
    if($isValid)
    {
      $this->assertEmpty($errors);
    }
    else
    {
      $this->assertNotEmpty($errors);
    }
  }
}
