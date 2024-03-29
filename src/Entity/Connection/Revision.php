<?php

namespace Janus\ServiceRegistry\Entity\Connection;

use DateTime;

use Doctrine\ORM\Mapping AS ORM;
use Doctrine\ORM\PersistentCollection;
use Janus\ServiceRegistry\Connection\Metadata\MetadataDto;
use JMS\Serializer\Annotation AS Serializer;

use Janus\ServiceRegistry\Entity\Connection;
use Janus\ServiceRegistry\Connection\ConnectionDto;
use Janus\ServiceRegistry\Entity\User;
use Janus\ServiceRegistry\Value\Ip;

/**
 * @ORM\Entity(
 *  repositoryClass="Janus\ServiceRegistry\Entity\Connection\RevisionRepository"
 * )
 * @ORM\Table(
 *  name="connectionRevision",
 *  uniqueConstraints={@ORM\UniqueConstraint(name="unique_revision",columns={"eid", "revisionid"})}
 * )
 */
class Revision
{
    /**
     * @var int
     *
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(name="id", type="integer")
     */
    protected $id;

    /**
     * @var Connection
     *
     * @ORM\ManyToOne(targetEntity="Janus\ServiceRegistry\Entity\Connection", inversedBy="revisions")
     * @ORM\JoinColumn(name="eid", referencedColumnName="id", nullable=false, onDelete="CASCADE")
     * @Serializer\Groups({"compare"})
     */
    protected $connection;

    /**
     * @var string
     *
     * @ORM\Column(name="entityid", type="text")
     * @Serializer\Groups({"compare"})
     *
     */
    protected $name;

    /**
     * @var int
     *
     * @ORM\Column(name="revisionid", type="integer")
     */
    protected $revisionNr;

    /**
     * @var string
     *
     * @ORM\Column(name="state", type="text", nullable=true)
     * @Serializer\Groups({"compare"})
     */
    protected $state;

    /**
     * @var string
     *
     * @ORM\Column(name="type", type="text", nullable=true)
     * @Serializer\Groups({"compare"})
     */
    protected $type;

    /**
     * @var DateTime
     *
     * @ORM\Column(name="expiration", type="janusDateTime", nullable=true)
     */
    protected $expirationDate;

    /**
     * @var string
     *
     * @ORM\Column(name="metadataurl", type="text", nullable=true)
     * @Serializer\Groups({"compare"})
     */
    protected $metadataUrl;

    /**
     * @var DateTime
     *
     * @ORM\Column(name="metadata_valid_until", type="datetime", nullable=true)
     */
    protected $metadataValidUntil;

    /**
     * @var DateTime
     *
     * @ORM\Column(name="metadata_cache_until", type="datetime", nullable=true)
     */
    protected $metadataCacheUntil;

    /**
     * @var bool
     *
     * @ORM\Column(name="allowedall", type="janusBoolean", options={"default" = "yes"})
     * @Serializer\Groups({"compare"})
     */
    protected $allowAllEntities = true;

    /**
     * @var array
     *
     * @ORM\Column(name="arp_attributes", type="array", nullable=true)
     * @Serializer\Groups({"compare"})
     *
     */
    protected $arpAttributes;

    /**
     * @var string
     *
     * @ORM\Column(name="manipulation", type="text", columnDefinition="mediumtext", nullable=true)
     *
     */
    protected $manipulationCode;

    /**
     * @var string
     *
     * @Serializer\Groups({"compare"})
     * @Serializer\Accessor(getter="isManipulationCodePresent")
     */
    protected $manipulationCodePresent;

    /**
     * @var User
     *
     * @ORM\ManyToOne(targetEntity="Janus\ServiceRegistry\Entity\User")
     * @ORM\JoinColumn(name="user", referencedColumnName="uid", nullable=true)
     */
    protected $updatedByUser;

    /**
     * @var Datetime
     *
     * @ORM\Column(name="created", type="janusDateTime", nullable=true)
     */
    protected $createdAtDate;

    /**
     * @var Ip
     *
     * @ORM\Column(name="ip", type="janusIp", nullable=true)
     */
    protected $updatedFromIp;

    /**
     * @var int
     *
     * @ORM\Column(name="parent", type="integer", nullable=true)
     */
    protected $parentRevisionNr;

    /**
     * @var string
     *
     * @ORM\Column(name="revisionnote", type="text", nullable=true)
     */
    protected $revisionNote;

    /**
     * @var string
     *
     * @ORM\Column(name="notes", type="text", nullable=true)
     * @Serializer\Groups({"compare"})
     */
    protected $notes;
    /**
     * @var bool
     *
     * @ORM\Column(name="active", type="janusBoolean", options={"default" = "yes"})
     * @Serializer\Groups({"compare"})
     *
     */
    protected $isActive = true;

    /**
     * @var \Doctrine\ORM\PersistentCollection
     *
     * @ORM\OneToMany(targetEntity="Janus\ServiceRegistry\Entity\Connection\Revision\Metadata", mappedBy="connectionRevision", fetch="LAZY")
     * @Serializer\Groups({"compare"})
     *
     */
    protected $metadata;

    /**
     * @var array
     *
     * @ORM\OneToMany(targetEntity="Janus\ServiceRegistry\Entity\Connection\Revision\AllowedConnectionRelation", mappedBy="connectionRevision", cascade={"persist", "remove"})
     * @Serializer\Groups({"compare"})
     */
    protected $allowedConnectionRelations;

    /**
     * @var array
     *
     * @ORM\OneToMany(targetEntity="Janus\ServiceRegistry\Entity\Connection\Revision\BlockedConnectionRelation", mappedBy="connectionRevision", cascade={"persist", "remove"})
     * @Serializer\Groups({"compare"})
     */
    protected $blockedConnectionRelations;

    /**
     * @var array
     *
     * @ORM\OneToMany(targetEntity="Janus\ServiceRegistry\Entity\Connection\Revision\DisableConsentRelation", mappedBy="connectionRevision", cascade={"persist", "remove"})
     * @Serializer\Groups({"compare"})
     */
    protected $disableConsentConnectionRelations;

    /**
     * @param Connection $connection
     * @param int $revisionNr
     * @param int|null $parentRevisionNr
     * @param string $revisionNote
     * @param string $state
     * @param DateTime|null $expirationDate
     * @param string|null $metadataUrl
     * @param bool $allowAllEntities
     * @param array $arpAttributes
     * @param string|null $manipulationCode
     * @param bool $isActive
     * @param string|null $notes
     * @param array $allowedConnections
     * @param array $blockedConnections
     * @param array $disableConsentConnections
     */
    public function __construct(
        Connection $connection,
        $revisionNr,
        $parentRevisionNr = null,
        $revisionNote,
        $state,
        \DateTime $expirationDate = null,
        $metadataUrl = null,
        $allowAllEntities,
        $arpAttributes = null,
        $manipulationCode = null,
        $isActive,
        $notes = null,
        array $allowedConnections = array(),
        array $blockedConnections = array(),
        array $disableConsentConnections = array()
    )
    {
        $this->connection       = $connection;
        $this->name             = $connection->getName();
        $this->type             = $connection->getType();
        $this->revisionNr       = $revisionNr;
        $this->parentRevisionNr = $parentRevisionNr;
        $this->state            = $state;
        $this->expirationDate   = $expirationDate;
        $this->metadataUrl      = $metadataUrl;
        $this->allowAllEntities = $allowAllEntities;
        $this->arpAttributes    = $arpAttributes;
        $this->manipulationCode = $manipulationCode;
        $this->isActive         = $isActive;
        $this->notes            = $notes;

        foreach ($allowedConnections as $allowedConnection) {
            $this->allowConnection($allowedConnection);
        }

        foreach ($blockedConnections as $blockedConnection) {
            $this->blockConnection($blockedConnection);
        }

        foreach ($disableConsentConnections as $disableConsentConnection) {
            $this->disableConsentForConnection($disableConsentConnection);
        }

        $this->setRevisionNote($revisionNote);
    }

    /**
     * Creates a ConnectionDto that can be used to clone a revision
     *
     * @todo move this to an Assembler
     *
     * @param $janusConfig
     * @return ConnectionDto
     */
    public function toDto($janusConfig = null)
    {
        $dto = new ConnectionDto();
        $dto->setId($this->connection->getId());
        $dto->setName($this->name);
        $dto->setType($this->type);
        $dto->setRevisionNr($this->revisionNr);
        $dto->setParentRevisionNr($this->parentRevisionNr);
        $dto->setRevisionNote($this->revisionNote);
        $dto->setState($this->state);
        $dto->setExpirationDate($this->expirationDate);
        $dto->setMetadataUrl($this->metadataUrl);
        $dto->setAllowAllEntities($this->allowAllEntities);
        $dto->setArpAttributes($this->arpAttributes);
        $dto->setManipulationCode($this->manipulationCode);
        $dto->setIsActive($this->isActive);
        $dto->setNotes($this->notes);

        $setAuditProperties = !empty($this->id);
        if ($setAuditProperties) {
            $dto->setCreatedAtDate($this->connection->getCreatedAtDate());
            $dto->setUpdatedAtDate($this->createdAtDate);
            $dto->setUpdatedByUser($this->updatedByUser);
            $dto->setUpdatedFromIp($this->updatedFromIp);
        }

        if ($this->metadata instanceof PersistentCollection) {
            // If we were given JANUS configuration we can make sure the types are correct,
            if ($janusConfig) {
                $assembler = new MetadataDto\Assembler\CastingAssembler(
                    new MetadataDto\MetadataDefinitionHelper($this->type, $janusConfig)
                );
            }
            // otherwise all values will be strings.
            else {
                $assembler = new MetadataDto\Assembler\SimpleAssembler();
            }

            $metadataDto = $assembler->assemble($this->metadata);

            $dto->setMetadata($metadataDto);
        }

        if ($this->allowedConnectionRelations instanceof PersistentCollection) {

            $allowedConnections = array();
            /** @var $relation \Janus\ServiceRegistry\Entity\Connection\Revision\AllowedConnectionRelation */
            foreach ($this->allowedConnectionRelations as $relation) {
                $remoteConnection = $relation->getRemoteConnection();
                $allowedConnections[] = array(
                    'id' => $remoteConnection->getId(),
                    'name' => $remoteConnection->getName()
                );
            }
            $dto->setAllowedConnections($allowedConnections);
        }

        if ($this->blockedConnectionRelations instanceof PersistentCollection) {
            $blockedConnections = array();
            /** @var $relation \Janus\ServiceRegistry\Entity\Connection\Revision\BlockedConnectionRelation */
            foreach ($this->blockedConnectionRelations as $relation) {
                $remoteConnection = $relation->getRemoteConnection();
                $blockedConnections[] = array(
                    'id' => $remoteConnection->getId(),
                    'name' => $remoteConnection->getName()
                );
            }
            $dto->setBlockedConnections($blockedConnections);
        }

        if ($this->disableConsentConnectionRelations instanceof PersistentCollection) {
            $disableConsentConnections = array();
            /** @var $relation \Janus\ServiceRegistry\Entity\Connection\Revision\DisableConsentRelation */
            foreach ($this->disableConsentConnectionRelations as $relation) {
                $remoteConnection = $relation->getRemoteConnection();
                $disableConsentConnections[] = array(
                    'id' => $remoteConnection->getId(),
                    'name' => $remoteConnection->getName()
                );
            }
            $dto->setDisableConsentConnections($disableConsentConnections);
        }

        return $dto;
    }

    /**
     * @param string $revisionNote
     * @throws \InvalidArgumentException
     */
    private function setRevisionNote($revisionNote)
    {
        if (!is_string($revisionNote) || empty($revisionNote)) {
            throw new \InvalidArgumentException("Invalid revision note '{$revisionNote}'");
        }
        $this->revisionNote = $revisionNote;
    }

    /**
     * @param \DateTime $createdAtDate
     * @return $this
     */
    public function setCreatedAtDate(DateTime $createdAtDate)
    {
        $this->createdAtDate = $createdAtDate;
        return $this;
    }

    /**
     * @param User $updatedByUser
     * @return $this
     */
    public function setUpdatedByUser(User $updatedByUser)
    {
        $this->updatedByUser = $updatedByUser;
        return $this;
    }

    /**
     * @param Ip $updatedFromIp
     * @return $this
     */
    public function setUpdatedFromIp(Ip $updatedFromIp)
    {
        $this->updatedFromIp = $updatedFromIp;
        return $this;
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return Connection
     */
    public function getConnection()
    {
        return $this->connection;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @return int
     */
    public function getRevisionNr()
    {
        return $this->revisionNr;
    }

    /**
     * @return \Doctrine\ORM\PersistentCollection
     */
    public function getMetadata()
    {
        return $this->metadata;
    }

    public function isManipulationCodePresent()
    {
        return !empty($this->manipulationCode);
    }

    public function getState()
    {
        return $this->state;
    }

    public function getUpdatedByUser()
    {
        return $this->updatedByUser;
    }

    public function getRevisionNote()
    {
        return $this->revisionNote;
    }

    public function getCreatedAtDate()
    {
        return $this->createdAtDate;
    }

    public function allowConnection($connection)
    {
        $this->allowedConnectionRelations[] = new Connection\Revision\AllowedConnectionRelation(
            $this,
            $connection
        );
        return $this;
    }

    public function blockConnection($connection)
    {
        $this->blockedConnectionRelations[] = new Connection\Revision\BlockedConnectionRelation(
            $this,
            $connection
        );
        return $this;
    }

    public function disableConsentForConnection($connection)
    {
        $this->disableConsentConnectionRelations[] = new Connection\Revision\DisableConsentRelation(
            $this,
            $connection
        );
        return $this;
    }
}
