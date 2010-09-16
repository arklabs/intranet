<?php

class dmAdminDoctrineGenerateModuleTask extends sfDoctrineGenerateModuleTask
{
  protected
  $moduleObject;
  
  /**
   * @see sfTask
   */
  protected function configure()
  {
    parent::configure();

    $this->addOptions(array(
      new sfCommandOption('from-admin-generate', null, sfCommandOption::PARAMETER_REQUIRED, '', false)
    ));

    $this->aliases = array();
    $this->namespace = 'dmAdmin';
    $this->name = 'generate-module';
    $this->briefDescription = 'Generates a Diem admin module';
  }

  /**
   * @see sfTask
   */
  protected function execute($arguments = array(), $options = array())
  {
    if (!$options['from-admin-generate'])
    {
      throw new dmException('Please use dmAdmin:generate instead.');
    }

    return parent::execute($arguments, $options);
  }

  protected function executeInit($arguments = array(), $options = array())
  {
    $moduleObject = dmContext::getInstance()->getModuleManager()->getModule($arguments['module']);
    
    $arguments['module'] = $moduleObject->getSfName();

    if ($pluginName = $moduleObject->getPluginName())
    {
      if($moduleObject->isOverridden())
      {
        return;
      }

      $moduleDir = dmOs::join($this->configuration->getPluginConfiguration($pluginName)->getRootDir(), 'modules', $moduleObject->getSfName());
    }
    else
    {
      $moduleDir = dmOs::join(sfConfig::get('sf_apps_dir'), 'admin/modules', $moduleObject->getSfName());
    }

    if(!$moduleDir)
    {
      throw new dmException('No generate dir');
    }

    $this->logSection('diem', sprintf('Generating admin module "%s" for model "%s" in %s', $moduleObject->getKey(), $moduleObject->getModel(), $moduleDir));
    
    // create basic application structure
    $finder = sfFinder::type('any')->discard('.sf');
    $dirs = $this->configuration->getGeneratorSkeletonDirs('dmAdminDoctrineModule', $options['theme']);

    foreach ($dirs as $dir)
    {
      if (is_dir($dir))
      {
        $this->getFilesystem()->mirror($dir, $moduleDir, $finder);
        break;
      }
    }

    // move configuration file
    if (file_exists($config = $moduleDir.'/lib/configuration.php'))
    {
      if (file_exists($target = $moduleDir.'/lib/'.$arguments['module'].'GeneratorConfiguration.class.php'))
      {
        $this->getFilesystem()->remove($config);
      }
      else
      {
        $this->getFilesystem()->rename($config, $target);
      }
    }

    // move helper file
    if (file_exists($config = $moduleDir.'/lib/helper.php'))
    {
      if (file_exists($target = $moduleDir.'/lib/'.$arguments['module'].'GeneratorHelper.class.php'))
      {
        $this->getFilesystem()->remove($config);
      }
      else
      {
        $this->getFilesystem()->rename($config, $target);
      }
    }

    // move form file
    if (file_exists($config = $moduleDir.'/lib/form.php'))
    {
      if (file_exists($target = $moduleDir.'/lib/'.$arguments['model'].'AdminForm.php'))
      {
        $this->getFilesystem()->remove($config);
      }
      else
      {
        $this->getFilesystem()->rename($config, $target);
      }
    }

    // move export file
    if (file_exists($config = $moduleDir.'/lib/export.php'))
    {
      if (file_exists($target = $moduleDir.'/lib/'.$arguments['model'].'AdminExport.class.php'))
      {
        $this->getFilesystem()->remove($config);
      }
      else
      {
        $this->getFilesystem()->rename($config, $target);
      }
    }

    // customize php and yml files
    $finder = sfFinder::type('file')->name('*.php', '*.yml');
    
    $this->constants['MODULE_NAME'] = $arguments['module'];
    $this->constants['UC_MODULE_NAME'] = ucfirst($arguments['module']);
    $this->constants['CONFIG'] = sprintf(<<<EOF
    model_class:           %s
    theme:                 %s
    non_verbose_templates: %s
    with_show:             %s
    route_prefix:          %s
    with_doctrine_route:   %s
EOF
    ,
      $arguments['model'],
      $options['theme'],
      $options['non-verbose-templates'] ? 'true' : 'false',
      $options['with-show'] ? 'true' : 'false',
      $options['route-prefix'] ? $options['route-prefix'] : '~',
      'false'
    );

    $this->getFilesystem()->replaceTokens($finder->in($moduleDir), '##', '##', $this->constants);

    $generatorFile = dmOs::join($moduleDir, 'config/generator.yml');

    $generatorBuilder = new dmAdminGeneratorBuilder($moduleObject, $this->dispatcher);

    file_put_contents(
      $generatorFile,
      $generatorBuilder->getTransformed(file_get_contents($generatorFile))
    );
  }
}
