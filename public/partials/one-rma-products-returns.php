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
    <h2 class="onerma-title">Mis RMA</h2>

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

                $output .= '<table id="onerma_results_tbl">';

                $data = $response->data;
                $output .= '<thead>';
                $output .= '<tr>';
                
                foreach($data[0] as $table_head => $value){
                    $output .= '<th>'.$table_head.'</th>';
                }

                $output .= '</tr>';
                $output .= '</thead>';

                $output .= '<tbody>';
                
                foreach($data as $values){
                    $output .= '<tr>';
                    foreach($values as $table_data){
                        if(is_array($table_data)){
                            // PRODUCTS ARRAY
                            foreach($table_data as $item){
                                $output .= '<td>'.$item->NOMBRE.' x'.$item->CANT.'</td>';
                            }
                        }else{
                            $output .= '<td>'.$table_data.'</td>';
                        }
                    }
                    $output .= '</tr>';
                }
                
                $output .= '</tbody>';

                $output .= '</table>';
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
   