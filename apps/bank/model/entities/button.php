<?php


namespace Model\Entities;


class Button {  
    use \Library\Shared;

    
    public function __construct(private String $title, private ?String $request = null, private ?String $value = null, private ?Int $id = 0) {
        $this->db = $this->getDB();
    }


    public function save():self {
        $db = $this->db;
        if (!$this->id) {
            $this->id = $db->insert([
				'Buttons' => [ 'title' => $this->title, 'value' => $this->value, 'request' => $this->request]
			])->run(true)->storage['inserted'];
        }
        
        return $this;
    }    

    // TODO: Добавить возможность получения всех кнопок за раз
    public static function get(?Int $id = null, Int $limit = 0):self|array|null {
		$result = [];
        foreach (['id'] as $var)
			if ($$var)
				$filters[$var] = $$var;
		$db = self::getDB();
		$buttons = $db -> select(['Buttons' => []]);
		if(!empty($filters))
			$buttons->where(['Buttons' => $filters]);
		foreach ($buttons->many($limit) as $button) {
			$class = __CLASS__;
			$result[] = new $class($button['title'], $button['request'], $button['value'], $button['id']);
            break;
		}

		return $result[0]->toArray();
	}

    public function toArray():array {
        $data = ['id' => $this->id, 'title' => $this->title, 'request' => $this->request, 'value' => $this->value];
        return $data;
    }
    
    public function getTitle():string {
        return $this->title;
    }

    public function getValue():string {
        return $this->value;
    }

    public function getRequest():string {
        return $this->request;
    }

    public function setTitle(String $title):void {
        $this->title = $title;
    }

    public function setValue(String $value):void {
        $this->value = $value;
    }

    public function setRequest(String $request):void {
        $this->request = $request;
    }


}