<?php
/**
 * Main components
 * 
 * No redirection nor database manipulation ( insert, update, delete ) here
 * 
 * 
 * 
 * 
 * 
 * 
 * 
 * 
 * 
 * 
 * 
 * 
 * 
 * 
 */
class mainComponents extends myFrontModuleComponents
{

    public static $_ParsedSideYamlFile = null;

    public static $_ParsedDashYamlFile = null;

  public function executeHeader()
  {
    // Your code here
  }

  public function executeFooter()
  {
    // Your code here
  }

  public function executeQDashBoard(sfWebRequest $request)
  {
    
  }

  public function executeSidebar()
  {
    if (is_null(self::$_ParsedSideYamlFile)){
          // parse sidebar configuration yml file.
          $configFile = sfConfig::get('sf_app_dir') . '/config/sidebar.yml';
            if (!file_exists($configFile)) {
                return;
            }
            self::$_ParsedSideYamlFile = sfYaml::load($configFile);
      }
      $config = self::$_ParsedSideYamlFile;
      $this->sideDescription = $config['side'];
  }

  public function executeAjaxloader()
  {
    // Your code here
  }

  public function executeAjaxcontent()
  {
    // Your code here
  }

  public function executeCenterfooter()
  {
    // Your code here
  }

  public function executeNotifications()
  {
    // Your code here
  }

  public function executeFacebox()
  {
    // Your code here
  }

  public function executeFullcalendar()
  {
    // Your code here
  }

  public function executeFullcalendardependences()
  {
    // Your code here
  }

  public function executeMyCustomMenu()
  {
    $this->menu = $this->getService('menu');
      
    // Build the menu
    $this->menu
    ->addChild('Dashboard', '@homepage')->liClass('nav-top-item no-childs')->end()
    ->addChild('Reportes', _link('main/reportes'))->liClass('nav-top-item no-childs')->credentials(array('viewReports_front'))->end()
    ->addChild('Recepci&oacute;n', _link('main/recepcion'))->liClass('nav-top-item no-childs')->credentials(array('listIncommingCalls_front'))->end()
    ->addChild('Citas')->label('<span>Citas</span>')->liClass('nav-top-item')->credentials(array('listClientFiles_front', 'listEvents_front', 'viewClientFileCalendar_front', 'viewEventCalendar_front'))
            ->addChild('Calendario de Eventos',$this->getHelper()->link('evento/fullCalendar'))->liClass('nav-trackable')->credentials('viewEventCalendar_front')->end()
            ->addChild('Listado de Eventos',  $this->getHelper()->link('event/list'))->liClass('nav-trackable')->credentials('listEvents_front')->end()
            ->addChild('Asignar Citas',  $this->getHelper()->link('event/asignarCitas'))->liClass('nav-trackable')->credentials('assignDates_front')->end()
            ->addChild('Consultar Fraseologias',  $this->getHelper()->link('event/fraseologias'))->liClass('nav-trackable')->end()
            ->addChild('Calendario de Trámites',  $this->getHelper()->link('clientFile/fullCalendar'))->liClass('nav-trackable')->credentials('viewClientFileCalendar_front')->end()
            ->addChild('Listado de Trámites',  $this->getHelper()->link('clientFile/list'))->liClass('nav-trackable')->credentials('listClientFiles_front')->end()
    ->end()
    ->addChild('Clientes')->label('<span>Clientes</span>')->liClass('nav-top-item')->credentials('listClients_front')
            ->addChild('Listado de Clientes',  $this->getHelper()->link('client/list'))->liClass('nav-trackable')->credentials('listClients_front')->end()
    ->end()
    ->addChild('Geolocalizar')->label('<span>Geolocalizar</span>')->liClass('nav-top-item')->credentials(array('geoListEvents_front','geoListClients_front'))
            ->addChild('Clientes',  $this->getHelper()->link('client/geolocalizar'))->liClass('nav-trackable')->credentials('geoListClients_front')->end()
            ->addChild('Citas',  $this->getHelper()->link('event/geolocalizar'))->liClass('nav-trackable')->credentials('geoListEvents_front')->end()
    ->end()
     ->end();
    $this->menu->ulClass('main-nav');
  }

  public function executeLogo()
  {
    // Your code here
  }

  public function executeMyStyledList()
  {
    
  }

  public function executeUserInfo()
  {
    $this->user = (($this->getUser()->getDmUser())?$this->getUser():'');
        $unreadEvents = 0;
        if ($this->user) {
            $this->profile = 'Administrador';
            if ($this->getUser()->getDmUser()){
            $this->unreadEvents = Doctrine::getTable('Event')
                      ->createQuery('e')
                      ->where('e.dm_user_id = ?',$this->user->getDmUser()->getId())
                      ->andWhere('e.is_new = 1')
                      ->count();
            }
        }
  }

  public function executeMainDashBoard()
  {
    if (is_null(self::$_ParsedDashYamlFile)){
              // parse sidebar configuration yml file.
              $configFile = sfConfig::get('sf_app_dir') . '/config/qdashboard.yml';
                if (!file_exists($configFile)) {
                    return;
                }
                self::$_ParsedDashYamlFile = sfYaml::load($configFile);
          }
          $config = self::$_ParsedDashYamlFile;
          $this->dashDescription = $config['dashboards'];
          $this->dashDescription = $this->dashDescription['main'];
          if (is_null($this->requestedDashBoardName)||$this->requestedDashBoardName==''){
                return ''; // dashboard name most be passed
          }
  }

  public function executeLegend()
  {
    $this->legends = array(
          'Estados' => array(
              '<div class="color" style="background-color:#7F00FF;"></div>'=>'No Show',
              '<div class="color" style="background-color:#CC3333;"></div>'=>'Cancelado',
              '<div class="color" style="background-color:#9ECE00;"></div>'=>'Cerrado',
              '<div class="color" style="background-color:#ABB408;"></div>'=>'Finalizado',
              '<div class="color" style="background-color:#09C2F1;"></div>'=>'Reasignado',
    	      '<div class="color" style="background-color:#2F929F;"></div>'=>'Asignado',
    		  '<div class="color" style="background-color:#EFA32D;"></div>'=>'Seguimiento'
          ),
          'Tipos de Eventos' => array(
              '<span class="ark-icon-2-16 ark-icon-meeting ark-icon-left"></span>'=>'Cita',
              '<span class="ark-icon-2-16 ark-icon-private ark-icon-left"></span>'=>'Privado',
              '<span class="ark-icon-2-16 ark-icon-reminder ark-icon-left"></span>'=>'Recordatorio',
              '<span class="ark-icon-2-16 ark-icon-bussiness-meeting ark-icon-left"></span>'=>'Reuni&oacute;n'
          )
      );
      //$this->legend_name = 'Leyenda de Mierda';
      //$this->elements = array(
          //);
    // Your code here
  }

  public function executeTramitlegend()
  {
    // Your code here
    $this->legends = array(
          'Departamentos' => array(
              '<div class="color" style="background-color:#CC3333;"></div>'=>'Procesamiento',
              '<div class="color" style="background-color:#ABB408;"></div>'=>'Estructuración',
              '<div class="color" style="background-color:#EFA32D;"></div>'=>'Contabilidad'
          ),
          'Tipo de Archivo' => array(
              '<span class="ark-icon-2-16 ark-icon-file-modification ark-icon-left"></span>'=>'Modificaci&oacute;n',
              '<span class="ark-icon-2-16 ark-icon-file-realestate ark-icon-left"></span>'=>'RealEstate',
              '<span class="ark-icon-2-16 ark-icon-file-ssale ark-icon-left"></span>'=>'ShortSale'
          )
      );
  }

  public function executeGeolocalizador()
  {
    $this->address = urldecode($this->getRequest()->getParameter('address','Los Angeles, California'));
  }

  public function executeDateRangeSelector()
  {
    $this->dateEnd = new sfDate(time());
     $this->dateStart = $this->dateEnd->copy();
     $this->dateEnd = $this->dateEnd->dump();
     $this->dateStart->subtractMonth(1);
     $this->dateStart = $this->dateStart->dump();
  }

  public function executeReportDashBoard()
  {
    if (is_null(self::$_ParsedDashYamlFile)){
          // parse sidebar configuration yml file.
          $configFile = sfConfig::get('sf_app_dir') . '/config/qdashboard.yml';
            if (!file_exists($configFile)) {
                return;
            }
            self::$_ParsedDashYamlFile = sfYaml::load($configFile);
      }
      $config = self::$_ParsedDashYamlFile;
      $this->dashDescription = $config['dashboards'];
      $this->dashDescription = $this->dashDescription['reports'];
      if (is_null($this->requestedDashBoardName)||$this->requestedDashBoardName==''){
            return ''; // dashboard name most be passed
      }
  }

  public function executeReportDatesByAgentConfig()
  {
    // Your code here
  }

  public function executeReportGraphViewer()
  {
    // Your code here
  }

  public function executeReportListViewer()
  {
    // Your code here
  }

  public function executeReportCore()
  {
    // Your code here
  }

  public function executeReportDashBoardAutoToogle()
  {
    // Your code here
  }

  public function executeReportDatesByTelemarkerConfig()
  {
    // Your code here
  }

  public function executeReportDatesByCityConfig()
  {
    // Your code here
  }

  public function executeAssignDatesRangeSelector()
  {
    // Your code here
  }

  

}
