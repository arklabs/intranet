<?php
class myMenu extends dmMenu
{  
  
  // build the menu hierarchy  
  public function build()  
  {
     $this
    ->addChild('Home', '@homepage')->end()
    ->addChild('Contact', 'http://diem-project.org')->addChild('Contact-child', 'http://diem-project.org')->ulClass('nav-top-item')->liClass('nav-trackable')->end()->end()
    ->addChild('Blog', 'http://diem-project.org')->end()
    ->addChild('Sites')
      ->addChild('Diem', 'http://diem-project.org')->end()
      ->addChild('Symfony', 'http://symfony-project.org')->end()
    ->end();
    /*$this
    ->addChild('Dasboard', '@homepage')->end();
    ->addChild('Calendario', '')->ulClass('nav-top-item')
        ->addChild('Ver Calendario', 'calendario/ver-calendario')->liClass('nav-trackable')->end()
    ->end()
  //  ->addChild('Tareas', '')
  //      ->addChild('Mis Tareas', 'articles/list')->end()
    ->addChild('Clientes', '')
        ->addChild('Mis Clientes', 'clientes/mis-clientes')->end()
    ->end();*/
 
    return $this;  
  }  
}  
?>