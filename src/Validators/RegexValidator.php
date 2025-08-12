<?php
namespace Packaged\Validate\Validators;

use Generator;
use Packaged\Validate\AbstractSerializableValidator;
use Packaged\Validate\SerializableValidator;

class RegexValidator extends AbstractSerializableValidator
{
  public const DICT_INVALID = 'invalid';
  protected $_pattern;
  protected $_message;

  protected $_dictionary = [
    self::DICT_INVALID => 'Does not match regular expression',
  ];

  /**
   * @param string $pattern
   */
  public function __construct($pattern)
  {
    $this->_pattern = $pattern;
  }

  public static function deserialize($configuration): SerializableValidator
  {
    return new static($configuration->pattern);
  }

  public function serialize(): array
  {
    return [
      'pattern' => $this->_pattern,
    ];
  }

  protected function _doValidate($value): Generator
  {
    if(preg_match($this->_pattern, $value) !== 1)
    {
      yield $this->_makeError($this->getDictionary()[self::DICT_INVALID]);
    }
  }

  /**
   * @return string
   */
  public function getPattern(): string
  {
    return $this->_pattern;
  }

}
