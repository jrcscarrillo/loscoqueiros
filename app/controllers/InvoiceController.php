<?php

use Phalcon\Mvc\Model\Criteria;
use Phalcon\Paginator\Adapter\Model as Paginator;

class InvoiceController extends ControllerBase {

    public function indexAction() {
        $this->session->conditions = null;
        $this->view->form = new InvoiceForm;
    }

    public function searchAction() {
        $numberPage = 1;
        if ($this->request->isPost()) {
            $query = Criteria::fromInput($this->di, 'Invoice', $_POST);
            $this->persistent->parameters = $query->getParams();
        } else {
            $numberPage = $this->request->getQuery("page", "int");
        }

        $parameters = $this->persistent->parameters;
        if (!is_array($parameters)) {
            $parameters = [];
        }
        $parameters["order"] = "RefNumber";

        $miscodigos = Invoice::find($parameters);
        if (count($miscodigos) == 0) {
            $this->flash->notice("El resultado de la busqueda no arrojo ninguna factura sincronizada desde el QB");

            $this->dispatcher->forward([
               "controller" => "invoice",
               "action" => "index"
            ]);

            return;
        }

        $paginator = new Paginator([
           'data' => $miscodigos,
           'limit' => 10,
           'page' => $numberPage
        ]);

        $this->view->page = $paginator->getPaginate();
    }

    public function newAction() {
        
    }

    /**
     * Select an invoice to certificate
     *
     * @param string $TxnID
     */
    public function firmarAction($TxnID) {
        $parameters = array('conditions' => '[TxnID] = :txnid:', 'bind' => array('txnid' => $TxnID));

        if (!$this->session->has('contribuyente')) {
            $this->flash->error("No se ha seleccionado al contribuyente");
            return $this->dispatcher->forward(
                  [
                     "controller" => "invoice",
                     "action" => "index",
                  ]
            );
        }
        
        $factura = Invoice::findFirst($parameters);
        if ($factura == false) {
            $this->flash->error("Esta factura no existe");
            return $this->dispatcher->forward(
                  [
                     "controller" => "invoice",
                     "action" => "index",
                  ]
            );
        }

        $this->_registerInvoice($factura);
        $this->flash->success('Factura del QB Seleccionada || ' . $factura->RefNumber);
        $this->firmaFactura($factura);
        return $this->dispatcher->forward(
              [
                 "controller" => "invoice",
                 "action" => "search",
              ]
        );
    }

    private function _registerInvoice($arreglo) {
        $this->session->set('cabecera', array(
           'TxnID' => $arreglo->TxnID,
           'TimeCreated' => $arreglo->TimeCreated,
           'TimeModified' => $arreglo->TimeModified,
           'EditSequence' => $arreglo->EditSequence,
           'numeroTransaccion' => $arreglo->TxnNumber,
           'CustomerRef_ListID' => $arreglo->CustomerRef_ListID,
           'razonSocialComprador' => $arreglo->CustomerRef_FullName,
           'fechaDocumento' => $arreglo->TxnDate,
           'numeroDocumento' => $arreglo->RefNumber,
           'direccionComprador' => $arreglo->BillAddress_Addr1,
           'BillAddress_City' => $arreglo->BillAddress_City,
           'BillAddress_State' => $arreglo->BillAddress_State,
           'BillAddress_PostalCode' => $arreglo->BillAddress_PostalCode,
           'BillAddress_Country' => $arreglo->BillAddress_Country,
           'SalesRepRef_FullName' => $arreglo->SalesRepRef_FullName,
           'Subtotal' => $arreglo->Subtotal,
           'SalesTaxPercentage' => $arreglo->SalesTaxPercentage,
           'SalesTaxTotal' => $arreglo->SalesTaxTotal,
           'AppliedAmount' => $arreglo->AppliedAmount,
           'BalanceRemaining' => $arreglo->BalanceRemaining,
           'CustomField15' => $arreglo->CustomField15
        ));
        $this->session->set('factura', array(
           'baseImponible' => 0,
           'valorImpuestos' => 0,
           'valorSinImpuestos' => 0,
           'valorDescuentos' => 0,
           'valorTotal' => 0
        ));
        $this->session->set('codigoImpuesto', '2');
        $this->session->set('porcentajeImpuesto', '12');
        $this->session->set('codigoTarifaImpuesto', '2');
        $this->session->set('facturas', array());
        $this->session->set('programas', array());
    }

    private function firmaFactura($param) {
        $this->session->set('stringDetalles', '<detalles>');
//        print_r($param);
        foreach ($param->invoicelinedetail as $producto) {
            if ($producto->ItemRef_ListID <> " ") {
                $stringItem = $this->procesaItem($producto);
            }
        }

        $this->totalFactura($param);
        $this->sriCliente();
    }

    function sriCliente() {
        $archivo = $this->session->get('archivo');
        $firmado = $this->session->get('firmado');
        $ruc = $this->session->get('contribuyente');
        $doc = new DOMDocument();
        $doc->load($firmado);
        $content = $doc->saveXML();

        if ($ruc['ambiente'] == 1) {
            $this->flash->success("Estamos en ambiente de prueba");
            $wsdl = "https://celcer.sri.gob.ec/comprobantes-electronicos-ws/RecepcionComprobantesOffline?wsdl";
        } else {
            $wsdl = "https://cel.sri.gob.ec/comprobantes-electronicos-ws/RecepcionComprobantesOffline?wsdl";
        }

        $options = array('soap_version' => SOAP_1_1, 'trace' => true);
        $client = new SoapClient($wsdl, $options);


        try {
            $respuesta = $client->ValidarComprobante(array('xml' => $content));
            $datosXML = $client->__getLastResponse();
        } catch (SoapFault $exp) {
            
        }
        $doc = new DOMDocument();
        $doc->loadXML($datosXML);
        $doc->save('porFormato.xml');
        $mensaje = $doc->getElementsByTagName('estado')->item(0)->nodeValue;
        if ($mensaje == 'RECIBIDA') {
//            $this->facturaAutorizada();
            $this->preparaFactura();
        } else {
            $this->sri_mensajes_rechazada($doc);
        }
    }

    function sri_mensajes_rechazada($node) {
        $fin_mensaje = '';
        try {
            $Tag_mensajes = $node->getElementsByTagName('mensaje');
            foreach ($Tag_mensajes as $autoriza) {
                $string_mensaje = NULL;
                if ($autoriza->hasChildNodes()) {
                    foreach ($autoriza->childNodes as $mensaje) {
                        switch ($mensaje->nodeName) {
                            case "identificador":
                                $string_mensaje = "<br><b> *** mensaje => " . $mensaje->nodeValue . " </b> ";
                                break;
                            case "mensaje":
                                $string_mensaje .= $mensaje->nodeValue . "  ";
                                break;
                            case "informacionAdicional":
                                $string_mensaje .= $mensaje->nodeValue . " *** error ";
                                break;
                            case "tipo":
                                $string_mensaje .= $mensaje->nodeValue . "<br>";
                                break;
                        }
                    }

                    $fin_mensaje .= $string_mensaje;
                }
            }
        } catch (Exception $exc) {
            $this->flash->error($exc->getTraceAsString());
        }
        $this->flash->error($fin_mensaje);
    }

    function facturaAutorizada() {
        $doc = new DOMDocument();
        $firmado = $this->session->get('firmado');
        $doc->load($firmado);
        $w_cabecera = $this->session->get('cabecera');
        $TxnID = $w_cabecera["TxnID"];
        $invoice = Invoice::findFirstByTxnID($TxnID);

        if (!$invoice) {
            $this->flash->error("invoice does not exist " . $TxnID);

            $this->dispatcher->forward([
               'controller' => "invoice",
               'action' => 'index'
            ]);

            return;
        }

        $invoice->setCustomField15("AUTORIZADA");
        if (!$invoice->save()) {

            foreach ($invoice->getMessages() as $message) {
                $this->flash->error($message);
            }

            $this->dispatcher->forward([
               'controller' => "invoice",
               'action' => 'index',
            ]);

            return;
        }

        $this->flash->success("invoice was updated successfully");
        $this->grabaFacturaAutorizada();
    }

    function grabaFacturaAutorizada() {

        $doc = new DOMDocument();

        $firmado = $this->session->get('firmado');
        $doc->load($firmado);
        $this->session->set('sri', array(
           'codDoc' => $doc->getElementsByTagName('codDoc')->item(0)->nodeValue,
           'secuencial' => $doc->getElementsByTagName('secuencial')->item(0)->nodeValue,
           'fechaEmision' => $doc->getElementsByTagName('fechaEmision')->item(0)->nodeValue,
           'tipoIdentificacion' => $doc->getElementsByTagName('tipoIdentificacionComprador')->item(0)->nodeValue,
           'identificacionComprador' => $doc->getElementsByTagName('identificacionComprador')->item(0)->nodeValue,
           'razonSocialComprador' => $doc->getElementsByTagName('razonSocialComprador')->item(0)->nodeValue,
           'direccionComprador' => $doc->getElementsByTagName('dirEstablecimiento')->item(0)->nodeValue,
           'telefono' => '',
           'propina' => 0,
           'totalSinImpuesto' => $doc->getElementsByTagName('totalSinImpuestos')->item(0)->nodeValue,
           'propina' => $doc->getElementsByTagName('propina')->item(0)->nodeValue,
           'importeTotal' => $doc->getElementsByTagName('importeTotal')->item(0)->nodeValue,
           'totalImpto' => $doc->getElementsByTagName('importeTotal')->item(0)->nodeValue - $doc->getElementsByTagName('totalSinImpuestos')->item(0)->nodeValue,
           'numeroAutorizacion' => $doc->getElementsByTagName('claveAcceso')->item(0)->nodeValue,
           'moneda' => 'DOLAR',
           'estado' => 'AUTORIZADA',
           'codMsg' => '',
           'mensaje' => '',
           'msgAd' => '',
           'msgError' => ''
        ));
        $this->preparaFactura();
        }

    function agregaFactura() {
        
    }

    function preparaFactura() {
    $doc = new DOMDocument();
    $firmado = $this->session->get('firmado');
    $doc->load($firmado);
    $param = 'fact' . $this->session->get('secuencial') . '.html';
    $pdf = 'fact' . $this->session->get('secuencial') . '.pdf';
    $prefijo = 'fact' . $this->session->get('secuencial') . '.xml';
    $directorio = $_SERVER['DOCUMENT_ROOT'] . '/loscoqueiros/public/plantillaFactura.html';
    $salida = $_SERVER['DOCUMENT_ROOT'] . '/ComprobantesSRI/facturas/autorizados/' . $param;
    $autorizado = $_SERVER['DOCUMENT_ROOT'] . '/ComprobantesSRI/facturas/generados/' . $prefijo;
    $this->session->set('autorizado', $autorizado);
    $this->session->set('htmlparaPDF', $param);
    $sri = $this->session->get('sri');
    $w_ruc = $this->session->get('contribuyente');

    copy($directorio, $salida);
    $doc1 = new DOMDocument();
    $doc1->loadHTMLFile($salida);

    $get_dia = substr($sri['fechaEmision'], 0, 2);
    $get_mes = substr($sri['fechaEmision'], 3, 2);
    $get_anual = substr($sri['fechaEmision'], 6, 4);

    $E_matriz = $doc1->getElementById('matriz');
    $E_emisor = $doc1->getElementById('emisor');
    $E_especial = $doc1->getElementById('especial');
    $E_obligado = $doc1->getElementById('obligado');
    $E_ambiente = $doc1->getElementById('ambiente');
    $E_emision = $doc1->getElementById('emision');
    $E_ruc = $doc1->getElementById('ruc');
    $E_claveacceso = $doc1->getElementById('claveacceso');
    $E_numeroFactura = $doc1->getElementById('numerofactura');
    $E_fechaemision = $doc1->getElementById('fechaemision');
    $E_rucCliente = $doc1->getElementById('rucCliente');
    $E_razon = $doc1->getElementById('razon');
    $E_cia = $doc1->getElementById('cia');
    $E_direccion = $doc1->getElementById('direccion');
    $E_imponible12 = $doc1->getElementById('imponible12');
    $E_totalSinImpto = $doc1->getElementById('totalSinImpto');
    $E_valorImpto12 = $doc1->getElementById('valorImpto12');
    $E_apagar = $doc1->getElementById('apagar');
    $E_codigo = $doc1->getElementsByTagName('td')->item(0);
    $E_descripcion = $doc1->getElementsByTagName('td')->item(1);
    $E_cantidad = $doc1->getElementsByTagName('td')->item(2);
    $E_pUnitario = $doc1->getElementsByTagName('td')->item(3);
    $E_dscto = $doc1->getElementsByTagName('td')->item(4);
    $E_parcial = $doc1->getElementsByTagName('td')->item(5);

    $numerito = $this->session->get('estab') . "-" . $this->session->get('punto') . "-" . $sri['secuencial'];
    $P_cia = $doc1->createElement('p', $w_ruc['razon']);
    $P_ruc = $doc1->createElement('p', $w_ruc['ruc']);
    $P_especial = $doc1->createElement('p', $w_ruc['Resolucion']);
    $P_obligado = $doc1->createElement('p', $w_ruc['LlevaContabilidad']);
    $P_matriz = $doc1->createElement('p', $w_ruc['DirMatriz']);
    $P_emisor = $doc1->createElement('p', $w_ruc['DirEmisor']);
    $P_claveacceso = $doc1->createElement('p', implode($this->session->get('claveAcceso')));
    if ($w_ruc['ambiente'] == 1) {
        $P_ambiente = $doc1->createElement('p', 'PRUEBAS');
    } else {
        $P_ambiente = $doc1->createElement('p', 'PRODUCCION');
    }
    if ($w_ruc['emision'] == 1) {
        $P_emision = $doc1->createElement('p', 'NORMAL');
    } else {
        $P_emision = $doc1->createElement('p', 'CONTINGENCIA');
    }
    $uno = $doc1->createElement('p', $numerito);
    $dos = $doc1->createElement('p', implode($this->session->get('claveAcceso')));
    $tres = $doc1->createElement('p', $sri['fechaEmision']);
    $cuatro = $doc1->createElement('p', $sri['identificacionComprador']);
    $cinco = $doc1->createElement('p', $sri['razonSocialComprador']);
    $seis = $doc1->createElement('p', $sri['direccionComprador']);
    $totalTotalSinImpto = number_format($sri['totalSinImpuesto'], 2, ',', '.');
    $ocho = $doc1->createElement('p', $totalTotalSinImpto);
    $totalImpto = number_format($sri['totalImpto'], 2, ',', '.');
    $nueve = $doc1->createElement('p', $totalImpto);
    $diez = $doc1->createElement('p', '');
    $totalAPagar = number_format($sri['importeTotal'], 2, ',', '.');
    $once = $doc1->createElement('p', $totalAPagar);

    $E_ruc->appendChild($P_ruc);
    $E_cia->appendChild($P_cia);
    $E_claveacceso->appendChild($P_claveacceso);
    $E_especial->appendChild($P_especial);
    $E_obligado->appendChild($P_obligado);
    $E_ambiente->appendChild($P_ambiente);
    $E_emision->appendChild($P_emision);
    $E_matriz->appendChild($P_matriz);
    $E_emisor->appendChild($P_emisor);
    $E_numeroFactura->appendChild($uno);
    $E_fechaemision->appendChild($tres);
    $E_rucCliente->appendChild($cuatro);
    $E_razon->appendChild($cinco);
    $E_direccion->appendChild($seis);
    $E_totalSinImpto->appendChild($ocho);
    $E_valorImpto12->appendChild($nueve);
//    $E_valorImpto0 -> appendChild($diez);
    $E_apagar->appendChild($once);

    $cuentaAd = @count($doc->getElementsByTagName('infoAdicional'));
    if ($cuentaAd > 0) {
        $i = 0;
        $j = 0;
        $Tag_adi = $doc->getElementsByTagName('infoAdicional');
        foreach ($Tag_adi as $adicional) {
            if ($adicional->hasChildNodes()) {
                foreach ($adicional->childNodes as $child) {
                    $P_adDato = $doc1->createElement('p', $child->nodeValue);
                    switch ($i) {
                        case 0:
                            $E_adDato = $doc1->getElementById('dato1');
                            $E_adDato->appendChild($P_adDato);
                            break;
                        case 1:
                            $E_adDato = $doc1->getElementById('detalle1');
                            $E_adDato->appendChild($P_adDato);
                            break;
                        case 2:
                            $E_adDato = $doc1->getElementById('dato2');
                            $E_adDato->appendChild($P_adDato);
                            break;
                        case 3:
                            $E_adDato = $doc1->getElementById('detalle2');
                            $E_adDato->appendChild($P_adDato);
                            break;
                        case 4:
                            $E_adDato = $doc1->getElementById('dato3');
                            $E_adDato->appendChild($P_adDato);
                            break;
                        case 5:
                            $E_adDato = $doc1->getElementById('detalle3');
                            $E_adDato->appendChild($P_adDato);
                            break;

                        default:
                            break;
                    }
                    $i++;
                }
            }
        }
    }

    $wk_subtotal12 = 0;
    $Tag_product = $doc->getElementsByTagName('detalle');
    $lineas = 0;
    foreach ($Tag_product as $producto) {
        if ($producto->hasChildNodes()) {
            foreach ($producto->childNodes as $child) {
                switch ($child->nodeName) {
                    case 'codigoPrincipal':
                        $wk_codigo = $child->nodeValue;
                        break;
                    case 'descripcion':
//                        $wk_descripcion = substr($child->nodeValue, 0, 42);
                        $wk_descripcion = $child->nodeValue;
                        break;
                    case 'cantidad':
                        $wk_cantidad = $child->nodeValue;
                        $wk_cantidad_out = number_format($wk_cantidad, 2, ',', '.');
                        break;
                    case 'precioUnitario':
                        $wk_pUnitario = $child->nodeValue;
                        $wk_pUnitario_out = number_format($wk_pUnitario, 4, ',', '.');
                        break;
                    case 'descuento':
                        $wk_descuento = $child->nodeValue;
                        break;
                    case 'precioTotalSinImpuesto':
                        $wk_totalSinImpto = $child->nodeValue;
                        $wk_totalSinImpto_out = number_format($wk_totalSinImpto, 2, ',', '.');
                        $wk_subtotal12 = $wk_subtotal12 + $wk_totalSinImpto;
                        break;
                }
            }
        }
        $doce = $doc1->createElement('p', $wk_codigo);
        $E_codigo->appendChild($doce);
        $trece = $doc1->createElement('p', $wk_descripcion);
        $E_descripcion->appendChild($trece);

        $largo = strlen($wk_descripcion);
        if ($largo > 120) {
            $doce = $doc1->createElement('p', ' ');
            $E_codigo->appendChild($doce);
            $catorce = $doc1->createElement('p', ' ');
            $E_cantidad->appendChild($catorce);
            $quince = $doc1->createElement('p', ' ');
            $E_pUnitario->appendChild($quince);
            $diezyseis = $doc1->createElement('p', ' ');
            $E_parcial->appendChild($diezyseis);
        }
        if ($largo > 60) {
            $doce = $doc1->createElement('p', ' ');
            $E_codigo->appendChild($doce);
            $catorce = $doc1->createElement('p', ' ');
            $E_cantidad->appendChild($catorce);
            $quince = $doc1->createElement('p', ' ');
            $E_pUnitario->appendChild($quince);
            $diezyseis = $doc1->createElement('p', ' ');
            $E_parcial->appendChild($diezyseis);
        }
        $catorce = $doc1->createElement('p', $wk_cantidad_out);
        $E_cantidad->appendChild($catorce);
        $quince = $doc1->createElement('p', $wk_pUnitario_out);
        $E_pUnitario->appendChild($quince);
        $diezyseis = $doc1->createElement('p', $wk_totalSinImpto_out);
        $E_parcial->appendChild($diezyseis);
        $lineas++;
    }
    if ($lineas < 11) {
        do {
            $doce = $doc1->createElement('p', ' ');
            $E_codigo->appendChild($doce);
            $trece = $doc1->createElement('p', ' ');
            $E_descripcion->appendChild($trece);
            $catorce = $doc1->createElement('p', ' ');
            $E_cantidad->appendChild($catorce);
            $quince = $doc1->createElement('p', ' ');
            $E_pUnitario->appendChild($quince);
            $diezyseis = $doc1->createElement('p', ' ');
            $E_parcial->appendChild($diezyseis);
            $lineas++;
        } while ($lineas < 11);
    }
    $wk_subtotal12_out = number_format($wk_subtotal12, 2, ',', '.');
    $imponible12 = $doc1->createElement('p', $wk_subtotal12_out);
    $E_imponible12->appendChild($imponible12);
    $doc1->saveHTMLFile($salida);
    $this->tophp->htmltopdf($salida, $pdf);
    }

    function facturaRechazada($node) {
        $mensaje = '';
        try {
            $Tag_autorizacion = $node->getElementsByTagName('autorizacion');
            $Tag_mensajes = $node->getElementsByTagName('mensaje');
            foreach ($Tag_autorizacion as $autoriza) {
                $string_mensaje = NULL;
                if ($autoriza->hasChildNodes()) {
                    foreach ($autoriza->childNodes as $child) {
                        switch ($child->nodeName) {
                            case 'estado':
                                $string_mensaje = "<br><b>" . $child->nodeValue . "</b> el ";
                                break;
                            case 'fechaAutorizacion':
                                $stringDate = strtotime($child->nodeValue);
                                $dateString = date('d/m/Y h:m:s', $stringDate);
                                $string_mensaje .= $dateString . " en ";
                                break;
                            case 'ambiente':
                                $string_mensaje .= $child->nodeValue . " <br> =====>  ";
                                break;
                            case 'mensajes':
                                foreach ($Tag_mensajes as $mensaje) {
                                    if ($mensaje->hasChildNodes()) {
                                        $i = 0;
                                        foreach ($mensaje->childNodes as $datos) {
                                            switch ($datos->nodeName) {

                                                case "identificador":
                                                    $string_mensaje .= $datos->nodeValue . " *** mensaje => ";
                                                    break;
                                                case "mensaje":
                                                    $string_mensaje .= $datos->nodeValue . "  ";
                                                    break;
                                                case "informacionAdicional":
                                                    $string_mensaje .= $datos->nodeValue . "  ";
                                                    break;
                                                case "tipo":
                                                    $string_mensaje .= $datos->nodeValue . "<br>";
                                                    break;
                                            }
                                        }
                                    }
                                }
                                break;

                            default:
                                break;
                        }
                    }
                }
                $mensaje .= $string_mensaje;
            }
        } catch (Exception $exc) {
            $this->flash->error($exc->getTraceAsString());
        }
        $this->flash->success($mensaje);
    }

    function totalFactura($factYcliente) {

        $w_factura = $this->session->get('factura');
        $w_cabecera = $this->session->get('cabecera');
        $w_ruc = $this->session->get('contribuyente');
        $prefijo = 'fact' . $w_cabecera['numeroDocumento'] . '.xml';
        $salida = $_SERVER['DOCUMENT_ROOT'] . '/ComprobantesSRI/facturas/generados/' . $prefijo;
        $firmado = $_SERVER['DOCUMENT_ROOT'] . '/ComprobantesSRI/facturas/firmados/' . $prefijo;
        $pasaXML = $_SERVER['DOCUMENT_ROOT'] . '/ComprobantesSRI/ecuador/generado.xml';
        $regresaXML = $_SERVER['DOCUMENT_ROOT'] . '/ComprobantesSRI/ecuador/firmado.xml';
        $this->session->set('archivo', $salida);
        $this->session->set('firmado', $firmado);
        $cliente = $w_cabecera['CustomerRef_ListID'];
            $pararuc = Customer::findFirstByListID($cliente);
            if (!$pararuc) {
                $this->flash->error("No se pudo encontrar al cliente");
                $this->dispatcher->forward([
                   'controller' => "invoice",
                   'action' => 'index'
                ]);
                return;
            }
        $this->session->set('rucComprador', $pararuc->AccountNumber);
        $this->crea_clave();
        $db_claveAcceso1 = implode($this->session->get('claveAcceso'));
        $this->session->set('claves', array(
           'claveAcceso' => $db_claveAcceso1
        ));

        $stringDate = strtotime($w_cabecera['fechaDocumento']);
        $dateString = date('d/m/Y', $stringDate);
        $out_SinImp = number_format($w_factura['valorSinImpuestos'], '2', '.', '');
        $out_Base = number_format($w_factura['baseImponible'], '2', '.', '');
        $out_ValorImp = number_format($w_factura['valorImpuestos'], '2', '.', '');
        $out_Total = number_format($w_factura['valorTotal'], '2', '.', '');
        $regresaName = $this->claves->limpiaString($w_cabecera['razonSocialComprador']);

        if ($w_ruc['ambiente'] == 1) {
            $regresaName = 'PRUEBAS SERVICIO DE RENTAS INTERNAS';
        }
        $regresaDireccion = $this->claves->limpiaString($w_cabecera['direccionComprador']);

        $stringTributaria = '<infoTributaria><ambiente>' . $w_ruc['ambiente'] . '</ambiente>';
        $stringTributaria .= '<tipoEmision>' . $w_ruc['emision'] . '</tipoEmision><razonSocial>' . $w_ruc['razon'] . '</razonSocial>';
        $stringTributaria .= '<nombreComercial>' . $w_ruc['NombreComercial'] . '</nombreComercial>';
        $stringTributaria .= '<ruc>' . $w_ruc['ruc'] . '</ruc><claveAcceso>' . $db_claveAcceso1 . '</claveAcceso><codDoc>01</codDoc>';
        $stringTributaria .= '<estab>' . $w_ruc['estab'] . '</estab><ptoEmi>' . $w_ruc['punto'] . '</ptoEmi><secuencial>' . $this->session->get('numeroDocumentoLleno') . '</secuencial>';
        $stringTributaria .= '<dirMatriz>' . $w_ruc['DirMatriz'] . '</dirMatriz></infoTributaria>';
        $stringInfo = '<infoFactura><fechaEmision>' . $dateString . '</fechaEmision><dirEstablecimiento>' . $regresaDireccion . '</dirEstablecimiento>';
        $stringInfo .= '<obligadoContabilidad>' . $w_ruc['LlevaContabilidad'] . '</obligadoContabilidad>';
        $stringInfo .= '<tipoIdentificacionComprador>' . $this->session->get('tipoIdentificacion') . '</tipoIdentificacionComprador><razonSocialComprador>' . $regresaName . '</razonSocialComprador>';
        $stringInfo .= '<identificacionComprador>' . $this->session->get('rucLimpio') . '</identificacionComprador><totalSinImpuestos>' . $out_SinImp . '</totalSinImpuestos>';
        $stringInfo .= '<totalDescuento>0.00</totalDescuento><totalConImpuestos><totalImpuesto><codigo>' . $this->session->get('codigoImpuesto');
        $stringInfo .= '</codigo><codigoPorcentaje>' . $this->session->get('codigoTarifaImpuesto') . '</codigoPorcentaje><baseImponible>' . $out_Base . '</baseImponible>';
        $stringInfo .= '<valor>' . $out_ValorImp . '</valor></totalImpuesto></totalConImpuestos><propina>0.00</propina><importeTotal>' . $out_Total;
        $stringInfo .= '</importeTotal><moneda>DOLAR</moneda></infoFactura>';

        $stringFactura = '<factura id="comprobante" version="1.1.0">' . $stringTributaria . $stringInfo . $this->session->get('stringDetalles') . '</detalles></factura>';

        $stringDoc = '<?xml version="1.0" encoding="UTF-8" ?>';
        $stringDoc .= $stringFactura;
        $doc = new DOMDocument();
        $doc->loadXML($stringDoc);
        $doc->saveXML();
        file_put_contents($pasaXML, $stringDoc);
        $this->session->set('documentoXML', $pasaXML);
        $ret = exec('c:\wamp64\www\ComprobantesSRI\ecuador\corre.bat', $out, $return);
        $docpasa = new DOMDocument();
        $docpasa->load($pasaXML);
        $docpasa->save($salida);
        $docregresa = new DOMDocument();
        $docregresa->load($regresaXML);
        $docregresa->save($firmado);
    }

    function crea_clave() {

        $w_factura = $this->session->get('cabecera');
        $w_ruc = $this->session->get('contribuyente');
        $stringDate = strtotime($w_factura['fechaDocumento']);
        $dateString = date('dmY', $stringDate);
        $args['fecha'] = $dateString;
        $args['tipodoc'] = '01';

        $args1['dato'] = $this->session->get('rucComprador');
        $this->session->set('tipoIdentificacion', '04'); // ruc 04 cedula 05 pasaporte 06 consumidor final 07
        $args1['longitud'] = 12;
        if ($this->session->get('rucComprador') == '9999999999999') {
            $this->session->set('tipoIdentificacion', '07');
        } else {
            if (strlen($this->session->get('rucComprador')) == 10) {
                $this->session->set('tipoIdentificacion', '05');
                $args1['longitud'] = 9;
            } elseif (strlen($this->session->get('rucComprador')) < 10 OR strlen($this->session->get('rucComprador')) > 13) {
                $this->session->set('tipoIdentificacion', '08');
                $args1['longitud'] = strlen($this->session->get('rucComprador')) - 1;
            }
        }

        $args1['vector'] = 'D'; //I=Izquierdo D=Derecho;
        $args1['relleno'] = 'N'; //N=Numero A=Alfas;
        $this->session->set('rucLimpio', implode($this->claves->generaString($args1)));

        $args['ruc'] = $w_ruc['ruc']; // llenar a 13 si es cedula

        $args['ambiente'] = $w_ruc['ambiente'];
        $args['establecimiento'] = $w_ruc['estab'];
        $args['punto'] = $w_ruc['punto'];

        $args1['dato'] = $w_factura['numeroDocumento'];
        $args1['longitud'] = 8; // debe ser -1 de la longitud deseada
        $args1['vector'] = 'D'; //I=Izquierdo D=Derecho;
        $args1['relleno'] = 'N'; //N=Numero A=Alfas;
        $this->session->set('numeroDocumentoLleno', implode($this->claves->generaString($args1)));

        $args['factura'] = $this->session->get('numeroDocumentoLleno'); // llenar a 9

        $args1['dato'] = $w_factura['numeroTransaccion'];
        $args1['longitud'] = 7; // debe ser -1 de la longitud deseada
        $args1['vector'] = 'D'; //I=Izquierdo D=Derecho;
        $args1['relleno'] = 'N'; //N=Numero A=Alfas;
        $this->session->set('numeroTransaccionLleno', implode($this->claves->generaString($args1)));

        $args['codigo'] = $this->session->get('numeroTransaccionLleno'); // mismo numero factura? o secuencial
        $args['emision'] = $w_ruc['emision'];
        $claveArray = [];

        $claveArray = $this->claves->generaClave($args);

        $this->session->set('claveAcceso', $claveArray);
        return TRUE;
    }

    function procesaItem($producto) {

        $db_valor = $producto->Amount * $this->session->get('porcentajeImpuesto') / 100;
        $w_factura = $this->session->get('factura');
        $w_string = $this->session->get('stringDetalles');
        $subtotal = $w_factura['baseImponible'];
        $subtotalImpuestos = $w_factura['valorImpuestos'];
        $subtotalSinImpuestos = $w_factura['valorSinImpuestos'];
        $valorTotal = $w_factura['valorTotal'];
        $out_valor = number_format($db_valor, '2', '.', '');
        $out_Amount = number_format($producto->Amount, '2', '.', '');
        $item = $producto->items;
        $regresaDescripcion = $this->claves->limpiaString($item->description);
        $stringItem = '<detalle><codigoPrincipal>' . $producto->ItemRef_ListID . '</codigoPrincipal>';
        $stringItem .= '<descripcion>' . $regresaDescripcion . '</descripcion><cantidad>' . $producto->Quantity . '</cantidad>';
        $stringItem .= '<precioUnitario>' . $producto->Rate . '</precioUnitario><descuento>0</descuento>';
        $stringItem .= '<precioTotalSinImpuesto>' . $out_Amount . '</precioTotalSinImpuesto>';
        $stringItem .= '<impuestos><impuesto><codigo>' . $this->session->get('codigoImpuesto') . '</codigo><codigoPorcentaje>' . $this->session->get('codigoTarifaImpuesto') . '</codigoPorcentaje>';
        $stringItem .= '<tarifa>' . $this->session->get('porcentajeImpuesto') . '</tarifa><baseImponible>' . $out_Amount . '</baseImponible><valor>' . $out_valor . '</valor></impuesto></impuestos></detalle>';

        $this->session->set('factura', array(
           'baseImponible' => $subtotal + $producto->Amount,
           'valorImpuestos' => $subtotalImpuestos + $db_valor,
           'valorSinImpuestos' => $subtotalSinImpuestos + $producto->Amount,
           'valorTotal' => $valorTotal + $producto->Amount + $db_valor
        ));
        $w_string .= $stringItem;
        $this->session->set('stringDetalles', $w_string);
        return true;
    }

    public function editAction($TxnID) {
        if (!$this->request->isPost()) {

            $invoice = Invoice::findFirstByTxnID($TxnID);
            if (!$invoice) {
                $this->flash->error("invoice was not found");
                $this->dispatcher->forward([
                   'controller' => "invoice",
                   'action' => 'index'
                ]);

                return;
            }

            $this->view->TxnID = $invoice->getTxnid();

            $this->tag->setDefault("TxnID", $invoice->getTxnid());
            $this->tag->setDefault("TimeCreated", $invoice->getTimecreated());
            $this->tag->setDefault("TimeModified", $invoice->getTimemodified());
            $this->tag->setDefault("EditSequence", $invoice->getEditsequence());
            $this->tag->setDefault("TxnNumber", $invoice->getTxnnumber());
            $this->tag->setDefault("CustomerRef_ListID", $invoice->getCustomerrefListid());
            $this->tag->setDefault("CustomerRef_FullName", $invoice->getCustomerrefFullname());
            $this->tag->setDefault("ClassRef_ListID", $invoice->getClassrefListid());
            $this->tag->setDefault("ClassRef_FullName", $invoice->getClassrefFullname());
            $this->tag->setDefault("ARAccountRef_ListID", $invoice->getAraccountrefListid());
            $this->tag->setDefault("ARAccountRef_FullName", $invoice->getAraccountrefFullname());
            $this->tag->setDefault("TemplateRef_ListID", $invoice->getTemplaterefListid());
            $this->tag->setDefault("TemplateRef_FullName", $invoice->getTemplaterefFullname());
            $this->tag->setDefault("TxnDate", $invoice->getTxndate());
            $this->tag->setDefault("RefNumber", $invoice->getRefnumber());
            $this->tag->setDefault("BillAddress_Addr1", $invoice->getBilladdressAddr1());
            $this->tag->setDefault("BillAddress_Addr2", $invoice->getBilladdressAddr2());
            $this->tag->setDefault("BillAddress_Addr3", $invoice->getBilladdressAddr3());
            $this->tag->setDefault("BillAddress_Addr4", $invoice->getBilladdressAddr4());
            $this->tag->setDefault("BillAddress_Addr5", $invoice->getBilladdressAddr5());
            $this->tag->setDefault("BillAddress_City", $invoice->getBilladdressCity());
            $this->tag->setDefault("BillAddress_State", $invoice->getBilladdressState());
            $this->tag->setDefault("BillAddress_PostalCode", $invoice->getBilladdressPostalcode());
            $this->tag->setDefault("BillAddress_Country", $invoice->getBilladdressCountry());
            $this->tag->setDefault("BillAddress_Note", $invoice->getBilladdressNote());
            $this->tag->setDefault("ShipAddress_Addr1", $invoice->getShipaddressAddr1());
            $this->tag->setDefault("ShipAddress_Addr2", $invoice->getShipaddressAddr2());
            $this->tag->setDefault("ShipAddress_Addr3", $invoice->getShipaddressAddr3());
            $this->tag->setDefault("ShipAddress_Addr4", $invoice->getShipaddressAddr4());
            $this->tag->setDefault("ShipAddress_Addr5", $invoice->getShipaddressAddr5());
            $this->tag->setDefault("ShipAddress_City", $invoice->getShipaddressCity());
            $this->tag->setDefault("ShipAddress_State", $invoice->getShipaddressState());
            $this->tag->setDefault("ShipAddress_PostalCode", $invoice->getShipaddressPostalcode());
            $this->tag->setDefault("ShipAddress_Country", $invoice->getShipaddressCountry());
            $this->tag->setDefault("ShipAddress_Note", $invoice->getShipaddressNote());
            $this->tag->setDefault("IsPending", $invoice->getIspending());
            $this->tag->setDefault("IsFinanceCharge", $invoice->getIsfinancecharge());
            $this->tag->setDefault("PONumber", $invoice->getPonumber());
            $this->tag->setDefault("TermsRef_ListID", $invoice->getTermsrefListid());
            $this->tag->setDefault("TermsRef_FullName", $invoice->getTermsrefFullname());
            $this->tag->setDefault("DueDate", $invoice->getDuedate());
            $this->tag->setDefault("SalesRepRef_ListID", $invoice->getSalesreprefListid());
            $this->tag->setDefault("SalesRepRef_FullName", $invoice->getSalesreprefFullname());
            $this->tag->setDefault("FOB", $invoice->getFob());
            $this->tag->setDefault("ShipDate", $invoice->getShipdate());
            $this->tag->setDefault("ShipMethodRef_ListID", $invoice->getShipmethodrefListid());
            $this->tag->setDefault("ShipMethodRef_FullName", $invoice->getShipmethodrefFullname());
            $this->tag->setDefault("Subtotal", $invoice->getSubtotal());
            $this->tag->setDefault("ItemSalesTaxRef_ListID", $invoice->getItemsalestaxrefListid());
            $this->tag->setDefault("ItemSalesTaxRef_FullName", $invoice->getItemsalestaxrefFullname());
            $this->tag->setDefault("SalesTaxPercentage", $invoice->getSalestaxpercentage());
            $this->tag->setDefault("SalesTaxTotal", $invoice->getSalestaxtotal());
            $this->tag->setDefault("AppliedAmount", $invoice->getAppliedamount());
            $this->tag->setDefault("BalanceRemaining", $invoice->getBalanceremaining());
            $this->tag->setDefault("CurrencyRef_ListID", $invoice->getCurrencyrefListid());
            $this->tag->setDefault("CurrencyRef_FullName", $invoice->getCurrencyrefFullname());
            $this->tag->setDefault("ExchangeRate", $invoice->getExchangerate());
            $this->tag->setDefault("BalanceRemainingInHomeCurrency", $invoice->getBalanceremaininginhomecurrency());
            $this->tag->setDefault("Memo", $invoice->getMemo());
            $this->tag->setDefault("IsPaID", $invoice->getIspaid());
            $this->tag->setDefault("CustomerMsgRef_ListID", $invoice->getCustomermsgrefListid());
            $this->tag->setDefault("CustomerMsgRef_FullName", $invoice->getCustomermsgrefFullname());
            $this->tag->setDefault("IsToBePrinted", $invoice->getIstobeprinted());
            $this->tag->setDefault("IsToBeEmailed", $invoice->getIstobeemailed());
            $this->tag->setDefault("IsTaxIncluded", $invoice->getIstaxincluded());
            $this->tag->setDefault("CustomerSalesTaxCodeRef_ListID", $invoice->getCustomersalestaxcoderefListid());
            $this->tag->setDefault("CustomerSalesTaxCodeRef_FullName", $invoice->getCustomersalestaxcoderefFullname());
            $this->tag->setDefault("SuggestedDiscountAmount", $invoice->getSuggesteddiscountamount());
            $this->tag->setDefault("SuggestedDiscountDate", $invoice->getSuggesteddiscountdate());
            $this->tag->setDefault("Other", $invoice->getOther());
            $this->tag->setDefault("CustomField1", $invoice->getCustomfield1());
            $this->tag->setDefault("CustomField2", $invoice->getCustomfield2());
            $this->tag->setDefault("CustomField3", $invoice->getCustomfield3());
            $this->tag->setDefault("CustomField4", $invoice->getCustomfield4());
            $this->tag->setDefault("CustomField5", $invoice->getCustomfield5());
            $this->tag->setDefault("CustomField6", $invoice->getCustomfield6());
            $this->tag->setDefault("CustomField7", $invoice->getCustomfield7());
            $this->tag->setDefault("CustomField8", $invoice->getCustomfield8());
            $this->tag->setDefault("CustomField9", $invoice->getCustomfield9());
            $this->tag->setDefault("CustomField10", $invoice->getCustomfield10());
            $this->tag->setDefault("CustomField11", $invoice->getCustomfield11());
            $this->tag->setDefault("CustomField12", $invoice->getCustomfield12());
            $this->tag->setDefault("CustomField13", $invoice->getCustomfield13());
            $this->tag->setDefault("CustomField14", $invoice->getCustomfield14());
            $this->tag->setDefault("CustomField15", $invoice->getCustomfield15());
            $this->tag->setDefault("Status", $invoice->getStatus());
        }
    }

    /**
     * Creates a new invoice
     */
    public function createAction() {
        if (!$this->request->isPost()) {
            $this->dispatcher->forward([
               'controller' => "invoice",
               'action' => 'index'
            ]);

            return;
        }

        $invoice = new Invoice();
        $invoice->setTxnid($this->request->getPost("TxnID"));
        $invoice->setTimecreated($this->request->getPost("TimeCreated"));
        $invoice->setTimemodified($this->request->getPost("TimeModified"));
        $invoice->setEditsequence($this->request->getPost("EditSequence"));
        $invoice->setTxnnumber($this->request->getPost("TxnNumber"));
        $invoice->setCustomerrefListid($this->request->getPost("CustomerRef_ListID"));
        $invoice->setCustomerrefFullname($this->request->getPost("CustomerRef_FullName"));
        $invoice->setClassrefListid($this->request->getPost("ClassRef_ListID"));
        $invoice->setClassrefFullname($this->request->getPost("ClassRef_FullName"));
        $invoice->setAraccountrefListid($this->request->getPost("ARAccountRef_ListID"));
        $invoice->setAraccountrefFullname($this->request->getPost("ARAccountRef_FullName"));
        $invoice->setTemplaterefListid($this->request->getPost("TemplateRef_ListID"));
        $invoice->setTemplaterefFullname($this->request->getPost("TemplateRef_FullName"));
        $invoice->setTxndate($this->request->getPost("TxnDate"));
        $invoice->setRefnumber($this->request->getPost("RefNumber"));
        $invoice->setBilladdressAddr1($this->request->getPost("BillAddress_Addr1"));
        $invoice->setBilladdressAddr2($this->request->getPost("BillAddress_Addr2"));
        $invoice->setBilladdressAddr3($this->request->getPost("BillAddress_Addr3"));
        $invoice->setBilladdressAddr4($this->request->getPost("BillAddress_Addr4"));
        $invoice->setBilladdressAddr5($this->request->getPost("BillAddress_Addr5"));
        $invoice->setBilladdressCity($this->request->getPost("BillAddress_City"));
        $invoice->setBilladdressState($this->request->getPost("BillAddress_State"));
        $invoice->setBilladdressPostalcode($this->request->getPost("BillAddress_PostalCode"));
        $invoice->setBilladdressCountry($this->request->getPost("BillAddress_Country"));
        $invoice->setBilladdressNote($this->request->getPost("BillAddress_Note"));
        $invoice->setShipaddressAddr1($this->request->getPost("ShipAddress_Addr1"));
        $invoice->setShipaddressAddr2($this->request->getPost("ShipAddress_Addr2"));
        $invoice->setShipaddressAddr3($this->request->getPost("ShipAddress_Addr3"));
        $invoice->setShipaddressAddr4($this->request->getPost("ShipAddress_Addr4"));
        $invoice->setShipaddressAddr5($this->request->getPost("ShipAddress_Addr5"));
        $invoice->setShipaddressCity($this->request->getPost("ShipAddress_City"));
        $invoice->setShipaddressState($this->request->getPost("ShipAddress_State"));
        $invoice->setShipaddressPostalcode($this->request->getPost("ShipAddress_PostalCode"));
        $invoice->setShipaddressCountry($this->request->getPost("ShipAddress_Country"));
        $invoice->setShipaddressNote($this->request->getPost("ShipAddress_Note"));
        $invoice->setIspending($this->request->getPost("IsPending"));
        $invoice->setIsfinancecharge($this->request->getPost("IsFinanceCharge"));
        $invoice->setPonumber($this->request->getPost("PONumber"));
        $invoice->setTermsrefListid($this->request->getPost("TermsRef_ListID"));
        $invoice->setTermsrefFullname($this->request->getPost("TermsRef_FullName"));
        $invoice->setDuedate($this->request->getPost("DueDate"));
        $invoice->setSalesreprefListid($this->request->getPost("SalesRepRef_ListID"));
        $invoice->setSalesreprefFullname($this->request->getPost("SalesRepRef_FullName"));
        $invoice->setFob($this->request->getPost("FOB"));
        $invoice->setShipdate($this->request->getPost("ShipDate"));
        $invoice->setShipmethodrefListid($this->request->getPost("ShipMethodRef_ListID"));
        $invoice->setShipmethodrefFullname($this->request->getPost("ShipMethodRef_FullName"));
        $invoice->setSubtotal($this->request->getPost("Subtotal"));
        $invoice->setItemsalestaxrefListid($this->request->getPost("ItemSalesTaxRef_ListID"));
        $invoice->setItemsalestaxrefFullname($this->request->getPost("ItemSalesTaxRef_FullName"));
        $invoice->setSalestaxpercentage($this->request->getPost("SalesTaxPercentage"));
        $invoice->setSalestaxtotal($this->request->getPost("SalesTaxTotal"));
        $invoice->setAppliedamount($this->request->getPost("AppliedAmount"));
        $invoice->setBalanceremaining($this->request->getPost("BalanceRemaining"));
        $invoice->setCurrencyrefListid($this->request->getPost("CurrencyRef_ListID"));
        $invoice->setCurrencyrefFullname($this->request->getPost("CurrencyRef_FullName"));
        $invoice->setExchangerate($this->request->getPost("ExchangeRate"));
        $invoice->setBalanceremaininginhomecurrency($this->request->getPost("BalanceRemainingInHomeCurrency"));
        $invoice->setMemo($this->request->getPost("Memo"));
        $invoice->setIspaid($this->request->getPost("IsPaID"));
        $invoice->setCustomermsgrefListid($this->request->getPost("CustomerMsgRef_ListID"));
        $invoice->setCustomermsgrefFullname($this->request->getPost("CustomerMsgRef_FullName"));
        $invoice->setIstobeprinted($this->request->getPost("IsToBePrinted"));
        $invoice->setIstobeemailed($this->request->getPost("IsToBeEmailed"));
        $invoice->setIstaxincluded($this->request->getPost("IsTaxIncluded"));
        $invoice->setCustomersalestaxcoderefListid($this->request->getPost("CustomerSalesTaxCodeRef_ListID"));
        $invoice->setCustomersalestaxcoderefFullname($this->request->getPost("CustomerSalesTaxCodeRef_FullName"));
        $invoice->setSuggesteddiscountamount($this->request->getPost("SuggestedDiscountAmount"));
        $invoice->setSuggesteddiscountdate($this->request->getPost("SuggestedDiscountDate"));
        $invoice->setOther($this->request->getPost("Other"));
        $invoice->setCustomfield1($this->request->getPost("CustomField1"));
        $invoice->setCustomfield2($this->request->getPost("CustomField2"));
        $invoice->setCustomfield3($this->request->getPost("CustomField3"));
        $invoice->setCustomfield4($this->request->getPost("CustomField4"));
        $invoice->setCustomfield5($this->request->getPost("CustomField5"));
        $invoice->setCustomfield6($this->request->getPost("CustomField6"));
        $invoice->setCustomfield7($this->request->getPost("CustomField7"));
        $invoice->setCustomfield8($this->request->getPost("CustomField8"));
        $invoice->setCustomfield9($this->request->getPost("CustomField9"));
        $invoice->setCustomfield10($this->request->getPost("CustomField10"));
        $invoice->setCustomfield11($this->request->getPost("CustomField11"));
        $invoice->setCustomfield12($this->request->getPost("CustomField12"));
        $invoice->setCustomfield13($this->request->getPost("CustomField13"));
        $invoice->setCustomfield14($this->request->getPost("CustomField14"));
        $invoice->setCustomfield15($this->request->getPost("CustomField15"));
        $invoice->setStatus($this->request->getPost("Status"));


        if (!$invoice->save()) {
            foreach ($invoice->getMessages() as $message) {
                $this->flash->error($message);
            }

            $this->dispatcher->forward([
               'controller' => "invoice",
               'action' => 'new'
            ]);

            return;
        }

        $this->flash->success("invoice was created successfully");

        $this->dispatcher->forward([
           'controller' => "invoice",
           'action' => 'index'
        ]);
    }

    /**
     * Saves a invoice edited
     *
     */
    public function saveAction() {

        if (!$this->request->isPost()) {
            $this->dispatcher->forward([
               'controller' => "invoice",
               'action' => 'index'
            ]);

            return;
        }

        $TxnID = $this->request->getPost("TxnID");
        $invoice = Invoice::findFirstByTxnID($TxnID);

        if (!$invoice) {
            $this->flash->error("invoice does not exist " . $TxnID);

            $this->dispatcher->forward([
               'controller' => "invoice",
               'action' => 'index'
            ]);

            return;
        }

        $invoice->setTxnid($this->request->getPost("TxnID"));
        $invoice->setTimecreated($this->request->getPost("TimeCreated"));
        $invoice->setTimemodified($this->request->getPost("TimeModified"));
        $invoice->setEditsequence($this->request->getPost("EditSequence"));
        $invoice->setTxnnumber($this->request->getPost("TxnNumber"));
        $invoice->setCustomerrefListid($this->request->getPost("CustomerRef_ListID"));
        $invoice->setCustomerrefFullname($this->request->getPost("CustomerRef_FullName"));
        $invoice->setClassrefListid($this->request->getPost("ClassRef_ListID"));
        $invoice->setClassrefFullname($this->request->getPost("ClassRef_FullName"));
        $invoice->setAraccountrefListid($this->request->getPost("ARAccountRef_ListID"));
        $invoice->setAraccountrefFullname($this->request->getPost("ARAccountRef_FullName"));
        $invoice->setTemplaterefListid($this->request->getPost("TemplateRef_ListID"));
        $invoice->setTemplaterefFullname($this->request->getPost("TemplateRef_FullName"));
        $invoice->setTxndate($this->request->getPost("TxnDate"));
        $invoice->setRefnumber($this->request->getPost("RefNumber"));
        $invoice->setBilladdressAddr1($this->request->getPost("BillAddress_Addr1"));
        $invoice->setBilladdressAddr2($this->request->getPost("BillAddress_Addr2"));
        $invoice->setBilladdressAddr3($this->request->getPost("BillAddress_Addr3"));
        $invoice->setBilladdressAddr4($this->request->getPost("BillAddress_Addr4"));
        $invoice->setBilladdressAddr5($this->request->getPost("BillAddress_Addr5"));
        $invoice->setBilladdressCity($this->request->getPost("BillAddress_City"));
        $invoice->setBilladdressState($this->request->getPost("BillAddress_State"));
        $invoice->setBilladdressPostalcode($this->request->getPost("BillAddress_PostalCode"));
        $invoice->setBilladdressCountry($this->request->getPost("BillAddress_Country"));
        $invoice->setBilladdressNote($this->request->getPost("BillAddress_Note"));
        $invoice->setShipaddressAddr1($this->request->getPost("ShipAddress_Addr1"));
        $invoice->setShipaddressAddr2($this->request->getPost("ShipAddress_Addr2"));
        $invoice->setShipaddressAddr3($this->request->getPost("ShipAddress_Addr3"));
        $invoice->setShipaddressAddr4($this->request->getPost("ShipAddress_Addr4"));
        $invoice->setShipaddressAddr5($this->request->getPost("ShipAddress_Addr5"));
        $invoice->setShipaddressCity($this->request->getPost("ShipAddress_City"));
        $invoice->setShipaddressState($this->request->getPost("ShipAddress_State"));
        $invoice->setShipaddressPostalcode($this->request->getPost("ShipAddress_PostalCode"));
        $invoice->setShipaddressCountry($this->request->getPost("ShipAddress_Country"));
        $invoice->setShipaddressNote($this->request->getPost("ShipAddress_Note"));
        $invoice->setIspending($this->request->getPost("IsPending"));
        $invoice->setIsfinancecharge($this->request->getPost("IsFinanceCharge"));
        $invoice->setPonumber($this->request->getPost("PONumber"));
        $invoice->setTermsrefListid($this->request->getPost("TermsRef_ListID"));
        $invoice->setTermsrefFullname($this->request->getPost("TermsRef_FullName"));
        $invoice->setDuedate($this->request->getPost("DueDate"));
        $invoice->setSalesreprefListid($this->request->getPost("SalesRepRef_ListID"));
        $invoice->setSalesreprefFullname($this->request->getPost("SalesRepRef_FullName"));
        $invoice->setFob($this->request->getPost("FOB"));
        $invoice->setShipdate($this->request->getPost("ShipDate"));
        $invoice->setShipmethodrefListid($this->request->getPost("ShipMethodRef_ListID"));
        $invoice->setShipmethodrefFullname($this->request->getPost("ShipMethodRef_FullName"));
        $invoice->setSubtotal($this->request->getPost("Subtotal"));
        $invoice->setItemsalestaxrefListid($this->request->getPost("ItemSalesTaxRef_ListID"));
        $invoice->setItemsalestaxrefFullname($this->request->getPost("ItemSalesTaxRef_FullName"));
        $invoice->setSalestaxpercentage($this->request->getPost("SalesTaxPercentage"));
        $invoice->setSalestaxtotal($this->request->getPost("SalesTaxTotal"));
        $invoice->setAppliedamount($this->request->getPost("AppliedAmount"));
        $invoice->setBalanceremaining($this->request->getPost("BalanceRemaining"));
        $invoice->setCurrencyrefListid($this->request->getPost("CurrencyRef_ListID"));
        $invoice->setCurrencyrefFullname($this->request->getPost("CurrencyRef_FullName"));
        $invoice->setExchangerate($this->request->getPost("ExchangeRate"));
        $invoice->setBalanceremaininginhomecurrency($this->request->getPost("BalanceRemainingInHomeCurrency"));
        $invoice->setMemo($this->request->getPost("Memo"));
        $invoice->setIspaid($this->request->getPost("IsPaID"));
        $invoice->setCustomermsgrefListid($this->request->getPost("CustomerMsgRef_ListID"));
        $invoice->setCustomermsgrefFullname($this->request->getPost("CustomerMsgRef_FullName"));
        $invoice->setIstobeprinted($this->request->getPost("IsToBePrinted"));
        $invoice->setIstobeemailed($this->request->getPost("IsToBeEmailed"));
        $invoice->setIstaxincluded($this->request->getPost("IsTaxIncluded"));
        $invoice->setCustomersalestaxcoderefListid($this->request->getPost("CustomerSalesTaxCodeRef_ListID"));
        $invoice->setCustomersalestaxcoderefFullname($this->request->getPost("CustomerSalesTaxCodeRef_FullName"));
        $invoice->setSuggesteddiscountamount($this->request->getPost("SuggestedDiscountAmount"));
        $invoice->setSuggesteddiscountdate($this->request->getPost("SuggestedDiscountDate"));
        $invoice->setOther($this->request->getPost("Other"));
        $invoice->setCustomfield1($this->request->getPost("CustomField1"));
        $invoice->setCustomfield2($this->request->getPost("CustomField2"));
        $invoice->setCustomfield3($this->request->getPost("CustomField3"));
        $invoice->setCustomfield4($this->request->getPost("CustomField4"));
        $invoice->setCustomfield5($this->request->getPost("CustomField5"));
        $invoice->setCustomfield6($this->request->getPost("CustomField6"));
        $invoice->setCustomfield7($this->request->getPost("CustomField7"));
        $invoice->setCustomfield8($this->request->getPost("CustomField8"));
        $invoice->setCustomfield9($this->request->getPost("CustomField9"));
        $invoice->setCustomfield10($this->request->getPost("CustomField10"));
        $invoice->setCustomfield11($this->request->getPost("CustomField11"));
        $invoice->setCustomfield12($this->request->getPost("CustomField12"));
        $invoice->setCustomfield13($this->request->getPost("CustomField13"));
        $invoice->setCustomfield14($this->request->getPost("CustomField14"));
        $invoice->setCustomfield15($this->request->getPost("CustomField15"));
        $invoice->setStatus($this->request->getPost("Status"));


        if (!$invoice->save()) {

            foreach ($invoice->getMessages() as $message) {
                $this->flash->error($message);
            }

            $this->dispatcher->forward([
               'controller' => "invoice",
               'action' => 'edit',
               'params' => [$invoice->getTxnid()]
            ]);

            return;
        }

        $this->flash->success("invoice was updated successfully");

        $this->dispatcher->forward([
           'controller' => "invoice",
           'action' => 'index'
        ]);
    }

    /**
     * Deletes a invoice
     *
     * @param string $TxnID
     */
    public function deleteAction($TxnID) {
        $invoice = Invoice::findFirstByTxnID($TxnID);
        if (!$invoice) {
            $this->flash->error("invoice was not found");

            $this->dispatcher->forward([
               'controller' => "invoice",
               'action' => 'index'
            ]);

            return;
        }

        if (!$invoice->delete()) {

            foreach ($invoice->getMessages() as $message) {
                $this->flash->error($message);
            }

            $this->dispatcher->forward([
               'controller' => "invoice",
               'action' => 'search'
            ]);

            return;
        }

        $this->flash->success("invoice was deleted successfully");

        $this->dispatcher->forward([
           'controller' => "invoice",
           'action' => "index"
        ]);
    }

}

/**
 * No borrar este es una forma de procesar el RESULTSET de la base de datos que retorna un objeto simple y que
 * hay que trabajarlo como un objeto json
 */
//    public function firmarAction($TxnID) {
//        $parameters = array('conditions' => '[TxnID] = :txnid:', 'bind' => array('txnid' => $TxnID));
//        $factura = Invoice::find($parameters);
//        $Ruc = json_encode($factura);
//        $otro = json_decode($Ruc, true);
//        if ($factura == false) {
//            $this->flash->error("Esta factura no existe");
//            return $this->dispatcher->forward(
//                  [
//                     "controller" => "invoice",
//                     "action" => "index",
//                  ]
//            );
//        }
//
//        foreach ($otro as $key => $value) {
//            $this->_registerInvoice($value);
//        }
//        $saca = $this->session->get('invoice');
//        $this->flash->success('Factura del QB Seleccionada || ' . $_SESSION['invoice']['TxnID']);
////        $_POST['id'] = null; SE PUEDE MODIFICAR EL POST
//
//        $invoice_qb = Invoice::findFirst($parameters);
//        foreach ($invoice_qb->invoicelinedetail as $productos) {
//            echo $productos->TxnLineID;
//        }
//        return $this->dispatcher->forward(
//              [
//                 "controller" => "invoice",
//                 "action" => "search",
//              ]
//        );
//    }
//
//    private function _registerInvoice($arreglo) {
//        $this->session->set('invoice', array(
//           'TxnID' => $arreglo['TxnID,
//           'TimeCreated' => $arreglo['TxnID'],
//           'TimeModified' => $arreglo['TxnID'],
//           'EditSequence' => $arreglo['TxnID'],
//           'TxnNumber' => $arreglo['TxnID'],
//           'CustomerRef_ListID' => $arreglo['TxnID'],
//           'CustomerRef_FullName' => $arreglo['TxnID'],
//           'ClassRef_ListID' => $arreglo['TxnID'],
//           'ClassRef_FullName' => $arreglo['TxnID'],
//           'ARAccountRef_ListID' => $arreglo['TxnID'],
//           'ARAccountRef_FullName' => $arreglo['TxnID'],
//           'TemplateRef_ListID' => $arreglo['TxnID'],
//           'TemplateRef_FullName' => $arreglo['TxnID'],
//           'TxnDate' => $arreglo['TxnID'],
//           'RefNumber' => $arreglo['TxnID'],
//           'BillAddress_Addr1' => $arreglo['TxnID'],
//           'BillAddress_Addr2' => $arreglo['TxnID'],
//           'BillAddress_Addr3' => $arreglo['TxnID'],
//           'BillAddress_Addr4' => $arreglo['TxnID'],
//           'BillAddress_Addr5' => $arreglo['TxnID'],
//           'BillAddress_City' => $arreglo['TxnID'],
//           'BillAddress_State' => $arreglo['TxnID'],
//           'BillAddress_PostalCode' => $arreglo['TxnID'],
//           'BillAddress_Country' => $arreglo['TxnID'],
//           'BillAddress_Note' => $arreglo['TxnID'],
//           'ShipAddress_Addr1' => $arreglo['TxnID'],
//           'ShipAddress_Addr2' => $arreglo['TxnID'],
//           'ShipAddress_City' => $arreglo['TxnID'],
//           'ShipAddress_State' => $arreglo['TxnID'],
//           'ShipAddress_PostalCode' => $arreglo['TxnID'],
//           'ShipAddress_Country' => $arreglo['TxnID'],
//           'ShipAddress_Note' => $arreglo['TxnID'],
//           'IsPending' => $arreglo['TxnID'],
//           'IsFinanceCharge' => $arreglo['TxnID'],
//           'PONumber' => $arreglo['TxnID'],
//           'TermsRef_ListID' => $arreglo['TxnID'],
//           'TermsRef_FullName' => $arreglo['TxnID'],
//           'DueDate' => $arreglo['TxnID'],
//           'SalesRepRef_ListID' => $arreglo['TxnID'],
//           'SalesRepRef_FullName' => $arreglo['TxnID'],
//           'FOB' => $arreglo['TxnID'],
//           'ShipDate' => $arreglo['TxnID'],
//           'ShipMethodRef_ListID' => $arreglo['TxnID'],
//           'ShipMethodRef_FullName' => $arreglo['TxnID'],
//           'Subtotal' => $arreglo['TxnID'],
//           'ItemSalesTaxRef_ListID' => $arreglo['TxnID'],
//           'ItemSalesTaxRef_FullName' => $arreglo['TxnID'],
//           'SalesTaxPercentage' => $arreglo['TxnID'],
//           'SalesTaxTotal' => $arreglo['TxnID'],
//           'AppliedAmount' => $arreglo['TxnID'],
//           'BalanceRemaining' => $arreglo['TxnID']
//        ));
//    }
