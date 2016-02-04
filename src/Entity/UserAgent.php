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

    /**
     * @Column(type="string", name="uaSource", nullable=true)
     */
    public $source;

    /**
     * @Column(type="string", name="uaFileName", nullable=true)
     */
    public $fileName;

    /**
     * @Column(type="string", name="uaBrowserName", nullable=true)
     */
    public $browserName;

    /**
     * @Column(type="string", name="uaBrowserVersion", nullable=true)
     */
    public $browserVersion;

    /**
     * @Column(type="string", name="uaEngineName", nullable=true)
     */
    public $engineName;

    /**
     * @Column(type="string", name="uaEngineVersion", nullable=true)
     */
    public $engineVersion;

    /**
     * @Column(type="string", name="uaOsName", nullable=true)
     */
    public $osName;

    /**
     * @Column(type="string", name="uaOsVersion", nullable=true)
     */
    public $osVersion;

    /**
     * @Column(type="string", name="uaDeviceModel", nullable=true)
     */
    public $deviceModel;

    /**
     * @Column(type="string", name="uaDeviceBrand", nullable=true)
     */
    public $deviceBrand;

    /**
     * @Column(type="string", name="uaDeviceType", nullable=true)
     */
    public $deviceType;

    /**
     * @Column(type="boolean", name="uaDeviceIsMobile", nullable=true)
     */
    public $deviceIsMobile;

    /**
     * @Column(type="boolean", name="uaDeviceIsTouch", nullable=true)
     */
    public $deviceIsTouch;

    /**
     * @Column(type="boolean", name="uaBotIsBot", nullable=true)
     */
    public $botIsBot;

    /**
     * @Column(type="string", name="uaBotName", nullable=true)
     */
    public $botName;

    /**
     * @Column(type="string", name="uaBotType", nullable=true)
     */
    public $botType;

    /**
     * @Column(type="object", name="uaRawResult", nullable=true)
     */
    public $rawResult;

    public function __construct()
    {
        $this->id = Uuid::uuid4();
    }
}
