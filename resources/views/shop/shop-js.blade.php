{{-- Bootstrap --}}
<script src="{{ asset('public/client/assets/js/bootstrap.min.js') }}"></script>

{{-- Jquery --}}
<script src="{{ asset('public/client/assets/js/jquery.min.js') }}"></script>

<script src="{{ asset('public/client/assets/js/swiper-bundle.min.js') }}"></script>

{{-- Toastr --}}
<script src="{{ asset('public/admin/assets/vendor/js/toastr.min.js') }}"></script>

<!-- owl Carousel slider -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/owl.carousel.min.js"></script>

{{-- Custom JS --}}
<script src="{{ asset('public/client/assets/js/custom.js') }}"></script>


{{-- Masonary --}}
<script src="{{ asset('public/client/assets/js/lightbox.js') }}"></script>

<script type="text/javascript" src="//translate.google.com/translate_a/element.js"></script>


<script src="{{ asset('public/client/assets/js/jquery.flipster.min.js') }}"></script>


{{--  <script src="https://cdn.jsdelivr.net/gh/kenwheeler/slick@1.8.0/slick/slick.min.js"></script>  --}}
<script type="text/javascript" src="https://cdn.jsdelivr.net/jquery.slick/1.4.1/slick.min.js"></script>



<!-- Bootstrap JS, Popper.js, and jQuery -->

<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"
    integrity="sha384-IQsoLXl5PILFhosVNubq5LC7Qb9DXgDA9i+tQ8Zj3iwWAwPtgFTxbJ8NT4GN1R8p" crossorigin="anonymous">
</script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js"
    integrity="sha384-cVKIPhGWiC2Al4u+LWgxfKTRIcfu0JTxR+EQDz/bgldoEyl4H0zUF0QKbrJ0EcQF" crossorigin="anonymous">
</script>


{{-- Common JS Functions --}}
<script type="text/javascript">
    // Toastr Settings
    toastr.options = {
        "closeButton": true,
        "progressBar": true,
        "positionClass": "toast-bottom-right",
        timeOut: 4000
    }

    // Function for Change Language
    function changeLanguage(langCode) {

        $.ajax({
            type: "POST",
            url: "{{ route('shop.locale.change') }}",
            data: {
                "_token": "{{ csrf_token() }}",
                "lang_code": langCode,
            },
            dataType: "JSON",
            success: function(response) {
                if (response.success == 1) {
                    location.reload();
                }
            }
        });
    }

    // Layput 2 Search home page
    $('#openSearch').on('click', function() {
        $("#search_input").removeClass("d-none");
        $("#openSearch").addClass("d-none");
        $("#closeSearch").removeClass("d-none");
    });

    $('#closeSearch').on('click', function() {
        $("#search_input").addClass('d-none');
        $("#openSearch").removeClass('d-none');
        $("#closeSearch").addClass('d-none');
        $('#search_layout').val('');
        $('#search_btn').click();
    });

    // Layout 2 Search inner page
    $('.openSearchInnerPage').on('click', function() {
        $(".search_input_inner").removeClass("d-none");
        $(".openSearchInnerPage").addClass("d-none");
        $(".closeSearchInnerPage").removeClass("d-none");
        window.scrollTo({
            top: 0,
            behavior: 'smooth'
        });
    });

    $('.closeSearchInnerPage').on('click', function() {
        $(".search_input_inner").addClass('d-none');
        $(".openSearchInnerPage").removeClass('d-none');
        $(".closeSearchInnerPage").addClass('d-none');
        $('.search_layout').val('');
        $('.src_btn_inner').click();
        location.reload();

    });



    // Search Toggle
    $('.openSearchBox').on('click', function() {

        $(".search_input").addClass("d-block");
        $('.openSearchBox').addClass("d-none");
        $('.closeSearchBox').removeClass("d-none");
    });

    $('.closeSearchBox').on('click', function() {
        $(".closeSearchBox").addClass("d-none");
        $('.openSearchBox').removeClass("d-none");
        $(".search_input").removeClass("d-block");
        $('#search').val('');
        $('#search').trigger('keyup');
    });

    // Open & Close Language Sidebar
    $('.lang_bt').on('click', function() {
        $(".lang_inr").addClass("sidebar");
    });
    $('.close_bt').on('click', function() {
        $(".lang_inr").removeClass("sidebar");
    });

    $(window).scroll(function() {

        var scroll = $(window).scrollTop();
        var header = $('.header_preview');
    });

    // Function for Get Item Details
    function getItemDetails(id, shopID) {
        // $('#itemDetailsModal').modal('show');
        $('#itemDetailsModal #item_dt_div').html('');

        $.ajax({
            type: "POST",
            url: "{{ route('items.get.details') }}",
            data: {
                "_token": "{{ csrf_token() }}",
                "item_id": id,
                "shop_id": shopID,
            },
            dataType: "json",
            success: function(response) {
                if (response.success == 1) {
                    $('#itemDetailsModal #item_dt_div').html('');
                    $('#itemDetailsModal #item_dt_div').append(response.data);
                    $('#itemDetailsModal').modal('show');
                    updatePrice();
                } else {
                    toastr.error(response.message);
                }
            }
        });
    }

    // openRatingmodel
    function openRatingModel(id) {
        $('#itemReviewModal #review_dt_div').html('');
        $.ajax({
            type: "POST",
            url: "{{ route('item.review') }}",
            data: {
                "_token": "{{ csrf_token() }}",
                "item_id": id,
            },
            dataType: "json",
            success: function(response) {
                if (response.success == 1) {
                    $('#itemReviewModal #review_dt_div').html('');
                    $('#itemReviewModal #review_dt_div').append(response.data);
                    $('#itemReviewModal').modal('show');
                } else {
                    toastr.error(response.message);
                }
            }
        });

    }

    // openServiceRatingmodel
    function openServiceRatingmodel(id) {

        $('#itemReviewModal #review_dt_div').html('');
        $.ajax({
            type: "POST",
            url: "{{ route('service.review') }}",
            data: {
                "_token": "{{ csrf_token() }}",
                "shop_id": id,
            },
            dataType: "json",
            success: function(response) {
                if (response.success == 1) {
                    $('#itemReviewModal #review_dt_div').html('');
                    $('#itemReviewModal #review_dt_div').append(response.data);
                    $('#itemReviewModal').modal('show');
                } else {
                    toastr.error(response.message);
                }
            }
        });
    }

    function openMenu(id) {
        $('#menuModal #menu_dt_div').html('');
        $.ajax({
            type: "POST",
            url: "{{ route('mobile.item.category') }}",
            data: {
                "_token": "{{ csrf_token() }}",
                "shop_id": id,
            },
            dataType: "json",
            success: function(response) {
                if (response.success == 1) {
                    $('#menuModal #menu_dt_div').html('');
                    $('#menuModal #menu_dt_div').append(response.data);
                    $('#menuModal').modal('show');
                } else {
                    toastr.error(response.message);
                }
            }
        });

    }


    // openWaiter
    function openWaiter(id) {
        $('#callWaiterModal #waiter_dt_div').html('');
        $.ajax({
            type: "POST",
            url: "{{ route('call.waiter') }}",
            data: {
                "_token": "{{ csrf_token() }}",
                "shop_id": id,
            },
            dataType: "json",
            success: function(response) {
                if (response.success == 1) {
                    $('#callWaiterModal #waiter_dt_div').html('');
                    $('#callWaiterModal #waiter_dt_div').append(response.data);
                    $('#callWaiterModal').modal('show');
                } else {
                    toastr.error(response.message);
                }
            }
        });

    }



    // Update Price
    function updatePrice() {
        var base_price = 0.00;
        var radio_price = 0.00;
        var checkbox_price = 0.00;
        var def_currency = $('#def_currency').val();
        const option_ids = JSON.parse($('#option_ids').val());
        let quantity = $('#itemDetailsModal #quantity').val();


        if ($('#itemDetailsModal input[name="base_price"]:checked').val() != undefined) {
            base_price = $('#itemDetailsModal input[name="base_price"]:checked').val();
        }

        if (option_ids.length > 0) {
            $.each(option_ids, function(opt_key, option_id) {
                var inner_radio = 0.00;
                if ($('#itemDetailsModal input[name="option_price_radio_' + opt_key + '"]:checked').val()) {
                    inner_radio = $('#itemDetailsModal input[name="option_price_radio_' + opt_key +
                        '"]:checked').val();
                }

                var checkbox_array = $('input[name="option_price_checkbox_' + opt_key + '"]:checked').map(
                    function() {
                        if (this.value) {
                            checkbox_price += parseFloat(this.value);
                        }
                    }).get();
                radio_price += parseFloat(inner_radio);
            });
        }

        base_price = (parseFloat(base_price) + parseFloat(radio_price) + parseFloat(checkbox_price)) * parseInt(
            quantity);
        base_price = base_price.toFixed(2);

        // Get Total with Currency
        $.ajax({
            type: "POST",
            url: "{{ route('total.with.currency') }}",
            data: {
                '_token': "{{ csrf_token() }}",
                'total': base_price,
                'currency': def_currency,
            },
            dataType: "JSON",
            success: function(response) {
                if (response.success == 1) {

                    $('#itemDetailsModal #total_price').html('');
                    $('#itemDetailsModal #total_price').append(response.total);
                    $('#itemDetailsModal #total_amount').val(base_price);
                }
            }
        });

    }

    function changeSerive(value) {
        if (value == '0') {
            $(".room-dropdown").addClass('d-none');
            $(".table-dropdown").removeClass('d-none');
        } else if (value == '1') {
            $(".room-dropdown").removeClass('d-none');
            $(".table-dropdown").addClass('d-none');
        } else {
            $(".room-dropdown").addClass('d-none');
            $(".table-dropdown").addClass('d-none');
        }
    }

    function toggleDescribeUs(val) {
        if (val) {
            $('.describe_to_us').removeClass('d-none');
        } else {
            $('.describe_to_us').addClass('d-none');
        }
    }

    //     // Hide show Room or Table DropDown
    //     $("#location").on("change",function(){
    //        var location = $(this).val();

    //        if(location=='0'){
    //            $(".room-dropdown").addClass('d-none');
    //            $(".table-dropdown").removeClass('d-none');
    //        }else{
    //            $(".room-dropdown").removeClass('d-none');
    //            $(".table-dropdown").addClass('d-none');
    //        }
    //    })

    // Add to Cart
    function addToCart(itemId) {
        const delivery_schedule = @json($delivery_schedule);
        if (delivery_schedule == 1) {
            const option_ids = JSON.parse($('#option_ids').val());
            let cart_data = {};
            let categories_data = {};

            // Quantity
            cart_data['quantity'] = $('#itemDetailsModal #quantity').val();
            cart_data['total_amount'] = $('#itemDetailsModal #total_amount').val();
            cart_data['total_amount_text'] = $('#itemDetailsModal #total_price').html();
            cart_data['item_id'] = $('#itemDetailsModal #item_id').val();
            cart_data['shop_id'] = $('#itemDetailsModal #shop_id').val();
            cart_data['currency'] = $('#itemDetailsModal #def_currency').val();
            cart_data['option_id'] = $('#itemDetailsModal input[name="base_price"]:checked').attr('option-id');

            if (option_ids.length > 0) {
                $.each(option_ids, function(ids_key, option_id) {
                    var options = [];
                    // CheckBox Value
                    $('#itemDetailsModal input[name="option_price_checkbox_' + ids_key + '"]:checked').map(
                        function() {
                            if (this.value) {
                                var check_id = this.id;
                                var attr_val = $('#' + check_id).attr('opt_price_id');
                                options.push(attr_val);
                            }
                        }).get();

                    if (options.length > 0) {
                        categories_data[option_id] = options;
                    }

                    // Radio Button Value
                    if ($('#itemDetailsModal input[name="option_price_radio_' + ids_key + '"]:checked').val()) {
                        categories_data[option_id] = $('#itemDetailsModal input[name="option_price_radio_' +
                            ids_key + '"]:checked').attr('opt_price_id');
                    }
                });
                cart_data['categories_data'] = JSON.stringify(categories_data);
            }

            $.ajax({
                type: "POST",
                url: "{{ route('shop.add.to.cart') }}",
                data: {
                    '_token': "{{ csrf_token() }}",
                    'cart_data': cart_data,
                },
                dataType: "JSON",
                success: function(response) {
                    if (response.success == 1) {
                        $('#itemDetailsModal #item_dt_div').html('');
                        $('#itemDetailsModal').modal('hide');
                        toastr.success(response.message);
                        setTimeout(() => {
                            location.reload();
                        }, 1200);
                    } else {
                        $('#itemDetailsModal #item_dt_div').html('');
                        $('#itemDetailsModal').modal('hide');
                        toastr.error(response.message);
                    }
                }
            });
        } else {
            $('#itemDetailsModal').modal('hide');
            $('#storeCloseModal').modal('show');
        }


    }

    // Update to Cart
    function updateToCart(itemID, priceID, item_key) {

        const option_ids = JSON.parse($('#option_ids').val());
        let cart_data = {};
        let categories_data = {};

        // Quantity
        cart_data['quantity'] = $('#itemDetailsModal #quantity').val();
        cart_data['total_amount'] = $('#itemDetailsModal #total_amount').val();
        cart_data['total_amount_text'] = $('#itemDetailsModal #total_price').html();
        cart_data['item_id'] = $('#itemDetailsModal #item_id').val();
        cart_data['shop_id'] = $('#itemDetailsModal #shop_id').val();
        cart_data['currency'] = $('#itemDetailsModal #def_currency').val();
        cart_data['option_id'] = $('#itemDetailsModal input[name="base_price"]:checked').attr('option-id');

        if (option_ids.length > 0) {
            $.each(option_ids, function(ids_key, option_id) {
                var options = [];
                // CheckBox Value
                $('#itemDetailsModal input[name="option_price_checkbox_' + ids_key + '"]:checked').map(
                    function() {
                        if (this.value) {
                            var check_id = this.id;
                            var attr_val = $('#' + check_id).attr('opt_price_id');
                            options.push(attr_val);
                        }
                    }).get();

                if (options.length > 0) {
                    categories_data[option_id] = options;
                }

                // Radio Button Value
                if ($('#itemDetailsModal input[name="option_price_radio_' + ids_key + '"]:checked').val()) {
                    categories_data[option_id] = $('#itemDetailsModal input[name="option_price_radio_' +
                        ids_key + '"]:checked').attr('opt_price_id');
                }
            });
            cart_data['categories_data'] = JSON.stringify(categories_data);
        }

        $.ajax({
            type: "POST",
            url: "{{ route('shop.item.update.cart') }}",
            data: {
                '_token': "{{ csrf_token() }}",
                'cart_data': cart_data,
                'itemID': itemID,
                'price_id': priceID,
                'item_key': item_key,

            },
            dataType: "JSON",
            success: function(response) {
                if (response.success == 1) {
                    $('#itemDetailsModal #item_dt_div').html('');
                    $('#itemDetailsModal').modal('hide');
                    toastr.success(response.message);
                    setTimeout(() => {
                        location.reload();
                    }, 1200);
                } else {
                    $('#itemDetailsModal #item_dt_div').html('');
                    $('#itemDetailsModal').modal('hide');
                    toastr.error(response.message);
                }
            }
        });


    }


    // Quantity Increment Decrement using +,- Button
    function QuntityIncDec(ele) {
        var fieldName = $(ele).attr('data-field');
        var type = $(ele).attr('data-type');
        var input = $("input[name='" + fieldName + "']");
        var currentVal = parseInt(input.val());
        var name = $(input).attr('name');

        if (!isNaN(currentVal)) {
            if (type == 'minus') {
                if (currentVal > input.attr('min')) {
                    input.val(currentVal - 1).change();
                }

                if (parseInt(input.val()) == input.attr('min')) {
                    $(ele).attr('disabled', true);
                }
            } else if (type == 'plus') {
                if (currentVal < input.attr('max')) {
                    input.val(currentVal + 1).change();
                }

                if (parseInt(input.val()) == input.attr('max')) {
                    $(ele).attr('disabled', true);
                }
            }
        } else {
            input.val(1);
        }

        var changedVal = parseInt(input.val());
        var minValue = parseInt($(input).attr('min'));
        var maxValue = parseInt($(input).attr('max'));

        if (changedVal > minValue) {
            $(".btn-number[data-type='minus'][data-field='" + name + "']").removeAttr('disabled');
        }

        if (changedVal < maxValue) {
            $(".btn-number[data-type='plus'][data-field='" + name + "']").removeAttr('disabled');
        }

        updatePrice();
    }

    // Quantity Increment Decrement using Onchange
    function QuntityIncDecOnChange(ele) {
        var minValue = parseInt($(ele).attr('min'));
        var maxValue = parseInt($(ele).attr('max'));
        var valueCurrent = parseInt($(ele).val());
        var name = $(ele).attr('name');

        if (!$.isNumeric(valueCurrent)) {
            alert('Sorry, Please Enter Valid Quantity Number');
            $(ele).val(1);
            updatePrice();
            return false;
        }

        if (valueCurrent >= minValue) {
            $(".btn-number[data-type='minus'][data-field='" + name + "']").removeAttr('disabled')
        } else {
            alert('Sorry, the minimum value was reached');
            $(ele).val(1);
        }

        if (valueCurrent <= maxValue) {
            $(".btn-number[data-type='plus'][data-field='" + name + "']").removeAttr('disabled')
        } else {
            alert('Sorry, the maximum value was reached');
            $(ele).val(1);
        }
        updatePrice();
    }

    // Function for Submit Item Review & Rating
    function submitItemReview() {

        // Clear all Toastr Messages
        toastr.clear();

        var myFormData = new FormData(document.getElementById('reviewForm'));

        $.ajax({
            type: "POST",
            url: "{{ route('send.item.review') }}",
            data: myFormData,
            dataType: "JSON",
            contentType: false,
            cache: false,
            processData: false,
            beforeSend: function() {
                $('#btn-review').hide();
                $('#load-btn-review').show();
            },
            success: function(response) {
                if (response.success == 1) {
                    $('#btn-review').show();
                    $('#load-btn-review').hide();
                    $('#reviewForm').trigger("reset");
                    $('#itemReviewModal').modal('hide');
                    // $('#item_review').val('');
                    // $("input[name='rating']").removeAttr('checked');
                    // $("#star3").prop('checked', true);
                    toastr.success(response.message);
                } else {
                    toastr.error(response.message);
                    $('#itemDetailsModal').modal('hide');
                    $('#itemReviewModal').modal('hide');
                }
            },
            error: function(response) {
                if (response.responseJSON.errors) {
                    $('#btn-review').show();
                    $('#load-btn-review').hide();

                    $.each(response.responseJSON.errors, function(i, error) {
                        toastr.error(error);
                    });
                }
            }
        });
    }

    // Function for Submit Service Review & Rating
    function submitServiceReview() {
        // Clear all Toastr Messages
        toastr.clear();

        var myFormData = new FormData(document.getElementById('reviewForm'));

        $.ajax({
            type: "POST",
            url: "{{ route('send.service.review') }}",
            data: myFormData,
            dataType: "JSON",
            contentType: false,
            cache: false,
            processData: false,
            beforeSend: function() {
                $('#btn-review').hide();
                $('#load-btn-review').show();
            },
            success: function(response) {
                if (response.success == 1) {
                    $('#btn-review').show();
                    $('#load-btn-review').hide();
                    $('#reviewForm').trigger("reset");
                    $('#itemReviewModal').modal('hide');
                    // $('#item_review').val('');
                    // $("input[name='rating']").removeAttr('checked');
                    // $("#star3").prop('checked', true);
                    toastr.success(response.message);
                } else {
                    toastr.error(response.message);
                    $('#itemDetailsModal').modal('hide');
                    $('#itemReviewModal').modal('hide');
                }
            },
            error: function(response) {
                if (response.responseJSON.errors) {
                    $('#btn-review').show();
                    $('#load-btn-review').hide();

                    $.each(response.responseJSON.errors, function(i, error) {
                        toastr.error(error);
                    });
                }
            }
        });
    }

    // Function for Submit Call Waiter
    function submitCallWaiter() {
        // Clear all Toastr Messages
        toastr.clear();

        var myFormData = new FormData(document.getElementById('callWaiterForm'));

        $.ajax({
            type: "POST",
            url: "{{ route('send.call.waiter') }}",
            data: myFormData,
            dataType: "JSON",
            contentType: false,
            cache: false,
            processData: false,
            success: function(response) {
                if (response.success == 1) {
                    $('#callWaiterForm').trigger("reset");
                    $('#callWaiterModal').modal('hide');
                    // $('#item_review').val('');
                    // $("input[name='rating']").removeAttr('checked');
                    // $("#star3").prop('checked', true);
                    toastr.success(response.message);
                } else {
                    toastr.error(response.message);
                    // $('#itemDetailsModal').modal('hide');
                    $('#callWaiterModal').modal('hide');
                }
            },
            error: function(response) {
                if (response.responseJSON.errors) {
                    $.each(response.responseJSON.errors, function(i, error) {
                        toastr.error(error);
                    });
                }
            }
        });


    }




    // Auto Translate
    $('#auto_translate').on('change', function() {
        var isChecked = $(this).prop('checked');
        if (isChecked == true) {
            $('#translated_languages').show();
            new google.translate.TranslateElement({
                pageLanguage: 'en'
            }, 'translated_languages');
            $('.goog-te-combo').addClass('form-select');
        } else {
            $('#translated_languages').hide();
        }
    });

    // Auto Translate
    $('#auto_translate_layout_two').on('change', function() {
        var isChecked = $(this).prop('checked');
        if (isChecked == true) {
            $('#translated_languages_layout_two').show();
            new google.translate.TranslateElement({
                pageLanguage: 'en'
            }, 'translated_languages_layout_two');
            $('.goog-te-combo').addClass('form-select');
        } else {
            $('#translated_languages_layout_two').hide();
        }
    });





    $("#cart-box").on("click", function() {
        if ($("#current-order").hasClass("openCartDetails")) {
            $("#current-order").addClass("closeCartDetails");
            $("#current-order").removeClass("openCartDetails");
        } else {
            $("#current-order").removeClass("closeCartDetails");
            $("#current-order").addClass("openCartDetails");
        }
    });

    $("#cart-box-layout-1").on("click", function() {
        if ($("#current-order").hasClass("openCartDetails")) {
            $("#current-order").addClass("closeCartDetails");
            $("#current-order").removeClass("openCartDetails");
        } else {
            $("#current-order").removeClass("closeCartDetails");
            $("#current-order").addClass("openCartDetails");
        }
    });

    $("#ask").on("click", function() {
        $(".reason p").toggle();
    });


    // Function for Remove Cart Items
    function removeCartItem(itemID, priceID, item_key) {
        $.ajax({
            type: "POST",
            url: "{{ route('shop.remove.cart.item') }}",
            data: {
                "_token": "{{ csrf_token() }}",
                'item_id': itemID,
                'price_id': priceID,
                'item_key': item_key,
            },
            dataType: "JSON",
            success: function(response) {
                if (response.success == 1) {
                    toastr.success(response.message);
                    setTimeout(() => {
                        location.reload();
                    }, 1000);
                } else {
                    toastr.error(response.message);
                    setTimeout(() => {
                        location.reload();
                    }, 1000);
                }
            }
        });
    }

    function getCartDetails(itemID, priceID, item_key, shopId) {
        $('#itemDetailsModal #item_dt_div').html('');

        $.ajax({
            type: "POST",
            url: "{{ route('shop.edit.cart') }}",
            data: {
                "_token": "{{ csrf_token() }}",
                'item_id': itemID,
                'price_id': priceID,
                'item_key': item_key,
                'shop_id': shopId,
            },
            dataType: "JSON",
            success: function(response) {
                if (response.success == 1) {
                    $('#itemDetailsModal #item_dt_div').html('');
                    $('#itemDetailsModal #item_dt_div').append(response.data);
                    $('#itemDetailsModal').modal('show');
                    updatePrice();
                } else {
                    toastr.error(response.message);
                    setTimeout(() => {
                        location.reload();
                    }, 1000);
                }
            }
        });
    }

    function homePage(shop_slug) {
        $.ajax({
            type: "POST",
            url: "{{ route('is.cover') }}",
            data: {
                "_token": "{{ csrf_token() }}",
            },
            dataType: "JSON",
            success: function(response) {
                if (response.success == 1) {
                    // Construct the URL using the route function
                    var url = "{{ route('restaurant', ':shop_slug') }}";
                    // Replace the placeholder :shop_slug with the actual value
                    url = url.replace(':shop_slug', shop_slug);
                    // Redirect to the constructed URL
                    window.location.href = url;
                }
            }
        });

    }
</script>
