<?php


namespace Library;

class TableManager {

    public function __construct() {
    }

    private static function getHandle(String $fileName): ?string { // Формирует путь к файлу
        $handle = ROOT . '/library/data/' . "$fileName.csv"; 
         return $handle;
    }

    public static function createFile(String $fileName):bool {
        $handle = self::getHandle($fileName);

        if (file_exists($handle)) {
            return false;
        }

        $header = array(
            '№', 'IBAN платника', 'Сума', 'Валюта', 'IBAN отримувача', 'Назва отримувача', 
            'Ідентифікаційний код отримувача (ЕДРПО)', 'Призначення платежу'
        );

        return $this->writeData($fileName, $header);
    }

    public static function writeData(String $fileName, array $data):bool {
        $handle = self::getHandle($fileName);
        $file = fopen($handle, 'a');

        if (is_null($file)) {
            return false;
        }

        if (is_array($data[0])) {
            foreach($data as $row) {
                fputcsv($file, $row, ';');
            }
        }
        else {
            fputcsv($file, $data, ';');
        }
        fclose($file);

        $file = fopen(ROOT . '/library/data/last_modified.txt', 'w');
        fwrite($file, filemtime(ROOT . '/library/data/last_modified.txt'));
        fclose($file);
        
        return true;
    }

}