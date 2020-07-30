<?php

/**
 * Gestion des lecteurs
 */

namespace App;

use App\Collection\PersonFollowedCollection;

class Reader extends User {
	
	/**
	 * Clé en session
	 */
	protected const SESSION_KEY = 'reader_user';
	
	/**
	 * Personnes suivies par le lecteur
	 * @var array
	 */
	private ?array $_persons_followed = NULL;
	
	/********************************************************/
	
	/**
	 * Retourne les personnes suivies par le lecteur
	 * @return array
	 */
	public function personsFollowed() : array
	{
		if($this->_persons_followed === NULL)
		{
			$personsFollowed = [];
			$types = Contributor::allowedTypes();
			array_walk($types, function($type) use (&$personsFollowed) {
				$personsFollowed[$type] = [];
			});
			
			$collection = PersonFollowedCollection::factory()->reader($this)->get();
			
			foreach($collection as $personFollowed)
			{
				$personsFollowed[$personFollowed->type][$personFollowed->person] = $personFollowed;
			}
			
			$this->_persons_followed = $personsFollowed;
		}
		return $this->_persons_followed;
	}
	
	/**
	 * Retourne si le lecteur suit la personne en paramètre
	 * @param Person $person
	 * @param string $type
	 * @return bool
	 */
	public function followed(Person $person, string $type) : bool
	{
		$personsFollowed = getArray($this->personsFollowed(), $type, []);
		return (array_key_exists($person->id, $personsFollowed));
	}
	
	/**
	 * Ajoute la personne dont le type est en paramètre, à la liste des abonnements
	 * @param Person $person
	 * @param string $type
	 * @return bool
	 */
	public function follow(Person $person, string $type) : bool
	{
		$alreadyFollowed = $this->followed($person, $type);
		if($alreadyFollowed)
		{
			return FALSE;
		}
		
		$personFollowed = PersonFollowed::factory([
			'type' => $type,
		]);
		$personFollowed->reader($this);
		$personFollowed->person($person);
		
		$success = $personFollowed->save();
		if($success)
		{
			$this->_persons_followed[$type][$person->id] = $person;
		}
		
		return $success;
	}
	
	/**
	 * Retire la personne dont le type est en paramètre, à la liste des abonnements
	 * @param Person $person
	 * @param string $type
	 * @return bool
	 */
	public function unfollow(Person $person, string $type) : bool
	{
		$followed = $this->followed($person, $type);
		if(! $followed)
		{
			return FALSE;
		}
		
		$personFollowed = PersonFollowed::factory([
			'type' => $type,
		]);
		$personFollowed->reader($this);
		$personFollowed->person($person);
		
		$success = $personFollowed->delete();
		if($success)
		{
			unset($this->_persons_followed[$type][$person->id]);
		}
		
		return $success;
	}
	
	/********************************************************/
	
	/**
	 * Retourne les noms des routes
	 * @return array
	 */
	protected static function _routeNames() : array
	{
		return [
			'login' => 'login',
			'logout' => 'logout',
		];
	}
	
	/********************************************************/
	
}