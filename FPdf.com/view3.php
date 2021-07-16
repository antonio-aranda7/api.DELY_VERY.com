<?php
    $diccionario = array(
        'subtitle'=>array(VIEW_SET_CAPA=>'Crear un nueva capa',
            VIEW_GET_CAPA=>'Buscar capa',
            VIEW_DELETE_CAPA=>'Eliminar un capa',
            VIEW_EDIT_CAPA=>'Modificar capa',
            VIEW_REPORT_CAPA=>'Reportar capa'
        ),

        'links_menu'=>array(
            'VIEW_SET_CAPA'=>MODULO.VIEW_SET_CAPA.'/',
            'VIEW_GET_CAPA'=>MODULO.VIEW_GET_CAPA.'/',
            'VIEW_EDIT_CAPA'=>MODULO.VIEW_EDIT_CAPA.'/',
            'VIEW_DELETE_CAPA'=>MODULO.VIEW_DELETE_CAPA.'/',
            'VIEW_REPORT_CAPA'=>MODULO.VIEW_REPORT_CAPA.'/'
        ),
        //mvc
        'form_actions'=>array(
            'SET'=>'/MVC/'.MODULO.SET_CAPA.'/',
            'GET'=>'/MVC/'.MODULO.GET_CAPA.'/',
            'DELETE'=>'/MVC/'.MODULO.DELETE_CAPA.'/',
            'EDIT'=>'/MVC/'.MODULO.EDIT_CAPA.'/',
            'REPORT'=>'/MVC/'.MODULO.REPORT_CAPA.'/'
        )
    );

    function get_template($form='get') {
        $file = '../site_media/html/capa_'.$form.'.html';
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
        if(array_key_exists('titulo', $data)&&
            array_key_exists('precio', $data)&&
            $vista==VIEW_EDIT_CAPA) {
                $mensaje = 'Editar capa '.$data['titulo'].' '.$data['precio'];
        } else {
            if(array_key_exists('mensaje', $data)) {
                $mensaje = $data['mensaje'];
            } else {
                $mensaje = 'Datos del capa:';
            }
        }
        $html = str_replace('{mensaje}', $mensaje, $html);
        print $html;
    }
?>