<?php
namespace UserAgentParserComparison\Entity;

use Ramsey\Uuid\Uuid;

/**
 * @Entity
 * @Table(name="userAgentEvaluation")
 */
class UserAgentEvaluation
{

    /**
     * @Id
     * @Column(type="uuid", name="uevId")
     * @GeneratedValue(strategy="NONE")
     */
    public $id;

    /**
     * @OneToOne(targetEntity="UserAgent")
     * @JoinColumn(name="userAgent_id", referencedColumnName="uaId")
     *
     * @var UserAgent
     */
    public $userAgent;

    /**
     * @Column(type="datetime")
     */
    public $lastChangeDate;

    /**
     * @Column(type="integer")
     */
    public $resultCount;

    /**
     * @Column(type="integer")
     */
    public $resultFound;

    /**
     * @Column(type="object")
     */
    public $browserNames;

    /**
     * @Column(type="object")
     */
    public $browserNamesHarmonized;

    /**
     * @Column(type="integer")
     */
    public $browserNameFound;

    /**
     * @Column(type="integer")
     */
    public $browserNameFoundUnique;

    /**
     * @Column(type="integer")
     */
    public $browserNameMaxSameResultCount;

    /**
     * @Column(type="integer")
     */
    public $browserNameHarmonizedFoundUnique;

    /**
     * @Column(type="integer")
     */
    public $browserNameHarmonizedMaxSameResultCount;

    /**
     * @Column(type="object")
     */
    public $browserVersions;

    /**
     * @Column(type="object")
     */
    public $browserVersionsHarmonized;

    /**
     * @Column(type="integer")
     */
    public $browserVersionFound;

    /**
     * @Column(type="integer")
     */
    public $browserVersionFoundUnique;

    /**
     * @Column(type="integer")
     */
    public $browserVersionMaxSameResultCount;

    /**
     * @Column(type="integer")
     */
    public $browserVersionHarmonizedFoundUnique;

    /**
     * @Column(type="integer")
     */
    public $browserVersionHarmonizedMaxSameResultCount;

    /**
     * @Column(type="object")
     */
    public $engineNames;

    /**
     * @Column(type="object")
     */
    public $engineNamesHarmonized;

    /**
     * @Column(type="integer")
     */
    public $engineNameFound;

    /**
     * @Column(type="integer")
     */
    public $engineNameFoundUnique;

    /**
     * @Column(type="integer")
     */
    public $engineNameMaxSameResultCount;

    /**
     * @Column(type="integer")
     */
    public $engineNameHarmonizedFoundUnique;

    /**
     * @Column(type="integer")
     */
    public $engineNameHarmonizedMaxSameResultCount;

    /**
     * @Column(type="object")
     */
    public $engineVersions;

    /**
     * @Column(type="object")
     */
    public $engineVersionsHarmonized;

    /**
     * @Column(type="integer")
     */
    public $engineVersionFound;

    /**
     * @Column(type="integer")
     */
    public $engineVersionFoundUnique;

    /**
     * @Column(type="integer")
     */
    public $engineVersionMaxSameResultCount;

    /**
     * @Column(type="integer")
     */
    public $engineVersionHarmonizedFoundUnique;

    /**
     * @Column(type="integer")
     */
    public $engineVersionHarmonizedMaxSameResultCount;

    /**
     * @Column(type="object")
     */
    public $osNames;

    /**
     * @Column(type="object")
     */
    public $osNamesHarmonized;

    /**
     * @Column(type="integer")
     */
    public $osNameFound;

    /**
     * @Column(type="integer")
     */
    public $osNameFoundUnique;

    /**
     * @Column(type="integer")
     */
    public $osNameMaxSameResultCount;

    /**
     * @Column(type="integer")
     */
    public $osNameHarmonizedFoundUnique;

    /**
     * @Column(type="integer")
     */
    public $osNameHarmonizedMaxSameResultCount;

    /**
     * @Column(type="object")
     */
    public $osVersions;

    /**
     * @Column(type="object")
     */
    public $osVersionsHarmonized;

    /**
     * @Column(type="integer")
     */
    public $osVersionFound;

    /**
     * @Column(type="integer")
     */
    public $osVersionFoundUnique;

    /**
     * @Column(type="integer")
     */
    public $osVersionMaxSameResultCount;

    /**
     * @Column(type="integer")
     */
    public $osVersionHarmonizedFoundUnique;

    /**
     * @Column(type="integer")
     */
    public $osVersionHarmonizedMaxSameResultCount;

    /**
     * @Column(type="object")
     */
    public $deviceModels;

    /**
     * @Column(type="object")
     */
    public $deviceModelsHarmonized;

    /**
     * @Column(type="integer")
     */
    public $deviceModelFound;

    /**
     * @Column(type="integer")
     */
    public $deviceModelFoundUnique;

    /**
     * @Column(type="integer")
     */
    public $deviceModelMaxSameResultCount;

    /**
     * @Column(type="integer")
     */
    public $deviceModelHarmonizedFoundUnique;

    /**
     * @Column(type="integer")
     */
    public $deviceModelHarmonizedMaxSameResultCount;

    /**
     * @Column(type="object")
     */
    public $deviceBrands;

    /**
     * @Column(type="object")
     */
    public $deviceBrandsHarmonized;

    /**
     * @Column(type="integer")
     */
    public $deviceBrandFound;

    /**
     * @Column(type="integer")
     */
    public $deviceBrandFoundUnique;

    /**
     * @Column(type="integer")
     */
    public $deviceBrandMaxSameResultCount;

    /**
     * @Column(type="integer")
     */
    public $deviceBrandHarmonizedFoundUnique;

    /**
     * @Column(type="integer")
     */
    public $deviceBrandHarmonizedMaxSameResultCount;

    /**
     * @Column(type="object")
     */
    public $deviceTypes;

    /**
     * @Column(type="object")
     */
    public $deviceTypesHarmonized;

    /**
     * @Column(type="integer")
     */
    public $deviceTypeFound;

    /**
     * @Column(type="integer")
     */
    public $deviceTypeFoundUnique;

    /**
     * @Column(type="integer")
     */
    public $deviceTypeMaxSameResultCount;

    /**
     * @Column(type="integer")
     */
    public $deviceTypeHarmonizedFoundUnique;

    /**
     * @Column(type="integer")
     */
    public $deviceTypeHarmonizedMaxSameResultCount;

    /**
     * @Column(type="integer")
     */
    public $asMobileDetectedCount;

    /**
     * @Column(type="integer")
     */
    public $asTouchDetectedCount;

    /**
     * @Column(type="integer")
     */
    public $asBotDetectedCount;

    /**
     * @Column(type="object")
     */
    public $botNames;

    /**
     * @Column(type="object")
     */
    public $botNamesHarmonized;

    /**
     * @Column(type="integer")
     */
    public $botNameFound;

    /**
     * @Column(type="integer")
     */
    public $botNameFoundUnique;

    /**
     * @Column(type="integer")
     */
    public $botNameMaxSameResultCount;

    /**
     * @Column(type="integer")
     */
    public $botNameHarmonizedFoundUnique;

    /**
     * @Column(type="integer")
     */
    public $botNameHarmonizedMaxSameResultCount;

    /**
     * @Column(type="object")
     */
    public $botTypes;

    /**
     * @Column(type="object")
     */
    public $botTypesHarmonized;

    /**
     * @Column(type="integer")
     */
    public $botTypeFound;

    /**
     * @Column(type="integer")
     */
    public $botTypeFoundUnique;

    /**
     * @Column(type="integer")
     */
    public $botTypeMaxSameResultCount;

    /**
     * @Column(type="integer")
     */
    public $botTypeHarmonizedFoundUnique;

    /**
     * @Column(type="integer")
     */
    public $botTypeHarmonizedMaxSameResultCount;

    public function __construct()
    {
        $this->id = Uuid::uuid4();
    }
}
