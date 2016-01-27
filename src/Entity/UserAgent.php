<?php
namespace UserAgentParserComparison\Entity;

use Ramsey\Uuid\Uuid;

/**
 * @Entity
 * @Table(name="userAgent",
 * uniqueConstraints={@UniqueConstraint(name="userAgent_hash", columns={"uaHash"})}
 * )
 */
class UserAgent
{

    /**
     * @Id
     * @Column(type="uuid", name="uaId")
     * @GeneratedValue(strategy="NONE")
     */
    protected $id;

    /**
     * @Column(type="binary", name="uaHash")
     */
    protected $hash;

    /**
     * @Column(type="text", name="uaString")
     */
    protected $string;

    public function __construct()
    {
        $this->id = Uuid::uuid4();
    }

    public function getId()
    {
        return $this->id;
    }

    public function setHash($hash)
    {
        $this->hash = $hash;
    }

    public function getHash()
    {
        return $this->hash;
    }

    public function setString($string)
    {
        $this->string = $string;
    }

    public function getString()
    {
        return $this->string;
    }
}
