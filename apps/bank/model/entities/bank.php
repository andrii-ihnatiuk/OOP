<?php


namespace Model\Entities;

class Bank {
    use \Library\Shared;
    use \Library\Entity;

    public function __construct(private String $name, private String $code, private ?Int $users = 0, private ?Int $id = 0) {
        $this->db = $this->getDB();
    }


    public function save():self {
        $db = $this->db;

        if (!$this->id) {
            $this->id = $db->insert([
				'Banks' => [ 
                    'name' => $this->name, 
                    'code' => $this->code,
                    'users' => $this->users 
                ]
			])->run(true)->storage['inserted'];
        } 
        if ($this->_changed) {
            $db -> update('Banks', $this->_changed)
                -> where(['Banks'=> ['id' => $this->id]])
                -> run();
        }
        
        return $this;
    }   

    public static function get(?String $name = null, ?String $code = null,  Int $limit = 0):self|array|null {
		$result = [];
		foreach (['name', 'code'] as $var)
			if ($$var)
				$filters[$var] = $$var;
		$db = self::getDB();
		$banks = $db -> select(['Banks' => []]);
		if(!empty($filters))
			$banks->where(['Banks' => $filters]);
        
		foreach ($banks->many($limit) as $bank) {
				$class = __CLASS__;
                $result[] = new $class($bank['name'], $bank['code'], $bank['users'], $bank['id']);
		}
        
		return $limit == 1 ? (isset($result[0]) ? $result[0] : null) : $result;
	}

    public function format():array {
        $data = ['name' => $this->name, 'code' => $this->code, 'users' => $this->users, 'id' => $this->id];
        return $data;
    }

    public function getName(): string {
        return $this->name;
    }

    public function getCode(): string {
        return $this->code;
    }

    public function getUsers(): Int {
        return $this->users;
    }

    public function setName(String $name) {
        $this->name = $name;
    }

    public function setCode(String $code) {
        $this->code = $code;
    }

    public function setUsers(Int $users) {
        $this->users = $users;
    }


}