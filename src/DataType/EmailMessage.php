<?php


namespace App\DataType;

/**
 * Class EmailMessage
 */
class EmailMessage
{
    /**
     * @var string
     */
    private $body;
    /**
     * @var string
     */
    private $subject;
    /**
     * @var string
     */
    private $to;

    /**
     * @return string
     */
    public function getBody(): string
    {
        return $this->body;
    }

    /**
     * @param string $body
     * @return $this
     */
    public function setBody(string $body): self
    {
        $this->body = $body;

        return $this;
    }

    /**
     * @return string
     */
    public function getSubject(): string
    {
        return $this->subject;
    }

    /**
     * @param string $subject
     * @return $this
     */
    public function setSubject(string $subject): self
    {
        $this->subject = $subject;

        return $this;
    }

    /**
     * @return string
     */
    public function getTo(): string
    {
        return $this->to;
    }

    /**
     * @param string $to
     * @return $this
     */
    public function setTo(string $to): self
    {
        $this->to = $to;

        return $this;
    }
}