<?php
namespace Packaged\Validate\Tests;

use Packaged\Validate\Validation;
use Packaged\Validate\Validators\IPv4AddressValidator;
use PHPUnit\Framework\TestCase;

class IPv4ValidatorTest extends TestCase
{
  public function ipv4Provider()
  {
    return [
      ['0.0.0.0', true],
      ['1.2.3.4', true],
      ['255.255.255.255', true],
      ['256.255.255.255', false],
      ['255.256.255.255', false],
      ['255.255.256.255', false],
      ['255.255.255.256', false],
      ['31.32.33.34', true],
      ['a.b.c.d', false],
    ];
  }

  /**
   * @dataProvider ipv4Provider
   *
   * @param string $address
   * @param bool   $isValid
   */
  public function testIpv4Validator($address, $isValid)
  {
    $validator = new IPv4AddressValidator();
    $errors = $validator->validate($address);
    if($isValid)
    {
      $this->assertEmpty($errors);
    }
    else
    {
      $this->assertNotEmpty($errors);
    }
  }

  public function testSerialize()
  {
    $validator = new IPv4AddressValidator();
    $this->assertTrue($validator->isValid('255.255.255.255'));

    $jsn = json_encode($validator);
    $unsValidator = Validation::fromJsonObject(json_decode($jsn));
    $this->assertInstanceOf(get_class($validator), $unsValidator);
    $this->assertTrue($validator->isValid('255.255.255.255'));
    $this->assertEquals(json_encode($validator), json_encode($unsValidator));
  }
}
