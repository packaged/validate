<?php
namespace Packaged\Validate;

abstract class AbstractValidator implements IValidator
{
  /**
   * @var bool|string|string[]
   */
  private $_lastError = false;

  /**
   * @param bool $asString If true then implode an array of errors into a string
   *
   * @return string|string[]
   */
  public function getLastError($asString = false)
  {
    if($asString && is_array($this->_lastError))
    {
      $lines = [];
      foreach($this->_lastError as $name => $error)
      {
        $lines[] = $name . ': ' . $error;
      }
      return implode(', ', $lines);
    }
    return $this->_lastError;
  }

  protected function _setLastError($error)
  {
    $this->_lastError = $error;
  }
}
