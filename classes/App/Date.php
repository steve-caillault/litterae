<?php

/**
 * Gestion de date
 */

namespace App;

use DateTime, DateTimeZone, DateInterval;

final class Date {
	
	public const PERIOD_DAY = 'day';
	public const PERIOD_MONTH = 'month';
	public const PERIOD_YEAR = 'year';
	
	/**
	 * Fuseau horaire à utiliser
	 * @var string
	 */
	private string $_timezone = 'Europe/Paris';
	
	/**
	 * Objet DateTime pour la manipulation de la date
	 * @var DateTime
	 */
	private DateTime $_datetime;
	
	/**
	 * Liste des périodes autorisées
	 * @var array
	 */
	private static array $_periods_allowed = [ 
		self::PERIOD_YEAR, 
		self::PERIOD_MONTH, 
		self::PERIOD_DAY, 
	];
	
	/**************************************************************************/
	
	/* CONSTRUCTEUR / INSTANCIATION */
	
	/**
	 * Retourne une instance de Date
	 * @param string $date
	 * @param string $timezone
	 * @return self
	 */
	public static function instance(string $date, ?string $timezone = NULL) : self
	{
		return new static($date, $timezone);
	}
	
	/**
	 * Instancie une date de la date actuelle
	 * @param string $timezone
	 * @return self
	 */
	public static function now(?string $timezone = NULL) : self
	{
		return static::instance('now', $timezone);
	}
	
	/**
	 * Contructeur
	 * @param string $date
	 * @param string $timezone
	 */
	private function __construct(string $date, ?string $timezone = NULL)
	{
		$this->_timezone = ($timezone !== NULL) ? $timezone : $this->_timezone;
		$dateTimeZone = new DateTimeZone($this->_timezone);
		$this->_datetime = new DateTime($date, $dateTimeZone);
	}
	
	/**
	 * Retourne les dates comprisent entre les dates en paramètre
	 * @param string $frequency (jour, mois ou année
	 * @param self $dateSince
	 * @param self $dateUntil
	 * @return array
	 */
	public static function between(string $frequency, self $dateSince, self $dateUntil) : array
	{
		$timestampSince = $dateSince->timestamp();
		$timestampUntil = $dateUntil->timestamp();
		
		if($timestampSince > $timestampUntil)
		{
			exception('La date de début doit être inférieur à la date de fin.');
		}
		
		$dates = [];
		
		$currentDate = clone $dateSince;
		
		while($currentDate->timestamp() <= $dateUntil->timestamp())
		{
			$dates[] = Date::instance($currentDate->format('Y-m-d'), $currentDate->_timezone);
			$currentDate = $currentDate->addPeriod($frequency, 1);
		}
		
		return $dates;
	}
	
	/**************************************************************************/
	
	/**
	 * Modification de l'horaire
	 * @param int $hour
	 * @param int $minute
	 * @param int $second
	 * @return self
	 */
	public function setTime(int $hour = 0, int $minute = 0, int $second = 0)
	{
		$this->_datetime->setTime($hour, $minute, $second);
		return $this;
	}
	
	/**
	 * Ajoute une période à la date
	 * @param string $type Type de période (jour, mois ou date)
	 * @param int $value Le nombre à ajouter à la date 
	 */
	public function addPeriod(string $type, int $value) : self
	{
		if(! in_array($type, static::$_periods_allowed))
		{
			exception('Type de période non autorisé.');
		}
		
		$method = ($value >= 0) ? 'add' : 'sub';
		$interval = 'P' . abs($value) . ucfirst($type[0]);
		$dateInterval = new DateInterval($interval);
		
		$this->_datetime->{ $method }($dateInterval);
		
		// echo debug($this->_datetime) . '<br />';

		return $this;
	}
	
	/**************************************************************************/
	
	/**
	 * Retourne le nom du mois en paramètre
	 * @param int $month
	 * @return string
	 */
	public static function monthName(int $month) : string
	{
		return DateTime::createFromFormat('!m', $month)->format('F');
	}
	
	/**
	 * Retourne la date avec les mois en caractères alphabétiques
	 * @param array $options : array(
	 * 		'with_year' => <bool>, // FALSE par défault
	 * )
	 * @return string
	 */
	public function letterFormat(array $options = []) : string
	{
		$withYear = (bool) getArray($options, 'with_year', FALSE);
		
		$monthNumber = (int) $this->format('n');
		$monthName = mb_strtolower(translate(self::monthName($monthNumber)));
		
		$format = ($this->format('d') . ' ' . $monthName);
		
		if($withYear)
		{
			$format .= ' ' . $this->format('Y');
		}
		
		return $format;
	}
	
	/**
	 * Retourne la date au format demandé
	 * @param string $format Voir la fonction date de PHP
	 * @return string 
	 */
	public function format(string $format) : string
	{
		return $this->_datetime->format($format);
	}
	
	/**
	 * Retourne le timestamp de la date
	 * @return int
	 */
	public function timestamp() : int
	{
		return $this->_datetime->getTimestamp();
	}
	
	/**************************************************************************/
	
	/**
	 * Retourne le fuseau horaire par défaut
	 * @return string
	 */
	public static function defaultTimeZone() : DateTimeZone
	{
		return self::now()->_datetime->getTimezone();
	}
	
	/**
	 * Retourne le décalage du fuseau horaire par rapport à GMT
	 * @param DateTimeZone $timeZone
	 * @return int
	 */
	public static function timeZoneOffsetUTC(DateTimeZone $timeZone) : int
	{
		$datetimeUTC = new DateTime('now', new DateTimeZone('UTC'));
		return $timeZone->getOffset($datetimeUTC);
	}
	
	/**************************************************************************/

	public function __clone()
	{
		$this->_datetime = clone $this->_datetime;
	}
	
	/**************************************************************************/
	
}