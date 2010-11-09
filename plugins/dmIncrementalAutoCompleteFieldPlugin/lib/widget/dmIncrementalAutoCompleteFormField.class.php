<?php

/*
 *  
 * This file is part of dmIncrementalAutocompleteFieldPlugin, is based on sfWidgetFormJQueryAutocompleter widget and under MIT licence.
 */

/**
 * dmIncrementalAutoCompleteFormField represents an autocompleter input widget rendered by JQuery. If needed value dont exist writtit
 * first time and next time you needit will be in the autocomplete set
 *
 * This widget needs JQuery to work.
 *
 * You also need to include the JavaScripts and stylesheets files returned by the getJavaScripts()
 * and getStylesheets() methods.
 *
 * If you use symfony 1.2, it can be done automatically for you.
 *
 * @package    dmIncrementalAutoCompleteFieldPlugin
 * @subpackage widget
 * @author     Alberto Perez <alberto2perez@gmail.com>
 * 
 */
class dmIncrementalAutoCompleteFormField extends sfWidgetFormInput
{
  /**
   * Configures the current widget.
   *
   * Available options:
   *
   *  * url:            The URL to call to get the choices to use (required)
   *  * owner_form      Field owner form, usually pass $this (required)
   *  * field_name      Field you want to autocomplete  (required)
   *  * config:         A JavaScript array that configures the JQuery autocompleter widget
   *  * value_callback: A callback that converts the value before it is displayed
   *
   * @param array $options     An array of options
   * @param array $attributes  An array of default HTML attributes
   *
   * @see sfWidgetForm
   */
  protected $model = '';
  protected $autoCompleterForeignField = '';

  protected function configure($options = array(), $attributes = array())
  {
    $this->addRequiredOption('url');
    $this->addRequiredOption('owner_form');
    $this->addRequiredOption('field_name');
    $this->addOption('value');
    $this->addOption('foreignColumnName','');
    $this->addOption('value_callback');
    $this->addOption('config', '{ }');

    parent::configure($options, $attributes);
    
  }

  protected function setForeignTableModel(){
     $thisFormModel = $this->getOption('owner_form')->getModelName();
     $this->addOption('model','');
     foreach (Doctrine::getTable($thisFormModel)->getRelations() as $name=>$relation){
        if ($relation->getLocal() == $this->getOption('field_name')){
          $this->setOption('model', $name);
          break;
        }
     }
     $this->addOption('autoCompleterForeignField',($this->getOption('foreignColumnName')!='')?$this->getOption('foreignColumnName'):self::GuessModelColumn($this->getOption('model')));
  }

  protected function setFormField(){
     $form = $this->getOption('owner_form');
     if (is_null($form))
	throw new RuntimeException('NULL form parameter in dmIncrementalAutoCompleteFormField widget', 500, '');

     // getting foreign model name
     $this->setForeignTableModel();
     if ($this->getOption('model') == '')
      throw new RunTimeException('Invalid Relation Field '.$this->getOption('field_name'), 500, '');

     $this->setForeignTableModel();
     $value = '';
     if (!$form->isNew()){
        $foreignObjectId = $form->getObject()->get($this->getOption('model'))->getId();
        $tmp = (is_null($foreignObjectId))?null:Doctrine::getTable($this->getOption('model'))->findById($foreignObjectId);
        $value = (is_null($tmp))?"":$tmp[0]->get($this->getOption('autoCompleterForeignField'));
     }
     
     $this->setOption('value', $value);
  }
  /**
   * @param  string $name        The element name
   * @param  string $value       The date displayed in this widget
   * @param  array  $attributes  An array of HTML attributes to be merged with the default HTML attributes
   * @param  array  $errors      An array of errors for the field
   *
   * @return string An HTML tag string
   *
   * @see sfWidgetForm
   */

  public static function findCorrectValue($model, $value, $foreignColumnName = null){
      if (is_null($foreignColumnName))
          $foreignColumnName = self::GuessModelColumn($model);
      $tmp = (is_null($value))?
             null:
             Doctrine::getTable($model)->findBy($foreignColumnName, $value);
      if (is_null($tmp) || $tmp->count() == 0){
         if (trim($value) == ''){
                 $value = null;
         }
         else
         {
             $newRecord = Doctrine::getTable($model)->create(array($foreignColumnName=>$value));
             $newRecord->save();
             $value = $newRecord->getId();
         }
      }
      else
          $value = $tmp[0]->getId();
      return $value;
  }

  public function save($field_name){

      $form = $this->getOption('owner_form');
      $values = $form->getValues();
      $this->setFormField();

      $tmp = (is_null($values[$field_name]))?null:Doctrine::getTable($this->getOption('model'))->findBy($this->getOption('autoCompleterForeignField'), $values[$field_name]);
      
      if (is_null($tmp) || $tmp->count() == 0){
         if (trim($values[$field_name]) == ''){
                 $values[$field_name] = null;
         }
         else
         {
             $newRecord = Doctrine::getTable($this->getOption('model'))->create(array($this->getOption('autoCompleterForeignField')=>$values[$field_name]));
             $newRecord->save(); 
             $values[$field_name] = $newRecord->getId();
         }
      }
      else
          $values[$field_name] = $tmp[0]->getId();

      return $values;
  }
  public function render($name, $value = null, $attributes = array(), $errors = array())
  {
    $this->setFormField($this->getOption('owner_form'), $this->getOption('field_name'));

    $visibleValue = $this->getOption('value_callback') ? call_user_func($this->getOption('value_callback'), $this->getOption('value')) : $this->getOption('value');
    return $this->renderTag('input', array('type' => 'hidden', 'name' => $name, 'value' =>  $this->getOption('value'))).
           parent::render('autocomplete_'.$name, $visibleValue, $attributes, $errors).
           sprintf(<<<EOF
<script type="text/javascript">
  jQuery(document).ready(function() {
    jQuery("#%s").change(function(){
            if ($(this).attr('value').trim() == ''){
                 $("#%s").val("");
            }
        }
    );
    jQuery("#%s")
    .autocomplete('%s', jQuery.extend({}, {
      dataType: 'json',
      parse:    function(data) {
        var parsed = [];
	var autVal =  jQuery("#%s").attr('value');
        data[autVal] = autVal;
        for (key in data) {
          parsed[parsed.length] = { data: [ data[key], key ], value: data[key], result: data[key] };
        }
        return parsed;
      }
    }, %s))
    .result(function(event, data) {
	    jQuery("#%s").val(data[0].trim());
     });
  });
</script>
EOF
      ,
      $this->generateId('autocomplete_'.$name),
      $this->generateId($name),
      $this->generateId('autocomplete_'.$name),
      $this->getOption('url'),
      $this->generateId('autocomplete_'.$name),
      $this->getOption('config'),
      $this->generateId($name)

    );
  }

  /**
   * Gets the stylesheet paths associated with the widget.
   *
   * @return array An array of stylesheet paths
   */
  public function getStylesheets()
  {
    return array('/dmIncrementalAutoCompleteFieldPlugin/css/jquery.autocompleter.css' => 'all');
  }

  /**
   * Gets the JavaScript paths associated with the widget.
   *
   * @return array An array of JavaScript paths
   */
  public function getJavascripts()
  {
    return array('/dmIncrementalAutoCompleteFieldPlugin/js/jquery.autocompleter.js');
  }
  
  public static function getGuessingNamesSet(){
        return array('name', 'nombre', 'title', 'titulo', 'category', 'categoria');
  }

  public static function getChoices($model, $pattern, $limit, $foreignColumnName=null){
        try{
            $autoCompleterForeignField = ($foreignColumnName!=null)?$foreignColumnName:self::GuessModelColumn($model);

            $choices = array();
            $choicesQuery = Doctrine::getTable($model)->createQuery('u')
                            ->where('u.'.$autoCompleterForeignField.' LIKE ?', '%'.$pattern.'%')
                            ->limit($limit)
                            ->execute();
            
            foreach ($choicesQuery  as $record){
               $choices[$record->get($autoCompleterForeignField)] = $record->get($autoCompleterForeignField);
            }
            return $choices;

        }
        catch (Exception $e){
            return array();
        }
  }

  public static function GuessModelColumn($model){
        foreach (self::getGuessingNamesSet() as $guess){
                 if (Doctrine::getTable($model)->hasField($guess))
                         return $guess;
             }
             return null;
    }

    
  
}
