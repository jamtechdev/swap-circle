<!-- SCRIPTS -->
<script src="{{ asset('users/assets/js/bootstrap.bundle.js') }}"></script>
<script src="{{ asset('users/assets/js/jquery.min.js') }}"></script>
<script src="{{ asset('users/assets/js/jquery.validate.min.js') }}"></script>
<script src="{{ asset('users/assets/js/jquery.ui.min.js') }}"></script>
<script src="{{ asset('users/assets/js/jquery.additional.methods.js') }}"></script>
<script src="{{ asset('users/assets/js/clipboard.min.js') }}"></script>
<script src="{{ asset('users/assets/plugin/splide/splide.min.js') }}"></script>
<script src="{{ asset('users/assets/js/file-upload/index.js') }}"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script>
    var el = document.getElementById("dashboard-wrapper")
    var toggleButton = document.getElementById("menu-toggle")

    toggleButton.onclick = function(){
        el.classList.toggle("toggled")
    }
</script>
<script>
$(document).ready(function () {
    $('select.form-select').select2({
        width: '100%',
        placeholder: '--Select--',
        minimumResultsForSearch: Infinity // hide search box
    });
});

</script>

<script>
function disableBuyNow(btn) {
    btn.prop('disabled', true);
    btn.data('original-text', btn.text());
    btn.html('Processing...');
}

function enableBuyNow(btn) {
    btn.prop('disabled', false);
    btn.html(btn.data('original-text'));
}
</script>


<script>
    document.addEventListener( 'DOMContentLoaded', function() {
        var slider1 = document.querySelector('#slider-1');
        if (slider1) {
            var splide1 = new Splide('#slider-1' , {
                arrows:true,
                pagination:false,
                perPage:5,
                gap:"20px",
                breakpoints: {
                    992: {
                            perPage: 3,
                        },    
                    640: {
                            perPage: 2,
                        },
                }
            });
            splide1.mount();
        }

        var slider2 = document.querySelector('#slider-2');
        if (slider2) {
            var splide2 = new Splide('#slider-2' , {
                arrows:true,
                pagination:false,
                perPage:3,
                gap:"20px",
                breakpoints:{
                    992: {
                            perPage: 2,
                        },    
                    640: {
                            perPage: 1,
                        },
                }
            });
            splide2.mount();
        }
    });
</script>
<script>
    $(".edit-image-box").click(function(event) {
        var previewImg = $(this).children("img");

        $(this)
            .siblings()
            .children("input")
            .trigger("click");

        $(this)
            .siblings()
            .children("input")
            .change(function() {
                var reader = new FileReader();

                reader.onload = function(e) {
                    var urll = e.target.result;
                    $(previewImg).attr("src", urll);
                    $(previewImg).attr("class", "image");
                    previewImg.parent().css("background", "transparent");
                    previewImg.show();
                    previewImg.siblings("p").hide();
                };
                reader.readAsDataURL(this.files[0]);
            });
    });
</script>
<!-- SCRIPTS -->

<!-- TOASTERS -->
<link href="{{ asset('toasters/toastr.min.css') }}" rel="stylesheet" type="text/css" />   
<script src="{{ asset('toasters/toastr.min.js') }}" type="text/javascript"></script>
<script>
    toastr.options = {
        "closeButton": true,
        "debug": false,
        "positionClass": "toast-top-right",
        "onclick": null,
        "showDuration": "1000",
        "hideDuration": "1000",
        "timeOut": "5000",
        "extendedTimeOut": "1000",
        "showEasing": "swing",
        "hideEasing": "linear",
        "showMethod": "fadeIn",
        "hideMethod": "fadeOut"
    }
    //Command: toastr['success']("hello");

    <?php if(Session::has('success')){ ?> Command: toastr['success']("<?php echo Session('success'); ?>"); <?php } ?>
    <?php if(Session::has('error')){ ?> Command: toastr['error']("<?php echo Session('error'); ?>"); <?php } ?>
    <?php if(Session::has('warning')){ ?> Command: toastr['warning']("<?php echo Session('warning'); ?>"); <?php } ?>
    <?php if(Session::has('info')){ ?> Command: toastr['info']("<?php echo Session('info'); ?>"); <?php } ?>
</script>
<!-- TOASTERS -->
 
<style>
    .cursor_pointer {
        cursor: pointer;
    }

     .card .card-body .card-text{
        overflow: hidden;
        display: -webkit-box;
        -webkit-box-orient: vertical;
        -webkit-line-clamp: 5;
    }
</style>

<script>
    var users_customers_id      = "<?php echo session()->get('id'); ?>";
    var users_customers_email   = "<?php echo session()->get('email'); ?>";

    $(document).ready(function() {  
        get_profile_pic(); 
        get_unread_notifications();
        get_unread_messages();
        get_base_currency_params();
    });

    /* --------------- GET BASE CURRENCY PARAMS --------------- */
    function get_base_currency_params() {
        /* AJAX API CALL */
        var settings = {
            "url": "{{ rtrim(config('app.api_url'), '/') }}/system_settings/",
            "method": "GET",
            "timeout": 0,
        };
        $.ajax(settings).done(function (response) {
            if (response.status == 'success') {
                var result                 = Object.values(response.data).filter(obj => obj.type === "system_currencies_id");
                var system_currencies_id   = result.map((item) => item.description);
                
                /* AJAX API CALL */
                var settings = {
                    "url": "{{ rtrim(config('app.api_url'), '/') }}/get_currencies_by_id",
                    "method": "POST",
                    "timeout": 0,
                    "headers": {
                        "Content-Type": "application/json"
                    },
                    "data": JSON.stringify({
                        "system_currencies_id": system_currencies_id,
                    }),
                };
                $.ajax(settings).done(function (response) {
                    if (response.status == 'success') {
                        var currency = response.data;

                        $.each(currency, function (key, item) {
                            // base currency id
                            $('#system_currencies_id').val(item.system_currencies_id);

                            // base currency name
                            $('#system_currencies_name').val(item.name);

                            // base currency code
                            $('#system_currencies_code').val(item.code);

                            // base currency symbol
                            $('#system_currencies_symbol').val(item.symbol);
                        });
                    }
                });
                /* AJAX API CALL */
            }
        });
        /* AJAX API CALL */
    }
    /* --------------- GET BASE CURRENCY PARAMS --------------- */

    /* --------------- GET ALL WALLETS --------------- */
    function get_all_wallets() {
        /* AJAX API CALL */
        var settings = {
            "url": "{{ rtrim(config('app.api_url'), '/') }}/get_wallet",
            "method": "POST",
            "timeout": 0,
            "headers": {
                "Content-Type": "application/json"
            },
            "data": JSON.stringify({
                "users_customers_id": users_customers_id,
            }),
        };
        $.ajax(settings).done(function (response) {
           if (response.status == 'success') {
               $('#all_wallets').empty();
                var wallets = response.data;

                $.each(wallets, function (key, item) {
                    var flag_image = "{{ url('public') }}" + item.currency.country.image;
                    $('#all_wallets').append('\
                        <li class="col-lg-3 text-center wallet-item">\
                            <img src="'+ flag_image +'" class="img-fluid me-2" alt="image">\
                            <span>'+ item.currency.code +'</span>\
                            <h5 class="mb-0 text-black fw-bolder mt-1">'+ item.currency.symbol + item.wallet_amount +'</h5>\
                        </li>\
                    ');
                });
            }
        });
        /* AJAX API CALL */
    }
    /* --------------- GET ALL WALLETS --------------- */

    /* --------------- GET MY WALLETS --------------- */
    function get_my_wallets() {
        /* AJAX API CALL */
        var settings = {
            "url": "{{ rtrim(config('app.api_url'), '/') }}/get_wallet",
            "method": "POST",
            "timeout": 0,
            "headers": {
                "Content-Type": "application/json"
            },
            "data": JSON.stringify({
                "users_customers_id": users_customers_id,
            }),
        };
        $.ajax(settings).done(function (response) {
           if (response.status == 'success') {
               $('#wallets').empty();
                var wallets = response.data.slice(0, 7);

                $.each(wallets, function (key, item) {
                    var flag_image = "{{ url('public') }}" + item.currency.country.image;
                    $('#wallets').append('\
                        <li class="wallet-item">\
                            <img src="'+ flag_image +'" class="img-fluid me-2" alt="image">\
                            <span>'+ item.currency.code +'</span>\
                            <h5 class="mb-0 text-black fw-bolder mt-1">'+ item.currency.symbol + item.wallet_amount +'</h5>\
                        </li>\
                    ');
                });
           }
        });
        /* AJAX API CALL */
    }
    /* --------------- GET MY WALLETS --------------- */

    /* --------------- GET TRANSACTIONS --------------- */
    function get_transactions() {
        /* AJAX API CALL */
        var settings = {
            "url": "{{ rtrim(config('app.api_url'), '/') }}/all_transactions",
            "method": "POST",
            "timeout": 0,
            "headers": {
                "Content-Type": "application/json"
            },
            "data": JSON.stringify({
                "users_customers_id": users_customers_id,
            }),
        };
        $.ajax(settings).done(function (response) {
            if (response.status == 'success') {
                $('#transactions').empty();
                var transactions = response.data;

                $.each(response.data, function (key, item) {
                    var action_image = '';
                    if (item.to_users_customers) {
                        action_image += '<img src="{{ asset('users/assets/images/icons/send.png') }}" alt="" srcset="">';
                    } else {
                        action_image += '<img src="{{ asset('users/assets/images/icons/receive.png') }}" alt="" srcset="">';
                    }

                    var name = '';
                    if (item.to_users_customers) {
                        name += 'To';
                        name += ' ';
                        name += item.to_users_customers.first_name;
                        name += ' ';
                        name += item.to_users_customers.last_name;
                    } else {
                        name += 'From';
                        name += ' ';
                        name += item.from_users_customers.first_name;
                        name += ' ';
                        name += item.from_users_customers.last_name;
                    }
                     
                    var amount = '';
                    if (item.to_users_customers) { 
                        amount += '<span class="text-danger me-3">';
                        amount += '-';
                        amount += '(';
                        amount += item.from_system_currencies;
                        amount += item.from_amount;
                        amount += ')';
                        amount += '</span>';
                    } else {
                        amount += '<span class="text-primary me-3">';
                        amount += '(';
                        amount += item.to_system_currencies;
                        amount += item.to_amount;
                        amount += ')';
                        amount += '</span>';
                    }

                    $('#transactions').append('\
                        <div class="col-sm-6">\
                            <div class="card border-0 mb-3">\
                                <div class="card-body p-2 d-flex justify-content-between align-items-center">\
                                    <div class="d-flex align-items-center">\
                                        <div class="wallet-icon me-3 bg-green">'+ action_image +'</div>\
                                        <div>\
                                            <p class="mb-0 fw-bolder">'+ name +'</p>\
                                        </div>\
                                    </div>\
                                    <small class="text-center">\
                                        <span class="text-success me-3">\
                                            <span id="all_transaction_id_'+ item.users_customers_txns_id +'"></span>' + item.base_amount +'\
                                        </span>\
                                        <br/>'+ amount +'\
                                    </small>\
                                </div>\
                            </div>\
                        </div>\
                    ');
                });
            } else {
                $('#transactions').empty();
                $('#transactions').append('\
                    <div class="row justify-content-center">\
                        <div class="col-sm-6">\
                            <div class="card border-0 mb-3">\
                                <div class="card-body p-2 py-3 d-flex justify-content-center align-items-center">\
                                    <h5>No Transactions Yet.</h5>\
                                </div>\
                            </div>\
                        </div>\
                    </div>\
                ');
            }
        });
        /* AJAX API CALL */
    }
    /* --------------- GET TRANSACTIONS --------------- */

    /* --------------- GET HOT SWAP OFFERS --------------- */
    function get_hot_swap_offers() {
         /* AJAX API CALL */
         var settings = {
            "url": "{{ rtrim(config('app.api_url'), '/') }}/all_swap_offers",
            "method": "POST",
            "timeout": 0,
            "headers": {
                "Content-Type": "application/json"
            },
            "data": JSON.stringify({
                "users_customers_id": users_customers_id,
            }),
        };
        $.ajax(settings).done(function (response) {
            if (response.status == 'success') {
                $('#hot_swap_offers').empty();
                var offers = response.data;

                $.each(offers, function (key, item) {
                    var liked_offer = '';
                    if (item.liked == "Yes") {
                        liked_offer = '<img src="{{ asset('users/assets/images/icons/heart-fav.png') }}" class="ms-3 img-fluid cursor_pointer" alt="" onclick="remove_from_favorite_offers('+ item.swap_offers_id +'); event.stopPropagation();">';
                    } else{
                        liked_offer = '<img src="{{ asset('users/assets/images/icons/heart.png') }}" class="ms-3 img-fluid cursor_pointer" alt="" onclick="add_to_favorite_offers('+ item.swap_offers_id +'); event.stopPropagation();" id="favorite_offer_'+ item.swap_offers_id +'">';
                    }

                    var from_currency_country_flag   = "{{ url('/public') }}" + item.from_currency.country.image;
                    var to_currency_country_flag     = "{{ url('/public') }}" + item.to_currency.country.image;
                    
                    $('#hot_swap_offers').append('\
                        <div class="col-md-6 col-xl-4">\
                            <div class="card border-0 mb-3">\
                                <div class="card-body" onclick="display_send_offer_modal('+ item.swap_offers_id +');" style="cursor:pointer;">\
                                    <div class="d-flex align-items-center justify-content-between border-bottom pb-3 mb-3 flex-wrap gap-1">\
                                        <div class="d-flex align-items-center">\
                                            <p class="mb-0">'+ item.from_currency.symbol +'1</p>\
                                            <span class="plane-icon bg-primary mx-2">\
                                                <img src="{{ asset('users/assets/images/icons/mini-icon/Repeat.png') }}" class="img-fluid" alt="">\
                                            </span>\
                                            <p class="mb-0">'+ item.to_currency.symbol + item.exchange_rate +'</p>\
                                        </div>\
                                        <div class="d-flex align-items-center">\
                                            <img src="{{ asset('users/assets/images/icons/clock.png') }}" class="img-fluid" alt="">\
                                            <small class="ms-1 mb-0 text-primary">'+ item.time_ago +'</small>'+ liked_offer +'\
                                        </div>\
                                    </div>\
                                    <div>\
                                        <div class="offers-card-body d-flex align-items-center justify-content-between flex-wrap gap-1">\
                                            <div class="mb-0">\
                                                <small class="text-primary mb-2">You Pay</small>\
                                                <p class="my-1"><span class="text-success">'+ item.from_currency.symbol +'</span>'+ item.from_amount +'</p>\
                                            </div>\
                                            <div class="mb-0 d-flex align-items-center">\
                                                <img src="'+ from_currency_country_flag +'" class="img-fluid" alt="">\
                                                <small class="mx-2">'+ item.from_currency.country.code + '/' + item.to_currency.country.code +'</small>\
                                                <img src="'+ to_currency_country_flag +'" class="img-fluid" alt="">\
                                            </div>\
                                            <div class="mb-0 text-end">\
                                                <small class="text-primary mb-2">You Pay</small>\
                                                <p class="my-1"><span class="text-success">'+ item.to_currency.symbol +'</span>'+ item.to_amount +'</p>\
                                            </div>\
                                        </div>\
                                    </div>\
                                </div>\
                            </div>\
                        </div>\
                    ');
                });
            } else {
                $('#hot_swap_offers').empty();
            }
        })
        /* AJAX API CALL */
    }
    /* --------------- GET HOT SWAP OFFERS --------------- */

    /* --------------- GET CREATE WALLET CURRENCIES --------------- */
    function get_create_wallet_currencies() {
        $('#mdl_create_wallet').modal('show');

        /* AJAX API CALL */
        var settings = {
            "url": "{{ rtrim(config('app.api_url'), '/') }}/all_currencies",
            "method": "GET",
            "timeout": 0,
        };
        $.ajax(settings).done(function (response) {
            if (response.status == 'success') {
                $('#cw_base_currency').empty();
                var currencies = response.data ;

                $('#cw_base_currency').prepend('\
                    <option value="" disabled selected hidden>Select Currency</option>\
                ');
                $.each(response.data, function (key, item) { 
                    $('#cw_base_currency').append('\
                        <option value="'+ item.system_currencies_id +'">\
                            '+ item.country.name +' ('+ item.code +')\
                        </option>\
                    ');
                });
            }
        });
        /* AJAX API CALL */
    }
    /* --------------- GET CREATE WALLET CURRENCIES --------------- */
    
    /* --------------- VALIDATE CREATE WALLET --------------- */
    $('#frm_create_wallet').validate({
        rules: {
            cw_base_currency: {
                required: true
            },
        },
        messages: {
            cw_base_currency: {
                required: 'Please select base currency.'
            },
        },
        errorPlacement: function (error, element) {
            if (element.attr('name') == 'cw_base_currency') {
                $('#error_cw_base_currency').html(error);
            }
        }
    });
    /* --------------- VALIDATE CREATE WALLET --------------- */

    /* --------------- SUBMIT CREATE WALLET --------------- */
    $('#frm_create_wallet').on('submit', function (event) {
        event.preventDefault();
        if ($('#frm_create_wallet').valid()) {         
            var system_currencies_id = $('#cw_base_currency').val();  
            
            /* AJAX API CALL */
            var settings = {
                "url": "{{ rtrim(config('app.api_url'), '/') }}/create_wallet",
                "method": "POST",
                "timeout": 0,
                "headers": {
                    "Content-Type": "application/json"
                },
                "data": JSON.stringify({
                    "users_customers_id": users_customers_id,
                    "system_currencies_id": system_currencies_id,
                }),
            };
            $.ajax(settings).done(function (response) {
                if (response.status == 'success') {
                    get_my_wallets();
                    $('#mdl_create_wallet').modal('hide');
                    $('#frm_create_wallet')[0].reset();
                    toastr.success('Wallet is created successfully.');
                } else {
                    toastr.error(response.message);
                }
            });
            /* AJAX API CALL */
        }
    });
    /* --------------- SUBMIT CREATE WALLET --------------- */

    /* --------------- GET SEND CURRENCY PARAMS --------------- */
    function get_send_currency_params() {
        $('#mdl_send_currency').modal('show');

        // base currency
        var base_currency =  $('#system_currencies_symbol').val() 
                            + ' - ' + $('#system_currencies_code').val();
        $('#sc_base_currency').text(base_currency);

        get_sc_from_currency();
        get_sc_exchange_currency();
    }
    /* --------------- GET SEND CURRENCY PARAMS --------------- */

    /* --------------- GET SC FROM CURRENCY --------------- */
    function get_sc_from_currency() {
        /* AJAX API CALL */
        var settings = {
            "url": "{{ rtrim(config('app.api_url'), '/') }}/get_wallet",
            "method": "POST",
            "timeout": 0,
            "headers": {
                "Content-Type": "application/json"
            },
            "data": JSON.stringify({
                "users_customers_id": users_customers_id,
            }),
        };
        $.ajax(settings).done(function (response) { 
            if (response.status == 'success') {
                $('#sc_from_currency').empty();
                var wallets = response.data;

                $('#sc_from_currency').prepend('\
                    <option value="" disabled selected hidden>Select Currency</option>\
                ');
                $.each(wallets, function (key, item) {
                    $('#sc_from_currency').append('\
                        <option symbol="'+ item.currency.symbol +'" value="'+ item.system_currencies_id +'">\
                            '+ item.currency.symbol + ' - ' + item.currency.country.name +' ('+ item.currency.country.code +')\
                        </option>\
                    ');
                });
            }
        });    
        /* AJAX API CALL */
    }
    /* --------------- GET SC FROM CURRENCY --------------- */

    /* --------------- GET SC EXCHANGE CURRENCY --------------- */
    function get_sc_exchange_currency() {
        /* AJAX API CALL */
        var settings = {
            "url": "{{ rtrim(config('app.api_url'), '/') }}/all_currencies",
            "method": "GET",
            "timeout": 0,
        };
        $.ajax(settings).done(function (response) { 
            if (response.status == 'success') {
                $('#sc_exchange_currency').empty();
                var currencies = response.data;

                $('#sc_exchange_currency').prepend('\
                    <option value="" disabled selected hidden>Select Currency</option>\
                ');
                $.each(currencies, function (key, item) {
                    $('#sc_exchange_currency').append('\
                        <option symbol="'+ item.symbol +'" value="'+ item.system_currencies_id +'">\
                            '+ item.symbol + ' - ' + item.country.name +' ('+ item.country.code +')\
                        </option>\
                    ');
                });
            }
        });
        /* AJAX API CALL */
    }
    /* --------------- GET SC EXCHANGE CURRENCY --------------- */

    /* --------------- GET EXCHANGE RATE & AMOUNT --------------- */
    $('#sc_from_currency, #sc_total_amount, #sc_exchange_currency').on('change', function() {
        var sndr_currency_id       = $('#sc_from_currency').val();
        var from_amount            = $('#sc_total_amount').val();
        var rcvr_currency_id       = $('#sc_exchange_currency').val();
        var rcvr_currency_symbol   = $('#sc_exchange_currency option:selected').attr('symbol'); 

        if (sndr_currency_id !== null && from_amount !== null && rcvr_currency_id !== null) {
            /* AJAX API CALL */
            var settings = {
                "url": "{{ rtrim(config('app.api_url'), '/') }}/currency_converter",
                "method": "POST",
                "timeout": 0,
                "headers": {
                    "Content-Type": "application/json"
                },
                "data": JSON.stringify({
                    "sender_currency_id": sndr_currency_id,
                    "from_amount": from_amount,
                    "receiver_currency_id": rcvr_currency_id,
                }),
            };
            $.ajax(settings).done(function (response) { 
                if (response.status == 'success') {
                    // exchange rate
                    $('#sc_exchange_rate').empty();
                    var converted_rate   = response.data.converted_rate;
                    var rate             = converted_rate.toFixed(2).split('.');
                    $('#sc_exchange_rate').append(rcvr_currency_symbol + ' ' + rate[0] +'.<span class="fs-6 text-primary">'+ rate[1] +'</span>');

                    // exchange amount
                    $('#sc_exchange_amount').empty();
                    var converted_amount   = response.data.converted_amount;
                    var amount             = converted_amount.toFixed(2).split('.');
                    $('#sc_exchange_amount').append(rcvr_currency_symbol + ' ' + amount[0] +'.<span class="fs-6 text-primary">'+ amount[1] +'</span>');
                }
            });
            /* AJAX API CALL */
        }  
    });
    /* --------------- GET EXCHANGE RATE & AMOUNT --------------- */

    /* --------------- GET SUGGESTED USERS --------------- */
    $('#sc_email').on('keyup', function() {
        var email = $(this).val();

        if (email !== '') {
            /* AJAX API CALL */
            var settings = {
                "url": "{{ rtrim(config('app.api_url'), '/') }}/all_users_suggested",
                "method": "POST",
                "timeout": 0,
                "headers": {
                    "Content-Type": "application/json"
                },
                "data": JSON.stringify({
                    "email": email,
                }),
            };
            $.ajax(settings).done(function (response) {
                $('#suggested_users').empty();
                if (response.status == 'success') {
                    var users = response.data.slice(-5);

                    $.each(users, function (key, item) {
                        $('#suggested_users').fadeIn('fast').append('\
                            <ul id="myList">\
                                <li value="'+ item.users_customers_id +'" style="cursor:pointer;">'+ item.email +'</li>\
                            </ul>\
                        ');
                    }); 
                } else {
                    $('#suggested_users').fadeIn('fast').append('\
                        <ul id="myList">\
                            <li value="">No results found.</li>\
                        </ul>\
                    ');
                }
            });
           /* AJAX API CALL */
        } else {
            $('#suggested_users').fadeOut();
        }
    });
    /* --------------- GET SUGGESTED USERS --------------- */

    /* --------------- SELECT SUGGESTED USER --------------- */
    $(document).on('click', '#suggested_users li', function() {
        // alert('Are you sure to select this email?');
        $('#sc_email').val($(this).text());
        $('#suggested_users_id').val($(this).val());
        $('#suggested_users').fadeOut(); 
    });
    /* --------------- SELECT SUGGESTED USER --------------- */

    /* --------------- VALIDATE SEND CURRENCY --------------- */
    $('#frm_send_currency').validate({
        rules: {
            sc_from_currency: {
                required: true
            },
            sc_total_amount: {
                required: true,
                number: true
            },
            sc_exchange_currency: {
                required: true
            },
            sc_email: {
                required: true
            },
            sc_country: {
                required: true
            },
        },
        messages: {
            sc_from_currency: {
                required: 'This field is required.'
            },
            sc_total_amount: {
                required: 'This field is required.',
                number: 'Please enter a valid amount.'
            },
            sc_exchange_currency: {
                required: 'This field is required.'
            },
            sc_email: {
                required: 'This field is required.'
            },
            sc_country: {
                required: 'This field is required.'
            },
        },
        errorPlacement: function (error, element) {
            if (element.attr('name') == 'sc_from_currency') {
                $('#error_sc_from_currency').html(error);
            } else if (element.attr('name') == 'sc_total_amount') {
                $('#error_sc_total_amount').html(error);
            } else if (element.attr('name') == 'sc_exchange_currency') {
                $('#error_sc_exchange_currency').html(error);
            } else if (element.attr('name') == 'sc_email') {
                $('#error_sc_email').html(error);
            } else if (element.attr('name') == 'sc_country') {
                $('#error_sc_country').html(error);
            }
        }
    });
    /* --------------- VALIDATE SEND CURRENCY --------------- */

    /* --------------- SUBMIT SEND CURRENCY --------------- */
    $('#frm_send_currency').on('submit', function (event) {
        event.preventDefault();
        if ($('#frm_send_currency').valid()) {
            var suggested_users_id = $('#suggested_users_id').val();               
            
            /* AJAX API CALL */
            var settings = {
                "url": "{{ rtrim(config('app.api_url'), '/') }}/users_customers_profile",
                "method": "POST",
                "timeout": 0,
                "headers": {
                    "Content-Type": "application/json"
                },
                "data": JSON.stringify({
                    "users_customers_id": suggested_users_id,
                }),
            };
            $.ajax(settings).done(function (response) {
                if (response.status == 'success') {
                    var user = response.data;

                    // from amount
                    $('#sc2_from_amount').empty();
                    var from_currency_symbol   = $('#sc_from_currency option:selected').attr('symbol');
                    var from_amount             = $('#sc_total_amount').val().split('.');
                    if (from_amount[1] == null) {
                        from_amount[1] = '00';
                    }
                    $('#sc2_from_amount').append('\
                        '+ from_currency_symbol + ' ' + from_amount[0] +'.\
                        <span class="fs-6 text-primary">'+ from_amount[1] +'</span>\
                    ');

                    // exchange amount
                    $('#sc2_exchange_amount').empty();
                    var exchange_amount = $('#sc_exchange_amount').text().split('.');
                    $('#sc2_exchange_amount').append('\
                        '+ exchange_amount[0] +'.\
                        <span class="fs-6 text-primary">'+ exchange_amount[1] +'</span>\
                    ');

                    // receiver name
                    $('#sc2_receiver_name').html(user.first_name);

                    // receiver email
                    $('#sc2_receiver_email').html(user.email);

                    // receiver pic
                    var profile_pic = (user.profile_pic !== null && user.profile_pic !== '') 
                                        ? user.profile_pic 
                                        : 'users/assets/images/default_user.jpeg';
                    $('#sc2_receiver_image').attr('src', "{{ url('/public') }}" + "/" + profile_pic);


                    // country
                    $('#sc2_country_name').html($('#sc_country option:selected').text());

                    // current date
                    $('#sc2_current_date').html(get_today_date());

                    // display modal
                    $('#mdl_send_currency2').modal('show');
                } else {
                    toastr.error(response.error);
                }
            });
            /* AJAX API CALL */
        }
    });
    /* --------------- SUBMIT SEND CURRENCY --------------- */

    /* --------------- SEND CURRENCY --------------- */
    function send_currency() {
        var system_currencies_id        = $('#system_currencies_id').val();
        var to_users_customers_id       = $('#suggested_users_id').val();
        var from_system_currencies_id   = $('#sc_from_currency').val();
        var to_system_currencies_id     = $('#sc_exchange_currency').val();
        var from_amount                 = $('#sc_total_amount').val();
        var system_countries_id         = $('#sc_country').val();

        /* AJAX API CALL */
        var settings = {
            "url": "{{ rtrim(config('app.api_url'), '/') }}/transfer_currency",
            "method": "POST",
            "timeout": 0,
            "headers": {
                "Content-Type": "application/json"
            },
            "data": JSON.stringify({
                "system_currencies_id": system_currencies_id,
                "from_users_customers_id": users_customers_id,
                "to_users_customers_id": to_users_customers_id,
                "from_system_currencies_id": from_system_currencies_id,
                "to_system_currencies_id": to_system_currencies_id,
                "payment_method_id": '1',
                "from_amount": from_amount,
                "system_countries_id": system_countries_id,
            }),
        };
        $.ajax(settings).done(function (response) {
            if (response.status == 'success') {
                get_transactions();
                $('#mdl_send_currency2, #mdl_send_currency').modal('hide');
                $('#frm_send_currency')[0].reset();
                $('#frm_send_currency').find('p').text('');
                toastr.success('Amount is transferred successfully.');
            } else {
                toastr.error(response.message);
            }
        });
        /* AJAX API CALL */
    }
    /* --------------- SEND CURRENCY --------------- */

    /* --------------- GET CREATE SWAP PARAMS --------------- */
    function get_create_swap_params() {
        $('#mdl_create_swap').modal('show');

        // base currency
        var base_currency =  ($('#system_currencies_symbol').val())
                            + ' - ' + $('#system_currencies_code').val();
        $('#cs_base_currency').text(base_currency);

        /* AJAX API CALL */
        var settings = {
            "url": "{{ rtrim(config('app.api_url'), '/') }}/get_wallet",
            "method": "POST",
            "timeout": 0,
            "headers": {
                "Content-Type": "application/json"
            },
            "data": JSON.stringify({
                "users_customers_id": users_customers_id,
            }),
        };
        $.ajax(settings).done(function (response) {
            if (response.status == 'success') {
                var wallets       = response.data;
                var displayData   = '<option value="" disabled selected hidden>Select</option>';

                $.each(wallets, function (key, item) {
                    displayData +=
                        '<option amount="'+ item.wallet_amount +'" value="'+ item.users_customers_wallets_id +'">\
                            '+ item.currency.symbol + ' - ' + item.currency.country.name +' ('+ item.currency.country.code +')\
                        </option>';
                });
                $('#cs_from_account').html(displayData);
                $('#cs_to_account').html(displayData);
            }
        });
        /* AJAX API CALL */
    }
    /* --------------- GET CREATE SWAP PARAMS --------------- */

    /* --------------- SHOW CS FROM ACCOUNT WALLET --------------- */
    $('#cs_from_account').on('change', function() {
        var wallet_amount = $('option:selected', this).attr('amount');
        $('#cs_from_account_amount').html(wallet_amount);
    });
    /* --------------- SHOW CS FROM ACCOUNT WALLET --------------- */

    /* --------------- SHOW CS TO ACCOUNT WALLET --------------- */
    $('#cs_to_account').on('change', function() {
        var wallet_amount = $('option:selected', this).attr('amount');
        $('#cs_to_account_amount').html(wallet_amount);
    });
    /* --------------- SHOW CS TO ACCOUNT WALLET --------------- */

    /* --------------- COMPARE CS Total AMOUNT WITH WALLET --------------- */
    $(document).on('change keyup', '#cs_from_account, #cs_total_amount', function() {
        if ($('#cs_from_account').val() !== null && $('#cs_total_amount').val() !== '') {
            var users_customers_wallets_id   = $('#cs_from_account').val();
            var total_amount                 = $('#cs_total_amount').val();

            /* AJAX API CALL */
            var settings = {
                "url": "{{ rtrim(config('app.api_url'), '/') }}/user_wallet_detail",
                "method": "POST",
                "timeout": 0,
                "headers": {
                    "Content-type": "application/json"
                },
                "data": JSON.stringify({
                    "users_customers_id": users_customers_id,
                    "users_customers_wallets_id": users_customers_wallets_id
                }),
            };
            $.ajax(settings).done(function (response) { 
                if (response.status == 'success') {
                    var wallet_amount = response.data.wallet_amount;

                    if (wallet_amount - total_amount >= 0) {
                        return true;
                    } else {
                        alert('The amount you are trying to transfer exceeds your available balance.');
                        $('#cs_total_amount').val('');
                    }
                } else {
                    toastr.error(response.message);
                }
            });
            /* AJAX API CALL */
        }
    });
    /* --------------- COMPARE CS Total AMOUNT WITH WALLET --------------- */

    /* --------------- VALIDATE CREATE SWAP --------------- */
    $('#frm_create_swap').validate({
        rules: {
            cs_from_account: {
                required: true
            },
            cs_total_amount: {
                required: true,
                number: true
            },
            cs_to_account: {
                required: true
            },
        },
        messages: {
            cs_from_account: {
                required: 'This field is required.'
            },
            cs_total_amount: {
                required: 'This field is required.',
                number: 'Please enter a valid amount.'
            },
            cs_to_account: {
                required: 'This field is required.'
            },
        },
        errorPlacement: function (error, element) {
            if (element.attr('name') == 'cs_from_account') {
                $('#error_cs_from_account').html(error);
            } else if (element.attr('name') == 'cs_total_amount') {
                $('#error_cs_total_amount').html(error);
            } else if (element.attr('name') == 'cs_to_account') {
                $('#error_cs_to_account').html(error);
            }
        }
    });
    /* --------------- VALIDATE CREATE SWAP --------------- */

    /* --------------- SUBMIT CREATE SWAP --------------- */
    $('#frm_create_swap').on('submit', function (event) {
        event.preventDefault();
        if ($('#frm_create_swap').valid()) {
            var system_currencies_id              = $('#system_currencies_id').val(); 
            var from_users_customers_wallets_id   = $('#cs_from_account').val();
            var amount_from                       = $('#cs_total_amount').val();
            var to_users_customers_wallets_id     = $('#cs_to_account').val(); 

            /* AJAX API CALL */
            var settings = {
                "url": "{{ rtrim(config('app.api_url'), '/') }}/wallet_swap",
                "method": "POST",
                "timeout": 0,
                "headers": {
                    "Content-Type": "application/json"
                },
                "data": JSON.stringify({
                    "users_customers_id": users_customers_id,
                    "from_users_customers_wallets_id": from_users_customers_wallets_id,
                    "to_users_customers_wallets_id": to_users_customers_wallets_id,
                    "amount_from": amount_from,
                    "system_currencies_id": system_currencies_id,
                }),
            };
            $.ajax(settings).done(function (response) {
                $('#mdl_create_swap').modal('hide');
                $('#frm_create_swap')[0].reset();
                $('#frm_create_swap').find('span').text('');

                if (response.status == 'success') {
                    get_all_wallets();
                    toastr.success('Amount is swapped successfully.');
                } else {
                    toastr.error(response.message);
                }
            });
            /* AJAX API CALL */
        }
    });
    /* --------------- SUBMIT CREATE SWAP --------------- */



    /* --------------- GET ALL OFFERS --------------- */
    function get_all_offers() {
        /* AJAX API CALL */
        var settings = {
            "url": "{{ rtrim(config('app.api_url'), '/') }}/all_swap_offers",
            "method": "POST",
            "timeout": 0,
            "headers": {
                "Content-Type": "application/json"
            },
            "data": JSON.stringify({
                "users_customers_id": users_customers_id,
            }),
        };
        $.ajax(settings).done(function (response) {
            if (response.status == 'success') {
                $('#all_offers').empty();
                var offers = response.data;

                $.each(offers, function (key, item) {
                    var liked_offer = '';
                    if (item.liked == "Yes") {
                        liked_offer = '<img src="{{ asset('users/assets/images/icons/heart-fav.png') }}" class="ms-3 img-fluid" alt="" onclick="event.stopPropagation();">';
                    } else{
                        liked_offer = '<img src="{{ asset('users/assets/images/icons/heart.png') }}" class="ms-3 img-fluid cursor_pointer" alt="" onclick="add_to_favorite_offers('+ item.swap_offers_id +'); event.stopPropagation();" id="favorite_offer_'+ item.swap_offers_id +'">';
                    }

                    var from_currency_country_flag   = "{{ url('/public') }}" + item.from_currency.country.image;
                    var to_currency_country_flag     = "{{ url('/public') }}" + item.to_currency.country.image;
                    
                    $('#all_offers').append('\
                        <div class="col-md-6 col-xl-4">\
                            <div class="card border-0 mb-3">\
                                <div class="card-body" onclick="display_send_offer_modal('+ item.swap_offers_id +');" style="cursor:pointer;">\
                                    <div class="d-flex align-items-center justify-content-between border-bottom pb-3 mb-3 flex-wrap gap-1">\
                                        <div class="d-flex align-items-center">\
                                            <p class="mb-0">'+ item.from_currency.symbol +'1</p>\
                                            <span class="plane-icon bg-primary mx-2">\
                                                <img src="{{ asset('users/assets/images/icons/mini-icon/Repeat.png') }}" class="img-fluid" alt="">\
                                            </span>\
                                            <p class="mb-0">'+ item.to_currency.symbol + item.exchange_rate +'</p>\
                                        </div>\
                                        <div class="d-flex align-items-center">\
                                            <img src="{{ asset('users/assets/images/icons/clock.png') }}" class="img-fluid" alt="">\
                                            <small class="ms-1 mb-0 text-primary">'+ item.time_ago +'</small>'+ liked_offer +'\
                                        </div>\
                                    </div>\
                                    <div>\
                                        <div class="offers-card-body d-flex align-items-center justify-content-between flex-wrap gap-1">\
                                            <div class="mb-0">\
                                                <small class="text-primary mb-2">You Pay</small>\
                                                <p class="my-1"><span class="text-success">'+ item.from_currency.symbol +'</span>'+ item.from_amount +'</p>\
                                            </div>\
                                            <div class="mb-0 d-flex align-items-center">\
                                                <img src="'+ from_currency_country_flag +'" class="img-fluid" alt="">\
                                                <small class="mx-2">'+ item.from_currency.country.code + '/' + item.to_currency.country.code +'</small>\
                                                <img src="'+ to_currency_country_flag +'" class="img-fluid" alt="">\
                                            </div>\
                                            <div class="mb-0 text-end">\
                                                <small class="text-primary mb-2">You Pay</small>\
                                                <p class="my-1"><span class="text-success">'+ item.to_currency.symbol +'</span>'+ item.to_amount +'</p>\
                                            </div>\
                                        </div>\
                                    </div>\
                                </div>\
                            </div>\
                        </div>\
                    ');
                });
            } else {
                $('#all_offers').empty();
            }
        })
        /* AJAX API CALL */
    }
    /* --------------- GET ALL OFFERS --------------- */

    /* --------------- GET FAVORITE OFFERS --------------- */
    function get_favorite_offers() {
        /* AJAX API CALL */
        var settings = {
            "url": "{{ rtrim(config('app.api_url'), '/') }}/all_favorite_swaps_offers",
            "method": "POST",
            "timeout": 0,
            "headers": {
                "Content-Type": "application/json"
            },
            "data": JSON.stringify({
                "users_customers_id": users_customers_id,
            }),
        };
        $.ajax(settings).done(function (response) {
            if (response.status == 'success') {
                $('#favorite_offers').empty();
                var offers = response.data;

                $.each(offers, function (key, item) {
                    var from_currency_country_flag   = "{{ url('/public') }}" + item.from_currency.country.image;
                    var to_currency_country_flag     = "{{ url('/public') }}" + item.to_currency.country.image;

                    $('#favorite_offers').append('\
                        <div class="col-md-6 col-xl-4">\
                            <div class="card border-0 mb-3">\
                                <div class="card-body">\
                                    <div class="d-flex align-items-center justify-content-between border-bottom border-danger pb-3 mb-3 gap-1 flex-wrap">\
                                        <div class="d-flex align-items-center">\
                                            <p class="mb-0">'+ item.from_currency.symbol +'1</p>\
                                            <span class="plane-icon bg-primary mx-2">\
                                                <img src="{{ asset('users/assets/images/icons/mini-icon/Repeat.png') }}" class="img-fluid" alt="">\
                                            </span>\
                                            <p class="mb-0">'+ item.to_currency.symbol + item.exchange_rate +'</p>\
                                        </div>\
                                        <div class="d-flex align-items-center">\
                                            <img src="{{ asset('users/assets/images/icons/clock.png') }}" class="img-fluid" alt="">\
                                            <small class="ms-1 mb-0 text-primary">'+ item.time_ago +'</small>\
                                            <img src="{{ asset('users/assets/images/icons/heart-fav.png') }}" class="ms-3 img-fluid cursor_pointer" alt="" onclick="remove_from_favorite_offers('+ item.swap_offers_id +')" id="unfavorite_offer_'+item.swap_offers_id+'">\
                                        </div>\
                                    </div>\
                                    <div class="offers-card-body d-flex align-items-center justify-content-between flex-wrap gap-1">\
                                        <div class="mb-0">\
                                            <small class="text-primary mb-2">You Pay</small>\
                                            <p class="my-1"><span class="text-success">'+ item.from_currency.symbol +'</span>'+ item.from_amount +'</p>\
                                        </div>\
                                        <div class="mb-0 d-flex align-items-center">\
                                            <img src="'+ from_currency_country_flag +'" class="img-fluid" alt="">\
                                            <small class="mx-2">'+ item.from_currency.country.code + '/' + item.to_currency.country.code +'</small>\
                                            <img src="'+ to_currency_country_flag +'" class="img-fluid" alt="">\
                                        </div>\
                                        <div class="mb-0 text-end">\
                                            <small class="text-primary mb-2">You Pay</small>\
                                            <p class="my-1"><span class="text-success">'+ item.to_currency.symbol +'</span>'+ item.to_amount +'</p>\
                                        </div>\
                                    </div>\
                                </div>\
                            </div>\
                        </div>\
                    ');
                });
            } else {
                $('#favorite_offers').empty();
            }
        });
        /* AJAX API CALL */
    }
    /* --------------- GET FAVORITE OFFERS --------------- */

    /* --------------- GET MY OFFERS --------------- */
    function get_my_offers() {
        /* AJAX API CALL */
        var settings = {
            "url": "{{ rtrim(config('app.api_url'), '/') }}/user_swap_offers",
            "method": "POST",
            "timeout": 0,
            "headers": {
                "Content-Type": "application/json"
            },
            "data":JSON.stringify({
                "users_customers_id": users_customers_id,
            }),
        };
        $.ajax(settings).done(function (response) {
            if (response.status == 'success') {
                $('#my_offers').empty();
                var offers = response.data;

                $.each(offers, function (key, item) {
                    var from_currency_country_flag   = "{{ url('/public') }}" + item.from_currency.country.image;
                    var to_currency_country_flag     = "{{ url('/public') }}" + item.to_currency.country.image;

                    $('#my_offers').append('\
                        <div class="col-md-6 col-xl-4">\
                            <div class="card border-0 mb-3 cursor_pointer" onclick="view_offer_requests('+ item.swap_offers_id +')">\
                                <div class="card-body">\
                                    <div class="d-flex align-items-center justify-content-between border-bottom border-danger pb-3 mb-3 gap-1 flex-wrap">\
                                        <div class="d-flex align-items-center">\
                                            <p class="mb-0">'+ item.from_currency.symbol +'1</p>\
                                            <span class="plane-icon bg-primary mx-2">\
                                                <img src="{{ asset('users/assets/images/icons/mini-icon/Repeat.png') }}" class="img-fluid" alt="">\
                                            </span>\
                                            <p class="mb-0">'+ item.to_currency.symbol + item.exchange_rate +'</p>\
                                        </div>\
                                        <div class="d-flex align-items-center">\
                                            <img src="{{ asset('users/assets/images/icons/clock.png') }}" class="img-fluid" alt="">\
                                            <small class="ms-1 mb-0 text-primary">'+ item.time_ago +'</small>\
                                        </div>\
                                    </div>\
                                    <div class="offers-card-body d-flex align-items-center justify-content-between flex-wrap gap-1">\
                                        <div class="mb-0">\
                                            <small class="text-primary mb-2">You Pay</small>\
                                            <p class="my-1"><span class="text-success">'+ item.from_currency.symbol +'</span>'+ item.from_amount +'</p>\
                                        </div>\
                                        <div class="mb-0 d-flex align-items-center">\
                                            <img src="'+ from_currency_country_flag +'" class="img-fluid" alt="">\
                                            <small class="mx-2">'+ item.from_currency.country.code + '/' + item.to_currency.country.code +'</small>\
                                            <img src="'+ to_currency_country_flag +'" class="img-fluid" alt="">\
                                        </div>\
                                        <div class="mb-0 text-end">\
                                            <small class="text-primary mb-2">You Pay</small>\
                                            <p class="my-1"><span class="text-success">'+ item.to_currency.symbol +'</span>'+ item.to_amount +'</p>\
                                        </div>\
                                    </div>\
                                </div>\
                            </div>\
                        </div>\
                    ');
                });
            } else {
                $('#my_offers').empty();
            }
        });
        /* AJAX API CALL */
    }
    /* --------------- GET MY OFFERS --------------- */

    /* --------------- DISPLAY SEND OFFER MODAL --------------- */
    function display_send_offer_modal(id) {
        // display modal
        $("#mdl_send_offer").modal('show');

        // store in send offer modal
        $('#so_swap_offers_id').val(id);

        /* AJAX API CALL */
        var settings = {
            "url": "{{ rtrim(config('app.api_url'), '/') }}/all_swap_offers",
            "method": "POST",
            "timeout": 0,
            "headers": {
                "Content-Type": "application/json"
            },
            "data": JSON.stringify({
                "users_customers_id": users_customers_id,
            }),
        };
        $.ajax(settings).done(function (response) {
            if (response.status == 'success') {
                var offer = Object.values(response.data).filter(obj => obj.swap_offers_id === id); 

                // from currency
                $('#so_from_currency').html('');
                $('#so_from_currency').html(offer[0].from_currency.symbol + 1);

                // exchange rate
                $('#so_exchange_rate').html('');
                $('#so_exchange_rate').html(offer[0].to_currency.symbol + offer[0].exchange_rate);

                // from amount
                $('#so_amount').html('');
                $('#so_amount').html(offer[0].from_currency.symbol + offer[0].from_amount);

                // converted amount
                $('#so_converted_amount').html('');
                $('#so_converted_amount').html(offer[0].to_currency.symbol + offer[0].to_amount);
    
                var sender_currency_id     = offer[0].from_system_currencies_id;
                var receiver_currency_id   = $('#system_currencies_id').val(); 
                var from_amount            = offer[0].from_amount;

                /* AJAX API CALL */
                var settings = {
                    "url": "{{ rtrim(config('app.api_url'), '/') }}/currency_converter",
                    "method": "POST",
                    "timeout": 0,
                    "headers": {
                        "Content-Type": "application/json"
                    },
                    "data": JSON.stringify({
                        "sender_currency_id": sender_currency_id,
                        "receiver_currency_id": receiver_currency_id,
                        "from_amount": from_amount
                    }),
                };
                $.ajax(settings).done(function (response) {
                    if (response.status == 'success') {
                        var base_amount = response.data.converted_amount.toFixed(2).split(".");
                        var amount = '';
                            amount += $('#system_currencies_symbol').val();
                            amount += base_amount[0];
                            amount += '.';
                            amount += base_amount[1];

                        // base amount
                        $('#so_base_amount').html('');
                        $('#so_base_amount').html(amount);
                    } else {
                        toastr.error(response.message);
                    }
                });
                /* AJAX API CALL */
            } else {
                toastr.error(response.message);
            }
        });
        /* AJAX API CALL */ 
    }
    /* --------------- DISPLAY SEND OFFER MODAL --------------- */

    /* --------------- SEND OFFER --------------- */
    function send_offer() {
        var swap_offers_id = $('#so_swap_offers_id').val();

        /* AJAX API CALL */
        var settings = {
            "url": "{{ rtrim(config('app.api_url'), '/') }}/swap_offer_request",
            "method": "POST",
            "timeout": 0,
            "headers": {
                "Content-Type": "application/json"
            },
            "data": JSON.stringify({
                "swap_offers_id": swap_offers_id,
                "from_users_customers_id": users_customers_id
            }),
        };
        $.ajax(settings).done(function (response) {
            if (response.status == 'success') {
                toastr.success('Offer is sent successfully.');
            } else {
                toastr.error(response.message);
            }
        });
        /* AJAX API CALL */
    }
    /* --------------- SEND OFFER --------------- */

    /* --------------- ADD TO FAVORITE OFFERS --------------- */
    function add_to_favorite_offers(id) {
        /* AJAX API CALL */
        var settings = {
            "url": "{{ rtrim(config('app.api_url'), '/') }}/add_favorite_swaps_offers",
            "method": "POST",
            "timeout": 0,
            "headers": {
                "Content-Type": "application/json"
            },
            "data": JSON.stringify({
                "users_customers_id": users_customers_id,
                "swap_offers_id": id,
            }),
        };
        $.ajax(settings).done(function (response) {
            if (response.status == 'success') {
                var source = "{{ asset('users/assets/images/icons/heart-fav.png') }}";
                $('#favorite_offer_' + id).attr('src', source);

                get_all_offers();
                get_favorite_offers();
                get_hot_swap_offers();
                toastr.success('Offer added to favorite.');
            } else {
                toastr.error(response.message);
            }
        });
        /* AJAX API CALL */
    }
    /* --------------- ADD TO FAVORITE OFFERS --------------- */

    /* --------------- REMOVE FROM FAVORITE OFFERS --------------- */
    function remove_from_favorite_offers(id) {
        /* AJAX API CALL */
        var settings = {
            "url": "{{ rtrim(config('app.api_url'), '/') }}/remove_favorite_swaps_offers",
            "method": "POST",
            "timeout": 0,
            "headers": {
                "Content-Type": "application/json"
            },
            "data": JSON.stringify({
                "users_customers_id": users_customers_id,
                "swap_offers_id": id,
            }),
        };
        $.ajax(settings).done(function (response) {
            if (response.status == 'success') {
                get_favorite_offers();
                get_all_offers();
                get_hot_swap_offers();
                toastr.success('Offer removed from favorite.');
            } else {
                toastr.error(response.message);
            }
        });
        /* AJAX API CALL */
    }
    /* --------------- REMOVE FROM FAVORITE OFFERS --------------- */

    /* --------------- GET CREATE OFFER PARAMS --------------- */
    function get_create_offer_params() {
        $('#mdl_create_offer').modal('show');

        get_co_from_account();
        get_co_exchange_currency();
    }
    /* --------------- GET CREATE OFFER PARAMS --------------- */

    /* --------------- GET CO FROM ACCOUNT --------------- */
    function get_co_from_account() {
        /* AJAX API CALL */
        var settings = {
            "url": "{{ rtrim(config('app.api_url'), '/') }}/get_wallet",
            "method": "POST",
            "timeout": 0,
            "headers": {
                "Content-Type": "application/json"
            },
            "data": JSON.stringify({
                "users_customers_id": users_customers_id,
            }),
        };
        $.ajax(settings).done(function (response) { 
            if (response.status == 'success') {
                $('#co_from_account').empty();
                var wallets = response.data;

                $.each(wallets, function (key, item) {
                    $('#co_from_account').append('\
                        <option value="'+ item.system_currencies_id +'">\
                            '+ item.currency.country.name +' ('+ item.currency.country.code +')\
                        </option>\
                    ');
                });
            }
        });
        /* AJAX API CALL */
    }
    /* --------------- GET CO FROM ACCOUNT --------------- */

    /* --------------- GET CO EXCHANGE CURRENCY --------------- */
    function get_co_exchange_currency() {
        /* AJAX API CALL */
        var settings = {
            "url": "{{ rtrim(config('app.api_url'), '/') }}/all_currencies",
            "method": "GET",
            "timeout": 0,
        };
        $.ajax(settings).done(function (response) { 
            if (response.status == 'success') {
                $('#co_exchange_currency').empty();
                var currencies = response.data;

                $.each(currencies, function (key, item) {
                    $('#co_exchange_currency').append('\
                        <option value="'+ item.system_currencies_id +'">\
                            '+ item.country.name +' ('+ item.country.code +')\
                        </option>\
                    ');
                });
            }
        });
        /* AJAX API CALL */
    }
    /* --------------- GET CO EXCHANGE CURRENCY --------------- */

    /* --------------- VALIDATE CREATE OFFER --------------- */
    $('#frm_create_offer').validate({
        rules: {
            co_from_account: {
                required: true
            },
            co_total_amount: {
                required: true,
                number: true
            },
            co_exchange_currency: {
                required: true
            },
            co_exchange_rate: {
                required: true,
                number: true
            },
            co_expires_in: {
                required: true
            },
        },
        messages: {
            co_from_account: {
                required: 'This field is required.'
            },
            co_total_amount: {
                required: 'This field is required.',
                number: 'Please enter a valid amount.'
            },
            co_exchange_currency: {
                required: 'This field is required.'
            },
            co_exchange_rate: {
                required: 'This field is required.',
                number: 'Please enter a valid amount.'
            },
            co_expires_in: {
                required: 'This field is required.'
            },
        },
        errorPlacement: function (error, element) {
            if (element.attr('name') == 'co_from_account') {
                $('#error_co_from_account').html(error);
            } else if (element.attr('name') == 'co_total_amount') {
                $('#error_co_total_amount').html(error);
            } else if (element.attr('name') == 'co_exchange_currency') {
                $('#error_co_exchange_currency').html(error);
            } else if (element.attr('name') == 'co_exchange_rate') {
                $('#error_co_exchange_rate').html(error);
            } else if (element.attr('name') == 'co_expires_in') {
                $('#error_co_expires_in').html(error);
            }
        }
    });
    /* --------------- VALIDATE CREATE OFFER --------------- */

    /* --------------- SUBMIT CREATE OFFER --------------- */
    $('#frm_create_offer').on('submit', function (event) {
        event.preventDefault();
        if ($('#frm_create_offer').valid()) {
            var system_currencies_id        = $('#system_currencies_id').val();
            var from_system_currencies_id   = $('#co_from_account').val();
            var to_system_currencies_id     = $('#co_exchange_currency').val();
            var from_amount                 = $('#co_total_amount').val();
            var exchange_rate               = $('#co_exchange_rate').val();
            var expiry_time                 = $('#co_expires_in').val();

            /* AJAX API CALL */
            var settings = {
                "url": "{{ rtrim(config('app.api_url'), '/') }}/swap_offer",
                "method": "POST",
                "timeout": 0,
                "headers": {
                    "Content-Type": "application/json"
                },
                "data": JSON.stringify({
                    "users_customers_id": users_customers_id,
                    "system_currencies_id": system_currencies_id,
                    "from_system_currencies_id": from_system_currencies_id,
                    "to_system_currencies_id": to_system_currencies_id,
                    "from_amount": from_amount,
                    "exchange_rate": exchange_rate,
                    "expiry_time": expiry_time
                }),
            };
            $.ajax(settings).done(function (response) { 
                if (response.status == 'success') {
                    get_my_offers();
                    $('#mdl_create_offer').modal('hide');
                    $('#frm_create_offer')[0].reset();
                    toastr.success('Offer is created successfully.');
                } else {
                    toastr.error(response.message);
                }
            });
            /* AJAX API CALL */
        }
    });
    /* --------------- SUBMIT CREATE OFFER --------------- */

    /* --------------- VIEW OFFER REQUESTS --------------- */
    function view_offer_requests(id) {
        window.location.href = '/users/offer_requests/' + id;
    }
    /* --------------- VIEW OFFER REQUESTS --------------- */

    /* --------------- GET OFFER REQUESTS --------------- */
    function get_offer_requests() {
        var swap_offers_id = $('#swap_offers_id').val();

        if (swap_offers_id !== '') {
            /* AJAX API CALL */
            var settings = {
                "url": "{{ rtrim(config('app.api_url'), '/') }}/user_swap_offers_requests",
                "method": "POST",
                "timeout": 0,
                "headers": {
                    "Content-Type": "application/json"
                },
                "data": JSON.stringify({
                    "swap_offers_id": swap_offers_id
                }),
            };
            $.ajax(settings).done(function (response) { 
                if (response.status == 'success') {
                    $('#offer_requests').empty();
                    var requests = response.data;

                    $.each(requests, function (key, item) {
                        var last_name = '';
                        if (item.user_data.last_name !== null) {
                            last_name += item.user_data.last_name;
                        }
                        var sender_image = "{{ url('/public') }}" + "/" + item.user_data.profile_pic;

                        $('#offer_requests').append('\
                            <div class="col-sm-6">\
                                <div class="card border-0 mb-3">\
                                    <div class="card-body p-2 d-flex justify-content-between align-items-center">\
                                        <div class="d-flex align-items-center">\
                                            <div class="wallet-icon me-3">\
                                                <img src="'+ sender_image +'" class="img-fluid border border-1" alt="" srcset="" id="user_image">\
                                            </div>\
                                            <div>\
                                                <p class="mb-0 fw-bolder">'+ item.user_data.first_name +' '+ last_name +'</p>\
                                            </div>\
                                        </div>\
                                        <a href="#" class="nav-link d-flex justify-content-right align-items-right" role="button" id="navbarDropdown2" data-bs-toggle="dropdown" aria-expanded="false">\
                                            <img src="{{ asset('users/assets/images/icons/messages-2.png') }}" class="img-fluid" alt="" srcset="" onclick="start_chat('+ item.from_users_customers_id +')">\
                                        </a>\
                                        <small class="text-center">\
                                            <button class="btn btn-primary me-3" onclick="display_accept_offer_modal('+ item.swap_offers_requests_id +', '+ item.swap_offers_id +', '+ item.user_data.users_customers_id +')">Accept</button><br/>\
                                            <button class="me-3" id="btn_remove" onclick="remove_offer_request('+ item.swap_offers_requests_id +')">Remove</button>\
                                        </small>\
                                    </div>\
                                </div>\
                            </div>\
                        ');
                    });
                } else {
                    $('#offer_requests').empty();
                }
            });
            /* AJAX API CALL */
        }
    }
    /* --------------- GET OFFER REQUESTS --------------- */

    /* --------------- DISPLAY ACCEPT OFFER MODAL --------------- */
    function display_accept_offer_modal(swap_offers_requests_id, swap_offers_id, request_sender_id) {
        // display modal
        $('#mdl_accept_offer').modal('show');

        // store in accept offer modal
        $('#request_sender_id').val(request_sender_id);
        $('#swap_offers_request_id').val(swap_offers_requests_id);
        
        /* AJAX API CALL */
        var settings = {
            "url": "{{ rtrim(config('app.api_url'), '/') }}/user_swap_offers",
            "method": "POST",
            "timeout": 0,
            "headers": {
                "Content-Type": "application/json"
            },
            "data": JSON.stringify({
                "users_customers_id": users_customers_id,
            }),
        };
        $.ajax(settings).done(function (response) { 
            if (response.status == 'success') { 
                var request = Object.values(response.data).filter(obj => obj.swap_offers_id === swap_offers_id);  

                // from currency
                $('#accept_offer_from_currency').html('');
                $('#accept_offer_from_currency').html(request[0].from_currency.symbol + '1');

                // exchange rate
                $('#accept_offer_exchange_rate').html('');
                $('#accept_offer_exchange_rate').html(request[0].to_currency.symbol + request[0].exchange_rate);
    
                // from amount
                $('#accept_offer_amount').html('');
                $('#accept_offer_amount').html(request[0].from_currency.symbol + request[0].from_amount);
    
                // converted amount
                $('#accept_offer_converted_amount').html('');
                $('#accept_offer_converted_amount').html(request[0].to_currency.symbol + request[0].to_amount);
    
                var sender_currency_id     = request[0].from_system_currencies_id;
                var receiver_currency_id   = $('#system_currencies_id').val();
                var from_amount            = request[0].from_amount;

                /* AJAX API CALL */
                var settings = {
                    "url": "{{ rtrim(config('app.api_url'), '/') }}/currency_converter",
                    "method": "POST",
                    "timeout": 0,
                    "headers": {
                        "Content-Type": "application/json"
                    },
                    "data": JSON.stringify({
                        "sender_currency_id": sender_currency_id,
                        "receiver_currency_id": receiver_currency_id,
                        "from_amount": from_amount,
                    }),
                };
                $.ajax(settings).done(function (response) {
                    if (response.status == 'success') {
                        var base_amount = response.data.converted_amount.toFixed(2).split('.');
                        var amount = '';
                            amount += $('#system_currencies_symbol').val();
                            amount += base_amount[0];
                            amount += '.';
                            amount += base_amount[1];
    
                        // base amount
                        $('#accept_offer_base_amount').html('');
                        $('#accept_offer_base_amount').html(amount);
                    } else {
                        toastr.error(response.message);
                    }
                });
                /* AJAX API CALL */
            } else {
                toastr.error(response.message);
            }
        });
        /* AJAX API CALL */
    }
    /* --------------- DISPLAY ACCEPT OFFER MODAL --------------- */

    /* --------------- ACCEPT OFFER REQUEST --------------- */
    function accept_offer_request() {
        var swap_offers_requests_id   = $('#swap_offers_request_id').val();
        var swap_offers_id            = $('#swap_offers_id').val();
        var from_users_customers_id   = $('#request_sender_id').val();        

        /* AJAX API CALL */
        var settings = {
            "url": "{{ env('API_URL') }}" +"swap_offer_request_approve",
            "method": "POST",
            "timeout": 0,
            "headers": {
                "Content-Type": "application/json"
            },
            "data": JSON.stringify({
                "swap_offers_requests_id": swap_offers_requests_id,
                "swap_offers_id": swap_offers_id,
                "from_users_customers_id": from_users_customers_id
            }),
        };
        $.ajax(settings).done(function (response) {
            if (response.status == 'success') {
                get_offer_requests();
                toastr.success('Offer request accepted successfully.');
                window.location.href = '{{ url("/users/offers") }}#pills-my-offers';
            } else {
                toastr.error(response.message);
            }
        });
        /* AJAX API CALL */
    }
    /* --------------- ACCEPT OFFER REQUEST --------------- */

    /* --------------- REMOVE OFFER REQUEST --------------- */
    function remove_offer_request(id) {
        /* AJAX API CALL */
        var settings = {
            "url": "{{ env('API_URL') }}" +"swap_offer_request_reject",
            "method": "POST",
            "timeout": 0,
            "headers": {
                "Content-Type": "application/json"
            },
            "data": JSON.stringify({
                "swap_offers_requests_id": id,
            }),
        };
        $.ajax(settings).done(function (response) {
            if (response.status == 'success') {
                get_offer_requests();
                toastr.success('Offer request is removed successfully.');
            } else {
                toastr.error(response.message);
            }
        });
        /* AJAX API CALL */
    }
    /* --------------- REMOVE OFFER REQUEST --------------- */



    /* --------------- GET CONNECT CATEGORIES --------------- */
    function get_connect_categories() {
        /* AJAX API CALL */
        var settings = {
            "url": "{{ rtrim(config('app.api_url'), '/') }}/connect_categories",
            "method": "GET",
            "timeout": 0,
            "headers": {
                "Content-Type": "application/json"
            },
        };
        $.ajax(settings).done(function (response) {
            if (response.status == 'success') {
                var categories = response.data; 

                $.each(categories, function (key, item) {
                    var category_image = "{{ url('/public') }}" + "/" + item.icon; 

                    $('#connect_categories').append('\
                        <div class="connects-category">\
                            <div class="connects-item"><img src="'+ category_image +'" class="img-fluid" alt=""></div>\
                            <div class="connects-item-name text-center mt-1"><small>'+ item.name +'</small></div>\
                        </div>\
                    ');
                });
            } 
        });
        /* AJAX API CALL */
    }
    /* --------------- GET CONNECT CATEGORIES --------------- */

    /* --------------- GET POPULAR ARTICLES --------------- */
    function get_popular_articles() {
        /* AJAX API CALL */
        var settings = {
            "url": "{{ rtrim(config('app.api_url'), '/') }}/popular_connect_articles",
            "method": "POST",
            "timeout": 0,
            "headers": {
                "Content-Type": "application/json"
            },
            "data": JSON.stringify({
                "users_customers_id": users_customers_id,
            }),
        };
        $.ajax(settings).done(function (response) {
            if (response.status == 'success') {
                $('#popular_articles').html('');
                var articles = response.data;

                $.each(articles, function (key, item) {
                    var liked_article = '';
                    if (item.liked == 'Yes') {
                        liked_article = '<img src="{{ asset('users/assets/images/icons/heart1-fav.png') }}" alt="" onclick="unlike_popular_article('+ item.connect_articles_id +'); event.stopPropagation();" id="liked_popular_article_'+ item.connect_articles_id +'">';
                    } else {
                        liked_article = '<img src="{{ asset('users/assets/images/icons/heart1.png') }}" alt="" onclick="like_popular_article('+ item.connect_articles_id +'); event.stopPropagation();" id="unliked_popular_article_'+ item.connect_articles_id +'">';
                    }
                    var article_image       = "{{ url('/public') }}" + "/" + item.image;
                    var article_blog_link   = "https://portal.swapcircle.trade/" + "users/connect/blog/";
    
                    $('#popular_articles').append('\
                        <li class="splide__slide cursor_pointer" onclick="view_connect_article_blog('+ item.connect_articles_id +')">\
                            <div class="card text-start border-0 rounded-4 overflow-hidden h-100">\
                                <div class="card-body p-2">\
                                    <img class="card-img-top img-fluid" src="'+ article_image  +'" alt="Title">\
                                    <h4 class="card-title mt-1">'+ item.title +'</h4>\
                                    <p class="card-text w-30">'+ item.description +'</p>\
                                </div>\
                                <div class="card-footer border-top bg-white text-center py-2 d-flex justify-content-center gap-2">\
                                    '+ liked_article +'\
                                    <div class="card-icon" onclick="event.stopPropagation();" id="popular_article_link" data-clipboard-text="'+ article_blog_link + item. connect_articles_id +'">\
                                        <svg width="18" height="18" viewBox="0 0 18 18" fill="none" xmlns="http://www.w3.org/2000/svg">\
                                            <path d="M2.75293 1.875V10.8525C2.75293 11.5875 3.09793 12.285 3.69043 12.7275L7.59792 15.6525C8.43042 16.275 9.57792 16.275 10.4104 15.6525L14.3179 12.7275C14.9104 12.285 15.2554 11.5875 15.2554 10.8525V1.875H2.75293Z" stroke="#4BD16F" stroke-width="1.5" stroke-miterlimit="10"/>\
                                            <path d="M1.5 1.875H16.5" stroke="#4BD16F" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round"/>\
                                            <path d="M6 6H12" stroke="#4BD16F" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"/>\
                                            <path d="M6 9.75H12" stroke="#4BD16F" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"/>\
                                        </svg>\
                                    </div>\
                                </div>\
                            </div>\
                        </li>\
                    ');
                });
            }
        });
        /* AJAX API CALL */
    }
    /* --------------- GET POPULAR ARTICLES --------------- */

    /* --------------- GET OTHER ARTICLES --------------- */
    function get_other_articles() {
        /* AJAX API CALL */
        var settings = {
            "url": "{{ rtrim(config('app.api_url'), '/') }}/connect_articles",
            "method": "POST",
            "timeout": 0,
            "headers": {
                "Content-Type": "application/json"
            },
            "data": JSON.stringify({
                "users_customers_id": users_customers_id,
            }),
        };
        $.ajax(settings).done(function (response) {
            if (response.status == 'success') {
                $('#other_articles').html('');
                var articles = response.data;

                $.each(articles, function (key, item) {
                    var liked_article = '';
                    if (item.liked == 'Yes') {
                        liked_article = '<img src="{{ asset('users/assets/images/icons/heart1-fav.png') }}" alt="" onclick="unlike_other_article('+ item.connect_articles_id +'); event.stopPropagation();" id="liked_other_article_'+ item.connect_articles_id +'">';
                    } else{
                        liked_article = '<img src="{{ asset('users/assets/images/icons/heart1.png') }}" alt="" onclick="like_other_article('+ item.connect_articles_id +'); event.stopPropagation();" id="unliked_other_article_'+ item.connect_articles_id +'">';
                    }
                    var article_image       = "{{ url('/public') }}" + "/" + item.image;
                    var article_blog_link   = "https://portal.swapcircle.trade/" + "users/connect/blog/";
    
                    $('#other_articles').append('\
                        <li class="splide__slide cursor_pointer" onclick="view_connect_article_blog('+ item.connect_articles_id +')">\
                            <div class="card text-start border-0 rounded-4 overflow-hidden p-2 h-100">\
                                <div class="card-image position-relative">\
                                    <img class="card-img-top img-fluid" src="'+ article_image +'" alt="Title">\
                                    <div class="position-absolute top-0 end-0 text-end p-2">\
                                        <div class="d-flex justify-content-end gap-2 mb-2">\
                                            '+ liked_article +'\
                                            <span class="card-icon" onclick="event.stopPropagation();" id="other_article_link" data-clipboard-text="'+ article_blog_link + item.connect_articles_id +'">\
                                                <svg width="18" height="18" viewBox="0 0 18 18" fill="none" xmlns="http://www.w3.org/2000/svg">\
                                                    <path d="M2.75293 1.875V10.8525C2.75293 11.5875 3.09793 12.285 3.69043 12.7275L7.59792 15.6525C8.43042 16.275 9.57792 16.275 10.4104 15.6525L14.3179 12.7275C14.9104 12.285 15.2554 11.5875 15.2554 10.8525V1.875H2.75293Z" stroke="#4BD16F" stroke-width="1.5" stroke-miterlimit="10"/>\
                                                    <path d="M1.5 1.875H16.5" stroke="#4BD16F" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round"/>\
                                                    <path d="M6 6H12" stroke="#4BD16F" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"/>\
                                                    <path d="M6 9.75H12" stroke="#4BD16F" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"/>\
                                                </svg>\
                                            </span>\
                                        </div>\
                                    </div>\
                                </div>\
                                <div class="card-body px-0 py-2">\
                                    <h4 class="card-title fw-bold">'+ item.title +'</h4>\
                                    <p class="card-text">'+ item.description +'</p>\
                                </div>\
                            </div>\
                        </li>\
                    ');
                });
            }
        });
        /* AJAX API CALL */
    }    
    /* --------------- GET OTHER ARTICLES --------------- */

    /* --------------- LIKE POPULAR ARTICLE --------------- */
    function like_popular_article(id) { 
        /* AJAX API CALL */
        var settings = {
            "url": "{{ rtrim(config('app.api_url'), '/') }}/add_favorite_connect_articles",
            "method": "POST",
            "timeout": 0,
            "headers": {
                "Content-Type": "application/json"
            },
            "data": JSON.stringify({
                "users_customers_id": users_customers_id,
                "connect_articles_id": id,
            }),
        };
        $.ajax(settings).done(function (response) {
            if (response.status == 'success') {
                // replace image
                var source = '{{ asset('users/assets/images/icons/heart1-fav.png') }}';
                $('#unliked_popular_article_' + id).attr('src', source);

                get_popular_articles();
                get_other_articles();
                toastr.success('Article added to favorite.');
            } else {
                toastr.error(response.message);
            }
        });
        /* AJAX API CALL */
    }
    /* --------------- LIKE POPULAR ARTICLE --------------- */

    /* --------------- UNLIKE POPULAR ARTICLE --------------- */
    function unlike_popular_article(id) {
        /* AJAX API CALL */
        var settings = {
            "url": "{{ rtrim(config('app.api_url'), '/') }}/remove_favorite_connect_articles",
            "method": "POST",
            "timeout": 0,
            "headers": {
                "Content-Type": "application/json"
            },
            "data": JSON.stringify({
                "users_customers_id": users_customers_id,
                "connect_articles_id": id,
            }),
        };
        $.ajax(settings).done(function (response) {
            if (response.status == 'success') {
                // replace image
                var source = '{{ asset('users/assets/images/icons/heart1.png') }}';
                $('#liked_popular_article_' + id).attr('src', source);

                get_popular_articles();
                get_other_articles();
                toastr.success('Article removed from favorite.');
            } else {
                toastr.error(response.message);
            }
        });
        /* AJAX API CALL */
    }
    /* --------------- UNLIKE POPULAR ARTICLE --------------- */

    /* --------------- LIKE OTHER ARTICLE --------------- */
    function like_other_article(id) {
        /* AJAX API CALL */
        var settings = {
            "url": "{{ rtrim(config('app.api_url'), '/') }}/add_favorite_connect_articles",
            "method": "POST",
            "timeout": 0,
            "headers": {
                "Content-Type": "application/json"
            },
            "data": JSON.stringify({
                "users_customers_id": users_customers_id,
                "connect_articles_id": id,
            }),
        };
        $.ajax(settings).done(function (response) {
            if (response.status == 'success') {
                // replace image
                var source = '{{ asset('users/assets/images/icons/heart1-fav.png') }}';
                $("#unliked_other_article_" + id).attr('src', source);

                get_popular_articles();
                get_other_articles();
                toastr.success('Article added to favorite.');
            } else {
                toastr.error(response.message);
            }
        });
        /* AJAX API CALL */
    }
    /* --------------- LIKE OTHER ARTICLE --------------- */

    /* --------------- UNLIKE OTHER ARTICLE --------------- */
    function unlike_other_article(id) {
        /* AJAX API CALL */
        var settings = {
            "url": "{{ rtrim(config('app.api_url'), '/') }}/remove_favorite_connect_articles",
            "method": "POST",
            "timeout": 0,
            "headers": {
                "Content-Type": "application/json"
            },
            "data": JSON.stringify({
                "users_customers_id": users_customers_id,
                "connect_articles_id": id,
            }),
        };
        $.ajax(settings).done(function (response) {
            if (response.status == 'success') {
                // replace image
                var source = '{{ asset('users/assets/images/icons/heart1.png') }}';
                $('#liked_other_article_' + id).attr("src", source);

                get_popular_articles();
                get_other_articles();
                toastr.success('Article removed from favorite.');
            } else {
                toastr.error(response.message);
            }
        });
        /* AJAX API CALL */
    }
    /* --------------- UNLIKE OTHER ARTICLE --------------- */

    /* --------------- VIEW CONNECT ARTICLE BLOG --------------- */
    function view_connect_article_blog(id) {
        window.location.href = '/users/connect/blog/' + id;
    }
    /* --------------- VIEW CONNECT ARTICLE BLOG --------------- */

    /* --------------- GET CONNECT ARTICLE BLOG --------------- */
    function get_connect_article_blog() {
        var connect_articles_id = Number($('#connect_articles_id').val());

        if (connect_articles_id !== '') {
            /* AJAX API CALL */
            var settings = {
                "url": "{{ rtrim(config('app.api_url'), '/') }}/popular_connect_articles",
                "method": "POST",
                "timeout": 0,
                "headers": {
                    "Content-Type": "application/json"
                },
                "data": JSON.stringify({
                    "users_customers_id": users_customers_id,
                }),
            };
            $.ajax(settings).done(function (response) { 
                if (response.status == 'success') {
                    $('#connect_article_blog').empty();
                    var article = Object.values(response.data).filter(obj => obj.connect_articles_id === connect_articles_id);
                  
                    $.each(article, function (key, item) {
                        var article_image = "{{ url('/public') }}" + "/" + item.image;
    
                        $('#connect_article_blog').append('\
                            <div class="row mt-0 d-flex justify-content-center">\
                                <div class="col-lg-5 col-md-7">\
                                    <div class="card text-start border-0 rounded-4 overflow-hidden p-3">\
                                        <div class="card-image position-relative">\
                                            <img class="card-img-top img-fluid" src="'+ article_image +'" alt="Title">\
                                        </div>\
                                        <div class="card-body px-0 py-2">\
                                            <h4 class="fw-bold">'+ item.title +'</h4>\
                                            <p>'+ item.description +'</p>\
                                        </div>\
                                    </div>\
                                </div>\
                            </div>\
                        ');
                    });
                }
            });
           /* AJAX API CALL */
        }
    }
    /* --------------- GET CONNECT ARTICLE BLOG --------------- */



    /* --------------- GET BUY SELL CURRENCIES --------------- */
    function get_buy_sell_currencies() {
        /* AJAX API CALL */
        var settings = {
            "url": "{{ rtrim(config('app.api_url'), '/') }}/all_currencies",
            "method": "GET",
            "timeout": 0,
        };
        $.ajax(settings).done(function (response) { 
            if (response.status == 'success') {
                var currencies = response.data;

                $.each(currencies, function (key, item) { 
                    // buy from currency
                    $('#buy_from_currency').append('\
                        <option code="'+ item.code +'" symbol="'+ item.symbol +'" value="'+ item.system_currencies_id +'">\
                            '+ item.code +' ('+ item.symbol +')\
                        </option>\
                    ');

                    // buy to currency
                    $('#buy_to_currency').append('\
                        <option code="'+ item.code +'" symbol="'+ item.symbol +'" value="'+ item.system_currencies_id +'">\
                            '+ item.code +' ('+ item.symbol +')\
                        </option>\
                    ');

                    // sell from currency
                    $('#sell_from_currency').append('\
                        <option code="'+ item.code +'" symbol="'+ item.symbol +'" value="'+ item.system_currencies_id +'">\
                            '+ item.code +' ('+ item.symbol +')\
                        </option>\
                    ');

                    // sell to currency
                    $('#sell_to_currency').append('\
                        <option code="'+ item.code +'" symbol="'+ item.symbol +'" value="'+ item.system_currencies_id +'">\
                            '+ item.code +' ('+ item.symbol +')\
                        </option>\
                    ');
                });
            }
        });
        /* AJAX API CALL */
    }
    /* --------------- GET BUY SELL CURRENCIES --------------- */

    /* --------------- GET BUY FROM CURRENCY CODE --------------- */  
    $(document).on('change', '#buy_from_currency', function() {
        var selected_code = $('option:selected', this).attr('code');
        $('#buy_from_currency_code').html(selected_code);
    });
    /* --------------- GET BUY FROM CURRENCY CODE --------------- */  
    
    /* --------------- GET BUY TO CURRENCY CODE --------------- */  
    $(document).on('change', '#buy_to_currency', function() {
        var selected_code = $('option:selected', this).attr('code');
        $('#buy_to_currency_code').html(selected_code);
    });
    /* --------------- GET BUY TO CURRENCY CODE --------------- */  

    /* --------------- GET SELL FROM CURRENCY CODE --------------- */ 
    $(document).on('change', '#sell_from_currency', function() {
        var selected_code = $('option:selected', this).attr('code');
        $('#sell_from_currency_code').html(selected_code);
    });
    /* --------------- GET SELL FROM CURRENCY CODE --------------- */ 

    /* --------------- GET SELL TO CURRENCY CODE --------------- */ 
    $(document).on('change', '#sell_to_currency', function() {
        var selected_code = $('option:selected', this).attr('code');
        $('#sell_to_currency_code').html(selected_code);
    }); 
    /* --------------- GET SELL TO CURRENCY CODE --------------- */  

    /* --------------- GET BUY CONVERTED AMOUNT --------------- */
    // $('#buy_from_currency, #buy_to_currency, #buy_entered_amount').on('change keyup', function() {
    //     var from_currency    = $('#buy_from_currency option:selected').val();
    //     var to_currency      = $('#buy_to_currency option:selected').val();
    //     var entered_amount   = $('#buy_entered_amount').val();

    //     if (entered_amount !== '') {
    //         /* AJAX API CALL */
    //         var settings = {
    //             "url": "{{ rtrim(config('app.api_url'), '/') }}/buy_currency_rate",
    //             "method": "POST",
    //             "timeout": 0,
    //             "headers": {
    //                 "Content-Type": "application/json"
    //             },
    //             "data": JSON.stringify({
    //                 "from_system_currencies_id": from_currency,
    //                 "to_system_currencies_id": to_currency,
    //                 "from_amount":entered_amount,
    //             }),
    //         };
    //         $.ajax(settings).done(function (response) {
    //             if (response.status == 'success') {
    //                /* AJAX API CALL */
    //                 var base_settings = {
    //                     "url": "{{ rtrim(config('app.api_url'), '/') }}/currency_converter",
    //                     "method": "POST",
    //                     "timeout": 0,
    //                     "headers": {
    //                         "Content-Type": "application/json"
    //                     },
    //                     "data": JSON.stringify({
    //                         "sender_currency_id": from_currency,
    //                         "receiver_currency_id": $('#system_currencies_id').val(),
    //                         "from_amount": entered_amount,
    //                     }),
    //                 };
    //                 $.ajax(base_settings).done(function (base_response) {
    //                     if (base_response.status == 'success') {
    //                         // buy converted amount
    //                         var amount = response.data.converted_amount.toFixed(2).split('.');
    //                         $('#buy_converted_amount').html('\
    //                             '+ amount[0] +'.\
    //                             <span class="text-primary">'+ amount[1] +'</span>\
    //                         ');

    //                         // buy entered amount2
    //                         var entered_amount2 = '';
    //                             entered_amount2 += $('#buy_from_currency option:selected').attr('symbol');
    //                             entered_amount2 += entered_amount;

    //                         $('#buy_entered_amount2').html('\
    //                             <span class="plane-icon bg-danger me-1">\
    //                                 <img src="{{ asset('users/assets/images/icons/mini-icon/send-down.png') }}" alt="">\
    //                             </span>\
    //                             <span>'+ entered_amount2 +'</span>\
    //                         ');

    //                         // buy converted amount2
    //                         var conveted_amount2 = '';
    //                             conveted_amount2 += $('#buy_to_currency option:selected').attr('symbol');
    //                             conveted_amount2 += response.data.converted_amount.toFixed(2);

    //                         $('#buy_converted_amount2').html('\
    //                             <span class="plane-icon bg-primary me-1">\
    //                                 <img src="{{ asset('users/assets/images/icons/mini-icon/send.png') }}" alt="">\
    //                             </span>\
    //                             <span>'+ conveted_amount2 +'</span>\
    //                         ');

    //                         // buy base amount
    //                         var base_amount = '';
    //                             base_amount += $('#system_currencies_symbol').val();
    //                             base_amount += base_response.data.converted_amount.toFixed(2);

    //                         $('#buy_base_amount').html('\
    //                             <span class="plane-icon bg-black me-1">\
    //                                 <img src="{{ asset('users/assets/images/icons/mini-icon/account_balance.png') }}" alt="">\
    //                             </span>\
    //                             <span>'+ base_amount +'</span>\
    //                         ');
    //                     } else {
    //                         toastr.error(response.message);
    //                     }
    //                 });
    //                /* AJAX API CALL */
    //             } else {
    //                 toastr.error(response.message);
    //             }
    //         });
    //         /* AJAX API CALL */
    //     }
    // });
    /* --------------- GET BUY CONVERTED AMOUNT --------------- */ 

    /* --------------- CONVERT BUY CURRENCY --------------- */
    function convert_buy_currency() { 
        var from_currency    = $('#buy_from_currency option:selected').val();
        var to_currency      = $('#buy_to_currency option:selected').val();
        var entered_amount   = $('#buy_entered_amount').val();
       
        if (entered_amount !== '') {
            /* AJAX API CALL */
            var settings = {
                "url": "{{ rtrim(config('app.api_url'), '/') }}/buy_currency_rate",
                "method": "POST",
                "timeout": 0,
                "headers": {
                    "Content-Type": "application/json"
                },
                "data": JSON.stringify({
                    "from_system_currencies_id": from_currency,
                    "to_system_currencies_id": to_currency,
                    "from_amount":entered_amount,
                }),
            };
            $.ajax(settings).done(function (response) {
                if (response.status == 'success') {
                    var buy = response.data; 

                    // buy converted amount
                    var converted_amount = buy.converted_amount.toFixed(2).split('.');
                    $('#buy_converted_amount').html('\
                        '+ converted_amount[0] +'.\
                        <span class="text-primary">'+ converted_amount[1] +'</span>\
                    ');
                   
                    // buy live rate
                    $('#buy_live_rate').html('\
                        '+ converted_amount[0] +'.\
                        <span class="text-primary">'+ converted_amount[1] +'</span>\
                    ');

                    // buy admin rate
                    var admin_rate = buy.admin_rate_amount.toFixed(2).split('.');
                    $('#buy_admin_rate').html('\
                        '+ admin_rate[0] +'.\
                        <span class="text-primary">'+ admin_rate[1] +'</span>\
                    ');

                    $('#buy_rates').removeClass('d-none');
                } else {
                    toastr.error(response.message);
                }
            });
            /* AJAX API CALL */
        }
    }
    /* --------------- CONVERT BUY CURRENCY --------------- */

    /* --------------- CONVERT SELL CURRENCY --------------- */
    function convert_sell_currency() {
        var from_currency    = $('#sell_from_currency option:selected').val();
        var to_currency      = $('#sell_to_currency option:selected').val();
        var entered_amount   = $('#sell_entered_amount').val();

        if (entered_amount !== '') {
            /* AJAX API CALL */
            var settings = {
                "url": "{{ rtrim(config('app.api_url'), '/') }}/sell_currency_rate",
                "method": "POST",
                "timeout": 0,
                "headers": {
                    "Content-Type": "application/json"
                },
                "data": JSON.stringify({
                    "from_system_currencies_id": from_currency,
                    "to_system_currencies_id": to_currency,
                    "from_amount":entered_amount,
                }),
            };
            $.ajax(settings).done(function (response) {
                if (response.status == 'success') { 
                    var sell = response.data; 

                    // sell converted amount
                    var converted_amount = sell.converted_amount.toFixed(2).split('.');
                    $('#sell_converted_amount').html('\
                        '+ converted_amount[0] +'.\
                        <span class="text-primary">'+ converted_amount[1] +'</span>\
                    ');
                   
                    // sell live rate
                    $('#sell_live_rate').html('\
                        '+ converted_amount[0] +'.\
                        <span class="text-primary">'+ converted_amount[1] +'</span>\
                    ');

                    // sell admin rate
                    var admin_rate = sell.admin_rate_amount.toFixed(2).split('.');
                    $('#sell_admin_rate').html('\
                        '+ admin_rate[0] +'.\
                        <span class="text-primary">'+ admin_rate[1] +'</span>\
                    ');

                    $('#sell_rates').removeClass('d-none');
                } else {
                    toastr.error(response.message);
                }
            });
            /* AJAX API CALL */
        }
    }
    /* --------------- CONVERT SELL CURRENCY --------------- */

    /* --------------- GET SELL CONVERTED AMOUNT --------------- */
    // $('#sell_from_currency, #sell_to_currency, #sell_entered_amount').on('change keyup', function() {
    //     var from_currency    = $('#sell_from_currency option:selected').val();
    //     var to_currency      = $('#sell_to_currency option:selected').val();
    //     var entered_amount   = $('#sell_entered_amount').val();

    //     if (entered_amount !== '') {
    //         /* AJAX API CALL */
    //         var settings = {
    //             "url": "{{ rtrim(config('app.api_url'), '/') }}/sell_currency_rate",
    //             "method": "POST",
    //             "timeout": 0,
    //             "headers": {
    //                 "Content-Type": "application/json"
    //             },
    //             "data": JSON.stringify({
    //                 "from_system_currencies_id": from_currency,
    //                 "to_system_currencies_id": to_currency,
    //                 "from_amount":entered_amount,
    //             }),
    //         };
    //         $.ajax(settings).done(function (response) {
    //             if (response.status == 'success') {
    //                 /* AJAX API CALL */
    //                 var base_settings = {
    //                     "url": "{{ rtrim(config('app.api_url'), '/') }}/currency_converter",
    //                     "method": "POST",
    //                     "timeout": 0,
    //                     "headers": {
    //                         "Content-Type": "application/json"
    //                     },
    //                     "data": JSON.stringify({
    //                         "sender_currency_id": from_currency,
    //                         "receiver_currency_id": $('#system_currencies_id').val(),
    //                         "from_amount": entered_amount,
    //                     }),
    //                 };
    //                 $.ajax(base_settings).done(function (base_response) {
    //                     if (base_response.status == 'success') {
    //                         // sell converted amount
    //                         var amount = response.data.converted_amount.toFixed(2).split('.');
    //                         $('#sell_converted_amount').html('\
    //                             '+ amount[0] +'.\
    //                             <span class="text-primary">'+ amount[1] +'</span>\
    //                         ');

    //                         // sell entered amount2
    //                         var entered_amount2 = '';
    //                             entered_amount2 += $('#sell_from_currency option:selected').attr('symbol');
    //                             entered_amount2 += entered_amount;

    //                         $('#sell_entered_amount2').html('\
    //                             <span class="plane-icon bg-danger me-1">\
    //                                 <img src="{{ asset('users/assets/images/icons/mini-icon/send-down.png') }}" alt="">\
    //                             </span>\
    //                             <span>'+ entered_amount2 +'</span>\
    //                         ');

    //                         // sell converted amount2
    //                         var conveted_amount2 = '';
    //                             conveted_amount2 += $('#sell_to_currency option:selected').attr('symbol');
    //                             conveted_amount2 += response.data.converted_amount.toFixed(2);

    //                         $('#sell_converted_amount2').html('\
    //                             <span class="plane-icon bg-primary me-1">\
    //                                 <img src="{{ asset('users/assets/images/icons/mini-icon/send.png') }}" alt="">\
    //                             </span>\
    //                             <span>'+ conveted_amount2 +'</span>\
    //                         ');
                            
    //                         // sell base amount
    //                         var base_amount = '';
    //                             base_amount += $('#system_currencies_symbol').val();
    //                             base_amount += base_response.data.converted_amount.toFixed(2);

    //                         $('#sell_base_amount').html('\
    //                             <span class="plane-icon bg-black me-1">\
    //                                 <img src="{{ asset('users/assets/images/icons/mini-icon/account_balance.png') }}" alt="">\
    //                             </span>\
    //                             <span>'+ base_amount +'</span>\
    //                         ');
    //                     } else {
    //                         toastr.error(response.message);
    //                     }
    //                 });
    //                 /* AJAX API CALL */
    //             } else {
    //                 toastr.error(response.message);
    //             }
    //         });
    //         /* AJAX API CALL */
    //     }
    // });
    /* --------------- GET SELL CONVERTED AMOUNT --------------- */

    /* --------------- GET PROFILE PIC --------------- */
    function get_profile_pic() {
        /* AJAX API CALL */
        var settings = {
            "url": "{{ rtrim(config('app.api_url'), '/') }}/users_customers_profile",
            "method": "POST",
            "timeout": 0,
            "headers": {
                "Content-Type": "application/json"
            },
            "data": JSON.stringify({
                "users_customers_id": users_customers_id,
            }),
        };
        $.ajax(settings).done(function (response) {
            if (response.status == 'success') {
                var profile_image = "{{ url('public') }}" + "/" + response.data.profile_pic;

                $('#user_profile').attr('src', profile_image); 
                $('#profile_pic').attr('src', profile_image); 
                $('#edit_profile_pic').attr('src', profile_image); 
            } else {

            }
        });
        /* AJAX API CALL */
    }
    /* --------------- GET PROFILE PIC --------------- */

    /* --------------- UPDATE PROFILE PIC --------------- */
    function update_profile_pic(imageInput) {
        var fileImage   = imageInput.files[0];
        var reader      = new FileReader();

        reader.addEventListener("load", function() {
            var img_base64 = reader.result.toString().replace(/^data:(.*,)?/, "");

            /* AJAX API CALL */
            var settings = {
                "url": "{{ rtrim(config('app.api_url'), '/') }}/users_customers_profile",
                "method": "POST",
                "timeout": 0,
                "headers": {
                    "Content-Type": "application/json"
                },
                "data": JSON.stringify({
                    "users_customers_id": users_customers_id,
                }),
            };
            $.ajax(settings).done(function (response) { 
                if (response.status ==  'success') {
                    users_customers_type = response.data.users_customers_type;

                    if (users_customers_type == 'Individual') { 
                        /* AJAX API CALL */
                        var settings = {
                            "url": "{{ rtrim(config('app.api_url'), '/') }}/update_profile",
                            "method": "POST",
                            "timeout": 0,
                            "headers": {
                                "Content-Type": "application/json"
                            },
                            "data": JSON.stringify({
                                "users_customers_id": users_customers_id,
                                "first_name": response.data.first_name,
                                "last_name": response.data.last_name,
                                "phone": response.data.phone,
                                "email": response.data.email,
                                "location": response.data.location,
                                "notifications": response.data.notifications,
                                "valid_document": response.data.valid_document,
                                "profile_pic": img_base64,
                            }),
                        };
                        $.ajax(settings).done(function (response) {
                            if (response.status == 'success') {
                                var new_dp = "{{ url('public') }}" + "/" + response.data[0].profile_pic;
                                $('#user_profile').attr('src', new_dp); 
                                $('#profile_pic').attr('src', new_dp); 
                                $('#edit_profile_pic').attr('src', new_dp); 
                                toastr.success('Profile Image is updated successfully.');
                            } else {
                                toastr.error(response.message);
                            }
                        });
                        /* AJAX API CALL */
                    } else {
                        /* AJAX API CALL */
                        var settings = {
                            "url": "{{ rtrim(config('app.api_url'), '/') }}/update_profile",
                            "method": "POST",
                            "timeout": 0,
                            "headers": {
                                "Content-Type": "application/json"
                            },
                            "data": JSON.stringify({
                                "users_customers_id": users_customers_id,
                                "company_name": response.data.company_name,
                                "first_name": response.data.first_name,
                                "last_name": response.data.last_name,
                                "phone": response.data.phone,
                                "email": response.data.email,
                                "location": response.data.location,
                                "notifications": response.data.notifications,
                                "valid_document": response.data.valid_document,
                                "profile_pic": img_base64,
                            }),
                        };
                        $.ajax(settings).done(function (response) { 
                            if (response.status == 'success') {
                                var new_dp = "{{ url('public') }}" + '/' + response.data[0].profile_pic;
                                $('#user_profile').attr('src', new_dp); 
                                $('#profile_pic').attr('src', new_dp); 
                                $('#edit_profile_pic').attr('src', new_dp); 
                                toastr.success('Profile Image is updated successfully.');
                            } else {
                                toastr.error(response.message);
                            }
                        });
                        /* AJAX API CALL */
                    }                 
                } else {
                    toastr.error(response.message);
                }
            });
            /* AJAX API CALL */
        }, false);

        if (fileImage) {
            reader.readAsDataURL(fileImage);
        }
    }
    /* --------------- UPDATE PROFILE PIC --------------- */

    /* ---------------SHOW HIDE PASSWORD --------------- */
    function show_hide_password(fieldId) {
        var input   = $('#' + fieldId);
        var image    = $('#icon_' + fieldId); 

        if (input.attr('type') === 'password') {
            input.attr('type', 'text');
            
            var source = '{{ asset('users/assets/images/icons/eye.svg') }}';
            image.attr('src', source);
        } else {
            input.attr('type', 'password');
            var source = '{{ asset('users/assets/images/icons/eye_slash.png') }}';
            image.attr('src', source);
        }
    }
    /* ---------------SHOW HIDE PASSWORD --------------- */

    /* --------------- VALIDATE CHANGE PASSWORD --------------- */
    $('#frm_change_password').validate({
        rules: {
            old_password: {
                required: true
            },
            new_password: {
                required: true,
                minlength: 7
            },
            confirm_password: {
                required: true,
                equalTo: '#new_password'
            },
        },
        messages: {
            old_password: {
                required: 'This field is required.'
            },
            new_password: {
                required: 'This field is required.',
                minlength: 'Password should be at least 7 characters long.'
            },
            confirm_password: {
                required: 'This field is required.',
                equalTo: 'Please enter the same value as password.' 
            },
        },
        errorPlacement: function (error, element) {
            if (element.attr('name') == 'old_password') {
                $('#error_old_password').html(error);
            } else if (element.attr('name') == 'new_password') {
                $('#error_new_password').html(error);
            } else if (element.attr('name') == 'confirm_password') {
                $('#error_confirm_password').html(error);
            }
        }
    });
    /* --------------- VALIDATE CHANGE PASSWORD --------------- */

    /* --------------- SUBMIT CHANGE PASSWORD --------------- */
    $('#frm_change_password').on('submit', function (event) {
        event.preventDefault();
        if($('#frm_change_password').valid()) {
            var old_password       = $('#old_password').val();
            var new_password       = $('#new_password').val();
            var confirm_password   = $('#confirm_password').val();
            
            /* AJAX API CALL */
            var settings = {
                "url": "{{ rtrim(config('app.api_url'), '/') }}/change_password",
                "method": "POST",
                "timeout": 0,
                "headers": {
                    "Content-Type": "application/json"
                },
                "data": JSON.stringify({
                    "email": users_customers_email,
                    "old_password": old_password,
                    "password": new_password,
                    "confirm_password": confirm_password,
                }),
            };
            $.ajax(settings).done(function (response) {
                if (response.status == "success") {
                    $('#frm_change_password')[0].reset();
                    toastr.success('Password is updated successfully.');
                } else {
                    toastr.error(response.message);
                }
            });
            /* AJAX API CALL */
        }
    });
    /* --------------- SUBMIT CHANGE PASSWORD --------------- */

    /* --------------- VALIDATE FEEDBACK --------------- */
    $('#frm_feedback').validate({
        rules: {
            fb_name: {
                required: true,
                minlength: 3
            },
            fb_email: {
                required: true,
                email: true
            },
            fb_subject: {
                required: true
            },
        },
        messages: {
            fb_name: {
                required: 'This field is required.',
                minlength: 'Name should be at least 3 characters long.'
            },
            fb_email: {
                required: 'This field is required.',
                email: 'Please enter a valid email address.'
            },
            fb_subject: {
                required: 'This field is required.'
            },
        },
        errorPlacement: function (error, element) {
            if (element.attr('name') == 'fb_name') {
                $('#error_fb_name').html(error);
            } else if (element.attr('name') == 'fb_email') {
                $('#error_fb_email').html(error);
            } else if (element.attr('name') == 'fb_subject') {
                $('#error_fb_subject').html(error);
            }
        }
    });
    /* --------------- VALIDATE FEEDBACK --------------- */

    /* --------------- SUBMIT FEEDBACK --------------- */
    $('#frm_feedback').on('submit', function (event) {
        event.preventDefault();
        if ($('#frm_feedback').valid()) {
            var name      = $('#fb_name').val();
            var email     = $('#fb_email').val();
            var subject   = $('#fb_subject').val();

            /* AJAX API CALL */
            var settings = {
                "url": "{{ rtrim(config('app.api_url'), '/') }}/user_feedback",
                "method": "POST",
                "timeout": 0,
                "headers": {
                    "Content-Type": "application/json"
                },
                "data": JSON.stringify({
                    "users_customers_id": users_customers_id,
                    "name": name,
                    "email": email,
                    "subject": subject
                }),
            };
            $.ajax(settings).done(function (response) {
                if (response.status == 'success') {
                    toastr.success('Feedback is sent successfully.');
                } else {
                    toastr.error(response.message);
                }
                $('#mdl_feedback').modal('hide');
                $('#frm_feedback')[0].reset();
            });
            /* AJAX API CALL */
        }
    });
    /* --------------- SUBMIT FEEDBACK --------------- */

    /* --------------- GET ALL ACCOUNTS --------------- */
    function get_all_accounts() {
        /* AJAX API CALL */
        var settings = {
            "url": "{{ rtrim(config('app.api_url'), '/') }}/all_acounts",
            "method": "POST",
            "timeout": 0,
            "headers": {
                "Content-Type": "application/json"
            },
            "data": JSON.stringify({
                "users_customers_id": users_customers_id,
            }),
        };
        $.ajax(settings).done(function (response) {
            if (response.status == 'success') { 
                $('#all_accounts').empty();
                var accounts = response.data;

                $.each(accounts, function (key, item) { 
                    var user_image = "{{ url('/public') }}" + "/" + item.user_data.profile_pic;
                    $('#all_accounts').append('\
                        <div class="col-lg-4 col-md-6">\
                            <div class="card border-0 mb-3 rounded-4">\
                                <div class="card-body p-2 d-flex justify-content-between align-items-center p-3">\
                                    <div class="d-flex align-items-center">\
                                        <div class="wallet-icon me-3">\
                                            <img src="'+ user_image +'" class="img-fluid rounded-full border border-1" style="width: 45px !important; height: 45px !important;">\
                                        </div>\
                                        <div>\
                                            <p class="mb-0 fw-bolder">'+ item.full_name +'</p>\
                                            <small class="text-primary">'+ item.account_currency.symbol + ' ' + item.iban +'</small>\
                                        </div>\
                                    </div>\
                                    <a href="javascript:void(0);" onclick="view_account_detail('+ item.users_customers_accounts_id +', \'' + item.bank_name + '\', \'' + item.account_no + '\')" class="p-2">\
                                        <svg width="12" height="20" viewBox="0 0 12 20" fill="none" xmlns="http://www.w3.org/2000/svg">\
                                            <path d="M2 20L12 10L2 0L0.225 1.775L8.45 10L0.225 18.225L2 20Z" fill="#4BD16F"/>\
                                        </svg>\
                                    </a>\
                                </div>\
                            </div>\
                        </div>\
                    ');
                });
            }
        });
        /* AJAX API CALL */
    }
    /* --------------- GET ALL ACCOUNTS --------------- */

    /* --------------- GET ADD ACCOUNT PARAMS --------------- */
    function get_add_account_params() {
        $('#mdl_add_account').modal('show');

        get_account_currencies();
    }
    /* --------------- GET ADD ACCOUNT PARAMS --------------- */

    /* --------------- GET ACCOUNT CURRENCIES --------------- */
    function get_account_currencies() {
        /* AJAX API CALL */
        var settings = {
            "url": "{{ rtrim(config('app.api_url'), '/') }}/get_wallet",
            "method": "POST",
            "timeout": 0,
            "headers": {
                "Content-Type": "application/json"
            },
            "data": JSON.stringify({
                "users_customers_id": users_customers_id,
            }),
        };
        $.ajax(settings).done(function (response) {
           if (response.status == 'success') {
                var currencies = response.data;

                $.each(currencies, function (key, item) {
                    var flag_image = "{{ url('public') }}" + item.currency.country.image;
                    $('#account_currency').append('\
                        <option value="'+item.system_currencies_id+'">\
                            '+item.currency.name+' ('+item.currency.code+')\
                        </option>\
                    ');
                });
           }
        });
        /* AJAX API CALL */
    }
    /* --------------- GET ACCOUNT CURRENCIES --------------- */

    /* --------------- VALIDATE ADD ACCOUNT --------------- */
    $('#frm_add_account').validate({
        rules: {
            account_currency: {
                required: true
            },
            account_holder_name: {
                required: true,
                minlength: 3
            },
            account_bank_name: {
                required: true
            },
            account_branch_code: {
                required: true
            },
            account_number: {
                required: true
            },
            account_iban: {
                required: true
            },
        },
        messages: {
            account_currency: {
                required: 'This field is required.'
            },
            account_holder_name: {
                required: 'This field is required.',
                minlength: 'Name should be at least 3 characters long.'
            },
            account_bank_name: {
                required: 'This field is required.'
            },
            account_branch_code: {
                required: 'This field is required.'
            },
            account_number: {
                required: 'This field is required.'
            },
            account_iban: {
                required: 'This field is required.' 
            },
        },
        errorPlacement: function (error, element) {
            if (element.attr('name') == 'account_currency') {
                $('#error_account_currency').html(error);
            } else  if (element.attr('name') == 'account_holder_name') {
                $('#error_account_holder_name').html(error);
            } else  if (element.attr('name') == 'account_bank_name') {
                $('#error_account_bank_name').html(error);
            } else  if (element.attr('name') == 'account_branch_code') {
                $('#error_account_branch_code').html(error);
            } else  if (element.attr('name') == 'account_number') {
                $('#error_account_number').html(error);
            } else  if (element.attr('name') == 'account_iban') {
                $('#error_account_iban').html(error);
            }
        }
    });
    /* --------------- VALIDATE ADD ACCOUNT --------------- */

    /* --------------- SUBMIT ADD ACCOUNT --------------- */
    $('#frm_add_account').on('submit', function (event) {
        event.preventDefault();
        if ($('#frm_add_account').valid()) {
            var system_currencies_id   = $('#account_currency').val();
            var full_name              = $('#account_holder_name').val();
            var bank_name              = $('#account_bank_name').val();
            var branch_code            = $('#account_branch_code').val();
            var account_no             = $('#account_number').val();
            var iban                   = $('#account_iban').val();

            /* AJAX API CALL */
            var settings = {
                "url": "{{ rtrim(config('app.api_url'), '/') }}/add_acount",
                "method": "POST",
                "timeout": 0,
                "headers": {
                    "Content-Type": "application/json"
                },
                "data": JSON.stringify({
                    "users_customers_id": users_customers_id,
                    "system_currencies_id": system_currencies_id,
                    "full_name": full_name,
                    "bank_name": bank_name,
                    "branch_code": branch_code,
                    "account_no": account_no,
                    "iban": iban
                }),
            };
            $.ajax(settings).done(function (response) { 
                if (response.status == 'success') {
                    get_all_accounts();
                    $('#mdl_add_account').modal('hide');
                    $('#frm_add_account')[0].reset();
                    toastr.success('Account is added successfully.');
                } else {
                    toastr.error(response.message);
                }
            });
            /* AJAX API CALL */
        }
    });
    /* --------------- SUBMIT ADD ACCOUNT --------------- */

    /* --------------- VIEW ACCOUNT DETAIL --------------- */
    function view_account_detail(users_customers_accounts_id, bank_name, account_no) {
        $('#wa_accounts_id').val(users_customers_accounts_id);
        $('#wa_bank_name').val(bank_name);
        $('#wa_account_number').val(account_no);

        /* AJAX API CALL */
        var settings = {
            "url": "{{ rtrim(config('app.api_url'), '/') }}/get_wallet",
            "method": "POST",
            "timeout": 0,
            "headers": {
                "Content-Type": "application/json"
            },
            "data": JSON.stringify({
                "users_customers_id": users_customers_id,
            }),
        };
        $.ajax(settings).done(function (response) {
           if (response.status == 'success') {
               $('#wa_currency').empty();
                var wallets = response.data;

                $('#wa_currency').prepend('\
                    <option value="" disabled selected hidden>Select Currency</option>\
                ');
                $.each(wallets, function (key, item) {
                    $('#wa_currency').append('\
                        <option value="'+ item.users_customers_wallets_id +' ">\
                            '+ item.currency.country.currency +' ('+ item.currency.code +')\
                        </option>\
                    ');
                });
            }
        });
        /* AJAX API CALL */
        $('#mdl_withdraw_amount').modal('show');
    }
    /* --------------- VIEW ACCOUNT DETAIL --------------- */

    /* --------------- VALIDATE WITHDRAW AMOUNT --------------- */
    $('#frm_withdraw_amount').validate({
        rules: {
            wa_currency: {
                required: true
            },
            wa_amount: {
                required: true,
                number: true
            },
            wa_account_notes: {
                required: true
            },
        },
        messages: {
            wa_currency: {
                required: 'This field is required.'
            },
            wa_amount: {
                required: 'This field is required.',
                number: 'Please enter a valid amount.'
            },
            wa_account_notes: {
                required: 'This field is required.'
            },
        },
        errorPlacement: function (error, element) {
            if (element.attr('name') == 'wa_currency') {
                $('#error_wa_currency').html(error);
            } else  if (element.attr('name') == 'wa_amount') {
                $('#error_wa_amount').html(error);
            } else  if (element.attr('name') == 'wa_account_notes') {
                $('#error_wa_account_notes').html(error);
            }
        }
    });
    /* --------------- VALIDATE WITHDRAW AMOUNT --------------- */

    /* --------------- SUBMIT WITHDRAW AMOUNT --------------- */
    $('#frm_withdraw_amount').on('submit', function (event) {
        event.preventDefault();
        if ($('#frm_withdraw_amount').valid()) {
            var users_customers_wallets_id    = $('#wa_currency').val();
            var users_customers_accounts_id   = $('#wa_accounts_id').val();
            var amount                        = $('#wa_amount').val();
            var description                   = $('#wa_account_notes').val();

            /* AJAX API CALL */
            var settings = {
                "url": "{{ rtrim(config('app.api_url'), '/') }}/withdraw_wallets_request",
                "method": "POST",
                "timeout": 0,
                "headers": {
                    "Content-Type": "application/json"
                },
                "data": JSON.stringify({
                    "users_customers_id": users_customers_id,
                    "users_customers_wallets_id": users_customers_wallets_id,
                    "users_customers_accounts_id": users_customers_accounts_id,
                    "amount": amount,
                    "description": description
                }),
            };
            $.ajax(settings).done(function (response) { 
                if (response.status == 'success') {
                    $('#mdl_withdraw_amount').modal('hide');
                    $('#frm_withdraw_amount')[0].reset();
                    toastr.success('Withdraw request is sent successfully.');
                } else {
                    toastr.error(response.message);
                }
            });
            /* AJAX API CALL */
        }
    });
    /* --------------- SUBMIT WITHDRAW AMOUNT --------------- */

    /* --------------- GET FAQS --------------- */
    function get_faqs() {
        /* AJAX API CALL */
        var settings = {
            "url": "{{ rtrim(config('app.api_url'), '/') }}/all_faqs",
            "method": "GET",
            "timeout": 0,
        };
        $.ajax(settings).done(function (response) {
            if (response.status == 'success') {
                var faqs = response.data;

                $.each(faqs, function (key, item) { 
                    if (key == 0) {
                        $('#accordionExample').append('\
                            <div class="accordion-item">\
                                <h2 class="accordion-header" id="headingOne">\
                                    <button class="accordion-button bg-white" type="button" data-bs-toggle="collapse" data-bs-target="#'+ item.faqs_id +'" aria-expanded="true" aria-controls="'+ item.faqs_id +'">\
                                        '+ item.question +'\
                                    </button>\
                                </h2>\
                                <div id="'+ item.faqs_id +'" class="accordion-collapse collapse show" aria-labelledby="headingOne" data-bs-parent="#accordionExample">\
                                    <div class="accordion-body pt-0">'+ item.answer +'\</div>\
                                </div>\
                            </div> \
                        ');
                    } else {
                        $('#accordionExample').append('\
                            <div class="accordion-item">\
                                <h2 class="accordion-header" id="heading">\
                                    <button class="accordion-button bg-white collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#'+ item.faqs_id +'" aria-expanded="false" aria-controls="'+ item.faqs_id +'">\
                                        '+ item.question +'\
                                    </button>\
                                </h2>\
                                <div id="'+ item.faqs_id +'" class="accordion-collapse collapse" aria-labelledby="heading" data-bs-parent="#accordionExample">\
                                    <div class="accordion-body pt-0">'+ item.answer +'</div>\
                                </div>\
                            </div>\
                        ');
                    }
                });
            }
        });
        /* AJAX API CALL */
    }
    /* --------------- GET FAQS --------------- */

    /* --------------- VALIDATE DELETE ACCOUNT --------------- */
    $('#frm_delete_account').validate({
        rules: {
            delete_reason: {
                required: true
            },
            comments: {
                required: true
            },
        },
        messages: {
            delete_reason: {
                required: 'This field is required.'
            },
            comments: {
                required: 'This field is required.'
            },
        },
        errorPlacement: function (error, element) {
            if (element.attr('name') == 'delete_reason') {
                $('#error_delete_reason').html(error);
            } else if (element.attr('name') == 'comments') {
                $('#error_comments').html(error);
            }
        }
    });
    /* --------------- VALIDATE DELETE ACCOUNT --------------- */

    /* --------------- SUBMIT DELETE ACCOUNT --------------- */
    $('#frm_delete_account').on('submit', function (event) {
        event.preventDefault();
        if ($('#frm_delete_account').valid()) {
            var delete_reason   = $('#delete_reason').val();
            var comments        = $('#comments').val(); 

            /* AJAX API CALL */
            var settings = {
                "url": "{{ rtrim(config('app.api_url'), '/') }}/delete_account",
                "method": "POST",
                "timeout": 0,
                "headers": {
                    "Content-Type": "application/json"
                },
                "data": JSON.stringify({
                    "user_email": users_customers_email,
                    "delete_reason": delete_reason,
                    "comments": comments
                }),
            };
            $.ajax(settings).done(function (response) {
                if (response.status == 'success') {
                    toastr.success(response.message);
                } else {
                    toastr.error(response.message);
                }
                $('#mdl_delete_account').modal('hide');
                $('#frm_delete_account')[0].reset();
            });
            /* AJAX API CALL */
        }
    });
    /* --------------- SUBMIT DELETE ACCOUNT --------------- */

    /* --------------- START CHAT --------------- */
    function start_chat(id) {
        $('#msg_receiver_id').val(id);
        /* AJAX API CALL */
        var settings = {
            "url": "{{ rtrim(config('app.api_url'), '/') }}/user_chat",
            "method": "POST",
            "timeout": 0,
            "headers": {
                "Content-Type": "application/json"
            },
            "data": JSON.stringify({
                "requestType": "startChat",
                "users_customers_id": users_customers_id,
                "other_users_customers_id": id,  
            }),
        };
        $.ajax(settings).done(function (response) {
            if (response.status == 'success') {
                window.location.href = '/users/message/' + id;
            } else {
                toastr.error(response.message);
            }
        });
        /* AJAX API CALL */
    }
    /* --------------- START CHAT --------------- */

    /* --------------- GET ALL CHATS --------------- */
    function get_all_chats() {
        /* AJAX API CALL */
        var settings = {
            "url": "{{ rtrim(config('app.api_url'), '/') }}/getAllChat",
            "method": "POST",
            "timeout": 0,
            "headers": {
                "Content-Type": "application/json"
            },
            "data": JSON.stringify({
                "users_customers_id": users_customers_id,
            }),
        };
        $.ajax(settings).done(function (response) {
            if (response.status == 'success') {
                $('#all_chats').empty();
                var chats = response.data;

                $.each(chats, function (key, item) {
                    var user_id = '';
                        user_id += item.user_data.users_customers_id;

                    var user_name = '';
                        user_name += item.user_data.first_name;
                        user_name += ' ';
                        user_name += item.user_data.last_name;

                    var user_image = '';
                        user_image += "{{ url('/public') }}" + "/" + item.user_data.profile_pic;

                    $('#all_chats').append('\
                        <li class="px-3 py-0 d-flex gap-0 msg-tab" onclick="get_messages('+ user_id +')">\
                            <div class="me-2 d-flex align-items-center flex-grow-1">\
                                <div class="position-relative me-2">\
                                    <img src="'+ user_image +'" class="img-fluid border border-1 alt="image">\
                                </div>\
                                <div class="flex-grow-1 cursor_pointer">\
                                    <p class="mb-0 text-black">'+ user_name +'</p>\
                                    <small class="text-black">'+ item.last_message +'</small>\
                                </div>\
                            </div>\
                            <div class="text-end msg-show">\
                                <small class="mb-1" style="font-size: 9.5px;">'+ item.date +'</small>\
                            </div>\
                        </li>\
                        <hr>\
                    ');
                });
                $('#chat').removeClass('d-none');
                $('#no_chat').addClass('d-none');
            } else {
                $('#no_chat').removeClass('d-none');
                $('#chat').addClass('d-none');
            }
        });
        /* AJAX API CALL */
    }   
    /* --------------- GET ALL CHATS --------------- */

    /* --------------- GET MESSAGES --------------- */
    function get_messages(id) { 
        $('#msg_receiver_id').val(id);

       /* AJAX API CALL */
        var settings = {
            "url": "{{ rtrim(config('app.api_url'), '/') }}/user_chat",
            "method": "POST",
            "timeout": 0,
            "headers": {
                "Content-Type": "application/json"
            },
            "data": JSON.stringify({
                "requestType": "getMessages",
                "users_customers_id":users_customers_id,
                "other_users_customers_id": id,
            }),
        };
        $.ajax(settings).done(function (response) {
            if (response.status == 'success') {
                $('#messages').empty();
                var messages = response.data;

                $.each(messages, function (key, item) {
                    var date = '';
                    if (item.date !== "") {
                        date += '<span class="bg-secondary rounded-pill text-white px-2 py-1">';
                        date += item.date;
                        date += '</span>';
                    }
                
                    var my_msg = '';
                    var other_user_msg = '';
                    var other_user_image = '';
                    var other_user_name = '';
                    if (item.user_data.users_customers_id == users_customers_id) {
                        my_msg += '<p class="msg  ms-auto text-start">'+ item.message +'</p>';
                        my_msg += '<small class="sm-auto">'+ item.time +'</small>';
                    } else { 
                        other_user_image += "{{ url('/public')}}" +"/" + item.user_data.profile_pic;
                        other_user_msg += '<div class="position-relative me-4">';
                        other_user_msg += '<img src="'+ other_user_image +'" class="img-fluid" alt="image">';
                        other_user_msg += '</div>';
                        other_user_msg += '<div class="flex-grow-1">';
                        other_user_msg += '<div>';
                        other_user_msg += '<span class="text-success fw-normal">'+ item.user_data.first_name +'</span>';
                        other_user_msg += '<p class="msg">'+ item.message +'</p>';
                        other_user_msg += '</div>';
                        other_user_msg += '<small>'+ item.time +'</small>';
                        other_user_msg += '</div>';
                    }
                
                    $('#messages').append('\
                        <!-- msg day -->\
                        <li class="chat-list msg-day text-center">'+ date +'</li>\
                        <!-- other msg -->\
                        <li class="chat-list other-msg">'+ other_user_msg +'</li>\
                        <!-- my msg -->\
                        <li class="chat-list my-msg text-end">'+ my_msg + '</li>\
                    ');
                    $('#no_message').addClass('d-none');
                    $('#send_message').removeClass('d-none');
                });
                setInterval(update_messages, 2000);
                scroll_to_bottom();
            } else {
                $('#messages').empty();
                $('#no_message').addClass('d-none');
                $('#send_message').removeClass('d-none');
            }
        });
       /* AJAX API CALL */
    }
    /* --------------- GET MESSAGES --------------- */

    /* --------------- UPDATE MESSAGES --------------- */
    function update_messages() { 
        var receiver_id = $('#msg_receiver_id').val();

        if (receiver_id !== '') {
            /* AJAX API CALL */
            var settings = {
                "url": "{{ rtrim(config('app.api_url'), '/') }}/user_chat",
                "method": "POST",
                "timeout": 0,
                "headers": {
                    "Content-Type": "application/json"
                },
                "data": JSON.stringify({
                    "requestType": "updateMessages",
                    "users_customers_id": users_customers_id,
                    "other_users_customers_id": receiver_id,
                }),
            };
            $.ajax(settings).done(function (response) {
                if (response.status == 'success') {
                    get_messages(receiver_id);
                }
            });
            /* AJAX API CALL */
        }
    }
    /* --------------- UPDATE MESSAGES --------------- */

    /* --------------- SEND MESSAGE --------------- */
    function send_message() {
       /* AJAX API CALL */
        var settings = {
            "url": "{{ rtrim(config('app.api_url'), '/') }}/user_chat",
            "method": "POST",
            "timeout": 0,
            "headers": {
                "Content-Type": "application/json"
            },
            "data": JSON.stringify({
                "requestType": "sendMessage",
                "sender_type": "Users",
                "users_customers_id": users_customers_id,
                "other_users_customers_id": $("#msg_receiver_id").val(),
                "content": $("#entered_message").val(),
                "messageType": "1",
            }),
        };
        $.ajax(settings).done(function (response) {
            if (response.status == 'success') {
                get_messages($('#msg_receiver_id').val());
                $('#entered_message').val('');
            } else {
                toastr.error(response.message);
            }
        })
       /* AJAX API CALL */
    }
    /* --------------- SEND MESSAGE --------------- */



    /* --------------- GET NOTIFICATIONS --------------- */
    function get_all_notifications() {
        /*AJAX API CALL */
        var settings = {
            "url": "{{ rtrim(config('app.api_url'), '/') }}/notifications",
            "method": "POST",
            "timeout": 0,
            "headers": {
                "Content-Type": "application/json"
            },
            "data": JSON.stringify({
                "users_customers_id": users_customers_id,
            }),
        };
        $.ajax(settings).done(function (response) { 
            $('#notifications').empty();
            if (response.status == 'success' && response.data.length > 0) {
                var notifications = response.data;

                $.each(notifications, function (key, item) {
                    var sender_image = "{{ url('public') }}" + "/" + item.notification_sender.profile_pic;
                    $("#notifications").append('\
                        <div class="row px-3 py-3">\
                            <div class="col-lg col-xl">\
                                <div class="media d-flex">\
                                    <div class="avatar avatar-xl">\
                                        <img src="'+ sender_image +'" class="img-fluid rounded-circle border border-1" width="35px" height="35px" alt="image"/>\
                                    </div>\
                                    <div class="media-body ms-2 text-truncate">\
                                        <div class="my-0 fw-normal text-dark" style="font-size: 14px;">\
                                            '+ item.notification_sender.first_name +' '+ item.notification_sender.last_name +'\
                                        </div>\
                                        <small class="text-muted mb-0" style="font-size: 12px;">'+ item.message +'</small>\
                                    </div>\
                                    <div class="avatar avatar-xl">\
                                        <small class="float-end text-muted ps-2" style="font-size: 11px;">'+ item.time_ago +'</small>\
                                    </div>\
                                </div>\
                            </div>\
                        </div>\
                    ');
                });
            } else {
                $('#notifications').append('\
                    <div class="row px-3 py-3">\
                        <div class="col-lg col-xl">\
                            <div class="media d-flex">\
                                <div class="media-body ms-2 text-truncate">\
                                    <h6 class="my-0 fw-normal text-dark">No notification found.</h6>\
                                </div>\
                            </div>\
                        </div>\
                    </div>\
               ');
            }
        }); 
        /*AJAX API CALL */
    }
    /* --------------- GET NOTIFICATIONS --------------- */

    /* --------------- GET UNREAD NOTIFICATIONS --------------- */
    function get_unread_notifications() {
        /* AJAX API CALL */
        var settings = {
            "url": "{{ rtrim(config('app.api_url'), '/') }}/notifications_unread",
            "method": "POST",
            "timeout": 0,
            "headers": {
                "Content-Type": "application/json"
            },
            "data": JSON.stringify({
                "users_customers_id": users_customers_id
            }),
        };
        $.ajax(settings).done(function (response) { 
            if (response.status == 'success' && response.data.length > 0) {
                $('#unread_notifications').html(response.data.length);
                $("#unread_notification").removeClass('visually-hidden');
            }
        });
        /* AJAX API CALL */
    }
    /* --------------- GET UNREAD NOTIFICATIONS --------------- */

    /* --------------- GET UNREAD MESSAGES --------------- */
    function get_unread_messages() {
        /* AJAX API CALL */
        var settings = {
            "url": "{{ rtrim(config('app.api_url'), '/') }}/unreaded_messages",
            "method": "POST",
            "timeout": 0,
            "headers": {
                "Content-Type": "application/json"
            },
            "data": JSON.stringify({
                "users_customers_id": users_customers_id,
            }),
        };
        $.ajax(settings).done(function (response) {
            if (response.status == 'success' && response.data > 0) {
                $('#unread_messages').append('\
                    <span class="position-absolute top-0 start-100 translate-middle badge rounded-full bg-primary text-white">\
                        <span>'+ response.data +'</span>\
                    </span>\
                ');
                // $('#unread_messages').html(response.data);
                // $('#unread_message').removeClass('d-none');
            }
        });
        /* AJAX API CALL */
    }
    /* --------------- GET UNREAD MESSAGES --------------- */

    /* --------------- OPEN PRODUCT MODAL --------------- */
    function openProductModal(type, productId) {
        let modalId = '';
        let hiddenInputId = '';

        switch(type) {
            case 'A':
                modalId = 'mdl_prodA_details';
                hiddenInputId = 'prodA_products_id';
                break;
            case 'B':
                modalId = 'mdl_prodB_details';
                hiddenInputId = 'prodB_products_id';
                break;
            case 'C':
                modalId = 'mdl_prodC_details';
                hiddenInputId = 'prodC_products_id';
                break;
            default:
                console.error('Unknown product type:', type);
                return;
        }
        let hiddenInput = document.getElementById(hiddenInputId);
        if(hiddenInput) {
            hiddenInput.value = productId;
        }
        let myModal = new bootstrap.Modal(document.getElementById(modalId));
        myModal.show();
    }
    /* --------------- OPEN PRODUCT MODAL --------------- */

    // âœ… Custom validator: at least one of NIN or NIN document must be provided
    $.validator.addMethod("ninRequired", function(value, element) {
        var form = $(element).closest('form');
        var prefix = element.name.split('_nin')[0]; // e.g., prodA or prodB
        var nin = form.find('[name="' + prefix + '_nin"]').val().trim();
        var ninDoc = form.find('[name="' + prefix + '_nin_document"]').val().trim();

        return nin !== "" || ninDoc !== "";
    }, 'Enter either NIN or upload document.');


    /* --------------- VALIDATE PRODUCT A DETAILS --------------- */
    $('#frm_prodA_details').validate({
        ignore: [],
        rules: {
            prodA_first_name: { required: true },
            prodA_surname: { required: true },
            prodA_gender: { required: true },
            prodA_dob: { required: true },
            prodA_address: { required: true },
            prodA_occupations_id: { required: true },
            prodA_relationships_id: { required: true },
            prodA_nin: { 
                digits: true,
                minlength: 11,
                maxlength: 11
            },
        },
        messages: {
            prodA_first_name: { required: 'This field is required.' },
            prodA_surname: { required: 'This field is required.' },
            prodA_gender: { required: 'This field is required.' },
            prodA_dob: { required: 'This field is required.' },
            prodA_address: { required: 'This field is required.' },
            prodA_occupations_id: { required: 'This field is required.' },
            prodA_relationships_id: { required: 'This field is required.' },
            prodA_nin: { 
                digits: 'Phone number must contain digits only.',
                minlength: 'Phone number must be exactly 11 digits.',
                maxlength: 'Phone number must be exactly 11 digits.'
            },
        },
        errorPlacement: function(error, element) {
            $('#error_' + element.attr('name')).html(error);
        }
    });
    /* --------------- VALIDATE PRODUCT A DETAILS --------------- */

    /* --------------- VALIDATE PRODUCT B DETAILS --------------- */
    $('#frm_prodB_details').validate({
        ignore: [],
        rules: {
            prodB_first_name: { required: true },
            prodB_surname: { required: true },
            prodB_gender: { required: true },
            prodB_dob: { required: true },
            prodB_address: { required: true },
            prodB_occupations_id: { required: true },
            prodB_relationships_id: { required: true },
            prodB_nin: { 
                digits: true,
                minlength: 11,
                maxlength: 11
            },
        },
        messages: {
            prodB_first_name: { required: 'This field is required.' },
            prodB_surname: { required: 'This field is required.' },
            prodB_gender: { required: 'This field is required.' },
            prodB_dob: { required: 'This field is required.' },
            prodB_address: { required: 'This field is required.' },
            prodB_occupations_id: { required: 'This field is required.' },
            prodB_relationships_id: { required: 'This field is required.' },
            prodB_nin: { 
                digits: 'Phone number must contain digits only.',
                minlength: 'Phone number must be exactly 11 digits.',
                maxlength: 'Phone number must be exactly 11 digits.'
            },
        },
        errorPlacement: function(error, element) {
            $('#error_' + element.attr('name')).html(error);
        }
    });
    /* --------------- VALIDATE PRODUCT B DETAILS --------------- */

    /* --------------- VALIDATE PRODUCT C DETAILS --------------- */
 $('#frm_prodC_details').validate({
    ignore: [],
    
    errorElement: 'label', // âœ… IMPORTANT

    rules: {
        prodC_tasks_types_id: { required: true },
        prodC_task: { required: true },
        prodC_task_date: { required: true },
        prodC_description: { required: true },
        prodC_contact_person_name: { required: true },
        prodC_person_phone: {
            required: true,
            digits: true,
            minlength: 10,
            maxlength: 15
        },
        prodC_acknowledged: { required: true },
    },

    messages: {
        prodC_person_phone: {
            required: 'This field is required.',
            digits: 'Phone must contain digits only.',
            minlength: 'Enter at least 10 digits.',
            maxlength: 'Max 15 digits allowed.'
        }
    },

    errorPlacement: function (error, element) {
        let name = element.attr('name');

        // âœ… Place inside your custom span
        $('#error_' + name).html(error);
    },

    success: function (label, element) {
        // âœ… Remove error when valid
        let name = $(element).attr('name');
        $('#error_' + name).html('');
    }
});
    /* --------------- VALIDATE PRODUCT C DETAILS --------------- */

    /* --------------- SUBMIT PRODUCT A DETAILS --------------- */
    $('#frm_prodA_details').on('submit', function (event) {
        event.preventDefault();

        const $buyBtn = $('#btnBuyNowA');

        if ($('#frm_prodA_details').valid()) {  
            disableBuyNow($buyBtn);

            var products_id         = $('#prodA_products_id').val();
            var cover_duration      = $('#prodA_cover_duration').val();
            var cover_start_date    = $('#prodA_cover_start_date').val();
            var cover_end_date      = $('#prodA_cover_end_date').val();
            var product_type        = 'A'; 
            var first_name          = $('#prodA_first_name').val();
            var surname             = $('#prodA_surname').val();
            var gender              = $('#prodA_gender').val();
            var date_of_birth       = $('#prodA_dob').val().split('-').reverse().join('-');
            var address             = $('#prodA_address').val();
            var occupations_id      = $('#prodA_occupations_id').val();
            var relationships_id    = $('#prodA_relationships_id').val();
            var nin                 = $('#prodA_nin').val();
            var nin_document        = $('#prodA_nin_document_string').val();

            // console.log(products_id);
            // console.log(cover_duration);
            // console.log(cover_start_date);
            // console.log(cover_end_date);
            // console.log(first_name);
            // console.log(surname);
            // console.log(gender);
            // console.log(date_of_birth);
            // console.log(address);
            // console.log(occupations_id);
            // console.log(relationships_id);
            // console.log(nin);
            // console.log(nin_document);

            /* AJAX API CALL */
            var settings = {
                "url": "{{ rtrim(config('app.api_url'), '/') }}/purchase_product",
                "method": "POST",
                "timeout": 0,
                "headers": {
                    "Content-Type": "application/json"
                },
                "data": JSON.stringify({
                    "users_customers_id": users_customers_id,
                    "products_id": products_id,
                    "cover_duration": cover_duration,
                    "cover_start_date": cover_start_date,
                    "cover_end_date": cover_end_date,
                    "type": product_type,
                    "first_name" : first_name,
                    "surname": surname,
                    "gender": gender,
                    "date_of_birth": date_of_birth,
                    "address": address,
                    "occupations_id": occupations_id,
                    "relationships_id": relationships_id,
                    "nin": nin,
                    "nin_document": nin_document
                }),
            };
            $.ajax(settings).done(function (response) {
              if (response.status === 'success') {
                  $.ajax({
                      url: "{{ rtrim(config('app.api_url'), '/') }}/stripe/initiate-payment",
                      method: "POST",
                      timeout: 0,
                      headers: { "Content-Type": "application/json" },
                      data: JSON.stringify({ products_purchases_id: response.data.products_purchases_id }),
                      success: function (stripeRes) {
                          if (stripeRes.status === 'success' && stripeRes.checkout_url) {
                              window.location.href = stripeRes.checkout_url;
                          } else {
                              toastr.error(stripeRes.message || 'Payment setup failed. Please contact support.');
                              enableBuyNow($buyBtn);
                          }
                      },
                      error: function () {
                          toastr.error('Payment service unavailable. Please try again later.');
                          enableBuyNow($buyBtn);
                      }
                  });
              } else {
                  toastr.error(response.message || 'Something went wrong.');
                  enableBuyNow($buyBtn);
              }
            });
            /* AJAX API CALL */
        }
    });
    /* --------------- SUBMIT PRODUCT A DETAILS --------------- */

    /* --------------- SUBMIT PRODUCT B DETAILS --------------- */
    $('#frm_prodB_details').on('submit', function (event) {
        event.preventDefault();
        const $buyBtn = $('#btnBuyNowB');
        if ($('#frm_prodB_details').valid()) { 
            disableBuyNow($buyBtn); 
            var products_id         = $('#prodB_products_id').val();
            var cover_duration      = $('#prodB_cover_duration').val();
            var cover_start_date    = $('#prodB_cover_start_date').val();
            var cover_end_date      = $('#prodB_cover_end_date').val();
            var product_type        = 'B'; 
            var first_name          = $('#prodB_first_name').val();
            var surname             = $('#prodB_surname').val();
            var gender              = $('#prodB_gender').val();
            var date_of_birth       = $('#prodB_dob').val().split('-').reverse().join('-');
            var address             = $('#prodB_address').val();
            var occupations_id      = $('#prodB_occupations_id').val();
            var relationships_id    = $('#prodB_relationships_id').val();
            var nin                 = $('#prodB_nin').val();
            var nin_document        = $('#prodB_nin_document_string').val();

            // console.log(products_id);
            // console.log(cover_duration);
            // console.log(cover_start_date);
            // console.log(cover_end_date);
            // console.log(first_name);
            // console.log(surname);
            // console.log(gender);
            // console.log(date_of_birth);
            // console.log(address);
            // console.log(occupations_id);
            // console.log(relationships_id);
            // console.log(nin);
            // console.log(nin_document);

            /* AJAX API CALL */
            var settings = {
                "url": "{{ rtrim(config('app.api_url'), '/') }}/purchase_product",
                "method": "POST",
                "timeout": 0,
                "headers": {
                    "Content-Type": "application/json"
                },
                "data": JSON.stringify({
                    "users_customers_id": users_customers_id,
                    "products_id": products_id,
                    "cover_duration": cover_duration,
                    "cover_start_date": cover_start_date,
                    "cover_end_date": cover_end_date,
                    "type": product_type,
                    "first_name" : first_name,
                    "surname": surname,
                    "gender": gender,
                    "date_of_birth": date_of_birth,
                    "address": address,
                    "occupations_id": occupations_id,
                    "relationships_id": relationships_id,
                    "nin": nin,
                    "nin_document": nin_document
                }),
            };
            $.ajax(settings).done(function (response) {
              if (response.status === 'success') {
                  $.ajax({
                      url: "{{ rtrim(config('app.api_url'), '/') }}/stripe/initiate-payment",
                      method: "POST",
                      timeout: 0,
                      headers: { "Content-Type": "application/json" },
                      data: JSON.stringify({ products_purchases_id: response.data.products_purchases_id }),
                      success: function (stripeRes) {
                          if (stripeRes.status === 'success' && stripeRes.checkout_url) {
                              window.location.href = stripeRes.checkout_url;
                          } else {
                              toastr.error(stripeRes.message || 'Payment setup failed. Please contact support.');
                              enableBuyNow($buyBtn);
                          }
                      },
                      error: function () {
                          toastr.error('Payment service unavailable. Please try again later.');
                          enableBuyNow($buyBtn);
                      }
                  });
              } else {
                  toastr.error(response.message || 'Something went wrong.');
                  enableBuyNow($buyBtn);
              }
            });
        }
    });
    /* --------------- SUBMIT PRODUCT B DETAILS --------------- */

    /* --------------- SUBMIT PRODUCT C DETAILS --------------- */
    $('#frm_prodC_details').on('submit', function (event) {
        event.preventDefault();
        const $buyBtn = $('#btnBuyNowC');
        if ($('#frm_prodC_details').valid()) { 
            disableBuyNow($buyBtn);
            var products_id        = $('#prodC_products_id').val();
            var cover_duration     = 'Yearly';
            var cover_start_date   = $('#prodC_cover_start_date').val();
            var cover_end_date     = $('#prodC_cover_end_date').val();
            var product_type       = 'C'; 
            var tasks_types_id     = $('#prodC_tasks_types_id').val();
            var task               = $('#prodC_task').val();
            var task_date          = $('#prodC_task_date').val().split('-').reverse().join('-');
            var description        = $('#prodC_description').val();
            var recipient_name     = $('#prodC_contact_person_name').val();
            var recipient_phone    = $('#prodC_person_phone').val();

            /* AJAX API CALL */
            var settings = {
                "url": "{{ rtrim(config('app.api_url'), '/') }}/purchase_product",
                "method": "POST",
                "timeout": 0,
                "headers": {
                    "Content-Type": "application/json"
                },
                "data": JSON.stringify({
                    "users_customers_id": users_customers_id,
                    "products_id": products_id,
                    "cover_duration": cover_duration,
                    "cover_start_date": cover_start_date,
                    "cover_end_date": cover_end_date,
                    "type": product_type,
                    "tasks_types_id": tasks_types_id,
                    "task": task,
                    "task_date": task_date,
                    "description": description,
                    "recipient_name": recipient_name,
                    "recipient_phone": recipient_phone
                }),
            };
            $.ajax(settings).done(function (response) {
              if (response.status === 'success') {
                  $.ajax({
                      url: "{{ rtrim(config('app.api_url'), '/') }}/stripe/initiate-payment",
                      method: "POST",
                      timeout: 0,
                      headers: { "Content-Type": "application/json" },
                      data: JSON.stringify({ products_purchases_id: response.data.products_purchases_id }),
                      success: function (stripeRes) {
                          if (stripeRes.status === 'success' && stripeRes.checkout_url) {
                              window.location.href = stripeRes.checkout_url;
                          } else {
                              toastr.error(stripeRes.message || 'Payment setup failed. Please contact support.');
                              enableBuyNow($buyBtn);
                          }
                      },
                      error: function () {
                          toastr.error('Payment service unavailable. Please try again later.');
                          enableBuyNow($buyBtn);
                      }
                  });
              } else {
                  toastr.error(response.message || 'Something went wrong.');
                  enableBuyNow($buyBtn);
              }
            });
        }
    });
    /* --------------- SUBMIT PRODUCT C DETAILS --------------- */

    /* --------------- VALIDATE CLAIM PRODUCT --------------- */
    $('#frm_claim_product').validate({
        ignore: [],
        rules: {
            products_purchases_id: {
                required: true
            },
            claim_date: {
                required: true
            },
            claim_notes: {
                required: true
            },
            acknowledged: {
                required: true 
            },
            claim_image1: {
                required: true 
            },
            claim_image2: {
                required: true 
            },
            claim_image3: {
                required: true 
            }
        },
        messages: {
            products_purchases_id: {
                required: 'This field is required.'
            },
            claim_date: {
                required: 'This field is required.'
            },
            claim_notes: {
                required: 'This field is required.'
            },
            acknowledged: {
                required: 'You must acknowledge this before submitting.'
            },
            claim_image1: {
                required: 'This field is required.'
            },
            claim_image2: {
                required: 'This field is required.'
            },
            claim_image3: {
                required: 'This field is required.'
            }
        },
        errorPlacement: function (error, element) {
            if (element.attr('name') == 'products_purchases_id') {
                $('#error_products_purchases_id').html(error);
            } else if (element.attr('name') == 'claim_date') {
                $('#error_claim_date').html(error);
            } else if (element.attr('name') == 'claim_notes') {
                $('#error_claim_notes').html(error);
            } else if (element.attr('name') == 'acknowledged') {
                $('#error_acknowledged').html(error);
            } else if (element.attr('name') == 'claim_image1') {
                $('#error_claim_image1').html(error);
            } else if (element.attr('name') == 'claim_image2') {
                $('#error_claim_image2').html(error);
            } else if (element.attr('name') == 'claim_image3') {
                $('#error_claim_image3').html(error);
            }
        }
    });
    /* --------------- VALIDATE CLAIM PRODUCT --------------- */

    /* --------------- SUBMIT CLAIM PRODUCT --------------- */
    $('#frm_claim_product').on('submit', function (event) {
        event.preventDefault();
        if ($('#frm_claim_product').valid()) { 
            var products_purchases_id   = $('#products_purchases_id').val();
            var claim_date              = $('#claim_date').val().split('-').reverse().join('-');
            var claim_notes             = $('#claim_notes').val();
            var claim_image1            = $('#claim_image1_string').val();
            var claim_image2            = $('#claim_image2_string').val();
            var claim_image3            = $('#claim_image3_string').val();

            /* AJAX API CALL */
            var settings = {
                "url": "{{ rtrim(config('app.api_url'), '/') }}/claim_purchased_product",
                "method": "POST",
                "timeout": 0,
                "headers": {
                    "Content-Type": "application/json"
                },
                "data": JSON.stringify({
                    "users_customers_id": users_customers_id,
                    "products_purchases_id": products_purchases_id,
                    "date": claim_date,
                    "description": claim_notes,
                    "image1": claim_image1,
                    "image2": claim_image2,
                    "image3": claim_image3
                }),
            };
            $.ajax(settings).done(function (response) {
                if (response.status == 'success') {
                    toastr.success('Product claim submitted successfully.');
                } else {
                    toastr.error(response.message);
                }
                setTimeout(function() {
                    location.reload();
                }, 500);
            });
            /* AJAX API CALL */
        }
    });
    /* --------------- SUBMIT CLAIM PRODUCT --------------- */

    /* --------------- ACTIVATE TARGET TAB --------------- */
    function activate_target_tab() {
        // get hash from URL
        var hash = window.location.hash;
            
        if (hash === '#pills-my-offers') {
            // find tab corresponding to hash and activate it
            var tabToActivate = new bootstrap.Tab(document.querySelector('button[data-bs-target="' + hash + '"]'));
            tabToActivate.show();
        }
    }
    /* --------------- ACTIVATE TARGET TAB --------------- */
    
    /* --------------- GET MONTH NAME --------------- */
    function get_month_name(month_number) {
        var months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'June', 'July', 'Aug', 'Sept', 'Oct', 'Nov', 'Dec'];
        return months[month_number - 1];
    }
    /* --------------- GET MONTH NAME --------------- */

    /* --------------- GET TODAY DATE --------------- */
    function get_today_date() {
        var date   = new Date();
        var dd     = date.getDate(); 
        var mm     = date.getMonth() + 1; 
        var yyyy   = date.getFullYear();

        var current_date = dd + '-' + get_month_name(mm) + '-' + yyyy;
        return current_date;
    }
    /* --------------- GET TODAY DATE --------------- */

    /* --------------- SCROLL TO BOTTOM --------------- */
    function scroll_to_bottom() {
        const messagesSec     = document.getElementById('messages');
        messagesSec.scrollTop = messagesSec.scrollHeight;
    }
    /* --------------- SCROLL TO BOTTOM --------------- */
</script>