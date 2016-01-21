<?php

//MODIFICADO POR SKINA PARA POSTGRES
switch ($db->driver) {
    case 'mssql':
        $isql = 'select 
			a.anex_estado CHU_ESTADO
		,a.sgd_deve_codigo HID_DEVE_CODIGO
		,a.sgd_deve_fech AS "HID_SGD_DEVE_FECH" 
		,convert(varchar(15),a.radi_nume_salida) AS "IMG_Radicado Salida"
		,' . $radiPath . ' as "HID_RADI_PATH"
		,' . $db->conn->substr . '(convert(char(1),a.sgd_dir_tipo),2,3) AS "Copia"
		,convert(varchar(15),a.anex_radi_nume) AS "Radicado Padre"
		,c.radi_fech_radi AS "Fecha Radicado"
		,a.anex_desc AS "Descripcion"
		,a.sgd_fech_impres AS "Fecha Impresion"
		,a.anex_creador AS "Generado Por"
		,convert(varchar(20),a.anex_codigo) AS "CHR_RADI_NUME_SALIDA" 
		,a.sgd_deve_codigo HID_DEVE_CODIGO1
		,a.anex_estado HID_ANEX_ESTADO1
		,a.anex_nomb_archivo AS "HID_ANEX_NOMB_ARCHIVO" 
		,a.anex_tamano AS "HID_ANEX_TAMANO"
		,a.ANEX_RADI_FECH AS "HID_ANEX_RADI_FECH" 
		,' . "'WWW'" . ' AS "HID_WWW" 
		,' . "'9999'" . ' AS "HID_9999"     
		,a.anex_tipo AS "HID_ANEX_TIPO" 
		,a.anex_radi_nume AS "HID_ANEX_RADI_NUME" 
		,a.sgd_dir_tipo AS "HID_SGD_DIR_TIPO"
		,a.sgd_deve_codigo AS "HID_SGD_DEVE_CODIGO"
		,c.radi_path as "HID_IMGPPAL" 
		FROM anexos a,usuario b, radicado c
		WHERE  ANEX_ESTADO>=' . $estado_sal . ' ' .
                $dependencia_busq2 . '
		and a.ANEX_ESTADO <= ' . $estado_sal_max . '
		and a.radi_nume_salida=c.radi_nume_radi
		and a.anex_creador=b.usua_login
		and a.anex_borrado= ' . "'N'" . '
		and a.sgd_dir_tipo != 7
		and ((a.SGD_DEVE_CODIGO <=0 
		and a.SGD_DEVE_CODIGO <=99)
		OR a.SGD_DEVE_CODIGO IS NULL)
		AND
		((c.SGD_EANU_CODIGO <> 2
		AND c.SGD_EANU_CODIGO <> 1) 
		or c.SGD_EANU_CODIGO IS NULL)
		order by ' . $order . ' ' . $orderTipo;
        break;
    case 'oracle':
    case 'oci8':
    case 'oci8po':
        $isql = 'select 
			a.anex_estado as "CHU_ESTADO"
		 	,a.sgd_deve_codigo as "HID_DEVE_CODIGO"
			,a.sgd_deve_fech as "HID_SGD_DEVE_FECH" 
			,a.radi_nume_salida AS "IMG_Radicado Salida"
			,' . $radiPath . ' as "HID_RADI_PATH"
        	,' . $db->conn->substr . '(a.sgd_dir_tipo,2,3) AS "Copia"
			,a.anex_radi_nume AS "Radicado Padre"
			,c.radi_fech_radi AS "Fecha Radicado"
			,d.sgd_dir_nomremdes AS "Destinatario"
			,a.sgd_fech_impres AS "Fecha Impresion"
			,a.anex_creador AS "Generado Por"
			,a.anex_codigo AS "CHR_RADI_NUME_SALIDA" 
			,a.sgd_deve_codigo as "HID_DEVE_CODIGO1"
			,a.anex_estado as "HID_ANEX_ESTADO1"
			,a.anex_nomb_archivo AS "HID_ANEX_NOMB_ARCHIVO" 
			,a.anex_tamano AS "HID_ANEX_TAMANO"
			,a.ANEX_RADI_FECH AS "HID_ANEX_RADI_FECH" 
			,' . "'WWW'" . ' AS "HID_WWW" 
			,' . "'9999'" . ' AS "HID_9999"     
			,a.anex_tipo AS "HID_ANEX_TIPO" 
			,a.anex_radi_nume AS "HID_ANEX_RADI_NUME" 
			,a.sgd_dir_tipo AS "HID_SGD_DIR_TIPO"
			,a.sgd_deve_codigo AS "HID_SGD_DEVE_CODIGO"
			,c.radi_path as "HID_IMGPPAL"
			from anexos a,usuario b, radicado c,sgd_dir_drecciones d
			where a.ANEX_ESTADO>=' . $estado_sal . ' ' .
                $dependencia_busq2 . '
			and a.ANEX_ESTADO <= ' . $estado_sal_max . '
			and a.radi_nume_salida=c.radi_nume_radi
			and a.anex_creador=b.usua_login
			and a.anex_borrado= ' . "'N'" . '
			and a.sgd_dir_tipo != 7
			and 
			((a.SGD_DEVE_CODIGO <=0 and a.SGD_DEVE_CODIGO <=99) OR a.SGD_DEVE_CODIGO IS NULL)
			AND
			((c.SGD_EANU_CODIGO != 2
			AND c.SGD_EANU_CODIGO != 1) 
			or c.SGD_EANU_CODIGO IS NULL)
			and c.radi_nume_radi=d.radi_nume_radi
			and a.sgd_dir_tipo=d.sgd_dir_tipo
			order by ' . $order . ' ' . $orderTipo;
         $isqlTestAnex = 'select 
                            a.anex_codigo AS "CHR_RADI_NUME_SALIDA" 
			,CASE WHEN ((upper(c.RADI_PATH) like \'%.TIF\')or(upper(c.RADI_PATH) like \'%.PDF\') or(upper(c.RADI_PATH) like \'%.JPG\')) THEN \'SI\' ELSE \'NOPE\' END AS "TESTIMG"
		from anexos a
                join usuario b on a.anex_creador=b.usua_login
                join radicado c on a.radi_nume_salida=c.radi_nume_radi AND ((c.SGD_EANU_CODIGO != 2 AND c.SGD_EANU_CODIGO != 1) or c.SGD_EANU_CODIGO IS NULL) ' . $dependencia_busq2 . '
                join sgd_dir_drecciones d on d.radi_nume_radi=a.radi_nume_salida and a.sgd_dir_tipo=d.sgd_dir_tipo and a.sgd_dir_tipo != 7
		left join medio_recepcion m on m.mrec_codi=d.mrec_codi
                join dependencia dep on b.depe_codi=dep.depe_codi
			where a.ANEX_ESTADO>=' . $estado_sal . ' ' .
                $dependencia_busq2 . '
			and a.ANEX_ESTADO <= ' . $estado_sal_max . '
			and a.radi_nume_salida=c.radi_nume_radi
			and a.anex_creador=b.usua_login
			and a.anex_borrado= ' . "'N'" . '
			and a.sgd_dir_tipo != 7
			and 
			((a.SGD_DEVE_CODIGO <=0 and a.SGD_DEVE_CODIGO <=99) OR a.SGD_DEVE_CODIGO IS NULL)
			AND
			((c.SGD_EANU_CODIGO != 2
			AND c.SGD_EANU_CODIGO != 1) 
			or c.SGD_EANU_CODIGO IS NULL)
			and c.radi_nume_radi=d.radi_nume_radi
			and a.sgd_dir_tipo=d.sgd_dir_tipo
			order by 1 ' . $orderTipo;
         
        break;
    default:
        $fechaimpresion = utf8_encode("Fecha Impresión");
        $radiPath = $db->conn->Concat($db->conn->substr . "(cast(a.anex_codigo as varchar),1,4) ", "'/'", $db->conn->substr . "(cast(a.anex_codigo as varchar),5,3) ", "'/docs/'", "a.anex_nomb_archivo");
        $isql = 'select 
			a.anex_estado as "CHU_ESTADO"
		 	,a.sgd_deve_codigo as "HID_DEVE_CODIGO"
			,a.sgd_deve_fech as "HID_SGD_DEVE_FECH" 
			,a.radi_nume_salida AS "IMG_Radicado Salida"
			,' . $radiPath . ' as "HID_RADI_PATH"
        	,' . $db->conn->substr . '(cast(a.sgd_dir_tipo as varchar),2,3) AS "Copia"
			,a.anex_radi_nume AS "Radicado Padre"
			,to_char(c.radi_fech_radi, ' . "'YYYY-MM-DD - HH24:MI:SS'" . ') AS "Fecha Radicado"
			,d.sgd_dir_nomremdes AS "Destinatario"
			,to_char(a.sgd_fech_impres, ' . "'YYYY-MM-DD - HH24:MI:SS'" . ') AS "' . $fechaimpresion . '"
			,a.anex_creador AS "Generado Por"
			,a.anex_codigo AS "CHR_RADI_NUME_SALIDA" 
			,a.sgd_deve_codigo as "HID_DEVE_CODIGO1"
			,a.anex_estado as "HID_ANEX_ESTADO1"
			,a.anex_nomb_archivo AS "HID_ANEX_NOMB_ARCHIVO" 
			,a.anex_tamano AS "HID_ANEX_TAMANO"
			,a.ANEX_RADI_FECH AS "HID_ANEX_RADI_FECH" 
			,' . "'WWW'" . ' AS "HID_WWW" 
			,' . "'9999'" . ' AS "HID_9999"     
			,a.anex_tipo AS "HID_ANEX_TIPO" 
			,a.anex_radi_nume AS "HID_ANEX_RADI_NUME" 
			,a.sgd_dir_tipo AS "HID_SGD_DIR_TIPO"
			,a.sgd_deve_codigo AS "HID_SGD_DEVE_CODIGO" 
			,c.radi_path as "HID_IMGPPAL"
			from anexos a,usuario b, radicado c,sgd_dir_drecciones d
			where a.ANEX_ESTADO>=' . $estado_sal . ' ' .
                $dependencia_busq2 . '
			and a.ANEX_ESTADO <= ' . $estado_sal_max . '
			and a.radi_nume_salida=c.radi_nume_radi
			and a.anex_creador=b.usua_login
			and a.anex_borrado= ' . "'N'" . '
			and a.sgd_dir_tipo != 7
			and 
			((a.SGD_DEVE_CODIGO <=0 and a.SGD_DEVE_CODIGO <=99) OR a.SGD_DEVE_CODIGO IS NULL)
			AND
			((c.SGD_EANU_CODIGO != 2
			AND c.SGD_EANU_CODIGO != 1) 
			or c.SGD_EANU_CODIGO IS NULL)
			and c.radi_nume_radi=d.radi_nume_radi
			and a.sgd_dir_tipo=d.sgd_dir_tipo
			order by ' . $order . ' ' . $orderTipo;
                        
        $isqlTestAnex = 'select 
                            a.anex_codigo AS "CHR_RADI_NUME_SALIDA" 
			,CASE WHEN ((upper(c.RADI_PATH) like \'%.TIF\')or(upper(c.RADI_PATH) like \'%.PDF\')) is TRUE THEN \'SI\' ELSE \'NOPE\' END AS "TESTIMG"
		from anexos a
                join usuario b on a.anex_creador=b.usua_login
                join radicado c on a.radi_nume_salida=c.radi_nume_radi AND ((c.SGD_EANU_CODIGO != 2 AND c.SGD_EANU_CODIGO != 1) or c.SGD_EANU_CODIGO IS NULL) ' . $dependencia_busq2 . '
                join sgd_dir_drecciones d on d.radi_nume_radi=a.radi_nume_salida and a.sgd_dir_tipo=d.sgd_dir_tipo and a.sgd_dir_tipo != 7
		left join medio_recepcion m on m.mrec_codi=d.mrec_codi
                join dependencia dep on b.depe_codi=dep.depe_codi
			where a.ANEX_ESTADO>=' . $estado_sal . ' ' .
                $dependencia_busq2 . '
			and a.ANEX_ESTADO <= ' . $estado_sal_max . '
			and a.radi_nume_salida=c.radi_nume_radi
			and a.anex_creador=b.usua_login
			and a.anex_borrado= ' . "'N'" . '
			and a.sgd_dir_tipo != 7
			and 
			((a.SGD_DEVE_CODIGO <=0 and a.SGD_DEVE_CODIGO <=99) OR a.SGD_DEVE_CODIGO IS NULL)
			AND
			((c.SGD_EANU_CODIGO != 2
			AND c.SGD_EANU_CODIGO != 1) 
			or c.SGD_EANU_CODIGO IS NULL)
			and c.radi_nume_radi=d.radi_nume_radi
			and a.sgd_dir_tipo=d.sgd_dir_tipo
			order by 1 ' . $orderTipo;
        break;
}
?>