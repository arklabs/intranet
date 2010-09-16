<?php

class updatePermissionsTask extends sfDoctrineConfigureDatabaseTask

{

  protected function configure()

  {

          $this->addOptions(array(

          new sfCommandOption('application', null, sfCommandOption::PARAMETER_REQUIRED, 'The application name'),

          new sfCommandOption('env', null, sfCommandOption::PARAMETER_REQUIRED, 'The environment', 'dev'),

          new sfCommandOption('connection', null, sfCommandOption::PARAMETER_REQUIRED, 'The connection name', 'doctrine')
      ));


    $this->namespace        = 'intranet';

    $this->name             = 'update-permissions';

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

    $adminDirectoryIterator = new DirectoryIterator(dirname(__FILE__).'/../../apps/admin/modules');
    $onlyModulesWithNewPermissionsArray = array();

    $this->log('Starting modules exploration');
    foreach ($adminDirectoryIterator as $d){
        $databaseManager = new sfDatabaseManager($this->configuration);
        $connection = $databaseManager->getDatabase($options['connection'] ? $options['connection'] : null)->getConnection();
        if ($d->__toString()!='.' && $d->__toString()!='..' && $d->__toString()!='.svn'){
           $this->log('Found Module -> '.$d->__toString());
           $this->log('    Starting with standard permissions ...');
           $new_permission_name_delete  = $d->__toString().'_admin_borrar_entradas';
           $new_permission_name_list  = $d->__toString().'_admin_listar_contenido';
           $new_permission_name_edit  = $d->__toString().'_admin_modificar_entradas';
           $new_permission_name_add  = $d->__toString().'_admin_adicionar_entradas';

           $new_permission_name_front_delete  = $d->__toString().'_front_borrar_entradas';
           $new_permission_name_front_list  = $d->__toString().'_front_listar_contenido';
           $new_permission_name_front_edit  = $d->__toString().'_front_modificar_entradas';
           $new_permission_name_front_add  = $d->__toString().'_front_adicionar_entradas';

           /****    BACKEND PERMISSIONS ****/
           //delete permissions
           if (!($permission = Doctrine::getTable('DmPermission')->createQuery('p')->where('p.name = ?', $new_permission_name_delete)->fetchOne())){
                $permission = new DmPermission();
                $permission->setName($new_permission_name_delete);
                $permission->setDescription('Permitir Borrar Entradas en el modulo '.$d->__toString().' en el Backend');
                $permission->save();
                $this->log('        Added Permission- '.$new_permission_name_delete);
           }
           //list permissions
           if (!($permission = Doctrine::getTable('DmPermission')->createQuery('p')->where('p.name = ?', $new_permission_name_list)->fetchOne())){
                $permission = new DmPermission();
                $permission->setName($new_permission_name_list);
                $permission->setDescription('Permitir Listar todas las Entradas en el modulo '.$d->__toString().' en el Backend');
                $permission->save();
                $this->log('        Added Permission - '.$new_permission_name_list);
           }

           //edit permissions
           if (!($permission = Doctrine::getTable('DmPermission')->createQuery('p')->where('p.name = ?', $new_permission_name_edit)->fetchOne())){
                $permission = new DmPermission();
                $permission->setName($new_permission_name_edit);
                $permission->setDescription('Permitir Editar Entradas del modulo '.$d->__toString().' en el Backend');
                $permission->save();
                $this->log('        Added Permission - '.$new_permission_name_edit);
           }
           else
                $this->log('        Permission '.$new_permission_name_edit.' already exist.');

           //add permissions
           if (!($permission = Doctrine::getTable('DmPermission')->createQuery('p')->where('p.name = ?', $new_permission_name_add)->fetchOne())){
                $permission = new DmPermission();
                $permission->setName($new_permission_name_add);
                $permission->setDescription('Permitir Adicionar Entradas del modulo '.$d->__toString().' en el Backend');
                $permission->save();
                $this->log('    Added Permission - '.$new_permission_name_add);
           }
           else
                $this->log('        Permission '.$new_permission_name_edit.' already exist.');

           /****    FRONTEND PERMISSIONS ****/
           //delete permissions
           if (!($permission = Doctrine::getTable('DmPermission')->createQuery('p')->where('p.name = ?', $new_permission_name_front_delete)->fetchOne())){
                $permission = new DmPermission();
                $permission->setName($new_permission_name_front_delete);
                $permission->setDescription('Permitir Borrar Entradas en el modulo '.$d->__toString().' en el Frontend');
                $permission->save();
                $this->log('        Added Permission- '.$new_permission_name_front_delete);
           }
           else
                $this->log('        Permission '.$new_permission_name_edit.' already exist.');
           //list permissions
           if (!($permission = Doctrine::getTable('DmPermission')->createQuery('p')->where('p.name = ?', $new_permission_name_front_list)->fetchOne())){
                $permission = new DmPermission();
                $permission->setName($new_permission_name_front_list);
                $permission->setDescription('Permitir Listar todas las Entradas en el modulo '.$d->__toString().' en el Frontend');
                $permission->save();
                $this->log('        Added Permission - '.$new_permission_name_front_list);
           }
           else
                $this->log('        Permission '.$new_permission_name_edit.' already exist.');

           //edit permissions
           if (!($permission = Doctrine::getTable('DmPermission')->createQuery('p')->where('p.name = ?', $new_permission_name_front_edit)->fetchOne())){
                $permission = new DmPermission();
                $permission->setName($new_permission_name_front_edit);
                $permission->setDescription('Permitir Editar Entradas del modulo '.$d->__toString().' en el Frontend');
                $permission->save();
                $this->log('        Added Permission - '.$new_permission_name_front_edit);
           }
           else
                $this->log('        Permission '.$new_permission_name_edit.' already exist.');

           //add permissions
           if (!($permission = Doctrine::getTable('DmPermission')->createQuery('p')->where('p.name = ?', $new_permission_name_front_add)->fetchOne())){
                $permission = new DmPermission();
                $permission->setName($new_permission_name_front_add);
                $permission->setDescription('Permitir Adicionar Entradas del modulo '.$d->__toString().' en el Frontend');
                $permission->save();
                $this->log('    Added Permission - '.$new_permission_name_front_add);
           }
           else
                $this->log('        Permission '.$new_permission_name_edit.' already exist.');

           $this->log('    Starting  with aditional permissions ...');
           try{
           $cacheModuleDirectory = new DirectoryIterator(dirname(__FILE__).'/../../cache/admin/dev/modules'.'/auto'. ucfirst($d->__toString()).'/templates');
           $moduleChanged = false;
           foreach ($cacheModuleDirectory as $template){
           //    $this->log('             Look match for file '.$template);
                $regs = array();
                ereg('([a-zA-Z]+)Success.php', $template, $regs);
                if (array_key_exists('1', $regs)){
                        // excluding standard actions
                        if ($regs[1]!= 'index' && $regs[1]!='edit' && $regs[1]!='delete' && $regs[1]!='new' && $regs[1]!='index'){
                            $permissionName = trim($d->__toString().'_'.$regs[1]);
                            if (($permission = Doctrine::getTable('DmPermission')->createQuery('p')->where('p.name = ?', $permissionName)->fetchOne()) == null){
                                $this->log('        Found extra action with name '.$regs[1]);
                                $answer = $this->askAndValidate('Do you want to add permissions for this action?', new sfValidatorPass());
                                if ($answer == "y"|| $answer == 'Y' || $answer == 'yes' || $answer == 'YES' || $answer == 'si' || $answer == 'SI'){
                                    $anwser = $this->ask('Type a description for this new action: ');
                                    $permission = new DmPermission();
                                    $permission->setName($permissionName);
                                    $permission->setDescription($answer);
                                    $permission->save();
                                    $moduleChanged = true;
                                    $this->log('');
                                    $this->log('            Permission added successfully with name: '.$permissionName);
                                }
                            }
                        }
                }
           }
           if ($moduleChanged == true)
               $onlyModulesWithNewPermissionsArray[$d->__toString()] = sprintf(dirname(__FILE__).'/../../apps/admin/modules/%s/config/security.yml',$d->__toString());
           }catch(Exception $e)
           {
               $this->logBlock(sprintf('   -FAIL- Extra Permission Detection on module auto%s. Check if folder exist con cache/dev/modules folder. %sWith exception: %s', ucfirst($d->__toString()), chr(13),$e->getMessage()), 'ERROR');
           }
        }
    }
    $this->log('<<< Permissions Successfully updated on database >>>');
    $this->reWriteSecurity($onlyModulesWithNewPermissionsArray);
  }
  protected function reWriteSecurity($onlyModulesWithNewPermissionsArray){
      try{
      $this->log('');
      $this->log('');


      $answer = $this->askAndValidate('Say YES to re-write security file only in modules with new permissions detected, say NO to re-write them all, say C to cancel re-writting security files?: ', new sfValidatorString());
      $answer = strtolower($answer);
      if ($answer == 'c'){
          $this->logBlock('Security Files re-write ABORTED by user','','ERROR');
          return;
      }
      elseif ($answer == "y"|| $answer == 'Y' || $answer == 'yes' || $answer == 'YES' || $answer == 'si' || $answer == 'SI'){
            $adminModulesSecurityPaths = $onlyModulesWithNewPermissionsArray;
            $this->log('<<< Re-Writting security file for modified permissions modules started  >>>');
      }
      else
      {
          $this->log('<<< Re-Writting security file for all modules started  >>>');
          $adminModulesSecurityPaths = array(); // 'module'=> 'path/to/security.yml'
          //you said no and I will look for all modules
          $adminDirectoryIterator = new DirectoryIterator(dirname(__FILE__).sprintf('/../../apps/admin/modules'));
          foreach ($adminDirectoryIterator as $d){
            if ($d->__toString()!='.' && $d->__toString()!='..' && $d->__toString()!='.svn'){
                $adminModulesSecurityPaths[$d->__toString()] = sprintf(dirname(__FILE__).'/../../apps/admin/modules/%s/config/security.yml',$d->__toString());
            }
          }
          $frontDirectoryIterator = new DirectoryIterator(dirname(__FILE__).'/../../apps/front/modules');
          foreach ($frontDirectoryIterator as $d){
            if ($d->__toString()!='.' && $d->__toString()!='..' && $d->__toString()!='.svn'){
                if (!is_dir(dirname(__FILE__).sprintf('/../../apps/front/modules/%s/config', $d->__toString())))
                    mkdir(dirname(__FILE__).sprintf('/../../apps/front/modules/%s/config', $d->__toString()));

                $frontModulesSecurityPaths[$d->__toString()] = sprintf(dirname(__FILE__).'/../../apps/front/modules/%s/config/security.yml',$d->__toString());
            }
          }
      }


      // now for the front module list builded, find on database its permissions
      foreach (array_keys($adminModulesSecurityPaths) as $module){
          // fetch permissions for this modules from database
          $thisModulePermissions = Doctrine::getTable('DmPermission')->createQuery('p')->where('p.name LIKE ?', ucfirst($module).'\_admin%')->execute();
          // now build virtual security file
          $virtualFile = array('all'=>array('is_secure'=>'on', 'credentials'=>'[]'));

          foreach ($thisModulePermissions as $permission)
          {
              // parse name
              $name = explode('_', $permission->getName());
              if (strtolower($name[2]) == 'borrar') $virtualFile['delete'] = array('credentials'=>sprintf('%s',$permission->getName()));
              elseif (strtolower($name[2]) == 'adicionar')   $virtualFile['new'] = array('credentials'=>sprintf('%s',$permission->getName()));
              elseif (strtolower($name[2]) == 'listar')   $virtualFile['index'] = array('credentials'=>sprintf('%s',$permission->getName()));
              elseif (strtolower($name[2]) == 'modificar') $virtualFile['update'] = array('credentials'=>sprintf('%s',$permission->getName()));
              else {
                $virtualFile[strtolower($name[2])] = array('credentials'=>sprintf('%s',$permission->getName()));
              }
          }
          $this->log('');
          //write virtual security file to disk
          $handle = fopen($adminModulesSecurityPaths[$module], 'w');
          fwrite($handle, '## This File has been auto generated '. date('Y-m-d', time()).chr(13));

          foreach (array_keys($virtualFile) as $firstLevel){
            // fixing spanish words in standard cases
            fwrite($handle, sprintf('%s:%s',$firstLevel, chr(13))); //writting at first level

            foreach (array_keys($virtualFile[$firstLevel]) as $secondLevel){
                fwrite($handle, sprintf('  %s: %s%s',$secondLevel, $virtualFile[$firstLevel][$secondLevel], chr(13)));//writting at second level level
            }

          }
          fclose($handle);
          $this->log(sprintf('  Security File in module %s wrotted',$module));
      }

      // now for the module list builded, find on database its permissions
      foreach (array_keys($frontModulesSecurityPaths) as $module){
          // fetch permissions for this modules from database
          $thisModulePermissions = Doctrine::getTable('DmPermission')->createQuery('p')->where('p.name LIKE ?', lcfirst($module).'\_front%')->execute();
          // now build virtual security file
          $virtualFile = array('all'=>array('is_secure'=>'on', 'credentials'=>'[]'));

          foreach ($thisModulePermissions as $permission)
          {
              // parse name
              $name = explode('_', $permission->getName());
              if (strtolower($name[2]) == 'borrar') $virtualFile['delete'] = array('credentials'=>sprintf('%s',$permission->getName()));
              elseif (strtolower($name[2]) == 'adicionar')   $virtualFile['new'] = array('credentials'=>sprintf('%s',$permission->getName()));
              elseif (strtolower($name[2]) == 'listar')   $virtualFile['index'] = array('credentials'=>sprintf('%s',$permission->getName()));
              elseif (strtolower($name[2]) == 'modificar') $virtualFile['update'] = array('credentials'=>sprintf('%s',$permission->getName()));
              else {
                $virtualFile[strtolower($name[2])] = array('credentials'=>sprintf('%s',$permission->getName()));
              }
          }
          $this->log('');
          //write virtual security file to disk
          $handle = fopen($frontModulesSecurityPaths[$module], 'w');
          fwrite($handle, '## This File has been auto generated '. date('Y-m-d', time()).chr(13));

          foreach (array_keys($virtualFile) as $firstLevel){
            // fixing spanish words in standard cases
            fwrite($handle, sprintf('%s:%s',$firstLevel, chr(13))); //writting at first level

            foreach (array_keys($virtualFile[$firstLevel]) as $secondLevel){
                fwrite($handle, sprintf('  %s: %s%s',$secondLevel, $virtualFile[$firstLevel][$secondLevel], chr(13)));//writting at second level level
            }

          }
          fclose($handle);
          $this->log(sprintf('  Security File in module %s wrotted',$module));
      }
      
      $this->log('');
      $this->log('');
      $this->log('<<< Security Files Successfully Writted  >>>');
      }catch(Exception $e){
          $this->logBlock('[FAIL] - Generating Security Files.','', 'ERROR');

      }
  }
}

