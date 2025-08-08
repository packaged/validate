<?php
namespace Packaged\Validate\Validators;

use Generator;
use Packaged\Validate\AbstractSerializableValidator;
use Packaged\Validate\IValidator;
use Packaged\Validate\SerializableValidator;
use Packaged\Validate\Validation;

class ArrayValidator extends AbstractSerializableValidator
{
  public const DICT_INVALID = 'invalid';
  public const DICT_MIN = 'min';
  public const DICT_MAX = 'max';

  protected $_dictionary = [
    self::DICT_INVALID => 'must be an array',
    self::DICT_MIN     => 'must contain at least %s items',
    self::DICT_MAX     => 'must not contain more than %s items',
  ];

  protected $_validator;
  protected $_minCount;
  protected $_maxCount;

  public function __construct(IValidator $validator, $minCount = 0, $maxCount = 0)
  {
    $this->_validator = $validator;
    $this->_minCount = $minCount;
    $this->_maxCount = $maxCount;
  }

  public static function deserialize($configuration): SerializableValidator
  {
    return new static(
      Validation::fromJsonObject($configuration->validator),
      $configuration->minCount,
      $configuration->maxCount
    );
  }

  public function serialize(): array
  {
    return [
      'validator' => $this->_validator,
      'minCount'  => $this->_minCount,
      'maxCount'  => $this->_maxCount,
    ];
  }

  protected function _doValidate($value): Generator
  {
    if(!is_array($value))
    {
      return $this->_makeError($this->getDictionary()[self::DICT_INVALID]);
    }

    $numItems = count($value);
    if($numItems < $this->_minCount)
    {
      $err = str_replace('%s', $this->_minCount, $this->getDictionary()[self::DICT_MIN]);
      return $this->_makeError($err);
    }

    if(($this->_maxCount > 0) && ($numItems > $this->_maxCount))
    {
      $err = str_replace('%s', $this->_maxCount, $this->getDictionary()[self::DICT_MAX]);
      return $this->_makeError($err);
    }

    foreach($value as $idx => $entry)
    {
      foreach($this->_validator->validate($entry) as $error)
      {
        yield $error;
      }
    }
  }

  /**
   * @return int
   */
  public function getMinCount(): int
  {
    return $this->_minCount;
  }

  /**
   * @return int
   */
  public function getMaxCount(): int
  {
    return $this->_maxCount;
  }
}
