<?php


namespace Model\Entities;


class Message {  
    use \Library\Shared;

    
    public function __construct(private String $text, private ?String $label = null, private ?Int $id = 0) {
        $this->db = $this->getDB();
    }


    public function save():self {
        $db = $this->db;

        if (!$this->id) {
            $this->id = $db->insert([
				'Messages' => [ 'text' => $this->text, 'label' => $this->label]
			])->run(true)->storage['inserted'];
        }
        
        return $this;
    }    

    public static function search(Int $id = null, String $label = null, Int $limit = 0):self|string|null {
		$result = [];
		foreach (['id', 'label'] as $var)
			if ($$var)
				$filters[$var] = $$var;
		$db = self::getDB();
		$messages = $db -> select(['Messages' => []]);
		if(!empty($filters))
			$messages->where(['Messages' => $filters]);
		foreach ($messages->many($limit) as $message) {
				$class = __CLASS__;
				$result[] = new $class($message['text'], $message['id']);
		}

		return $result[0]->getText();
    }
    

    public function getText():string {
        return $this->text;
    }
 

    public function setText(String $text):void {
        $this->text = $text;
    }


}