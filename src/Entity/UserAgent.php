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
    public $id;

    /**
     * @Column(type="binary", name="uaHash")
     */
    public $hash;

    /**
     * @Column(type="text", name="uaString")
     */
    public $string;

    public function __construct()
    {
        $this->id = Uuid::uuid4();
    }
}
