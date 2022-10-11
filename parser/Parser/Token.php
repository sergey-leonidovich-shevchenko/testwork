<?php declare(strict_types=1);

namespace Parser;

class Token
{
  /** @var int */
  private int $type;

  /** @var string */
  private string $text;

  /** @var string|null */
  private ?string $name = null;

  /**
   * @return int
   */
  public function getType(): int
  {
    return $this->type;
  }

  /**
   * @param int $type
   * @return $this
   */
  public function setType(int $type): Token
  {
    $this->type = $type;

    return $this;
  }

  /**
   * @return string|null
   */
  public function getName(): ?string
  {
    return $this->name;
  }

  /**
   * @param string|null $name
   * @return $this
   */
  public function setName(?string $name): Token
  {
    $this->name = $name;

    return $this;
  }

  /**
   * @return string
   */
  public function getText(): string
  {
    return $this->text;
  }

  /**
   * @param string $text
   * @return $this
   */
  public function setText(string $text): Token
  {
    $this->text = $text;

    return $this;
  }
}
