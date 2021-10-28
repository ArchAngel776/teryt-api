<?php declare(strict_types=1);

namespace gq_group\Tests;

use DateTime;
use PHPUnit\Framework\TestCase;
use mrcnpdlk\Teryt\ResponseModel\Territory\Miejscowosc;
use mrcnpdlk\Lib\ConfigurationException;
use mrcnpdlk\Teryt\Exception;
use mrcnpdlk\Teryt\Exception\Connection;
use gq_group\Teryt\Config;
use gq_group\Teryt\NativeApi;

/**
 * Class TerytTest
 *
 * @property NativeApi $api
 */
class TerytTest extends TestCase {
    const ENV = "test";
    protected $api;

    /**
     * @throws ConfigurationException
     * @throws UndefinedEnvironmentException
     */
    protected function assertPreConditions() : void {
        parent::assertPreConditions();
        $config = new Config($this->getConfig());
        $this->api = NativeApi::create($config);
    }

    /**
     * @throws UndefinedEnvironmentException
     */
    protected function getConfig() : array {
        switch (self::ENV) {
            case "test":
                return $this->getTestConfig();
            case "prod":
                return $this->getProductionConfig();
            default:
                throw new UndefinedEnvironmentException(self::ENV);
        }
    }

    protected function getTestConfig() : array {
        return [
            "isProduction" => false
        ];
    }

    protected function getProductionConfig() : array {
        return [
            "isProduction" => true,
            "username" => $_ENV["TERYT_USER"],
            "password" => $_ENV["TERYT_PASS"]
        ];
    }

    /**
     * @test
     * @throws Exception
     * @throws Connection
     */
    public function checkLogin() : void {
        $this->assertTrue($this->api->CzyZalogowany());
    }

    /**
     * @test
     * @throws Exception
     * @throws Connection
     */
    public function findStreetCurrent() : void {
        $date = Date::get("27", "09", "2021");
        if ($place = $this->extractPlace("Stargard", $date)) {
            $terc_id = $this->getTercId($place);
            $streets = $this->api->PobierzListeUlicDlaMiejscowosci($terc_id, $place->cityId);
            foreach ($streets as $street) {
                if ($street->streetName1 === "Modra") {
                    $this->assertTrue(true);
                    return;
                }
            }
            $this->assertTrue(false);
        }
        $this->assertTrue(false);
    }

    /**
     * @test
     * @throws Exception
     * @throws Connection
     */
    public function findStreetOld() : void {
        $date = Date::get("27", "09", "2016");
        if ($place = $this->extractPlace("Stargard", $date)) {
            $terc_id = $this->getTercId($place);
            $streets = $this->api->DataStanu($date)->PobierzListeUlicDlaMiejscowosci($terc_id, $place->cityId);
            foreach ($streets as $street) {
                if ($street->streetName1 === "Kruczkowskiego" && $street->streetName2 === "Leona") {
                    $this->assertTrue(true);
                    return;
                }
            }
            $this->assertTrue(false);
        }
        $this->assertTrue(false);
    }

    /**
     * @throws Exception
     * @throws Connection
     */
    protected function extractPlace(string $name, DateTime $date) : ?Miejscowosc {
        foreach ($this->api->DataStanu($date)->WyszukajMiejscowosc($name) as $place)
            if ($place->cityName === $name)
                return $place;
        return null;
    }

    protected function getTercId(Miejscowosc $place) : int {
        return intval($place->provinceId . $place->districtId . $place->communeId . $place->communeTypeId);
    }
}