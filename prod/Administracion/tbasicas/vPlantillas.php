<html>
<link href="../../estilos/orfeo38/orfeo.css" rel="stylesheet" type="text/css">
<script language="javascript" src="../../js/ajax.js"></script>
<script>
function borrar(val)
{
	if(confirm("Est\xE1 seguro de eliminar el archivo?"))
	{
		if(xmlHttp) 
		{
	
		  var obj = document.getElementById('lstPlantillas');
			
		  xmlHttp.open("GET", "./adm_plantillas.php?fec=1&rut="+val);
		
		  xmlHttp.onreadystatechange = function()
		  {
		
			  if (xmlHttp.readyState == 4 && xmlHttp.status == 200) 
			  {
			
			  	obj.innerHTML = xmlHttp.responseText;
			  
			  }
		  }
		  xmlHttp.send(null);
		}
	}
}
function agregar()
{
	if(!document.getElementById('filePlantilla').value)
	{
		alert('Debe seleccionar un archivo');
		return false;
	}
        
	else if(document.getElementById('tipoAyuda').value==0)
	{
		alert('Debe seleccionar un tipo de ayuda');
		return false;
	}
	else return true;
}
</script>
<form id="frmPlantillas" method="post" action="adm_plantillas.php" enctype="multipart/form-data">
<input type="hidden" id="fec" name="fec" value="2">
	<div id="lstPlantillas">
	<?php echo $tbl;?>
	</div>
	<?echo $error?>
</form>
</html>