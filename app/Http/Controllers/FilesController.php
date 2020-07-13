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
    
    private $pdo;
    
    public function __construct() {
        $this->pdo = new PDO('mysql:dbname=laravel;host=localhost', 'root', '');
    }
    
    public function __destruct() {
        unset($this->pdo);
    }
    
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
        
        Log::info("________> $csvFile ~" . __METHOD__ . ' line: ' . __LINE__);
        
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
            $data = self::csvToArray();
            $batch = array_chunk($data, 1000);
            $qPeople = 'insert into people (first_name, last_name, email_address, status, group_member_id) values ';;
            $qGroups = 'insert into groups (group_name) values ';
        
            $q = null;
            $containsPeople = (stripos(self::$tableState, 'people') !== false);
            
            Log::info("_> batch = " . var_export($batch, true));
            
            // space_time_analysis = ~O(1000n)
            // OUTER_LOOP: O(n)
            foreach($batch as $data) {
                // INNER_LOOP: O(1000)
                if($containsPeople) {
                   $this->buildInsertQuery($data, $qPeople);
                }
                else {
                    $this->buildInsertQuery($data, $qGroups);
                }
            }
            
            if($containsPeople) $q = $qPeople;
            else $q = $qGroups;
            
            Log::info("_> QUERY q = $q");
            
            $ml = __METHOD__ . ' line: ' . __LINE__;
            $message = "_>  JULIUS_ERROR: the insert query is null ~$ml \n $q";
            
            if(is_null($q)) {
                Log::info($message);
                throw new \Exception($message);
            }
            
            // use a prepared statement
            $this->pdo->prepare($q)->execute();
            $message = __METHOD__ . ', line: ' . __LINE__ . '__>> successfully inserted data';
            Log::info($message);
            echo $message;
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
     * @param $data - data in batches
     * @param $query
     *
     */
    private function buildInsertQuery(array $data, string &$query): void {
        // cache header row
        $headerRow = $data[0];
        
        Log::info('_> headerRow = ' . var_export($headerRow, true));
        Log::info('_> body = ' . var_export($data, true));
        
        foreach($data as $i => $datum) {
            //skip the header row
            if(0 === $i) continue;
            
            // deal with column order
            $datum = array_combine($headerRow, $datum);
            
            // wrap in single 'quotes'
            foreach($datum as $key => $value) {
                $datum[$key] = "'{$datum[$key]}'";
            }
            
            Log::info('_> AFTER: ' . var_export($datum, true));
            
            $query .= ('(' . implode(', ', $datum) . '),');
        }
        
        // remove the trailing ','
        $query = substr($query, 0, strlen($query) - 1);
        
        $ml = __METHOD__ . ' line: ' . __LINE__;
        Log::info("$ml __>> The query \n= $query");
    }
}
