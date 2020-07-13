<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use PDO;

class FilesController extends Controller
{
    private static $peopleFilesFolderPath = '../storage/app/people_files/';
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
    
    }
    
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create() {
        //
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
            $fileName = $request->file('people_file')->store('people_files');
            $this->bulkUploadToDb($fileName);
            return $fileName;
        }
        catch(\Throwable $e) {
            return "__>> JULIUS ERROR: {$e->getMessage()}";
        }
    }
    
    /**
     * Display the specified resource.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function show($id) {
        //
    }
    
    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function edit($id) {
        //
    }
    
    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id) {
        //
    }
    
    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy($id) {
        //
    }
    
    /**
     * bulk insert in batches of 1,000 records
     *
     * @param string $csvFile
     */
    private function bulkUploadToDb(string $csvFile) {
        try {
            $data = self::csvToArray($csvFile);
            $batch = array_chunk($data, 1000);
            $q = 'insert into people (first_name, last_name, email_address, status) values ';
            
            // cache header fow
            $headerRow = $data[0];
            
            // OUTER_LOOP: O(n)
            // space_time_analysis = ~O(1000n)
            foreach($batch as $data) {
                // INNER_LOOP_1: O(1000)
                // construct query, this MIGHT hit a limit IF too many inserts are appened,
                // in SQL Server only 1,000 inserts are allowed at a time.
                foreach($data as $i => $datum) {
                    //skip the header row
                    if(0 === $i) continue;
                    
                    // deal with column order
                    $datum = array_combine($headerRow, $datum);
                    $first = $datum['first_name'];
                    $last = $datum['last_name'];
                    $email = $datum['email_address'];
                    $status = $datum['status'];
                    $datum = [$first, $last, $email, $status];
                    
                    // wrap in single 'quotes'
                    $datum = array_map(function($e) { return "'$e'"; }, $datum);
                    
                    $q .= ('(' . implode(', ', $datum) . '),');
                }
            }
            
            // remove the trailing ','
            $q = substr($q, 0, strlen($q) - 1);
            
            $pdo = new PDO('mysql:dbname=laravel;host=localhost', 'root', '');
            
            // use a prepared statement
            $pdo->prepare($q)->execute();
            echo '_> successfully inserted data';
            unset($pdo);
        }
        catch(\Throwable $e) {
            $ml = __METHOD__ . ' line: ' . __LINE__;
            $err = $e->getMessage();
            echo "_> JULIUS_ERROR: $err ~$ml";
        }
    }
    
    public function debug() {
        $fullPath = 'C:\Users\ideaguy3d\Documents\coding-exercise-api-react-v2\storage\app\people_files';
        $relPath = self::$peopleFilesFolderPath;
        $csvFileName = 'debug.txt';
        $path = $relPath . $csvFileName;
        //dd($path);
        // manually give a csv file name that got uploaded from React
        $this->bulkUploadToDb($csvFileName);
    }
    
    /**
     * @param string $csvFile - the path to the csv
     *
     * @return array
     */
    public static function csvToArray(string $csvFile): array {
        $csv = [];
        $count = 0;
        $csvFile = self::$peopleFilesFolderPath . $csvFile;
        //dd(getcwd() . "\n" . $csvFile);
        if(($handle = fopen($csvFile, 'r')) !== false) {
            while(($data = fgetcsv($handle, 8096, ",")) !== false) {
                $csv[$count] = $data;
                ++$count;
            }
            fclose($handle);
        }
        
        return $csv;
    }
}
