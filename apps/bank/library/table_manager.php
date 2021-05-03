<?php


namespace Library;

class TableManager {

    public function __construct() {
    }

    public function getHandle(String $fileName): ?string { // Формирует путь к файлу
        $handle = ROOT . '/library/' . "$fileName.csv"; 
         return $handle;
    }

    public function createFile(String $fileName):bool {
        $handle = $this->getHandle($fileName);

        if (file_exists($handle)) {
            return false;
        }

        $header = array(
            '№', 'IBAN платника', 'Сума', 'Валюта', 'IBAN отримувача', 'Назва отримувача', 
            'Ідентифікаційний код отримувача (ЕДРПО)', 'Призначення платежу'
        );

        return $this->writeData($fileName, $header);
    }

    public function writeData(String $fileName, array $data):bool {
        $handle = $this->getHandle($fileName);
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
        
        return true;
    }

}