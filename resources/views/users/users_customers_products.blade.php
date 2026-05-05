@extends('layout.users.master')
@section('content') 
    <style>
        .image-wrapper {
            width: 185px;          
            height: 120px;        
            overflow: hidden;      /* hide overflow */
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 12px;   /* rounded corners */
            /* box-shadow: 0 2px 8px rgba(0,0,0,0.15); */
        }
        .image-wrapper img {
            width: 100%;
            height: 100%;
            object-fit: cover;     /* cover container without distortion */
        }
    </style>
    <div class="page-content-wrapper">
        <div class="page-content-tab">
            <div class="container-fluid px-4 pb-4">
                <!-- <div class="d-flex flex-wrap justify-content-between align-items-center gap-3">
                    <h3 class="fw-bold sub-heading text-black">Marketplace</h3>
                </div> -->

                <div class="offers-wrapper">
                    <div class="wallet-tabs mt-1">
                        <div class="tab-content" id="pills-tabContent">
                            <div class="row">
                                @foreach($products as $key => $item)
                                <div class="col-md-4 col-xl-4">
                                    <div class="card border-0 mb-3">
                                        <div class="card-body">
                                            <div class="d-flex align-items-center justify-content-center pb-0 mb-3 flex-wrap">
                                                <button class="btn btn-primary" style="cursor:default;">{{ $item->name }}</button>    
                                            </div>
                                            @if($item->image)
                                            <div class="d-flex align-items-center justify-content-center pb-0 mb-3 flex-wrap">
                                                <div class="image-wrapper">
                                                    <img src="{{ asset($item->image) }}" alt="Product">
                                                </div>
                                            </div>
                                            @endif
                                            @if($item->description)
                                            <div class="d-flex align-items-center justify-content-center pb-0 mb-0 flex-wrap">
                                                <p>{{ $item->description }}</p>
                                            </div>
                                            @endif
                                            <div class="text-center mt-2">
                                                <strong class="text-success fs-5">{{ $item->currency_symbol ?: '£' }}{{ number_format($item->custom_price ?? $item->price, 2) }}</strong>
                                                @if($item->currency_code)
                                                    <span class="text-muted" style="font-size:13px;"> {{ $item->currency_code }}</span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>    
                                    <div class="d-flex align-items-center justify-content-center pb-0 mb-3 flex-wrap">
                                        <a href="{{ url('users/product/' . $item->type . '/' . $item->products_id) }}" class="btn btn-primary">Buy now</a>
                                        <!-- @if($item->type == 'A')
                                            <button class="btn btn-primary" onclick="openProductModal('A', {{ $item->products_id }})">Buy now</button>  
                                        @endif 
                                        @if($item->type == 'B')
                                            <button class="btn btn-primary" onclick="openProductModal('B', {{ $item->products_id }})">Buy now</button>  
                                        @endif 
                                        @if($item->type == 'C')
                                            <button class="btn btn-primary" onclick="openProductModal('C', {{ $item->products_id }})">Buy now</button> 
                                        @endif   -->
                                    </div> 
                                </div>
                                @endforeach
                            </div>
                        </div>
                    </div> 
                </div>
            </div>
        </div> 
    </div>
@endsection
@section('script') 
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-ui-timepicker-addon/1.6.3/jquery-ui-timepicker-addon.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-ui-timepicker-addon/1.6.3/jquery-ui-timepicker-addon.min.js"></script>
    <!-- <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script> -->
    
    <script>
        $(document).ready(function() {
            // DOB fields (can’t select future dates)
            $('.dob').datepicker({
                dateFormat: "yy-mm-dd",
                maxDate: 0,
                changeMonth: true,
                changeYear: true,
                yearRange: "-100:+0"
            });

            $('.cover_start_date').datepicker({
                dateFormat: "yy-mm-dd",
                minDate: 0, // cannot select past dates
                changeMonth: true,
                changeYear: true,
                onSelect: function(dateText, inst) {
                    var product = $(this).data('product'); // e.g. "A" or "B"
                    var startDate = $(this).datepicker('getDate');

                    if (startDate) {
                        var endDate = new Date(startDate);
                        endDate.setFullYear(endDate.getFullYear() + 1);
                        endDate.setDate(endDate.getDate() - 1); // ✅ subtract 1 day

                        var yyyy = endDate.getFullYear();
                        var mm = ("0" + (endDate.getMonth() + 1)).slice(-2);
                        var dd = ("0" + endDate.getDate()).slice(-2);

                        $('#prod' + product + '_cover_end_date').val(yyyy + "-" + mm + "-" + dd);
                    }
                }
            });

            $(".task_date").datepicker({
                dateFormat: "yy-mm-dd",
                minDate: 0,
                changeMonth: true,
                changeYear: true
            });
            flatpickr("#prodC_task_time", {
                enableTime: true,
                noCalendar: true,
                dateFormat: "H:i",   // 24-hour format
                time_24hr: true,
                onOpen: function(selectedDates, dateStr, instance) {
                    // Get the task date
                    let taskDateStr = $("#prodC_task_date").val(); // assuming you have a date field
                    if (!taskDateStr) return;

                    let today = new Date();
                    let selectedDate = new Date(taskDateStr);

                    // If selected date is today, disable past times
                    if (selectedDate.toDateString() === today.toDateString()) {
                        let hours = today.getHours();
                        let minutes = today.getMinutes();

                        // Set minimum time to current time
                        instance.set("minTime", `${hours}:${minutes}`);
                    } else {
                        // For future dates, allow any time
                        instance.set("minTime", "00:00");
                    }
                }
            });
        });
        $(document).ready(function () {
            // Handle file selection for Product A or B dynamically
            $(document).on("change", "input[type='file'][id^='prod'][id$='_identity_document']", function (event) {
                const input = event.target;
                const file = input.files[0];

                if (!file) return;

                const reader = new FileReader();

                // Extract product type dynamically (A or B)
                const inputId = $(input).attr('id'); // e.g. "prodA_identity_document"
                const productType = inputId.match(/prod([A-Z])_/i)[1].toUpperCase(); // => A or B

                // Get corresponding elements
                const previewImg = $(`#prod${productType}_identity_document_preview`);
                const textArea = $(`#prod${productType}_identity_document_string`);

                reader.onload = function (e) {
                    const fullBase64 = e.target.result;

                    // ✅ Remove "data:image/...;base64," part
                    const cleanBase64 = fullBase64.replace(/^data:image\/(png|jpg|jpeg);base64,/, "");

                    // Show preview image (full Base64 still needed for preview)
                    if (previewImg.length) {
                        previewImg.attr("src", fullBase64);
                    }

                    // Store clean Base64 in textarea
                    if (textArea.length) {
                        textArea.val(cleanBase64);
                    }
                };

                reader.readAsDataURL(file);
            });
        });
        $(document).ready(function () {
            // Function to format date as YYYY-MM-DD
            function formatDate(date) {
                const yyyy = date.getFullYear();
                const mm = ('0' + (date.getMonth() + 1)).slice(-2);
                const dd = ('0' + date.getDate()).slice(-2);
                return `${yyyy}-${mm}-${dd}`;
            }

            // Get today's date
            const startDate = new Date();

            // Calculate end date = +1 year - 1 day
            const endDate = new Date(startDate);
            endDate.setFullYear(endDate.getFullYear() + 1);
            endDate.setDate(endDate.getDate() - 1);

            // Fill inputs
            $('#prodC_cover_start_date').val(formatDate(startDate));
            $('#prodC_cover_end_date').val(formatDate(endDate));
        });
    </script>
@endsection