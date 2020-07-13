<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use PDO;

class FilesController extends Controller
{
    private static $peopleFilesFolderPath = '../storage/app/people_files/';
    
    private static $groupFilesFolderPath = '../storage/app/group_files/';
    
    private static $appFolder = '../storage/app/';
    
    private static $tableState;
    
    /**
     * Store a newly created resource in storage.
     *\Illuminate\Http\Response
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return string
     */
    public function store(Request $request) {
        try {
            $peopleCsv = $request->file('people_file') ?? null;
            $groupsCsv = $request->file('groups_file') ?? null;
            
            if($peopleCsv) {
                $fileName = $peopleCsv->store('people_files');
            }
            else {
                $fileName = $groupsCsv->store('group_files');
            }
            
            self::$tableState = self::$appFolder . $fileName;
            $ml = __METHOD__ . ', line: ' . __LINE__;
            Log::info(self::$tableState . $ml);
            $this->bulkUploadToDb();
            
            return $fileName;
        }
        catch(\Throwable $e) {
            $ml = __METHOD__ . ', line: ' . __LINE__;
            $message = "__>> JULIUS ERROR: {$e->getMessage()} $ml";
            Log::info($message);
            return $message;
        }
    }
    
    public function debug() {
        // manually give a csv file name that got uploaded from React
        $csvFileName = 'groups.csv';
        
        $this->bulkUploadToDb();
    }
    
    /**
     * @return array
     */
    public static function csvToArray(): array {
        $csv = [];
        $count = 0;
        $csvFile = self::$tableState;
        
        Log::info("________> $csvFile \n\n" . __METHOD__ . ' line: ' . __LINE__);
        
        if(($handle = fopen($csvFile, 'r')) !== false) {
            while(($data = fgetcsv($handle, 8096, ",")) !== false) {
                $csv[$count] = $data;
                ++$count;
            }
            fclose($handle);
        }
        
        return $csv;
    }
    
    
    /**
     * bulk insert in batches of 1,000 records
     *
     * @param string $fileName
     * @param string|null $table
     *
     * @throws \Exception
     */
    private function bulkUploadToDb(): void {
        try {
            Log::info(__METHOD__ . ' line: ' . __LINE__);
            $data = self::csvToArray();
            $batch = array_chunk($data, 1000);
            $qPeople = 'insert into people (first_name, last_name, email_address, status) values ';;
            $qGroups = 'insert into groups (group_name) values ';
            $q = null;
            $containsPeople = stripos(self::$tableState, 'people') !== false;
            
            // space_time_analysis = ~O(1000n)
            // OUTER_LOOP: O(n)
            foreach($batch as $data) {
                // INNER_LOOP: O(1000)
                if($containsPeople) $q = $this->buildInsertQuery($data, $qPeople);
                else $q = $this->buildInsertQuery($data, $qGroups);
            }
            
            $ml = __METHOD__ . ' line: ' . __LINE__;
            $message = "_>  JULIUS_ERROR: the insert query is null ~$ml \n\n $q";
            Log::info($message);
            
            if(is_null($q)) {
                throw new \Exception($message);
            }
            
            $pdo = new PDO('mysql:dbname=laravel;host=localhost', 'root', '');
            
            // use a prepared statement
            $pdo->prepare($q)->execute();
            Log::info(__METHOD__ . ', line: ' . __LINE__);
            echo '_> successfully inserted data';
            unset($pdo);
        }
        catch(\Throwable $e) {
            $ml = __METHOD__ . ' line: ' . __LINE__;
            $err = $e->getMessage();
            echo "_> JULIUS_ERROR: $err ~$ml";
        }
    }
    
    /**
     * Construct an insert query that will concatenate as many
     * values as records per batch
     *
     * @param $data
     *
     * @param $query
     *
     * @return string
     */
    private function buildInsertQuery($data, $query): string {
        // cache header fow
        $headerRow = $data[0];
        
        foreach($data as $i => $datum) {
            //skip the header row
            if(0 === $i) continue;
            // deal with column order
            $datum = array_combine($headerRow, $datum);
            // wrap in single 'quotes'
            foreach($datum as $key => $value) $datum[$key] = "'{$datum[$key]}'";
            $query .= ('(' . implode(', ', $datum) . '),');
        }
        
        // remove the trailing ','
        $query = substr($query, 0, strlen($query) - 1);
        
        return $query;
    }
}
