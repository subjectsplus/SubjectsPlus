<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Stats.
 *
 * @ORM\Table(name="stats")
 * @ORM\Entity
 */
class Stats
{
    /**
     * @var int
     *
     * @ORM\Column(name="stats_id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $statsId;

    /**
     * @var string|null
     *
     * @ORM\Column(name="http_referer", type="string", length=200, nullable=true)
     */
    private $httpReferer;

    /**
     * @var string|null
     *
     * @ORM\Column(name="query_string", type="string", length=200, nullable=true)
     */
    private $queryString;

    /**
     * @var string|null
     *
     * @ORM\Column(name="remote_address", type="string", length=200, nullable=true)
     */
    private $remoteAddress;

    /**
     * @var string|null
     *
     * @ORM\Column(name="guide_page", type="string", length=200, nullable=true)
     */
    private $guidePage;

    /**
     * @var int|null
     *
     * @ORM\Column(name="date", type="integer", nullable=true)
     */
    private $date;

    /**
     * @var string|null
     *
     * @ORM\Column(name="page_title", type="string", length=200, nullable=true)
     */
    private $pageTitle;

    /**
     * @var string|null
     *
     * @ORM\Column(name="user_agent", type="string", length=200, nullable=true)
     */
    private $userAgent;

    /**
     * @var string|null
     *
     * @ORM\Column(name="subject_short_form", type="string", length=200, nullable=true)
     */
    private $subjectShortForm;

    /**
     * @var string|null
     *
     * @ORM\Column(name="event_type", type="string", length=200, nullable=true)
     */
    private $eventType;

    /**
     * @var string|null
     *
     * @ORM\Column(name="tab_name", type="string", length=200, nullable=true)
     */
    private $tabName;

    /**
     * @var string|null
     *
     * @ORM\Column(name="link_url", type="string", length=200, nullable=true)
     */
    private $linkUrl;

    /**
     * @var string|null
     *
     * @ORM\Column(name="link_title", type="string", length=200, nullable=true)
     */
    private $linkTitle;

    /**
     * @var string|null
     *
     * @ORM\Column(name="in_tab", type="string", length=200, nullable=true)
     */
    private $inTab;

    /**
     * @var string|null
     *
     * @ORM\Column(name="in_pluslet", type="string", length=200, nullable=true)
     */
    private $inPluslet;

    public function getStatsId(): ?int
    {
        return $this->statsId;
    }

    public function getHttpReferer(): ?string
    {
        return $this->httpReferer;
    }

    public function setHttpReferer(?string $httpReferer): self
    {
        $this->httpReferer = $httpReferer;

        return $this;
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

    public function getRemoteAddress(): ?string
    {
        return $this->remoteAddress;
    }

    public function setRemoteAddress(?string $remoteAddress): self
    {
        $this->remoteAddress = $remoteAddress;

        return $this;
    }

    public function getGuidePage(): ?string
    {
        return $this->guidePage;
    }

    public function setGuidePage(?string $guidePage): self
    {
        $this->guidePage = $guidePage;

        return $this;
    }

    public function getDate(): ?int
    {
        return $this->date;
    }

    public function setDate(?int $date): self
    {
        $this->date = $date;

        return $this;
    }

    public function getPageTitle(): ?string
    {
        return $this->pageTitle;
    }

    public function setPageTitle(?string $pageTitle): self
    {
        $this->pageTitle = $pageTitle;

        return $this;
    }

    public function getUserAgent(): ?string
    {
        return $this->userAgent;
    }

    public function setUserAgent(?string $userAgent): self
    {
        $this->userAgent = $userAgent;

        return $this;
    }

    public function getSubjectShortForm(): ?string
    {
        return $this->subjectShortForm;
    }

    public function setSubjectShortForm(?string $subjectShortForm): self
    {
        $this->subjectShortForm = $subjectShortForm;

        return $this;
    }

    public function getEventType(): ?string
    {
        return $this->eventType;
    }

    public function setEventType(?string $eventType): self
    {
        $this->eventType = $eventType;

        return $this;
    }

    public function getTabName(): ?string
    {
        return $this->tabName;
    }

    public function setTabName(?string $tabName): self
    {
        $this->tabName = $tabName;

        return $this;
    }

    public function getLinkUrl(): ?string
    {
        return $this->linkUrl;
    }

    public function setLinkUrl(?string $linkUrl): self
    {
        $this->linkUrl = $linkUrl;

        return $this;
    }

    public function getLinkTitle(): ?string
    {
        return $this->linkTitle;
    }

    public function setLinkTitle(?string $linkTitle): self
    {
        $this->linkTitle = $linkTitle;

        return $this;
    }

    public function getInTab(): ?string
    {
        return $this->inTab;
    }

    public function setInTab(?string $inTab): self
    {
        $this->inTab = $inTab;

        return $this;
    }

    public function getInPluslet(): ?string
    {
        return $this->inPluslet;
    }

    public function setInPluslet(?string $inPluslet): self
    {
        $this->inPluslet = $inPluslet;

        return $this;
    }
}
