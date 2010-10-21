<?php

class fillZipCodeTableTask extends sfDoctrineConfigureDatabaseTask

{

  protected function configure()

  {

          $this->addOptions(array(

          new sfCommandOption('application', null, sfCommandOption::PARAMETER_REQUIRED, 'The application name'),

          new sfCommandOption('env', null, sfCommandOption::PARAMETER_REQUIRED, 'The environment', 'dev'),

          new sfCommandOption('connection', null, sfCommandOption::PARAMETER_REQUIRED, 'The connection name', 'doctrine')
      ));


    $this->namespace        = 'intranet';

    $this->name             = 'fill-codes';

    $this->briefDescription = 'Make permissions for all modules groups';

    $this->detailedDescription = <<<EOF

The [intranet:update-permissions|INFO] task updates available permission list for the current modules.

Call it with:

php

  [php symfony intranet:update-permissions|INFO]

EOF;

  }

  protected function execute($arguments = array(), $options = array())
  {
      // initialize memory limit

     ini_set("memory_limit","128M");

     $databaseManager = new sfDatabaseManager($this->configuration);

    // initialize the database connection
     $connection = $databaseManager->getDatabase($options['connection'] ? $options['connection'] : null)->getConnection();
     $this->configure();
     
     $dsn = sprintf('mysql:dbname=%s;host=%s', 'geonames', 'localhost');
     $dbh = new PDO($dsn, $user='root', $password = 'indesencri');
     $conn= Doctrine_Manager::connection($dbh);


     $dsn = sprintf('mysql:dbname=%s;host=%s', 'intranet', 'localhost');
     $dbh = new PDO($dsn, $user='root', $password = 'indesencri');
     $connIntranet= Doctrine_Manager::connection($dbh);

     $query = $conn->execute('select * from datos;');
     //$res = $query->postExec();
     //die;
     //$zipCodes = $query->postExec();
     try{
     foreach ($query as $zipentry){
         $connIntranet->execute(sprintf('INSERT INTO zip_code (zip_code, place_name, country_code, state_code, state_name) VALUES (\'%s\',\'%s\',\'%s\',\'%s\',\'%s\')', $zipentry['postal_code'], $zipentry['place_name'], $zipentry['country_code'], $zipentry['admin_code1'], $zipentry['admin_name1']));
     }
     }catch(Exception  $e){ 
         $this->log($e->getMessage());die;

     }
  }
  
}

