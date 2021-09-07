const rma = new Vue({
    el: "#return_widget_wrap",
    data: {
        buttonVisible: true,
        isVisible: false,
        loading1: false,
        loading2: false,
        alerts: false,
        alertsText: '',
        alertClass: '',
        selects: [],

        salesProducts: false,
        saleItems: [],
        isDisable: true,
        isTirms: true,
        tirmsModal: false,
    },
    methods: {
        widget_open: function () {
            this.select_product_id();
            this.alerts = false;
        },
        select_product_id: function () {
            let pthis = this;
            jQuery.ajax({
                type: "get",
                url: get_product_detail.ajaxurl,
                data: {
                    action: "get_self_sale_ids",
                    nonce: get_product_detail.nonce,
                    id: md5(get_product_detail.email)
                },
                dataType: "json",
                beforeSend: () => {
                    pthis.loading1 = true;
                },
                success: function (response) {
                    pthis.loading1 = false;
                    if (typeof response === "object" && response !== null) {
                        if (response.success) {
                            let dataLen = response.data.length;
                            for (let i = 0; i < dataLen; i++) {
                                let saleId = response.data[i]['Sale']['id'];
                                let select = response.data[i]['Sale']['select'];
                                let sdata = { saleId, select }
                                pthis.selects.push(sdata);
                                pthis.buttonVisible = false;
                                pthis.isVisible = true;
                            }
                        } else {
                            pthis.alerts = true;
                            pthis.alertClass = 'alert-danger';
                            pthis.alertsText = response.data;
                        }
                    } else {
                        pthis.loading1 = false;
                    }
                }
            });
        },
        select_sale_product: function (e) {
            let pthis = this;
            if (e.target.value !== '-1') {
                let sale_id = e.target.value;
                jQuery.ajax({
                    type: "get",
                    url: get_product_detail.ajaxurl,
                    data: {
                        action: "get_sale_product_detail",
                        nonce: get_product_detail.nonce,
                        sale_id: sale_id
                    },
                    dataType: "json",
                    beforeSend: () => {
                        pthis.loading2 = true;
                        pthis.salesProducts = false;
                        pthis.saleItems = []
                    },
                    success: function (response) {
                        if (typeof response === "object" && response !== null) {

                            pthis.salesProducts = true;
                            pthis.loading2 = false;
                            if (response !== 'null') {
                                
                                jQuery.each(response.data, function (index, value) {
                                    pthis.saleItems.push(value);
                                });

                            }
                        } else {
                            pthis.loading2 = false;
                        }
                    }
                });
            } else {
                pthis.salesProducts = false;
            }
        },
        sale_product_check: function (e) {
            if (e.target.checked == true) {
                this.isDisable = false;
            } else {
                this.isDisable = true;
            }
        },
        acceptsTirms: function (val) {
            if (val == 'accept') {
                this.tirmsModal = false;
                this.isTirms = false;
            }
            if (val == 'cancel') {
                this.tirmsModal = false;
                this.isTirms = true;
                jQuery('#tirmscondition').removeAttr('checked')
            }
        },
        tirmsModalPop: function (e) {
            if (e.target.checked == true) {
                this.tirmsModal = true;
            } else {
                this.tirmsModal = false;
                e.target.checked = false
                this.isTirms = true;
            }
        },
        submitSalesProducts: function () {
            if (this.isDisable == false && this.isTirms == false) {
                let pthis = this;
                let motive = jQuery('#motive').val();
                let mycomment = jQuery('#mycomment').val();

                if (motive !== '-1' && mycomment !== '') {

                    let url = get_product_detail.url;
                    let urlEnd = url.indexOf("?");
                    if (urlEnd == -1) {
                        urlEnd = url.length;
                    }
                
                    if (urlEnd > 0) {
                        if (url[urlEnd - 1] == "/") {
                            url = url.substr(0, urlEnd - 1) + url.substr(urlEnd);
                        }
                    }

                    let key = get_product_detail.key;
                    let restUrl = url + '/api/rmas/' + key;

                    let formData = jQuery("#return_widget_form :not(input[name^=product])").serializeArray();

                    let fdata = {};
                    formData.forEach(function (value, key) {
                        fdata[value.name] = value.value;
                    });

                    let products = [];
                    jQuery('.product_row').each(function () {
                        let product_max = jQuery(this).children('input.product_max').val();
                        let product_id = jQuery(this).children('input.product_id').val();
                        let product_cant = jQuery(this).children('input.product_cant').val();

                        productsArr = {
                            product_max, product_id, product_cant
                        }
                    
                        products.push(productsArr);
                    })
                

                    fdata['id'] = md5(get_product_detail.email);
                    fdata['products'] = products;
                    let data = JSON.stringify(fdata);
                
                    jQuery.ajax({
                        type: "POST",
                        url: restUrl,
                        data: data,
                        dataType: "json",
                        contentType: "application/json",
                        beforeSend: () => {
                            pthis.isDisable = true;
                        },
                        success: function (response) {
                            pthis.isDisable = false;
                            if (typeof response === "object" && response !== null) {
                                var message;
                                if (response.success) {
                                    message = 'Exito! ';
                                    color = 'alert-success';
                                } else {
                                    message = 'Error! ';
                                    color = 'alert-danger';
                                }
                                message += response.data;
                                sessionStorage.setItem('rma-message', message);
                                sessionStorage.setItem('rma-class', color);
                                window.location.href = window.location.href;
                            }
                        },
                    });
                } else {
                    pthis.alerts = true;
                    pthis.alertClass = 'alert-danger';
                    pthis.alertsText = 'Algo anda mal.';
                
                }
            }
        }
    },
    created: function () {
        jQuery('#return_widget_wrap').show()
        if (sessionStorage.getItem('rma-message') !== null && sessionStorage.getItem('rma-class') !== null) {
            this.alerts = true;
            this.alertsText = sessionStorage.getItem('rma-message');
            this.alertClass = sessionStorage.getItem('rma-class');
            sessionStorage.removeItem('rma-message');
            sessionStorage.removeItem('rma-class');
        }
    },
    updated: function () {
        if (this.alerts) {
            setTimeout(() => {
                this.alerts = false;
            }, 3000);
        }
    },
    mounted: function () {
        jQuery('td.accordions').each(function(){
            var thead = jQuery(this).find('thead');
            var tbody = jQuery(this).find('tbody');
            var arrow = jQuery(this).find('span.arrowIcon');

            tbody.hide();
            thead.on("click", function (e) {
                jQuery('td.accordions').find('tbody').each(function () {
                    if (jQuery(this)[0] !== tbody[0]) {
                        jQuery(this).hide();
                        arrow.css('transform', 'rotate( -90deg ) scaleY(1.5)')
                    }
                })

                jQuery('span.arrowIcon').each(function () {
                    if (jQuery(this)[0] !== tbody[0]) {
                        jQuery(this).css('transform', 'rotate( -90deg ) scaleY(1.5)')
                    }
                })
                
                if (tbody.is(':visible')) {
                    arrow.css('transform', 'rotate( -90deg ) scaleY(1.5)')
                } else {
                    arrow.css('transform', 'rotate( 90deg ) scaleY(1.5)')
                }

                tbody.slideToggle('fast');
                
            });
        });
    }
});