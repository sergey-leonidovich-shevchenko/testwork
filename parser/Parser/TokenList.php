<?php declare(strict_types=1);

namespace Parser;


use Countable;
use Iterator;

class TokenList implements Countable, Iterator
{
  /**
   * @var Token[]
   */
  private array $_tokens = [];

  /**
   * @var int
   */
  private int $_currentIndex = 0;

  /**
   * @param Token $token
   * @return void
   */
  public function addToken(Token $token): void
  {
    $this->_tokens[] = $token;
    $this->next();
  }

  /**
   * @inheritDoc
   */
  public function current(): Token
  {
    return $this->_tokens[$this->_currentIndex];
  }

  /**
   * @inheritDoc
   */
  public function next(): void
  {
    $this->_currentIndex++;
  }

  /**
   * @inheritDoc
   */
  public function key(): int
  {
    return $this->_currentIndex;
  }

  /**
   * @inheritDoc
   */
  public function valid(): bool
  {
    return isset($this->_tokens[$this->_currentIndex]);
  }

  /**
   * @inheritDoc
   */
  public function rewind(): void
  {
    $this->_currentIndex = 0;
  }

  /**
   * @inheritDoc
   */
  public function count(): int
  {
    return count($this->_tokens);
  }
}
