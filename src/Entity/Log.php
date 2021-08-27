<?php

namespace App\Entity;

use App\Repository\LogRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=LogRepository::class)
 */
class Log
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(name="id", type="integer")
     */
    private $id;

    /**
     * @ORM\Column(name="message", type="text")
     */
    private $message;

    /**
     * @ORM\Column(name="context", type="json")
     */
    private $context = [];

    /**
     * @ORM\Column(name="level", type="smallint")
     */
    private $level;

    /**
     * @ORM\Column(name="level_name", type="string", length=255)
     */
    private $levelName;

    /**
     * @ORM\Column(name="client_ip", type="string", length=50, nullable=true)
     */
    private $clientIp;

    /**
     * @ORM\Column(name="client_port", type="smallint", options={})
     */
    private $clientPort;

    /**
     * @ORM\Column(name="method", type="string", length=4)
     */
    private $method;

    /**
     * @ORM\Column(name="uri", type="text")
     */
    private $uri;

    /**
     * @ORM\Column(name="request", type="json")
     */
    private $request = [];

    /**
     * @ORM\Column(name="token", type="string", length=17, nullable=true)
     */
    private $token;

    /**
     * @ORM\Column(name="query_string", type="text", nullable=true)
     */
    private $queryString;

    /**
     * @ORM\Column(name="created_at", type="datetime_immutable")
     */
    private $createdAt;

    public function __construct(?string $token)
    {
        $this->token = $token;
        $this->createdAt = new \DateTimeImmutable();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getMessage(): ?string
    {
        return $this->message;
    }

    public function setMessage(string $message): self
    {
        $this->message = $message;

        return $this;
    }

    public function getContext(): ?array
    {
        return $this->context;
    }

    public function setContext(array $context): self
    {
        $this->context = $context;

        return $this;
    }

    public function getLevel(): ?int
    {
        return $this->level;
    }

    public function setLevel(int $level): self
    {
        $this->level = $level;

        return $this;
    }

    public function getLevelName(): ?string
    {
        return $this->levelName;
    }

    public function setLevelName(string $levelName): self
    {
        $this->levelName = $levelName;

        return $this;
    }

    public function getClientIp(): ?string
    {
        return $this->clientIp;
    }

    public function setClientIp(?string $clientIp): self
    {
        $this->clientIp = $clientIp;

        return $this;
    }

    public function getClientPort(): ?int
    {
        return $this->clientPort;
    }

    public function setClientPort(int $clientPort): self
    {
        $this->clientPort = $clientPort;

        return $this;
    }

    public function getMethod(): ?string
    {
        return $this->method;
    }

    public function setMethod(string $method): self
    {
        $this->method = $method;

        return $this;
    }

    public function getUri(): ?string
    {
        return $this->uri;
    }

    public function setUri(string $uri): self
    {
        $this->uri = $uri;

        return $this;
    }

    public function getRequest(): ?array
    {
        return $this->request;
    }

    public function setRequest(array $request): self
    {
        $this->request = $request;

        return $this;
    }

    public function getToken(): ?string
    {
        return $this->token;
    }

    public function getQueryString(): ?string
    {
        return $this->queryString;
    }

    public function setQueryString(?string $queryString): self
    {
        $this->queryString = $queryString;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }
}
