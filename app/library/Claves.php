<?php

class Claves {

    function __construct() {
        
    }

    /**
     * El argumento $node es el documento XML de respuesta del SRI
     */
    function buscaComprobantes($node) {
        $Tag_autorizacion = $node->getElementsByTagName('autorizacion');
        $Tag_mensaje = $node->getElementsByTagName('mensaje');
        $Tag_nroComp = $node->getElementsByTagName('RespuestaAutorizacionComprobante');
        foreach ($Tag_nroComp as $comp) {
            if ($comp->hasChildNodes()) {
                foreach ($comp->childNodes as $child) {
                    switch ($child->nodeName) {
                        case 'numeroComprobantes':
                            $this->session->set('nroComprobantesProcesados', $child->nodeValue);

                            break;

                        default:
                            break;
                    }
                }
            }
        }
        if ($this->session->get('nroComprobantesProcesados') > 0) {
            $lineas = 0;
            $this->session->Set('fechaAutoControl', "");
            $this->session->Set('fechaNoAutoControl', "");
            $sri_estado = array();
            $sri_fechaAuto = array();
            $sri_id = array();
            $sri_mensaje = array();
            $sri_tipo = array();
            $nroRechazados = 0;
            foreach ($Tag_autorizacion as $autoriza) {
                if ($autoriza->hasChildNodes()) {
                    foreach ($autoriza->childNodes as $child) {
                        switch ($child->nodeName) {
                            case 'estado':
                                $sri_estado[$lineas] = $child->nodeValue;
                                break;
                            case 'fechaAutorizacion':
                                $sri_fechaAuto[$lineas] = $child->nodeValue;
                                break;
                            case 'numeroAutorizacion':
                                $this->session->set('sri_nroAuto', $child->nodeValue);
                                break;
                        }
                    }
                }
                if ($sri_estado[$lineas] == 'NO AUTORIZADO') {
                    $nroRechazados ++;
                    if ($sri_fechaAuto[$lineas] > $this->session->get('fechaNoAutoControl')) {
                        $this->session->set('fechaNoAutoControl', $sri_fechaAuto[$lineas]);
                    }
                } elseif ($sri_estado[$lineas] == 'AUTORIZADO') {
                    if ($sri_fechaAuto[$lineas] > $this->session->get('fechaAutoControl')) {
                        $this->session->set('fechaAutoControl', $sri_fechaAuto[$lineas]);
                    }
                }
                $lineas++;
            }

            $lineas = 0;
            foreach ($Tag_mensaje as $mensaje) {
                if ($mensaje->hasChildNodes()) {
                    foreach ($mensaje->childNodes as $child) {
                        switch ($child->nodeName) {
                            case 'identificador':
                                $sri_id[$lineas] = $child->nodeValue;
                                break;
                            case 'mensaje':
                                $sri_mensaje[$lineas] = $child->nodeValue;
                                break;
                            case 'tipo':
                                $sri_tipo[$lineas] = $child->nodeValue;
                                break;
                        }
                    }
                    if ($mensaje->parentNode->tagName == 'mensajes') {
                        $lineas++;
                    }
                }
            }
            $this->session->set('sri_estado', $sri_estado);
            $this->session->set('sri_fechaAuto', $sri_fechaAuto);
            $this->session->set('sri_id', $sri_id);
            $this->session->set('sri_mensaje', $sri_mensaje);
            $this->session->set('sri_tipo', $sri_tipo);
        } else {
            $this->session->set('fechaAutoControl', "");
            $this->session->set('fechaNoAutoControl', "");
            $this->session->set('sri_estado', array("0" => 'Desconocido'));
            $this->session->set('sri_fechaAuto', array("0" => 'Sin Fecha'));
            $this->session->set('sri_id', array("0" => '00'));
            $this->session->set('sri_mensaje', array("0" => "No se ha procesado el documento"));
            $this->session->set('sri_tipo', array("0" => "GRAVE"));
        }
    }

    function compruebaClaveAcceso($node) {
        $Tag_claveAcceso = $this->buscaClave($node);
        
        $claveAcceso = $this->session->get('claveAcceso');
        $msgClave = "OK";
        if ($claveAcceso != $Tag_claveAcceso) {
            $this->session->set('fechaAutoControl', "");
            $this->session->set('fechaNoAutoControl', "");
            $this->session->set('sri_estado', array("0" => 'Desconocido'));
            $this->session->set('sri_fechaAuto', array("0" => 'Sin Fecha'));
            $this->session->set('sri_id', array("0" => '99'));
            $this->session->set('sri_mensaje', array("0" => "La clave de acceso consultada no corresponde a la respuesta del SRI"));
            $this->session->set('sri_tipo', array("0" => "ERROR GRAVE"));
            $this->session->set('claveAccesoConsultada', $Tag_claveAcceso);
            $msgClave = "BAD";
        }
        return $msgClave;
    }

    function buscaClave($node) {
        switch ($node->nodeType) {
            case XML_ELEMENT_NODE:

                if ($node->tagName == 'claveAccesoConsultada') {
                }
                break;
            case XML_TEXT_NODE:
                if (trim($node->wholeText)) {
                    if ($node->parentNode->tagName == 'claveAccesoConsultada') {
                        $claveAccesoConsultada = $node->wholeText;
                    }
                }
                break;
        }
        if ($node->hasChildNodes()) {
            foreach ($node->childNodes as $child) {
                $this->buscaClave($child);
            }
        }
        return $claveAccesoConsultada;
    }

    /*
     * El argumento $param debe ser una array asociativa 
     */

//$args['dato'] = '1703644805001 Registro del Contribuyente';
//$args['longitud'] = 12; //debe ser -1 de la longitud deseada
//$args['vector'] = 'I'; //I=Izquierdo D=Derecho;
//$args['relleno'] = 'N'; //N=Numero A=Alfas;
//$stringDate = generaString($args);
//echo implode($stringDate);

    function generaString($param) {
        $claveArray = [];
        $limite = $param['longitud'];

        for ($x = 0; $x < $limite; $x++) {
            $claveArray[$x] = 0;
        }

        $args['tabla'] = $param['dato'];
        $args['posini'] = 0;
        $args['posfin'] = $param['longitud'];
        $args['vector'] = $param['vector'];
        $args['relleno'] = $param['relleno'];

        if ($args['vector'] == "I") {
            $claveArray = $this->haceIzq($args);
        } else {
            $claveArray = $this->haceDer($args);
        }
        return $claveArray;
    }

    function haceDer($param) {
//    echo 'Viene ';
//    var_dump($param);
        $paso = str_split($param['tabla']);
//    var_dump($paso);
        $claveArray = [];
        $limite = $param['posfin'];
        for ($x = 0; $x < $limite; $x++) {
            $claveArray[$x] = 0;
        }
        $j = count($paso) - 1;
        $posini = $param['posini'];
        $posfin = $param['posfin'];
        $flag = TRUE;
        while ($flag) {
            if ($posfin >= $posini) {
//        echo 'Esto tiene ini ' . $posini . ' Esto tiene fin ' . $posfin;
                if ($j >= 0) {
                    $claveArray[$posfin] = $paso[$j];
                    if ($param['relleno'] == 'N') {
                        if (!is_numeric($claveArray[$posfin])) {
                            $claveArray[$posfin] = '0';
                        }
                    } else {
                        if (is_numeric($claveArray[$posfin])) {
                            $claveArray[$posfin] = ' ';
                        }
                    }

                    $j--;
                } else {
                    if ($param['relleno'] == 'N') {
                        $claveArray[$posfin] = '0';
                    } else {
                        $claveArray[$posfin] = ' ';
                    }
                }
                $posfin--;
            } else {
                $flag = FALSE;
            }
        }
        return $claveArray;
    }

    function haceIzq($param) {
//    echo 'Viene ';
//    var_dump($param);
        $paso = str_split($param['tabla']);
        $claveArray = [];
        $limite = $param['posfin'];
        for ($x = 0; $x < $limite; $x++) {
            $claveArray[$x] = 0;
        }
        $j = count($paso) - 1;
        $i = 0;
        $posini = $param['posini'];
        $posfin = $param['posfin'];
        $flag = TRUE;
        while ($flag) {
            if ($posini <= $posfin) {
//        echo 'Esto tiene ini ' . $posini . ' Esto tiene fin ' . $posfin;
                if ($i <= $j) {
                    $claveArray[$posini] = $paso[$i];
                    if ($param['relleno'] == 'N') {
                        if (!is_numeric($claveArray[$posini])) {
                            $claveArray[$posini] = '0';
                        }
                    } else {
                        if (is_numeric($claveArray[$posini])) {
                            $claveArray[$posini] = ' ';
                        }
                    }

                    $i++;
                } else {
                    if ($param['relleno'] == 'N') {
                        $claveArray[$posini] = '0';
                    } else {
                        $claveArray[$posini] = ' ';
                    }
                }
                $posini++;
            } else {
                $flag = FALSE;
            }
        }
        return $claveArray;
    }

    function limpiaString($string) {

        $string = str_replace(
           array('á', 'à', 'ä', 'â', 'ª', 'Á', 'À', 'Â', 'Ä'), array('a', 'a', 'a', 'a', 'a', 'A', 'A', 'A', 'A'), $string
        );

        $string = str_replace(
           array('é', 'è', 'ë', 'ê', 'É', 'È', 'Ê', 'Ë'), array('e', 'e', 'e', 'e', 'E', 'E', 'E', 'E'), $string
        );

        $string = str_replace(
           array('í', 'ì', 'ï', 'î', 'Í', 'Ì', 'Ï', 'Î'), array('i', 'i', 'i', 'i', 'I', 'I', 'I', 'I'), $string
        );

        $string = str_replace(
           array('ó', 'ò', 'ö', 'ô', 'Ó', 'Ò', 'Ö', 'Ô'), array('o', 'o', 'o', 'o', 'O', 'O', 'O', 'O'), $string
        );

        $string = str_replace(
           array('ú', 'ù', 'ü', 'û', 'Ú', 'Ù', 'Û', 'Ü'), array('u', 'u', 'u', 'u', 'U', 'U', 'U', 'U'), $string
        );

        $string = str_replace(
           array('ñ', 'Ñ', 'ç', 'Ç'), array('n', 'N', 'c', 'C',), $string
        );

        return preg_replace('/[^A-Za-z0-9 ,.\-]/', ' ', $string); // Removes special chars.
    }

    /*
     * El argumento $param debe ser una array asociativa 
     */

//$args['fecha'] = '02062014';
//$args['tipodoc'] = '01';
//$args['ruc'] = '1792067464001';
//$args['ambiente'] = '2';
//$args['establecimiento'] = '001';
//$args['punto'] = '001';
//$args['factura'] = '000039540';
//$args['codigo'] = '00039540';
//$args['emision'] = '1';
//$claveArray = [];
//$claveArray = generaClave($args);
//echo 'Esta es la resultante ';
//var_dump($claveArray);
//$digito = poneDigito($claveArray);
//echo 'Este es el digito autoverificador => ' . $digito;
    function poneDigito($param) {
        $posfin = 47;
        $flag = TRUE;
        $j = 2;
        $suma = 0;
        while ($flag) {
            if ($posfin >= 0) {
                $suma = $suma + ($param[$posfin] * $j);
//            echo $suma;
                $j++;
                if ($j > 7) {
                    $j = 2;
                }
                $posfin--;
            } else {
                $flag = FALSE;
            }
        }
//    echo 'Esta es la suma ' . $suma;
        $tienecero = $suma % 11;
        if ($tienecero == 0) {
            $digito = 0;
        } else {
            $digito = 11 - ($suma % 11);
        }
//    echo '<br>Este es el digito verificador ' . $digito;
        if ($digito == 10) {
            $digito = 1;
        }
        return $digito;
    }

    function generaClave($param) {
        $claveArray = [];
        /*
         * Generar con ceros la tabla de hasta 49 posiciones
         */
        for ($x = 0; $x < 49; $x++) {
            $claveArray[$x] = 0;
        }
        /*
         * Proceso de convertir cada campo en array para adicionar a la array de la clave
         */

        $args['tabla'] = $param['fecha'];
        $args['posini'] = 0;
        $args['posfin'] = 7;
        $args['claveArray'] = $claveArray;
        $claveArray = $this->haceArray($args);
//echo 'Pasa fecha';

        $args['tabla'] = $param['tipodoc'];
        $args['posini'] = 8;
        $args['posfin'] = 9;
        $args['claveArray'] = $claveArray;
        $claveArray = $this->haceArray($args);
//echo 'Pasa tipo documento';

        $args['tabla'] = $param['ruc'];
        $args['posini'] = 10;
        $args['posfin'] = 22;
        $args['claveArray'] = $claveArray;
        $claveArray = $this->haceArray($args);
//echo 'Pasa ruc';


        $args['tabla'] = $param['ambiente'];
        $args['posini'] = 23;
        $args['posfin'] = 23;
        $args['claveArray'] = $claveArray;
        $claveArray = $this->haceArray($args);



        $args['tabla'] = $param['establecimiento'];
        $args['posini'] = 24;
        $args['posfin'] = 26;
        $args['claveArray'] = $claveArray;
        $claveArray = $this->haceArray($args);



        $args['tabla'] = $param['punto'];
        $args['posini'] = 27;
        $args['posfin'] = 29;
        $args['claveArray'] = $claveArray;
        $claveArray = $this->haceArray($args);



        $args['tabla'] = $param['factura'];
        $args['posini'] = 30;
        $args['posfin'] = 38;
        $args['claveArray'] = $claveArray;
        $claveArray = $this->haceArray($args);



        $args['tabla'] = $param['codigo'];
        $args['posini'] = 39;
        $args['posfin'] = 46;
        $args['claveArray'] = $claveArray;
        $claveArray = $this->haceArray($args);



        $args['tabla'] = $param['emision'];
        $args['posini'] = 47;
        $args['posfin'] = 47;
        $args['claveArray'] = $claveArray;
        $claveArray = $this->haceArray($args);
        $digito = $this->poneDigito($claveArray);
        $claveArray[48] = $digito;
        return $claveArray;
    }

    function haceArray($param) {
//    echo 'Viene ';
//    var_dump($param);
        $paso = str_split($param['tabla']);

        $j = count($paso) - 1;
        $posini = $param['posini'];
        $posfin = $param['posfin'];
        $claveArray = $param['claveArray'];
        $flag = TRUE;
        while ($flag) {
            if ($posfin >= $posini) {
//        echo 'Esto tiene ini ' . $posini . ' Esto tiene fin ' . $posfin;
                if ($j >= 0) {
                    $claveArray[$posfin] = $paso[$j];
                    $j--;
                }
                $posfin--;
            } else {
                $flag = FALSE;
            }
        }
        return $claveArray;
    }

    function generaContingencia($param) {
        $claveArray = [];
        /*
         * Generar con ceros la tabla de hasta 49 posiciones
         */
        for ($x = 0; $x < 49; $x++) {
            $claveArray[$x] = 0;
        }
        /*
         * Proceso de convertir cada campo en array para adicionar a la array de la clave
         */

        $args['tabla'] = $param['fecha'];
        $args['posini'] = 0;
        $args['posfin'] = 7;
        $args['claveArray'] = $claveArray;
        $claveArray = haceArray($args);
//echo 'Pasa fecha';

        $args['tabla'] = $param['tipodoc'];
        $args['posini'] = 8;
        $args['posfin'] = 9;
        $args['claveArray'] = $claveArray;
        $claveArray = haceArray($args);
//echo 'Pasa tipo documento';

        $args['tabla'] = $param['ruc'];
        $args['posini'] = 10;
        $args['posfin'] = 22;
        $args['claveArray'] = $claveArray;
        $claveArray = haceArray($args);
//echo 'Pasa ruc';


        $args['tabla'] = $param['ambiente'];
        $args['posini'] = 23;
        $args['posfin'] = 23;
        $args['claveArray'] = $claveArray;
        $claveArray = haceArray($args);



        $args['tabla'] = $param['contingencia'];
        $args['posini'] = 24;
        $args['posfin'] = 46;
        $args['claveArray'] = $claveArray;
        $claveArray = haceArray($args);

        $args['tabla'] = $param['emision'];
        $args['posini'] = 47;
        $args['posfin'] = 47;
        $args['claveArray'] = $claveArray;
        $claveArray = haceArray($args);
        $digito = poneDigito($claveArray);
        $claveArray[48] = $digito;
        return $claveArray;
    }

}
