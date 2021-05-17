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

    //* OLD METHOD
    // public static function get(?Int $id = null, Int $limit = 0):self|array|null {
	// 	$result = [];
    //     foreach (['id'] as $var)
	// 		if ($$var)
	// 			$filters[$var] = $$var;
	// 	$db = self::getDB();
	// 	$buttons = $db -> select(['Buttons' => []]);
	// 	if(!empty($filters))
	// 		$buttons->where(['Buttons' => $filters]);
	// 	foreach ($buttons->many($limit) as $button) {
	// 		$class = __CLASS__;
	// 		$result[] = new $class($button['title'], $button['request'], $button['value'], $button['id']);
    //         break;
	// 	}

	// 	return $result[0]->toArray();
	// }

    //* ДОБАВЛЕНА ВОЗМОЖНОСТЬ ВЫБОРА ОПРЕДЕЛЕННЫХ КНОПОК ЗА ОДИН ЗАПРОС
    public static function get(Mixed $id = null, Int $limit = 0):self|array|null {
		$result = [];
        $modificator = 'AND';
        if (gettype($id) == 'array') {
            for ($i = 0; $i < count($id); $i++) {
                if (isset($id[$i])) {
                    $filters['id'][] = $id[$i];
                }
            }
            $modificator = 'OR';
        } else
            foreach (['id'] as $var)
			    if ($$var)
				    $filters[$var] = $$var;

		$db = self::getDB();
		$buttons = $db -> select(['Buttons' => []]);

		if(!empty($filters))
			$buttons->where(['Buttons' => $filters], $modificator);
		foreach ($buttons->many($limit) as $button) {
            $class = __CLASS__;
			$btn = new $class($button['title'], $button['request'], $button['value'], $button['id']);
            $result[] = $btn->toArray();
		}

        // header("Content-type: application/json; charset='utf-8'");
        // echo(json_encode($result));

		return gettype($id) == 'integer' ? (isset($result[0]) ? $result[0] : null) : $result;
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