<?php

class CsvDataImporter extends AuthController
{
    public function import($file)
    {
        $records = $this->loadFile($file);

        $this->importData($records);
    }

    private function loadFile($file)
    {
        $records = array();
        if (false !== $handle = fopen($file, 'r')) {
            while ($record = fgetcsv($handle)) {
                $records[] = $record;
            }
        }
        fclose($handle);

        return $records;
    }

    private function importData(array $records)
    {
        try {
            $this->db->beginTransaction();
            foreach ($records as $record) {
                $stmt = $this->db->prepare('INSERT INTO ...');
                $stmt->execute($record);
            }
            $this->db->commit();
        } catch (PDOException $e) {
            $this->db->rollback();
            throw $e;
        }
    }
}