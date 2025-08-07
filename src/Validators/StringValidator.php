<?php
namespace Packaged\Validate\Validators;

use Generator;
use Packaged\Validate\AbstractSerializableValidator;
use Packaged\Validate\SerializableValidator;

class StringValidator extends AbstractSerializableValidator
{
  public const DICT_INVALID = 'invalid';
  public const DICT_MIN = 'min';
  public const DICT_MAX = 'max';

  protected $_minLength;
  protected $_maxLength;

  protected $_dictionary = [
    self::DICT_MIN     => 'must be at least %s characters',
    self::DICT_MAX     => 'must be no more than %s characters',
    self::DICT_INVALID => 'invalid string',
  ];

  /**
   * @param int $minLength Min length in bytes, 0 to disable
   * @param int $maxLength Max length in bytes, 0 to disable
   */
  public function __construct($minLength = 0, $maxLength = 0)
  {
    if(($maxLength > 0) && ($maxLength < $minLength))
    {
      throw new \InvalidArgumentException(
        'maxLength must be greater than or equal to minLength'
      );
    }
    $this->_minLength = $minLength;
    $this->_maxLength = $maxLength;
  }

  public static function deserialize($configuration): SerializableValidator
  {
    return new static($configuration->minLength, $configuration->maxLength);
  }

  public function serialize(): array
  {
    return [
      'minLength' => $this->_minLength,
      'maxLength' => $this->_maxLength,
    ];
  }

  protected function _doValidate($value): Generator
  {
    $len = strlen($value);
    if($len < $this->_minLength)
    {
      $err = str_replace('%s', $this->_minLength, $this->getDictionary()[self::DICT_MIN]);
      yield $this->_makeError($err);
    }
    else if(($this->_maxLength > 0) && ($len > $this->_maxLength))
    {
      $err = str_replace('%s', $this->_maxLength, $this->getDictionary()[self::DICT_MAX]);
      yield $this->_makeError($err);
    }
  }

  /**
   * @return int
   */
  public function getMinLength(): int
  {
    return $this->_minLength;
  }

  /**
   * @return int
   */
  public function getMaxLength(): int
  {
    return $this->_maxLength;
  }
}
