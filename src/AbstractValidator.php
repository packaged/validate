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
    if($asString && (!is_string($this->_lastError)))
    {
      return $this->_errorsToString($this->_lastError);
    }
    return $this->_lastError;
  }

  private function _errorsToString($errors, $indent = '')
  {
    $lines = [];
    foreach($errors as $name => $message)
    {
      $line = $indent . $name . ': ';

      if(is_array($message))
      {
        $line .= "\n" . $this->_errorsToString($message, $indent . '  ');
      }
      else
      {
        $line .= $message;
      }
      $lines[] = $line;
    }
    return implode("\n", $lines);
  }

  protected function _setLastError($error)
  {
    $this->_lastError = $error;
  }
}
