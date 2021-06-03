<?php


namespace Model\Entities;


class User {  
    use \Library\Shared;

    
    public function __construct(private String $guid, private String $iban, private String $name, private String $surname, private String $patronymic, private ?Int $id = 0) {
        $this->db = $this->getDB();
    }


    public function save():self {
        $db = $this->db;

        if (!$this->id) {
            $this->id = $db->insert([
				'Users' => [ 'guid' => $this->guid, 'name' => $this->name, 
                             'surname' => $this->surname, 'patronymic' => $this->patronymic, 
                             'iban' => $this->iban]
			])->run(true)->storage['inserted'];
        }
        
        return $this;
    }    

    public static function get(?String $guid = '', Int $limit = 0):self|array|null {
		$result = [];
		foreach (['guid'] as $var)
			if ($$var)
				$filters[$var] = $$var;
		$db = self::getDB();
		$users = $db -> select(['Users' => []]);
		if(!empty($filters))
			$users->where(['Users' => $filters]);
		foreach ($users->many($limit) as $user) {
				$class = __CLASS__;
				$result[] = new $class($user['guid'], $user['iban'], $user['name'], $user['surname'], $user['patronymic'], $user['id']);
		}
		return $limit == 1 ? (isset($result[0]) ? $result[0] : null) : $result;
	}
    
    public function getGuid():string {
        return $this->guid;
    }

    public function getIban():string {
        return $this->iban;
    }

    public function getName():string {
        return $this->name;
    }

    public function getSurname():string {
        return $this->surname;
    }

    public function getPatronymic():string {
        return $this->patronymic;
    }

    public function toString():string {
        return $this->surname . ' ' . $this->name . ' ' . $this->patronymic;
    }

    public function setGuid(Int $id):void {
        $this->guid = $id;
    }

    public function setIban(Int $iban):void {
        $this->iban = $iban;
    }

    public function setName(String $name):void {
        $this->name = $name;
    }

    public function setSurname(String $surname):void {
        $this->surname = $surname;
    }

    public function setPatronymic(String $patronymic):void {
        $this->patronymic = $patronymic;
    }

}