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
<div id="return_widget_wrap">
    <button v-if="buttonVisible" @click="widget_open" id="openWidget">Nuevo Devoluciones</button>
    <span v-if="alerts" :class="alertClass">{{alertsText}}</span>

        <div v-if="loading1" class="loader-img">
            <img src="<?php echo plugin_dir_url( dirname(__FILE__) ).'img/load.svg' ?>" alt="loader-img">
        </div>

        <form class="form_rma" method="post" id="return_widget_form">

            <div v-if="isVisible" id="return_widget_s">
                <h3 class="onerma-title">Nuevo Devoluciones</h3>

                <label for="sale_ids">Seleccione pedido:</label>
                <select @change="select_sale_product(event)" name="sale_id" id="sale_ids">
                    <option value="-1">Seleccione un pedido</option>
                    <option v-for="select in selects" :value="select.saleId">{{select.select}}</option>
                </select>
            </div>

            <div v-if="loading2" class="loader-img">
                <img src="<?php echo plugin_dir_url( dirname(__FILE__) ).'img/load.svg' ?>" alt="loader-img">
            </div>

            <?php
            $notice_color = get_option( 'onerma_notice_color' )?get_option( 'onerma_notice_color' ):'#ff8400';
            ?>

            <div style="color: <?php echo $notice_color ?>" v-if="returnable_notice" class="returnable_alerts">
                <p><?php _e(get_option( 'onerma_available_return_notice' )) ?></p>
            </div>
        
            <div v-if="salesProducts" id="sales_products">
                <div id="rma_form_values">
                    <div class="rmatable">
                        <table id="sales_products_table">
                            <thead>
                                <tr>
                                    <th>SKU</th>
                                    <th>NOMBRE</th>
                                    <th>CANTIDAD</th>
                                    <th>DEVOLVER</th>
                                    <th>#</th>
                                </tr>
                            </thead>
                            <tbody id="sales_products_body">
                                <tr v-for="sale in saleItems">
                                    <td>{{sale.sku}}</td>
                                    <td>{{sale.name}}</td>
                                    <td>{{sale.cant}}</td>
                                    <td class="product_row">
                                        <input type="hidden" class="product_max" name="product_max" :value="sale.cant">
                                        <input type="hidden" class="product_id" name="product_id" :value="sale.id">
                                        <input type="number" :value="sale.cant" class="product_cant" name="product_cant" :min="sale.cant" :max="sale.cant" :disabled="isDisable">
                                    </td>
                                    <td>
                                        <input @change="sale_product_check(event)" type="checkbox" class="sale_product_check">
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <label :class="(isDisable == true)?'disable':''" for="motive">Motivo:</label>
                    <select :disabled="isDisable" name="motive" id="motive">
                        <option value="-1">Select Motivo</option>
                        <?php
                        $motives = $this->get_sales_product_option();
                        foreach ($motives as $key => $motive) {  
                            echo '<option value="'.$motive.'">'.$motive.'</option>';
                        }
                        ?>

                    </select>

                    <label :class="(isDisable == true)?'disable':''" for="mycomment">Detalles:</label>
                    <textarea :disabled="isDisable" name="comment" id="mycomment"></textarea>
                    
                    <div class="rma_buttons">
                        <label :class="(isDisable == true)?'disable':''" for="tirmscondition">
                            <input @change="tirmsModalPop(event)" :disabled="isDisable" type="checkbox" name="" id="tirmscondition">
                            Terminos y condiciones
                        </label>

                        <button @click="submitSalesProducts()" :disabled="isTirms" type="button" id="submit_rma">Guardar</button>
                    </div>
                </div>
            </div>
        </form>

        <div v-if="tirmsModal" id="tirmsModal">
            <div class="tirms_contents">
                <h3 class="tirms_title">T??rminos y condiciones</h3>
                <div class="tiems_body">
                    <?php echo wpautop( get_option( 'onerma_tirmsconditions' ) ) ?>
                </div>
                <div class="tirms_footer">
                    <button @click="acceptsTirms('cancel')" class="tirms_cancel">Cancelar</button>
                    <button @click="acceptsTirms('accept')" class="tirms_accepts">Estoy de Acuerdo</button>
                </div>
            </div>
        </div>
</div>