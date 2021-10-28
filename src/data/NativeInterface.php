<?php

namespace gq_group\Teryt\data;

use DateTime;
use SplFileObject;
use mrcnpdlk\Teryt\NativeApi;
use mrcnpdlk\Teryt\ResponseModel\Territory\ZweryfikowanyAdres;
use mrcnpdlk\Teryt\ResponseModel\Territory\ZweryfikowanyAdresBezUlic;

interface NativeInterface {
    public function CzyZalogowany() : bool;
    public function PobierzDateAktualnegoKatTerc() : ?string;
    public function PobierzDateAktualnegoKatNTS() : ?string;
    public function PobierzDateAktualnegoKatSimc() : ?string;
    public function PobierzDateAktualnegoKatUlic() : ?string;
    public function PobierzListeWojewodztw() : array;
    public function PobierzListePowiatow(string $provinceId) : array;
    public function PobierzListeGmin(string $provinceId, string $districtId) : array;
    public function PobierzGminyiPowDlaWoj(string $provinceId) : array;
    public function PobierzListeRegionow() : array;
    public function PobierzListeWojewodztwWRegionie(string $regionId) : array;
    public function PobierzListePodregionow(string $provinceId) : array;
    public function PobierzListePowiatowWPodregionie(string $subregionId): array;
    public function PobierzListeGminPowiecie(string $districtId, string $subregionId) : array;
    public function PobierzListeUlicDlaMiejscowosci(int $tercId, string $cityId, bool $asAddress = false) : array;
    public function PobierzListeMiejscowosciWGminie(string $provinceName, string $districtName, string $communeName) : array;
    public function PobierzListeMiejscowosciWRodzajuGminy(int $tercId) : array;
    public function PobierzSlownikRodzajowJednostek() : array;
    public function PobierzSlownikRodzajowSIMC() : array;
    public function PobierzSlownikCechULIC() : array;
    public function PobierzKatalogTERCAdr() : SplFileObject;
    public function PobierzKatalogTERC() : SplFileObject;
    public function PobierzKatalogNTS() : SplFileObject;
    public function PobierzKatalogSIMCAdr() : SplFileObject;
    public function PobierzKatalogSIMC(): SplFileObject;
    public function PobierzKatalogSIMCStat() : SplFileObject;
    public function PobierzKatalogULIC() : SplFileObject;
    public function PobierzKatalogULICAdr() : SplFileObject;
    public function PobierzKatalogULICBezDzielnic() : SplFileObject;
    public function PobierzKatalogWMRODZ() : SplFileObject;
    public function PobierzZmianyTercUrzedowy(DateTime $fromDate, DateTime $toDate = null) : SplFileObject;
    public function PobierzZmianyTercAdresowy(DateTime $fromDate, DateTime $toDate = null) : SplFileObject;
    public function PobierzZmianyNTS(DateTime $fromDate, DateTime $toDate = null) : SplFileObject;
    public function PobierzZmianySimcUrzedowy(DateTime $fromDate, DateTime $toDate = null) : SplFileObject;
    public function PobierzZmianySimcAdresowy(DateTime $fromDate, DateTime $toDate = null) : SplFileObject;
    public function PobierzZmianySimcStatystyczny(DateTime $fromDate, DateTime $toDate = null) : SplFileObject;
    public function PobierzZmianyUlicUrzedowy(DateTime $fromDate, DateTime $toDate = null) : SplFileObject;
    public function PobierzZmianyUlicAdresowy(DateTime $fromDate, DateTime $toDate = null) : SplFileObject;
    public function WeryfikujAdresDlaMiejscowosci(string $cityId) : ZweryfikowanyAdresBezUlic;
    public function WeryfikujAdresDlaMiejscowosciAdresowy(string $cityId) : ZweryfikowanyAdresBezUlic;
    public function WeryfikujAdresWmiejscowosci(string $provinceName, string $districtName, string $communeName, string $cityName, string $cityTypeName = null) : array;
    public function WeryfikujAdresWmiejscowosciAdresowy(string $provinceName, string $districtName, string $communeName, string $cityName, string $cityTypeName = null) : array;
    public function WeryfikujAdresDlaUlic(string $cityId, string $streetId) : ZweryfikowanyAdres;
    public function WeryfikujAdresDlaUlicAdresowy(string $cityId, string $streetId) : ZweryfikowanyAdres;
    public function WeryfikujNazwaAdresUlic(string $provinceName, string $districtName, string $communeName, string $cityName, string $cityTypeName = null, string $streetName) : array;
    public function WeryfikujNazwaAdresUlicAdresowy(string $provinceName, string $districtName, string $communeName, string $cityName, string $cityTypeName = null, string $streetName) : array;
    public function WyszukajJPT(string $name) : array;
    public function WyszukajMiejscowosc(string $cityName = null, string $cityId = null) : array;
    public function WyszukajMiejscowoscWJPT(string $provinceName, string $districtName, string $communeName, string $cityName, string $cityId = null) : array;
    public function WyszukajUlice(string $streetName = null, string $streetIdentityName = null, string $cityName = null) : array;
    public function WyszukajJednostkeWRejestrze(string $name = null, string $category = null, array $tSimc = [], array $tTerc = []) : array;
    public function WyszukajMiejscowoscWRejestrze(string $name = null, string $cityId = null, array $tSimc = [], array $tTerc = [], string $cityTypeName = NativeApi::SEARCH_CITY_TYPE_ALL) : array;
    public function WyszukajUliceWRejestrze(string $name = null, string $identityName = 'ul.', string $streetId = null, array $tSimc = [], array $tTerc = []) : array;
}