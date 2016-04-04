<?php
namespace UserAgentParserComparison\Entity;

use Ramsey\Uuid\Uuid;

/**
 * @Entity
 * @Table(name="provider",
 * uniqueConstraints={@UniqueConstraint(name="unique_provider_name", columns={"proType","proName"})}
 * )
 */
class Provider
{

    /**
     * @Id
     * @Column(type="uuid", name="proId")
     * @GeneratedValue(strategy="NONE")
     */
    public $id;

    /**
     * @Column(type="string", name="proType")
     */
    public $type;
    
    /**
     * @Column(type="string", name="proName")
     */
    public $name;

    /**
     * @Column(type="string", name="proPackageName", nullable=true)
     */
    public $packageName;

    /**
     * @Column(type="string", name="proHomepage", nullable=true)
     */
    public $homepage;
    
    /**
     * @Column(type="string", name="proVersion", nullable=true)
     */
    public $version;

    /**
     * @Column(type="boolean", name="proCanDetectBrowserName")
     */
    public $canDetectBrowserName;

    /**
     * @Column(type="boolean", name="proCanDetectBrowserVersion")
     */
    public $canDetectBrowserVersion;

    /**
     * @Column(type="boolean", name="proCanDetectEngineName")
     */
    public $canDetectEngineName;

    /**
     * @Column(type="boolean", name="proCanDetectEngineVersion")
     */
    public $canDetectEngineVersion;

    /**
     * @Column(type="boolean", name="proCanDetectOsName")
     */
    public $canDetectOsName;

    /**
     * @Column(type="boolean", name="proCanDetectOsVersion")
     */
    public $canDetectOsVersion;

    /**
     * @Column(type="boolean", name="proCanDetectDeviceModel")
     */
    public $canDetectDeviceModel;

    /**
     * @Column(type="boolean", name="proCanDetectDeviceBrand")
     */
    public $canDetectDeviceBrand;

    /**
     * @Column(type="boolean", name="proCanDetectDeviceType")
     */
    public $canDetectDeviceType;

    /**
     * @Column(type="boolean", name="proCanDetectDeviceIsMobile")
     */
    public $canDetectDeviceIsMobile;

    /**
     * @Column(type="boolean", name="proCanDetectDeviceIsTouch")
     */
    public $canDetectDeviceIsTouch;

    /**
     * @Column(type="boolean", name="proCanDetectBotIsBot")
     */
    public $canDetectBotIsBot;

    /**
     * @Column(type="boolean", name="proCanDetectBotName")
     */
    public $canDetectBotName;

    /**
     * @Column(type="boolean", name="proCanDetectBotType")
     */
    public $canDetectBotType;

    public function __construct()
    {
        $this->id = Uuid::uuid4();
    }
}
