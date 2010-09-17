function initDatatable(){
    if($dataTable = $('table.data_table').orNot())
    {
      $dataTable.dataTable({
        bJQueryUI: true,
        bPaginate: true,
        sPaginationType: "full_numbers",
        aaSorting: [ [5, "asc"], [6, "asc"],[2, "asc"] ],
	aoColumns: [
		      {"bSortable": false},
		      {"bSortable": true, "sClass": "title-col"},
		      null,
		      null,
		      {"bSortable": true, "sType": "date"},
		      {"bSortable": true, "sType": "date"},
		      null,
		   ]
      });
       $('th.ui-state-default:first').css('min-width', '1.5em');
       $('th.ui-state-default:first').css('width', '1.5em');
       $('.data_table').css('width', '100%');
       // building status list
       loadControls();
    }
}
initDatatable();

