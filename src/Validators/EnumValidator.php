<?php
namespace Packaged\Validate\Validators;

use Generator;
use Packaged\Validate\AbstractSerializableValidator;
use Packaged\Validate\SerializableValidator;

class EnumValidator extends AbstractSerializableValidator
{
  protected $_allowedValues;
  protected $_caseSensitive;
  protected $_negate;

  /**
   * @param string[] $allowedValues
   * @param bool     $caseSensitive
   */
  public function __construct(array $allowedValues, $caseSensitive = false)
  {
    $this->_allowedValues = $allowedValues;
    $this->_caseSensitive = $caseSensitive;
  }

  public function negate(bool $bool = true)
  {
    $this->_negate = $bool;
    return $this;
  }

  public static function deserialize($configuration): SerializableValidator
  {
    $o = new static($configuration->allowedValues, $configuration->caseSensitive);
    if($configuration->negate ?? false)
    {
      $o->negate();
    }
    return $o;
  }

  public function serialize(): array
  {
    return [
      'allowedValues' => $this->_allowedValues,
      'caseSensitive' => $this->_caseSensitive,
      'negate'        => $this->_negate,
    ];
  }

  protected function _doValidate($value): Generator
  {
    if(empty($this->_getAllowedValues()))
    {
      if($this->_negate xor ($value !== null && $value !== ''))
      {
        return $this->_makeError('not a valid value');
      }
      return null;
    }

    if($this->isCaseSensitive())
    {
      if($this->_negate xor !in_array($value, $this->_getAllowedValues()))
      {
        return $this->_makeError('not a valid value');
      }
      return null;
    }

    $result = false;
    foreach($this->_getAllowedValues() as $allowedValue)
    {
      if(strcasecmp($allowedValue, $value) == 0)
      {
        $result = true;
        break;
      }
    }
    if($this->_negate xor !$result)
    {
      yield $this->_makeError('not a valid value');
    }
  }

  protected function _getAllowedValues()
  {
    return $this->_allowedValues;
  }

  public function getAllowedValues(): array
  {
    return $this->_getAllowedValues();
  }

  public function isCaseSensitive(): bool
  {
    return $this->_caseSensitive;
  }

}
