<?php

namespace gq_group\Teryt;

use DateTime;

use SplFileObject;
use RuntimeException;
use LogicException;
use mrcnpdlk\Teryt\NativeApi as NativeApiBase;
use mrcnpdlk\Teryt\ResponseModel\Territory\JednostkaTerytorialna;
use mrcnpdlk\Teryt\ResponseModel\Territory\JednostkaPodzialuTerytorialnego;
use mrcnpdlk\Teryt\ResponseModel\Territory\JednostkaNomenklaturyNTS;
use mrcnpdlk\Teryt\ResponseModel\Territory\Miejscowosc;
use mrcnpdlk\Teryt\ResponseModel\Dictionary\RodzajMiejscowosci;
use mrcnpdlk\Teryt\ResponseModel\Territory\UlicaDrzewo;
use mrcnpdlk\Teryt\ResponseModel\Territory\Ulica;
use mrcnpdlk\Teryt\ResponseModel\Territory\WyszukanaMiejscowosc;
use mrcnpdlk\Teryt\ResponseModel\Territory\WyszukanaUlica;
use mrcnpdlk\Teryt\ResponseModel\Territory\ZweryfikowanyAdres;
use mrcnpdlk\Teryt\ResponseModel\Territory\ZweryfikowanyAdresBezUlic;
use mrcnpdlk\Teryt\Exception;
use mrcnpdlk\Teryt\Exception\NotFound;
use mrcnpdlk\Teryt\Exception\Connection;
use gq_group\Teryt\data\NativeInterface;

/**
 * Class NativeApi
 * @package gq_group\Teryt
 *
 * @property NativeApiBase $target
 * @property Config $config
 */
class NativeApi implements NativeInterface {
    protected static $instance;
    protected $target;
    protected $config;

    protected function __construct(Config $configuration) {
        $this->config = clone $configuration;
        $this->target = NativeApiBase::create($configuration);
    }

    public static function create(Config $configuration) : NativeApi {
        static::$instance = new self($configuration);
        return static::$instance;
    }

    /**
     * @throws Exception
     */
    public static function getInstance() : NativeApi {
        if (null === static::$instance)
            throw new Exception('First call CREATE method!');

        return static::$instance;
    }

    public function DataStanu(DateTime $dateState) : NativeApi {
        $configuration = clone $this->config;
        $configuration->setDateState($dateState);
        $this->target = NativeApiBase::create($configuration);
        return $this;
    }

    /**
     * Sprawdza czy u??ytkownik jest zalogowany
     *
     * @throws Exception
     * @throws Connection
     *
     * @return bool
     */
    public function CzyZalogowany() : bool {
        return $this->target->CzyZalogowany();
    }

    /**
     * Data pocz??tkowa bie????cego stanu katalogu TERC.
     *
     * @throws Exception
     * @throws Connection
     *
     * @return string|null
     */
    public function PobierzDateAktualnegoKatTerc(): ?string {
        return $this->target->PobierzDateAktualnegoKatTerc();
    }

    /**
     * Data pocz??tkowa bie????cego stanu katalogu NTS.
     *
     * @throws Exception
     * @throws Connection
     *
     * @return string|null
     */
    public function PobierzDateAktualnegoKatNTS() : ?string {
        return $this->target->PobierzDateAktualnegoKatNTS();
    }

    /**
     * Data pocz??tkowa bie????cego stanu katalogu SIMC.
     *
     * @throws Exception
     * @throws Connection
     *
     * @return string|null
     */
    public function PobierzDateAktualnegoKatSimc() : ?string {
        return $this->target->PobierzDateAktualnegoKatSimc();
    }

    /**
     * Data pocz??tkowa bie????cego stanu katalogu ULIC.
     *
     * @throws Exception
     * @throws Connection
     *
     * @return string|null
     */
    public function PobierzDateAktualnegoKatUlic(): ?string {
        return $this->target->PobierzDateAktualnegoKatUlic();
    }

    /**
     * Lista wojew??dztw
     *
     * @throws Exception
     * @throws Connection
     *
     * @return JednostkaTerytorialna[]
     */
    public function PobierzListeWojewodztw() : array {
        return $this->target->PobierzListeWojewodztw();
    }

    /**
     * Lista powiat??w we wskazanym wojew??dztwie
     *
     * @param string $provinceId
     *
     * @throws Exception
     * @throws Connection
     *
     * @return JednostkaTerytorialna[]
     */
    public function PobierzListePowiatow(string $provinceId) : array {
        return $this->target->PobierzListePowiatow($provinceId);
    }

    /**
     * Lista gmin we wskazanym powiecie
     *
     * @param string $provinceId
     * @param string $districtId
     *
     * @throws Exception
     * @throws Connection
     *
     * @return JednostkaTerytorialna[]
     */
    public function PobierzListeGmin(string $provinceId, string $districtId) : array {
        return $this->target->PobierzListeGmin($provinceId, $districtId);
    }

    /**
     * Lista powiat??w i gmin we wskazanym wojew??dztwie
     *
     * @param string $provinceId
     *
     * @throws Exception
     * @throws Connection
     *
     * @return JednostkaTerytorialna[]
     */
    public function PobierzGminyiPowDlaWoj(string $provinceId) : array {
        return $this->target->PobierzGminyiPowDlaWoj($provinceId);
    }

    /**
     * Lista region??w
     *
     * @throws Exception
     * @throws Connection
     *
     * @return JednostkaNomenklaturyNTS[]
     */
    public function PobierzListeRegionow() : array {
        return $this->target->PobierzListeRegionow();
    }

    /**
     * Lista wojew??dztw w regionie
     *
     * @param string $regionId
     *
     * @throws Exception
     * @throws Connection
     *
     * @return JednostkaNomenklaturyNTS[]
     */
    public function PobierzListeWojewodztwWRegionie(string $regionId) : array {
        return $this->target->PobierzListeWojewodztwWRegionie($regionId);
    }

    /**
     * Lista podregion??w
     *
     * @param string $provinceId
     *
     * @throws Exception
     * @throws Connection
     *
     * @return JednostkaNomenklaturyNTS[]
     */
    public function PobierzListePodregionow(string $provinceId) : array {
        return $this->target->PobierzListePodregionow($provinceId);
    }

    /**
     * Lista powiat??w w podregionie
     *
     * @param string $subregionId
     *
     * @throws Exception
     * @throws Connection
     *
     * @return JednostkaNomenklaturyNTS[]
     */
    public function PobierzListePowiatowWPodregionie(string $subregionId) : array {
        return $this->target->PobierzListePowiatowWPodregionie($subregionId);
    }

    /**
     * Lista gmin w powiecie
     *
     * @param string $districtId
     * @param string $subregionId
     *
     * @throws Exception
     * @throws Connection
     *
     * @return JednostkaNomenklaturyNTS[]
     */
    public function PobierzListeGminPowiecie(string $districtId, string $subregionId) : array {
        return $this->target->PobierzListeGminPowiecie($districtId, $subregionId);
    }

    /**
     * Lista ulic we wskazanej miejscowo??ci
     *
     * @param int $tercId
     * @param string $cityId
     * @param bool $asAddress
     *
     * @throws Exception
     * @throws Connection
     *
     * @return UlicaDrzewo[]
     */
    public function PobierzListeUlicDlaMiejscowosci(int $tercId, string $cityId, bool $asAddress = false) : array {
        return $this->target->PobierzListeUlicDlaMiejscowosci($tercId, $cityId, $asAddress);
    }

    /**
     * Lista miejscowo??ci znajduj??cych si?? we wskazanej gminie. Wyszukiwanie odbywa si?? z uwzgl??dnieniem nazw.
     *
     * @param string $provinceName
     * @param string $districtName
     * @param string $communeName
     *
     * @throws Exception
     * @throws Connection
     *
     * @return Miejscowosc[]
     */
    public function PobierzListeMiejscowosciWGminie(string $provinceName, string $districtName, string $communeName) : array {
        return $this->target->PobierzListeMiejscowosciWGminie($provinceName, $districtName, $communeName);
    }

    /**
     * Lista miejscowo??ci znajduj??cych si?? we wskazanej gminie. Wyszukiwanie odbywa si?? z uwzgl??dnieniem symboli.
     *
     * @param int $tercId
     *
     * @throws Exception
     * @throws Connection
     *
     * @return Miejscowosc[]
     */
    public function PobierzListeMiejscowosciWRodzajuGminy(int $tercId) : array {
        return $this->target->PobierzListeMiejscowosciWRodzajuGminy($tercId);
    }

    /**
     * Zwraca list?? rodzaj??w jednostek podzia??u terytorialnego.
     *
     * @throws Exception
     * @throws Connection
     *
     * @return string[]
     */
    public function PobierzSlownikRodzajowJednostek() : array {
        return $this->target->PobierzSlownikRodzajowJednostek();
    }

    /**
     * Zwraca list?? rodzaj??w miejscowo??ci wed??ug wybranego stanu.
     *
     * @throws Exception
     * @throws Connection
     *
     * @return RodzajMiejscowosci[]
     */
    public function PobierzSlownikRodzajowSIMC() : array {
        return $this->target->PobierzSlownikRodzajowSIMC();
    }

    /**
     * Zwraca list?? cech obiekt??w z katalogu ulic.
     *
     * @throws Exception
     * @throws Connection
     *
     * @return string[]
     */
    public function PobierzSlownikCechULIC() : array {
        return $this->target->PobierzSlownikCechULIC();
    }

    /**
     * Dane z systemu identyfikator??w TERC z wybranego stanu katalogu w wersji adresowej.
     *
     * @throws Exception
     * @throws RuntimeException
     * @throws LogicException
     * @throws Connection
     *
     * @return SplFileObject
     */
    public function PobierzKatalogTERCAdr() : SplFileObject {
        return $this->target->PobierzKatalogTERCAdr();
    }

    /**
     * Dane z systemu identyfikator??w TERC z wybranego stanu katalogu w wersji urz??dowej.
     *
     * @throws Exception
     * @throws RuntimeException
     * @throws LogicException
     * @throws Connection
     *
     * @return SplFileObject
     */
    public function PobierzKatalogTERC() : SplFileObject {
        return $this->target->PobierzKatalogTERC();
    }

    /**
     * Identyfikatory i nazwy jednostek nomenklatury z wybranego stanu katalogu.
     *
     * @throws Exception
     * @throws RuntimeException
     * @throws LogicException
     * @throws Connection
     *
     * @return SplFileObject
     */
    public function PobierzKatalogNTS() : SplFileObject {
        return $this->target->PobierzKatalogNTS();
    }

    /**
     * Dane o miejscowo??ciach z systemu identyfikator??w SIMC z wybranego stanu katalogu w wersji adresowej.
     *
     * @throws Exception
     * @throws RuntimeException
     * @throws LogicException
     * @throws Connection
     *
     * @return SplFileObject
     */
    public function PobierzKatalogSIMCAdr() : SplFileObject {
        return $this->target->PobierzKatalogSIMCAdr();
    }

    /**
     * Dane o miejscowo??ciach z systemu identyfikator??w SIMC z wybranego stanu katalogu w wersji adresowej.
     *
     * @throws Exception
     * @throws RuntimeException
     * @throws LogicException
     * @throws Connection
     *
     * @return SplFileObject
     */
    public function PobierzKatalogSIMC() : SplFileObject {
        return $this->target->PobierzKatalogSIMC();
    }

    /**
     * Dane o miejscowo??ciach z systemu identyfikator??w SIMC z wybranego stanu katalogu w wersji adresowej.
     *
     * @throws Exception
     * @throws RuntimeException
     * @throws LogicException
     * @throws Connection
     *
     * @return SplFileObject
     */
    public function PobierzKatalogSIMCStat() : SplFileObject {
        return $this->target->PobierzKatalogSIMCStat();
    }

    /**
     * Katalog ulic dla wskazanego stanu w wersji urz??dowej.
     *
     * @throws Exception
     * @throws RuntimeException
     * @throws LogicException
     * @throws Connection
     *
     * @return SplFileObject
     */
    public function PobierzKatalogULIC() : SplFileObject {
        return $this->target->PobierzKatalogULIC();
    }

    /**
     * Katalog ulic dla wskazanego stanu w wersji adresowej.
     *
     * @throws Exception
     * @throws RuntimeException
     * @throws LogicException
     * @throws Connection
     *
     * @return SplFileObject
     */
    public function PobierzKatalogULICAdr() : SplFileObject {
        return $this->target->PobierzKatalogULICAdr();
    }

    /**
     * Katalog ulic dla wskazanego stanu w wersji urz??dowej zmodyfikowany dla miast posiadaj??cy delegatury.
     *
     * @throws Exception
     * @throws RuntimeException
     * @throws LogicException
     * @throws Connection
     *
     * @return SplFileObject
     */
    public function PobierzKatalogULICBezDzielnic() : SplFileObject {
        return $this->target->PobierzKatalogULICBezDzielnic();
    }

    /**
     * Katalog rodzaj??w miejscowo??ci dla wskazanego stanu.
     *
     * @throws Exception
     * @throws RuntimeException
     * @throws LogicException
     * @throws Connection
     *
     * @return SplFileObject
     */
    public function PobierzKatalogWMRODZ() : SplFileObject {
        return $this->target->PobierzKatalogWMRODZ();
    }

    /**
     * Zmiany w katalogu TERC w wersji urz??dowej rejestru.
     *
     * @param DateTime $fromDate
     * @param DateTime|null $toDate
     *
     * @throws Exception
     * @throws RuntimeException
     * @throws LogicException
     * @throws Connection
     *
     * @return SplFileObject
     */
    public function PobierzZmianyTercUrzedowy(DateTime $fromDate, DateTime $toDate = null) : SplFileObject {
        return $this->target->PobierzZmianyTercUrzedowy($fromDate, $toDate);
    }

    /**
     * Zmiany w katalogu TERC w wersji adresowej rejestru.
     *
     * @param DateTime $fromDate
     * @param DateTime|null $toDate
     *
     * @throws Exception
     * @throws RuntimeException
     * @throws LogicException
     * @throws Connection
     *
     * @return SplFileObject
     */
    public function PobierzZmianyTercAdresowy(DateTime $fromDate, DateTime $toDate = null) : SplFileObject {
        return $this->target->PobierzZmianyTercAdresowy($fromDate, $toDate);
    }

    /**
     * Zmiany w katalogu TERC w wersji adresowej rejestru.
     *
     * @param DateTime $fromDate
     * @param DateTime|null $toDate
     *
     * @throws Exception
     * @throws RuntimeException
     * @throws LogicException
     * @throws Connection
     *
     * @return SplFileObject
     */
    public function PobierzZmianyNTS(DateTime $fromDate, DateTime $toDate = null) : SplFileObject {
        return $this->target->PobierzZmianyNTS($fromDate, $toDate);
    }

    /**
     * Zmiany w katalogu SIMC w wersji urz??dowej rejestru.
     *
     * @param DateTime $fromDate
     * @param DateTime|null $toDate
     *
     * @throws Exception
     * @throws RuntimeException
     * @throws LogicException
     * @throws Connection
     *
     * @return SplFileObject
     */
    public function PobierzZmianySimcUrzedowy(DateTime $fromDate, DateTime $toDate = null) : SplFileObject {
        return $this->target->PobierzZmianySimcUrzedowy($fromDate, $toDate);
    }

    /**
     * Zmiany w katalogu SIMC w wersji adresowej rejestru.
     *
     * @param DateTime $fromDate
     * @param DateTime|null $toDate
     *
     * @throws Exception
     * @throws RuntimeException
     * @throws LogicException
     * @throws Connection
     *
     * @return SplFileObject
     */
    public function PobierzZmianySimcAdresowy(DateTime $fromDate, DateTime $toDate = null) : SplFileObject {
        return $this->target->PobierzZmianySimcAdresowy($fromDate, $toDate);
    }

    /**
     * Zmiany w katalogu SIMC w wersji statystycznej rejestru.
     *
     * @param DateTime $fromDate
     * @param DateTime|null $toDate
     *
     * @throws Exception
     * @throws RuntimeException
     * @throws LogicException
     * @throws Connection
     *
     * @return SplFileObject
     */
    public function PobierzZmianySimcStatystyczny(DateTime $fromDate, DateTime $toDate = null) : SplFileObject {
        return $this->target->PobierzZmianySimcStatystyczny($fromDate, $toDate);
    }

    /**
     * Zmiany w katalogu ULIC w wersji urz??dowej rejestru.
     *
     * @param DateTime $fromDate
     * @param DateTime|null $toDate
     *
     * @throws Exception
     * @throws RuntimeException
     * @throws LogicException
     * @throws Connection
     *
     * @return SplFileObject
     */
    public function PobierzZmianyUlicUrzedowy(DateTime $fromDate, DateTime $toDate = null) : SplFileObject {
        return $this->target->PobierzZmianyUlicUrzedowy($fromDate, $toDate);
    }

    /**
     * Zmiany w katalogu ULIC w wersji adresowej rejestru.
     *
     * @param DateTime $fromDate
     * @param DateTime|null $toDate
     *
     * @throws Exception
     * @throws RuntimeException
     * @throws LogicException
     * @throws Connection
     *
     * @return SplFileObject
     */
    public function PobierzZmianyUlicAdresowy(DateTime $fromDate, DateTime $toDate = null) : SplFileObject {
        return $this->target->PobierzZmianyUlicAdresowy($fromDate, $toDate);
    }

    /**
     * Weryfikuje istnienie wskazanego obiektu w bazie TERYT do poziomu miejscowo??ci . Weryfikacja odbywa si?? za pomoca identyfikator??w.
     *
     * @param string $cityId
     *
     * @throws Exception
     * @throws NotFound
     * @throws Connection
     *
     * @return ZweryfikowanyAdresBezUlic
     */
    public function WeryfikujAdresDlaMiejscowosci(string $cityId) : ZweryfikowanyAdresBezUlic {
        return $this->target->WeryfikujAdresDlaMiejscowosci($cityId);
    }

    /**
     * Weryfikuje istnienie wskazanego obiektu w bazie TERYT, w wersji adresowej rejestru, do poziomu miejscowo??ci. Weryfikacja odbywa si?? za pomoca identyfikator??w.
     *
     * @param string $cityId
     *
     * @throws Exception
     * @throws NotFound
     * @throws Connection
     *
     * @return ZweryfikowanyAdresBezUlic
     */
    public function WeryfikujAdresDlaMiejscowosciAdresowy(string $cityId) : ZweryfikowanyAdresBezUlic {
        return $this->target->WeryfikujAdresDlaMiejscowosciAdresowy($cityId);
    }

    /**
     * Weryfikuje istnienie wskazanego obiektu w bazie TERYT do poziomu miejscowo??ci. Weryfikacja odbywa si?? za pomoca nazw.
     *
     * @param string $provinceName
     * @param string $districtName
     * @param string $communeName
     * @param string $cityName
     * @param string|null $cityTypeName
     *
     * @throws Exception
     * @throws Connection
     *
     * @return ZweryfikowanyAdresBezUlic[]
     */
    public function WeryfikujAdresWmiejscowosci(string $provinceName, string $districtName, string $communeName, string $cityName, string $cityTypeName = null) : array {
        return $this->target->WeryfikujAdresWmiejscowosci($provinceName, $districtName, $communeName, $cityName, $cityTypeName);
    }

    /**
     * Weryfikuje istnienie wskazanego obiektu w bazie TERYT, w wersji adresowej rejestru, do poziomu miejscowo??ci. Weryfikacja odbywa si?? za pomoca nazw.
     *
     * @param string $provinceName
     * @param string $districtName
     * @param string $communeName
     * @param string $cityName
     * @param string|null $cityTypeName
     *
     * @throws Exception
     * @throws Connection
     *
     * @return ZweryfikowanyAdresBezUlic[]
     */
    public function WeryfikujAdresWmiejscowosciAdresowy(string $provinceName, string $districtName, string $communeName, string $cityName, string $cityTypeName = null) : array {
        return $this->target->WeryfikujAdresWmiejscowosciAdresowy($provinceName, $districtName, $communeName, $cityName, $cityTypeName);
    }

    /**
     * Weryfikuje istnienie wskazanego obiektu w bazie TERYT w wersji adresowej, do poziomu miejscowo??ci. Weryfikacja odbywa si?? za pomoca identyfikator??w.
     *
     * @param string $cityId
     * @param string $streetId
     *
     * @throws Exception
     * @throws NotFound
     * @throws Connection
     *
     * @return ZweryfikowanyAdres
     */
    public function WeryfikujAdresDlaUlic(string $cityId, string $streetId) : ZweryfikowanyAdres {
        return $this->target->WeryfikujAdresDlaUlic($cityId, $streetId);
    }

    /**
     * Weryfikuje istnienie wskazanego obiektu w bazie TERYT do poziomu ulic w wersji adresowej rejestru. Weryfikacja odbywa si?? za pomoca identyfikator??w.
     *
     * @param string $cityId
     * @param string $streetId
     *
     * @throws Exception
     * @throws NotFound
     * @throws Connection
     *
     * @return ZweryfikowanyAdres
     */
    public function WeryfikujAdresDlaUlicAdresowy(string $cityId, string $streetId) : ZweryfikowanyAdres {
        return $this->target->WeryfikujAdresDlaUlicAdresowy($cityId, $streetId);
    }

    /**
     * Weryfikuje istnienie wskazanego obiektu w bazie TERYT do poziomu ulic. Weryfikacja odbywa si?? za pomoca nazw.
     *
     * @param string $provinceName
     * @param string $districtName
     * @param string $communeName
     * @param string $cityName
     * @param string|null $cityTypeName
     * @param string $streetName
     *
     * @throws Exception
     * @throws Connection
     *
     * @return ZweryfikowanyAdres[]
     */
    public function WeryfikujNazwaAdresUlic(string $provinceName, string $districtName, string $communeName, string $cityName, string $cityTypeName = null, string $streetName) : array {
        return $this->target->WeryfikujNazwaAdresUlic($provinceName, $districtName, $communeName, $cityName, $cityTypeName, $streetName);
    }

    /**
     * Weryfikuje istnienie wskazanego obiektu w bazie TERYT do poziomu ulic w wersji adresowej rejestru. Weryfikacja odbywa si?? za pomoca nazw.
     *
     * @param string $provinceName
     * @param string $districtName
     * @param string $communeName
     * @param string $cityName
     * @param string|null $cityTypeName
     * @param string $streetName
     *
     * @throws Exception
     * @throws Connection
     *
     * @return ZweryfikowanyAdres[]
     */
    public function WeryfikujNazwaAdresUlicAdresowy(string $provinceName, string $districtName, string $communeName, string $cityName, string $cityTypeName = null, string $streetName) : array {
        return $this->target->WeryfikujNazwaAdresUlicAdresowy($provinceName, $districtName, $communeName, $cityName, $cityTypeName, $streetName);
    }

    /**
     * Zwraca list?? znalezionych jednostek w katalagu TERC. Obiekty klasy JednostkaPodzialuTerytorialnego
     *
     * @param string $name
     *
     * @throws Exception
     * @throws Connection
     *
     * @return JednostkaPodzialuTerytorialnego[]
     */
    public function WyszukajJPT(string $name) : array {
        return $this->target->WyszukajJPT($name);
    }

    /**
     * Zwaraca list?? znalezionych miejscowo??ci w katalogu SIMC. Obiekty klasy Miejscowosc
     *
     * @param string|null $cityName
     * @param string|null $cityId
     *
     * @throws Exception
     * @throws Connection
     *
     * @return Miejscowosc[]
     */
    public function WyszukajMiejscowosc(string $cityName = null, string $cityId = null) : array {
        return $this->target->WyszukajMiejscowosc($cityName, $cityId);
    }

    /**
     * Zwaraca list?? znalezionych miejscowo??ci we wskazanej jednostce podzia??u terytorialnego. Obiekty klasy Miejscowosc.
     *
     * @param string $provinceName
     * @param string $districtName
     * @param string $communeName
     * @param string $cityName
     * @param string|null $cityId
     *
     * @throws Exception
     * @throws Connection
     *
     * @return Miejscowosc[]
     */
    public function WyszukajMiejscowoscWJPT(string $provinceName, string $districtName, string $communeName, string $cityName, string $cityId = null) : array {
        return $this->target->WyszukajMiejscowoscWJPT($provinceName, $districtName, $communeName, $cityName, $cityId);
    }

    /**
     * Wyszukuje wskazan?? ulic?? w katalogu ULIC. Wyszukiwanie odbywa si?? za pomoca nazw. Zwraca liste obiekt??w klasy Ulica
     *
     * @param string|null $streetName
     * @param string|null $streetIdentityName
     * @param string|null $cityName
     *
     * @throws Exception
     * @throws Connection
     *
     * @return Ulica[]
     */
    public function WyszukajUlice(string $streetName = null, string $streetIdentityName = null, string $cityName = null) : array {
        return $this->target->WyszukajUlice($streetName, $streetIdentityName, $cityName);
    }

    /**
     * Zwraca list?? znalezionych jednostek w katalagu TERC z uwzgl??dnieniem daty katalogu. Obiekty klasy JednostkaPodzialuTerytorialnego
     *
     * @param string|null $name
     * @param string|null $category
     * @param array $tSimc
     * @param array $tTerc
     *
     * @throws Exception
     * @throws Connection
     *
     * @return JednostkaPodzialuTerytorialnego[]
     */
    public function WyszukajJednostkeWRejestrze(string $name = null, string $category = null, array $tSimc = [], array $tTerc = []) : array {
        return $this->target->WyszukajJednostkeWRejestrze($name, $category, $tSimc, $tTerc);
    }

    /**
     * Zwaraca list?? znalezionych miejscowo??ci we wskazanej jednostcepodzia??u terytorialnego, z uwzgl??dnieniem daty katalogu. Obiekty klasy WyszukanaMiejscowosc
     *
     * @param string|null $name
     * @param string|null $cityId
     * @param array $tSimc
     * @param array $tTerc
     * @param string $cityTypeName
     *
     * @throws Exception
     * @throws Connection
     *
     * @return WyszukanaMiejscowosc[]
     */
    public function WyszukajMiejscowoscWRejestrze(string $name = null, string $cityId = null, array $tSimc = [], array $tTerc = [], string $cityTypeName = NativeApiBase::SEARCH_CITY_TYPE_ALL) : array {
        return $this->target->WyszukajMiejscowoscWRejestrze($name, $cityId, $tSimc, $tTerc, $cityTypeName);
    }

    /**
     * Wyszukuje wskazan?? ulic?? w katalogu ULIC, z uwzgl??dnieniem daty katalogu. Obiekty klasy WyszukanaUlica
     *
     * @param string|null $name
     * @param string $identityName
     * @param string|null $streetId
     * @param array $tSimc
     * @param array $tTerc
     *
     * @throws Exception
     * @throws Connection
     *
     * @return WyszukanaUlica[]
     */
    public function WyszukajUliceWRejestrze(string $name = null, string $identityName = 'ul.', string $streetId = null, array $tSimc = [], array $tTerc = []) : array {
        return $this->target->WyszukajUliceWRejestrze($name, $identityName, $streetId, $tSimc, $tTerc);
    }
}