<?php
    $diccionario = array(
        'subtitle'=>array(
            VIEW_GET_USER=>'Buscar usuario',
            VIEW_REPORT_USER=>'Reportar usuario'
        ),

        'links_menu'=>array(
            'VIEW_GET_USER'=>MODULO.VIEW_GET_USER.'/',
            'VIEW_REPORT_USER'=>MODULO.VIEW_REPORT_USER.'/'
        ),
        //mvc
        'form_actions'=>array(
            'GET'=>'/api.DELY_VERY.com/FPDF/'.MODULO.GET_USER.'/',
            'REPORT'=>'/api.DELY_VERY.com/FPDF/'.MODULO.REPORT_USER.'/'
        )
    );

    //function get_template($form='get') {
        function get_template($form='report') {
        $file = '../site_media/html/usuario_'.$form.'.html';
        $template = file_get_contents($file);
        return $template;
    }

    function render_dinamic_data($html, $data) {
        foreach ($data as $clave=>$valor) {
            $html = str_replace('{'.$clave.'}', $valor, $html);
        }
        return $html;
    }
    
    function retornar_vista($vista, $data=array()) {
        global $diccionario;
        $html = get_template('template');
        $html = str_replace('{subtitulo}', $diccionario['subtitle'][$vista], $html);
        $html = str_replace('{formulario}', get_template($vista), $html);
        $html = render_dinamic_data($html, $diccionario['form_actions']);
        $html = render_dinamic_data($html, $diccionario['links_menu']);
        $html = render_dinamic_data($html, $data);

        // render {mensaje}
        if(array_key_exists('nombre', $data) && array_key_exists('apellido', $data) && $vista==VIEW_EDIT_USER) 
        {
                $mensaje = 'Editar usuario '.$data['nombre'].' '.$data['apellido'];
        } else {
            if(array_key_exists('mensaje', $data)) {
                $mensaje = $data['mensaje'];
            } else {
                $mensaje = 'Datos del usuario:';
            }
        }
        $html = str_replace('{mensaje}', $mensaje, $html);
        print $html;
    }
    
?>