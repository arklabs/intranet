<?php
require_once dirname(__FILE__).'/../lib/eventGeneratorConfiguration.class.php';
require_once dirname(__FILE__).'/../lib/eventGeneratorHelper.class.php';

/**
 * event actions.
 *
 * @package    intranet
 * @subpackage event
 * @author     Your name here
 * @version    SVN: $Id: actions.class.php 12474 2008-10-31 10:41:27Z fabien $
 */
class eventActions extends autoEventActions
{
    
  protected function processForm(sfWebRequest $request, sfForm $form)
      {
        $form->bind($request->getParameter($form->getName()), $request->getFiles($form->getName()));
        if ($form->isValid())
        {
          $notice = $form->getObject()->isNew() ? 'The item was created successfully.' : 'The item was updated successfully.';

          try {
            $event = $form->save();
            $this->getUser()->setFlash('notice', $notice);
            $redirection = $this->getRouteArrayForAction('edit', $event);
            $this->redirect($redirection);
            if ($request->hasParameter('dm_embed') && $request->hasParameter('dm_embed') == 1){
                echo '<script type="text/javascript"> if (parent.reload) parent.reload(); parent.$.fn.colorbox.close();</script>'; die;
            }
          } catch (Doctrine_Validator_Exception $e) {

            $errorStack = $form->getObject()->getErrorStack();

            $message = get_class($form->getObject()) . ' has ' . count($errorStack) . " field" . (count($errorStack) > 1 ?  's' : null) . " with validation errors: ";
            foreach ($errorStack as $field => $errors) {
                $message .= "$field (" . implode(", ", $errors) . "), ";
            }
            $message = trim($message, ', ');

            $this->getUser()->setFlash('error', $message);
            return sfView::SUCCESS;
          }

          $this->dispatcher->notify(new sfEvent($this, 'admin.save_object', array('object' => $event)));

          if ($request->hasParameter('_save_and_add'))
          {
            $this->getUser()->setFlash('notice', $notice.' You can add another one below.');

            $redirection = $this->getRouteArrayForAction('new');
          }
          elseif ($request->hasParameter('_save_and_list'))
          {
            $this->getUser()->setFlash('notice', $notice);

            $redirection = $this->getRouteArrayForAction('index');
          }
          elseif ($request->hasParameter('_save_and_next'))
          {
            $this->getUser()->setFlash('notice', $notice);
            $redirection = $this->getRouteArrayForAction('edit', dmArray::get($event->getPrevNextRecords($this->buildQuery()), 'next', $this->form->getObject()));
          }
          else
          {
            $this->getUser()->setFlash('notice', $notice);

            $redirection = $this->getRouteArrayForAction('edit', $event);
          }

          $this->redirect($redirection);
        }
        else
        {
          $this->getUser()->setFlash('error', 'The item has not been saved due to some errors.', false);
        }
      }
  public function executeFixStatusList(sfWebRequest $request){
  	try{
  	 $selectedCategoryId = $request->getParameter('current-category','');
  	 $eventId = $request->getParameter('event-id','');
  	 $selectedStatus = -1;
  	 if ($eventId != ''){
  	 	$event = Doctrine::getTable('Event')->findById($eventId);
  	 	$selectedStatus = $event[0]->getStatusId(); 
  	 }
  	 if ($selectedCategoryId != ''){
	  	 $categories = Doctrine::getTable('EventCategory')->findById($selectedCategoryId);
	  	 $result = '';
	  	 foreach ($categories[0]->getPossibleStatuses() as $status){
	  	 	if ($selectedStatus == $status->getId())
	  	 		$result.= $this->getHelper()->open('option', array('selected'=>'selected','value'=>$status->getId())).$status->getName().$this->getHelper()->close('option');
	  	 	else 
	  	 		$result.= $this->getHelper()->open('option', array('value'=>$status->getId())).$status->getName().$this->getHelper()->close('option');
	  	 }
	  	 echo $result; 
	  	 return true;
  	 }
  	}catch(Exception $e){echo $e->getMessage();die;}
  }
  public function executeGetPhraseologyContent(sfWebRequest $request){
  	 $phraseology_id = $request->getParameter('phraseology-id', '');
  	 if ($phraseology_id != ''){
  	 	$phraseology = Doctrine::getTable('Phraseology')->findById($phraseology_id);
  	 	echo $phraseology[0]->getContent(); 
  	 }
  	 else 
  	 	echo "";
  	 return true;
  }
  public function executeTest()
{
  $config = sfTCPDFPluginConfigHandler::loadConfig();
  //sfTCPDFPluginConfigHandler::includeLangFile($this->getUser()->getCulture());

  $doc_title    = "test title";
//  $doc_subject  = "test description";
//  $doc_keywords = "test keywords";
    //$htmlcontent  = '<h2>Listado de Citas por Agentes</h2>';
    $citas = Doctrine::getTable('Event')->getActiveCitasR();
    

  //create new PDF document (document units are set by default to millimeters)
  $pdf = new sfTCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true);

  // set document information
  $pdf->SetCreator(PDF_CREATOR);
  $pdf->SetAuthor(PDF_AUTHOR);
  //$pdf->SetTitle($doc_title);
//  $pdf->SetSubject($doc_subject);
//  $pdf->SetKeywords($doc_keywords);

  $pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE, PDF_HEADER_STRING);

  //set margins
  $pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);

  //set auto page breaks
  $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
  $pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
  $pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
  $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO); //set image scale factor

  $pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
  $pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

  //initialize document
  $pdf->AliasNbPages();
  $pdf->AddPage();

  // set barcode
  $pdf->SetBarcode(date("Y-m-d H:i:s", time()));

    //titulo del reporte
    $pdf->Write(10, 'Listado de citas por agente', '', 0, 'C',1);

    $pdf->SetTextColor(255);
$pdf->SetFillColor(155,0,0);
$pdf->SetLineWidth(.1);

    $pdf->Cell(36,5,'Agente',1,0,'C',1);
    $pdf->Cell(60,5,'Titulo',1,0,'C',1);
    $pdf->Cell(36,5,'Estado',1,0,'C',1);
    $pdf->Cell(36,5,'Fecha',1,1,'C',1);

    $pdf->SetFontSize( 10);
    $pdf->SetFillColor(224,235,255);
    $pdf->SetTextColor(0);
    foreach ($citas as $cita)
    {
        $pdf->Cell(36,5,  sprintf($cita->getAsigned()),1,0,'C',0);
        $pdf->Cell(60,5,  sprintf($cita->getTitle()),1,0,'C',0);
        $pdf->Cell(36,5,  sprintf($cita->getEventStatus()),1,0,'C',0);
        $pdf->Cell(36,5,  sprintf($cita->getDateStart()),1,1,'C',0);
    }

    //$this->renderCompone
   // $pdf->writeHTML( , true, 0);
  // Close and output PDF document
  //$pdf->Output();
  //return sfView::NONE;
    $pdf->SetCompression(true);

    $this->setLayout('pdf');

    sfConfig::set('sf_web_debug', false);

    $this->pdf = $pdf;


  // Stop symfony process
  //throw new sfStopException();
}
}
