<?php
namespace UserAgentParserComparison\Entity;

use Ramsey\Uuid\Uuid;

/**
 * @Entity
 * @Table(name="resultEvaluation")
 */
class ResultEvaluation
{

    /**
     * @Id
     * @Column(type="uuid", name="revId")
     * @GeneratedValue(strategy="NONE")
     */
    public $id;

    /**
     * @OneToOne(targetEntity="Result", inversedBy="resultEvaluation")
     * @JoinColumn(name="result_id", referencedColumnName="resId")
     */
    public $result;

    /**
     * @Column(type="datetime")
     */
    public $lastChangeDate;
    
    /**
     * @Column(type="integer")
     */
    public $browserNameSameResult;

    /**
     * @Column(type="integer")
     */
    public $browserNameHarmonizedSameResult;

    /**
     * @Column(type="integer")
     */
    public $browserVersionSameResult;

    /**
     * @Column(type="integer")
     */
    public $browserVersionHarmonizedSameResult;

    /**
     * @Column(type="integer")
     */
    public $engineNameSameResult;

    /**
     * @Column(type="integer")
     */
    public $engineNameHarmonizedSameResult;

    /**
     * @Column(type="integer")
     */
    public $engineVersionSameResult;

    /**
     * @Column(type="integer")
     */
    public $engineVersionHarmonizedSameResult;

    /**
     * @Column(type="integer")
     */
    public $osNameSameResult;

    /**
     * @Column(type="integer")
     */
    public $osNameHarmonizedSameResult;

    /**
     * @Column(type="integer")
     */
    public $osVersionSameResult;

    /**
     * @Column(type="integer")
     */
    public $osVersionHarmonizedSameResult;

    /**
     * @Column(type="integer")
     */
    public $deviceModelSameResult;

    /**
     * @Column(type="integer")
     */
    public $deviceModelHarmonizedSameResult;

    /**
     * @Column(type="integer")
     */
    public $deviceBrandSameResult;

    /**
     * @Column(type="integer")
     */
    public $deviceBrandHarmonizedSameResult;

    /**
     * @Column(type="integer")
     */
    public $deviceTypeSameResult;

    /**
     * @Column(type="integer")
     */
    public $deviceTypeHarmonizedSameResult;

    /**
     * @Column(type="integer")
     */
    public $asMobileDetectedByOthers;

    /**
     * @Column(type="integer")
     */
    public $asTouchDetectedByOthers;

    /**
     * @Column(type="integer")
     */
    public $asBotDetectedByOthers;

    /**
     * @Column(type="integer")
     */
    public $botNameSameResult;

    /**
     * @Column(type="integer")
     */
    public $botNameHarmonizedSameResult;

    /**
     * @Column(type="integer")
     */
    public $botTypeSameResult;

    /**
     * @Column(type="integer")
     */
    public $botTypeHarmonizedSameResult;

    public function __construct()
    {
        $this->id = Uuid::uuid4();
    }
}
