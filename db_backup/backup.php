#!/usr/local/bin/php
<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

class SaveDB {

  // options
  public $retry = 3;
  public $filename_timestamp_format = '%Y%m%d';

  private $filename;
  private $db; // DB settings
  private $bash_status = -1; // BASH return status 0 = ok, other number = bad, -1 never ran

  function __construct()
  {
    define('ENV', "production");
    chdir($_ENV['HOME'].'/www');
    $db = require('config/database.inc.php');
    $this->db = $db["db"][ENV];

    setlocale(LC_TIME, array('fr_FR.utf8', 'fr_FR'));
    date_default_timezone_set("Europe/Paris");
  }

  public function run()
  {
    $trials = 0;

    do {
      $this->save();
      $trials += 1;
    } while($this->failed() && $trials < $this->retry);

    if($this->failed()) {
      printf("Backup failed! status=%s file=%s\n", $this->bash_status, $this->filename());
      exit(1);
    }
    exit(0);
  }

  public function save()
  {
    chdir($_ENV['HOME'].'/marctanguy/sovafrem_db/');

    system(sprintf("mysqldump -h%s -P%s -u%s -p%s %s | gzip -9 > %s",
      $this->db['host'],
      $this->db['port'],
      $this->db['user'],
      $this->db['password'],
      $this->db['database'],
      $this->filename()), $this->bash_status);
  }

  private function filename()
  {
    if(is_null($this->filename)){
      $this->filename = sprintf("snapshots/backup_%s_%s.sql.gz",
                          $this->db['database'],
                          strftime($this->filename_timestamp_format));
    }
    return $this->filename;
  }

  private function succeeded()
  {
    return $this->bash_status == 0;
  }

  private function failed()
  {
    return !$this->succeeded();
  }

}

(new SaveDB())->run();
