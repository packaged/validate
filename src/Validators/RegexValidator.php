<?php
namespace Packaged\Validate\Validators;

use Generator;
use Packaged\Validate\AbstractSerializableValidator;
use Packaged\Validate\SerializableValidator;

class RegexValidator extends AbstractSerializableValidator
{
  protected $_pattern;
  protected $_message;

  /**
   * @param string $pattern
   * @param string $message
   */
  public function __construct($pattern, $message = 'does not match regular expression')
  {
    $this->_pattern = $pattern;
    $this->_message = $message;
  }

  public static function deserialize($configuration): SerializableValidator
  {
    return new static($configuration->pattern, $configuration->message);
  }

  public function serialize(): array
  {
    return [
      'pattern' => $this->_pattern,
      'message' => $this->_message,
    ];
  }

  protected function _doValidate($value): Generator
  {
    if(preg_match($this->_pattern, $value) !== 1)
    {
      yield $this->_makeError($this->_message);
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
