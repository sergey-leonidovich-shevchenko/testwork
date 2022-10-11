<?php declare(strict_types=1);

namespace Parser;

use LogicException;
use Parser\Contracts\TokenizerInterface as ITokenizer;
use Parser\Enum\TokenType as ETokenType;


class HtmlTokenizer implements ITokenizer
{
  private const HTML_TAG_REGEX = '/<\/?+\w[^>]*>/i';
  private const HTML_OPEN_CLOSE_TAG = '/^<(\\/?+)([-_a-zA-Z0-9]++)([^>]*+)>$/';

  /** @var TokenList  */
  private TokenList $_tokens;

  /** @var string  */
  private string $text;

  /**
   * @param string $text Входной текст.
   */
  public function __construct(string $text)
  {
    $this->_tokens = new TokenList();
    $this->text = $text;
  }

  /**
   * @inheritDoc
   */
  public function getTokens(): TokenList
  {
    if ($this->_tokens->count() === 0) {
      $this->tokenize();
    }

    return $this->_tokens;
  }

  /**
   * Tokenize input data.
   *
   * @return void
   */
  private function tokenize(): void
  {
    $matches = [];

    $currentPosition = 0;

    while (preg_match(self::HTML_TAG_REGEX, $this->text, $matches, PREG_OFFSET_CAPTURE, $currentPosition) === 1) {
      $match = $matches[0][0];
      $lastPosition = $matches[0][1];

      if ($lastPosition > $currentPosition) {
        $this->unmatched(substr($this->text, $currentPosition, $lastPosition - $currentPosition));
      }

      $this->matched($match);

      $currentPosition = $lastPosition + strlen($match);

      if ($currentPosition == $lastPosition) {
        throw new LogicException('Parse pattern should not allow empty string');
      }
    }

    if ($currentPosition != strlen($this->text)) {
      $this->unmatched(substr($this->text, $currentPosition));
    }

    $token = (new Token())->setType(ETokenType::T_END);
    $this->_tokens->addToken($token);
  }

  /**
   * Обработка, если найдено совпадение с тегом.
   *
   * @param string $text
   * @return void
   */
  private function matched(string $text): void
  {
    $matches = [];

    if (preg_match(self::HTML_OPEN_CLOSE_TAG, $text, $matches) !== 1) {
      throw new LogicException('Tag pattern mismatch on \'' . $text . '\'');
    }

    $type = (empty($matches[1])) ? ETokenType::T_OPEN : ETokenType::T_CLOSE;
    $token = (new Token())
      ->setType($type)
      ->setName($matches[2])
      ->setText($text);
    $this->_tokens->addToken($token);
  }

  /**
   * Обработка, если найдено совпадение с содержимым тега.
   *
   * @param string $text
   * @return void
   */
  private function unmatched(string $text): void
  {
    $token = (new Token())
      ->setType(ETokenType::T_TEXT)
      ->setText($text);
    $this->_tokens->addToken($token);
  }
}
