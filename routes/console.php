<?php

use Illuminate\Foundation\Inspiring;
use Symfony\Component\Process\Process;
use Faker\Factory as Faker;
/*
|--------------------------------------------------------------------------
| Console Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of your Closure based console
| commands. Each Closure is bound to a command instance allowing a
| simple approach to interacting with each command's IO methods.
|
*/

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->describe('Display an inspiring quote');


Artisan::command('cbapps:seedme',function() {

	$composer_path = '';
	if (file_exists(getcwd().'/composer.phar')) {
            $composer_path = '"'.PHP_BINARY.'" '.getcwd().'/composer.phar';
    }else{
    	$composer_path = 'composer';
    }        


	$this->info('CRUDBOOSTER SEED-ME TOOL');	

	$tables = CRUDBooster::listTables();
	$php_string = "";

	foreach($tables as $table) {
		if($table->TABLE_NAME == 'cms_logs' || $table->TABLE_NAME == 'migrations') continue;

		$this->info("Create seeder for table : ".$table->TABLE_NAME);
		$rows = DB::table($table->TABLE_NAME)->get();
		$data = [];
		foreach($rows as $i=>$row) {
			$data[$i] = [];
			foreach($row as $key=>$val) {
				$data[$i][$key] = $val;
			}
		}	
		if(count($data)!=0) {			
			$php_string .= 'DB::table(\''.$table->TABLE_NAME.'\')->insert('.min_var_export($data).');'."\n\t\t\t";
		}	
	}


	$seederFileTemplate = '
<?php
use Illuminate\Database\Seeder;
class DefaultSeeder extends Seeder
{
    public function run()
    {
        $this->command->info(\'Please wait updating the data...\');                
        $this->call(\'DefaultData\');        
        $this->command->info(\'Updating the data completed !\');
    }
}

class DefaultData extends Seeder {
    public function run() {        
    	'.$php_string.'
    }
}
	';

	$this->info('Create seeder file');
	file_put_contents(base_path('database/seeds/DefaultSeeder.php'), $seederFileTemplate);

	$this->info('Dumping auto loads new file seeder !');

	$process = new Process($composer_path.' dump-autoload');
    $process->setWorkingDirectory(base_path())->run();

	$this->info('Done');

})->describe('Install SSM');

Artisan::command('cbapps:install',function() {
	$this->info("SSM INSTALLATION");

	Schema::disableForeignKeyConstraints();

	foreach(DB::select('SHOW TABLES') as $table) {
	    $table_array = get_object_vars($table);
	    Schema::drop($table_array[key($table_array)]);
	}	

	$this->info('Migrating the tables...');	
	$this->call("migrate");
	
	$this->info('Seeding the data...');
	$this->call("db:seed",["--class"=>"DefaultData"]);

	Schema::enableForeignKeyConstraints();

	$this->info('Cache the config files...');
	$this->call('config:cache');

	Cache::forever('is_installed',1);
	$this->info("DONE");
});
