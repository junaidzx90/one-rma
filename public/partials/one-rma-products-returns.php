<?php

/**
 * Provide a public-facing view for the plugin
 *
 * This file is used to markup the public-facing aspects of the plugin.
 *
 * @link       https://www.fiverr.com/junaidzx90
 * @since      1.0.0
 *
 * @package    One_Rma
 * @subpackage One_Rma/public/partials
 */
?>

<!-- This file should primarily consist of HTML with a little bit of PHP. -->
<div id="onerma_results__wrap">
    <h2 class="onerma-title">Mis Devoluciones</h2>

    <?php

    try {
        $response = $this->get_products_list();

        if(!is_object($response)){
            echo '<h3 class="alerts alert-danger">Error del servidor, inténtalo de nuevo.</h3>';
            return;
        }
        
        if($response && $response->success == true){
            if(!empty($response->data)){
                $output = '';
                $output .= '<div id="onerma_results">';
                #===============
                //  MAIN TABLE
                #===============
                $output .= '<div class="rmatable">';
                $output .= '<table id="onerma_results_tbl">';

                $data = $response->data;
                $output .= '<thead>';
                $output .= '<tr>';

                foreach($data[0] as $table_head => $value){
                    if($table_head !== 'MENSAJES'){
                        $output .= '<th>'.$table_head.'</th>';
                    }
                }
                $output .= '<th>#</th>';

                $output .= '</tr>';
                $output .= '</thead>';

                $output .= '<tbody>';

                $tbldata = [];
                foreach($data as $values){
                    $rows = [];
                    foreach($values as $key => $value){
                        if(is_array($value)){
                            $productos = [];
                            foreach($value as $items){
                                $itemArr = [];
                                foreach($items as $ikey => $item){
                                    $itemArr[$ikey] = $item;
                                }
                                array_push($productos,$itemArr);
                            }
                            $rows[$key] = $productos;
                        }else{
                            $rows[$key] = $value;
                        }
                    }
                    array_push($tbldata, $rows);
                }

                if(count($tbldata)>0){
                    $mensajes = '';
                    foreach($tbldata as $tblrow){
                        $output .= '<tr>';
                        $output .= '<td colspan="7" class="accordions">';
                        #=================
                        // ACCORDION TABLE
                        #=================
                        $output .= '<table cellpadding="0" cellspacing="0">';
                        #=================
                        // ACCORDION HEAD
                        #=================
                        $output .= '<thead>';
                        $output .= '<tr>';

                        
                        foreach($tblrow as $tblkey => $tblval){
                            if(is_array($tblval) && $tblkey == 'PRODUCTOS'){
                                // PRODUCTS ARRAY
                                $output .= '<td>';
                                $output .= '<ul class="productos_list">';
                                foreach($tblval as $valItem){
                                    $output .= '<li>'.$valItem['NOMBRE'].' (x'.$valItem['CANT'].').</li>';
                                }
                                $output .= '</ul>';
                                $output .= '</td>';
                            }else{
                                if($tblkey !== 'MENSAJES'){
                                    $output .= '<td>'.$tblval.'</td>';
                                }else{
                                    $mensajes = $tblval;
                                }
                            }
                        }
                        $output .= '<td><span class="arrowIcon">◄</span></td>';
                        $output .= '</tr>';
                        $output .= '</thead>';

                        #===============
                        // ACCORDION BODY
                        #===============
                        $output .= '<tbody class="accordion-panel">';
                        $output .= '<tr>';
                        $output .= '<td colspan="7">';

                        #===============
                        // MENSAJES
                        #===============
                        $output .= '<div class="mensajes">';
                        if($mensajes){
                            $output .= '<ul>';
                            foreach($mensajes[0] as $key => $msgs){
                                $output .= '<li>'.$key.': '.$msgs.'</li>';
                            }
                            $output .= '</ul>';
                        }else{
                            $output .= 'No se han encontrado mensajes.';
                        }
                        $output .= '</div>';
                        #===============
                        // MENSAJES
                        #===============
                        
                        $output .= '</td>';
                        $output .= '</tr>'; 
                        $output .= '</tbody>';
                        $output .= '</table>';
                        $output .= '</td>';
                        #===============
                        // Parent table
                        #===============
                        $output .= '</tr>';
                    }
                }

                $output .= '</tbody>';

                $output .= '</table>';
                $output .= '</div>';
                $output .= '</div>';
                echo $output;
            }
        }else{
            echo '<h3 class="alerts alert-danger">'.$response->data.'</h3>';
        }
    } catch (Exception $th) {
        //throw $th;
    }
    ?>
</div>  
   