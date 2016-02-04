<?php
namespace UserAgentParserComparison\Entity;

use Ramsey\Uuid\Uuid;

/**
 * @Entity
 * @Table(name="result"
 * ,uniqueConstraints={@UniqueConstraint(name="unique_userAgent_provider", columns={"userAgent_id", "provider_id"})}
 * ,indexes={
 * @Index(name="result_resBrowserName", columns={"resBrowserName"})
 * ,@Index(name="result_resEngineName", columns={"resEngineName"})
 * ,@Index(name="result_resOsName", columns={"resOsName"})
 * ,@Index(name="result_resDeviceModel", columns={"resDeviceModel"})
 * ,@Index(name="result_resDeviceBrand", columns={"resDeviceBrand"})
 * ,@Index(name="result_resDeviceType", columns={"resDeviceType"})
 * ,@Index(name="result_resBotName", columns={"resBotName"})
 * ,@Index(name="result_resBotType", columns={"resBotType"})
 * }
 * )
 */
class Result
{

    /**
     * @Id
     * @Column(type="uuid", name="resId")
     * @GeneratedValue(strategy="NONE")
     */
    protected $id;

    /**
     * @ManyToOne(targetEntity="UserAgent")
     * @JoinColumn(name="userAgent_id", referencedColumnName="uaId")
     */
    protected $userAgent;

    /**
     * @ManyToOne(targetEntity="Provider")
     * @JoinColumn(name="provider_id", referencedColumnName="proId")
     */
    protected $provider;

    /**
     * @OneToOne(targetEntity="ResultEvaluation", mappedBy="result")
     */
    protected $resultEvaluation;

    /**
     * @Column(type="string", name="resProviderVersion", nullable=true)
     */
    protected $providerVersion;

    /**
     * @Column(type="decimal", name="resParseTime", precision=20, scale=5)
     */
    protected $parseTime;

    /**
     * @Column(type="datetime", name="resLastChangeDate")
     */
    protected $lastChangeDate;

    /**
     * @Column(type="boolean", name="resResultFound")
     */
    protected $resultFound;

    /**
     * @Column(type="string", name="resBrowserName", nullable=true)
     */
    protected $browserName;

    /**
     * @Column(type="string", name="resBrowserVersion", nullable=true)
     */
    protected $browserVersion;

    /**
     * @Column(type="string", name="resEngineName", nullable=true)
     */
    protected $engineName;

    /**
     * @Column(type="string", name="resEngineVersion", nullable=true)
     */
    protected $engineVersion;

    /**
     * @Column(type="string", name="resOsName", nullable=true)
     */
    protected $osName;

    /**
     * @Column(type="string", name="resOsVersion", nullable=true)
     */
    protected $osVersion;

    /**
     * @Column(type="string", name="resDeviceModel", nullable=true)
     */
    protected $deviceModel;

    /**
     * @Column(type="string", name="resDeviceBrand", nullable=true)
     */
    protected $deviceBrand;

    /**
     * @Column(type="string", name="resDeviceType", nullable=true)
     */
    protected $deviceType;

    /**
     * @Column(type="boolean", name="resDeviceIsMobile", nullable=true)
     */
    protected $deviceIsMobile;

    /**
     * @Column(type="boolean", name="resDeviceIsTouch", nullable=true)
     */
    protected $deviceIsTouch;

    /**
     * @Column(type="boolean", name="resBotIsBot", nullable=true)
     */
    protected $botIsBot;

    /**
     * @Column(type="string", name="resBotName", nullable=true)
     */
    protected $botName;

    /**
     * @Column(type="string", name="resBotType", nullable=true)
     */
    protected $botType;

    /**
     * @Column(type="object", name="resRawResult", nullable=true)
     */
    protected $rawResult;

    public function __construct()
    {
        $this->id = Uuid::uuid4();
    }

    public function getId()
    {
        return $this->id;
    }

    public function setUserAgent($userAgent)
    {
        $this->userAgent = $userAgent;
    }

    public function getUserAgent()
    {
        return $this->userAgent;
    }

    public function setProvider($provider)
    {
        $this->provider = $provider;
    }

    /**
     *
     * @return Provider
     */
    public function getProvider()
    {
        return $this->provider;
    }

    public function setProviderVersion($providerVersion)
    {
        $this->providerVersion = $providerVersion;
    }

    public function getProviderVersion()
    {
        return $this->providerVersion;
    }

    public function setParseTime($parseTime)
    {
        $this->parseTime = $parseTime;
    }

    public function getParseTime()
    {
        return $this->parseTime;
    }

    public function setLastChangeDate($lastChangeDate)
    {
        $this->lastChangeDate = $lastChangeDate;
    }

    public function getLastChangeDate()
    {
        return $this->lastChangeDate;
    }

    /**
     *
     * @param boolean $resultFound            
     */
    public function setResultFound($resultFound)
    {
        $this->resultFound = $resultFound;
    }

    public function getResultFound()
    {
        return $this->resultFound;
    }

    public function setBrowserName($browserName)
    {
        $this->browserName = $browserName;
    }

    public function getBrowserName()
    {
        return $this->browserName;
    }

    public function setBrowserVersion($browserVersion)
    {
        $this->browserVersion = $browserVersion;
    }

    public function getBrowserVersion()
    {
        return $this->browserVersion;
    }

    public function setEngineName($engineName)
    {
        $this->engineName = $engineName;
    }

    public function getEngineName()
    {
        return $this->engineName;
    }

    public function setEngineVersion($engineVersion)
    {
        $this->engineVersion = $engineVersion;
    }

    public function getEngineVersion()
    {
        return $this->engineVersion;
    }

    public function setOsName($osName)
    {
        $this->osName = $osName;
    }

    public function getOsName()
    {
        return $this->osName;
    }

    public function setOsVersion($osVersion)
    {
        $this->osVersion = $osVersion;
    }

    public function getOsVersion()
    {
        return $this->osVersion;
    }

    public function setDeviceModel($deviceModel)
    {
        $this->deviceModel = $deviceModel;
    }

    public function getDeviceModel()
    {
        return $this->deviceModel;
    }

    public function setDeviceBrand($deviceBrand)
    {
        $this->deviceBrand = $deviceBrand;
    }

    public function getDeviceBrand()
    {
        return $this->deviceBrand;
    }

    public function setDeviceType($deviceType)
    {
        $this->deviceType = $deviceType;
    }

    public function getDeviceType()
    {
        return $this->deviceType;
    }

    public function setDeviceIsMobile($deviceIsMobile)
    {
        $this->deviceIsMobile = $deviceIsMobile;
    }

    public function getDeviceIsMobile()
    {
        return $this->deviceIsMobile;
    }

    public function setDeviceIsTouch($deviceIsTouch)
    {
        $this->deviceIsTouch = $deviceIsTouch;
    }

    public function getDeviceIsTouch()
    {
        return $this->deviceIsTouch;
    }

    public function setBotIsBot($botIsBot)
    {
        $this->botIsBot = $botIsBot;
    }

    public function getBotIsBot()
    {
        return $this->botIsBot;
    }

    public function setBotName($botName)
    {
        $this->botName = $botName;
    }

    public function getBotName()
    {
        return $this->botName;
    }

    public function setBotType($botType)
    {
        $this->botType = $botType;
    }

    public function getBotType()
    {
        return $this->botType;
    }

    public function setRawResult($rawResult)
    {
        $this->rawResult = $rawResult;
    }

    public function getRawResult()
    {
        return $this->rawResult;
    }
}
