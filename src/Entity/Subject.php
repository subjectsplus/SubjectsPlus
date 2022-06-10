<?php

namespace App\Entity;

use App\Service\PlusletService;
use Doctrine\ORM\Mapping as ORM;
use Psr\Log\LoggerInterface;
use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Annotation\ApiSubresource;
use Symfony\Component\Serializer\Annotation\Groups;
use Doctrine\ORM\Mapping\OrderBy;


/**
 * Subject.
 *
 * @ORM\Table(name="subject")
 * @ORM\Entity(repositoryClass="App\Repository\SubjectRepository")
 * 
 * @ApiResource(
 *     collectionOperations={"get", "post"},
 *     itemOperations={"get", "put", "delete"},
 *     order={"subject": "ASC"}
 * )
 */
class Subject
{
    /**
     * @var int
     *
     * @ORM\Column(name="subject_id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     * @Groups({"faq"})
     */
    private $subjectId;

    /**
     * @var string|null
     *
     * @ORM\Column(name="subject", type="string", length=255, nullable=true)
     * @Groups({"faq"})
     * @OrderBy({"subject" = "ASC"})
     */
    private $subject;

    /**
     * @var int
     *
     * @ORM\Column(name="active", type="smallint", nullable=false)
     * @Groups({"faq"})
     */
    private $active = 0;

    /**
     * @var string
     *
     * @ORM\Column(name="shortform", type="string", length=50, nullable=false)
     * @Groups({"faq"})
     */
    private $shortform = '';

    /**
     * @var string|null
     *
     * @ORM\Column(name="redirect_url", type="string", length=255, nullable=true)
     */
    private $redirectUrl;

    /**
     * @var string|null
     *
     * @ORM\Column(name="header", type="string", length=45, nullable=true)
     * @Groups({"faq"})
     */
    private $header;

    /**
     * @var string|null
     *
     * @ORM\Column(name="description", type="string", length=255, nullable=true)
     * @Groups({"faq"})
     */
    private $description;

    /**
     * @var string|null
     *
     * @ORM\Column(name="keywords", type="string", length=255, nullable=true)
     * @Groups({"faq"})
     */
    private $keywords;

    /**
     * @var string|null
     *
     * @ORM\Column(name="type", type="string", length=20, nullable=true)
     * @Groups({"faq"})
     */
    private $type;

    /**
     * @var \DateTime|null
     *
     * @ORM\Column(name="last_modified", type="datetime", nullable=true, options={"default": "CURRENT_TIMESTAMP"})
     * @Groups({"faq"})
     */
    private $lastModified;

    /**
     * @var string|null
     *
     * @ORM\Column(name="extra", type="string", length=255, nullable=true)
     * @Groups({"faq"})
     */
    private $extra;

    /**
     * @var string|null
     *
     * @ORM\Column(name="course_code", type="string", length=45, nullable=true)
     * @Groups({"faq"})
     */
    private $courseCode;

    /**
     * @var string|null
     *
     * @ORM\Column(name="instructor", type="string", length=255, nullable=true)
     * @Groups({"faq"})
     */
    private $instructor;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToMany(targetEntity="Discipline", inversedBy="subject")
     * @ORM\JoinTable(name="subject_discipline",
     *     joinColumns={
     *         @ORM\JoinColumn(name="subject_id", referencedColumnName="subject_id")
     *     },
     *     inverseJoinColumns={
     *         @ORM\JoinColumn(name="discipline_id", referencedColumnName="discipline_id")
     *     }
     * )
     */
    private $discipline;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Tab", mappedBy="subject", cascade={"persist", "remove"})
     * @ApiSubresource(maxDepth=1)
     */
    private $tabs;

    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Staff", mappedBy="subjects")
     * @Groups({"faq"})
     */
    private $staff;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Faq", mappedBy="subjects", cascade={"persist", "remove"})
     */
    private $faqs;

    /**
     * Constructor.
     */
    public function __construct(?LoggerInterface $logger=null)
    {
        $this->staff = new \Doctrine\Common\Collections\ArrayCollection();
        $this->discipline = new \Doctrine\Common\Collections\ArrayCollection();
        $this->tabs = new \Doctrine\Common\Collections\ArrayCollection();
        $this->faqs = new \Doctrine\Common\Collections\ArrayCollection();
        $this->logger = $logger;
    }

    /**
     * @return \Doctrine\Common\Collections\Collection|Tab[]
     */
    public function getTabs(): \Doctrine\Common\Collections\Collection
    {
        return $this->tabs;
    }

    public function getSubjectId(): ?int
    {
        return $this->subjectId;
    }

    public function getSubject(): ?string
    {
        return $this->subject;
    }

    public function setSubject(?string $subject): self
    {
        $this->subject = $subject;

        return $this;
    }

    // Register Magic Method to Print the subject
    public function __toString() {
        return $this->subject;
    }

    public function getActive(): int
    {
        return $this->active;
    }

    public function setActive(int $active): self
    {
        $this->active = $active;

        return $this;
    }

    public function getShortform(): ?string
    {
        return $this->shortform;
    }

    public function setShortform(string $shortform): self
    {
        if ($shortform[-1] === '-') {
            // Remove the last character from shortform
            $shortform = \substr($shortform, 0, -1);
        }
        
        $this->shortform = strtolower($shortform);

        return $this;
    }

    public function getRedirectUrl(): ?string
    {
        return $this->redirectUrl;
    }

    public function setRedirectUrl(?string $redirectUrl): self
    {
        $this->redirectUrl = $redirectUrl;

        return $this;
    }

    public function getHeader(): ?string
    {
        return $this->header;
    }

    public function setHeader(?string $header): self
    {
        $this->header = $header;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getKeywords(): ?string
    {
        return $this->keywords;
    }

    public function setKeywords(?string $keywords): self
    {
        $this->keywords = $keywords;

        return $this;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(?string $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function getLastModified(): ?\DateTimeInterface
    {
        return $this->lastModified;
    }

    public function setLastModified(?\DateTimeInterface $lastModified): self
    {
        $this->lastModified = $lastModified;

        return $this;
    }

    public function getExtra(): ?string
    {
        return $this->extra;
    }

    public function setExtra(?string $extra): self
    {
        $this->extra = $extra;

        return $this;
    }

    public function getCourseCode(): ?string
    {
        return $this->courseCode;
    }

    public function setCourseCode(?string $courseCode): self
    {
        $this->courseCode = $courseCode;

        return $this;
    }

    public function getInstructor(): ?string
    {
        return $this->instructor;
    }

    public function setInstructor(?string $instructor): self
    {
        $this->instructor = $instructor;

        return $this;
    }

    /**
     * @return \Doctrine\Common\Collections\Collection|Discipline[]
     */
    public function getDiscipline(): \Doctrine\Common\Collections\Collection
    {
        return $this->discipline;
    }

    public function addDiscipline(Discipline $discipline): self
    {
        if (!$this->discipline->contains($discipline)) {
            $this->discipline[] = $discipline;
        }

        return $this;
    }

    public function removeDiscipline(Discipline $discipline): self
    {
        $this->discipline->removeElement($discipline);

        return $this;
    }

    // TODO: test!  And probably refactor for SOC
    public function toPublicArray(PlusletService $plusletService): array
    {
        $tabs = [];
        foreach ($this->getTabs() as $key => $tab) {
            $tab_array = $tab->toArray();
            $tab_array['id'] = "tab-$key";
            $sections = $tab->getSections();
            foreach ($sections as $section) {
                $columns = [];
                $plusletSections = $section->getPlusletSections();
                foreach ($plusletSections as $plusletSection) {
                    $pluslet_model = $plusletSection->getPluslet();
                    if ($pluslet_model) {
                        $pluslet_obj = $plusletService->plusletClassName($pluslet_model->getType());
                        if (class_exists($pluslet_obj, true)) {
                            $pluslet_id = $pluslet_model->getPlusletId();
                            $pluslet = new $pluslet_obj($pluslet_id, '', $this->getSubjectId());
                            $columns[$plusletSection->getPcolumn()]['pluslets'][] = [
                                'title' => $pluslet_model->getTitle(),
                                'body' => $pluslet->output('view', 'public'),
                                'row' => $plusletSection->getProw()
                            ];
                        } else {
                            if ($this->logger) {
                                $this->logger->error("Could not autoload pluslet class $pluslet_obj in ".$this->getShortform().' guide');
                            }
                        }
                    }
                }
                $tab_array['sections'][] = [
                    'layout' => $section->getLayout(),
                    'columns' => $columns,
                ];
            }
            $tabs[] = $tab_array;
        }

        return $tabs;
    }

    public function addTab(Tab $tab): self
    {
        if (!$this->tabs->contains($tab)) {
            $this->tabs[] = $tab;
            $tab->setSubject($this);
        }

        return $this;
    }

    public function removeTab(Tab $tab): self
    {
        if ($this->tabs->removeElement($tab)) {
            // set the owning side to null (unless already changed)
            if ($tab->getSubject() === $this) {
                $tab->setSubject(null);
            }
        }

        return $this;
    }

    /**
     * @return \Doctrine\Common\Collections\Collection|Staff[]
     */
    public function getStaff(): \Doctrine\Common\Collections\Collection
    {
        return $this->staff;
    }

    public function addStaff(Staff $staff): self
    {
        if (!$this->staff->contains($staff)) {
            $this->staff[] = $staff;
            $staff->addSubject($this);
        }

        return $this;
    }

    public function removeStaff(Staff $staff): self
    {
        if ($this->staff->removeElement($staff)) {
            $staff->removeSubject($this);
        }

        return $this;
    }

    /**
     * @return \Doctrine\Common\Collections\Collection|Faq[]
     */
    public function getFaqs(): \Doctrine\Common\Collections\Collection
    {
        return $this->faqs;
    }

    public function addFaq(Faq $faq): self
    {
        if (!$this->faqs->contains($faq)) {
            $this->faqs[] = $faq;
            $faq->addSubject($this);
        }

        return $this;
    }

    public function removeFaq(Faq $faq): self
    {
        if ($this->faqs->removeElement($faq)) {
            $faq->removeSubject($this);
        }

        return $this;
    }
}
