@php
    $enable_print = (isset($printer_settings['enable_print']) && !empty($printer_settings['enable_print'])) ? $printer_settings['enable_print'] : 0;

    $admin_settings = getAdminSettings();
@endphp

@extends('client.layouts.client-layout')

@section('title', __('Printer Settings'))

@section('content')

    {{-- Page Title --}}
    <div class="pagetitle">
        <h1>{{ __('Printer Settings')}}</h1>
        <div class="row">
            <div class="col-md-8">
                <nav>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('client.dashboard') }}">{{ __('Dashboard') }}</a></li>
                        <li class="breadcrumb-item active">{{ __('Printer Settings') }}</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>

    {{-- Options Section --}}
    <section class="section dashboard">
        <div class="row">

            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <form method="POST" enctype="multipart/form-data" action="{{ route('update.printer.settings') }}" id="printerSettingsForm">
                            @csrf

                            {{-- Printer Settings --}}
                            <div class="row">
                                <h3>{{ __('Print Settings') }}</h3>
                                <div class="col-md-12">
                                    <label><i class="bi bi-arrow-right-circle me-1 text-success"></i>{{ __('Before you enable print option.') }}</label> <br>
                                    <label><i class="bi bi-arrow-right-circle me-1 text-success"></i>{{ __('You have to install the client software JS Print Manager Ver. 7.x.x') }}</label> <br>
                                    <label><i class="bi bi-arrow-right-circle me-1 text-success"></i>{{ __('Download or Visit this') }} <a target="_incognito" href="https://www.neodynamic.com/downloads/jspm/">{{ __('Link') }}</a></label> <br>
                                    <a href="{{ url('/neodynamic-jspm/jspm7-7-win.zip') }}" class="btn btn-primary btn-sm mt-2 mb-2"><i class="bi bi-download"></i> Download</a> <br>
                                </div>
                            </div>
                            <div class="row mt-3">
                                <div class="col-md-3 mb-2">
                                    <label class="switch me-2">
                                        <input type="checkbox" value="1" name="enable_print" id="enable_print" class="ord-setting" {{ (isset($printer_settings['enable_print']) && $printer_settings['enable_print'] == 1) ? 'checked' : '' }}>
                                        <span class="slider round">
                                            <i class="fa-solid fa-circle-check check_icon"></i>
                                            <i class="fa-sharp fa-solid fa-circle-xmark uncheck_icon"></i>
                                        </span>
                                    </label>
                                    <label for="enable_print" class="form-label">{{ __('Enable/Disable Print') }}</label>
                                </div>
                                <div class="col-md-3 mb-3">
                                    <label class="switch me-2">
                                        <input type="checkbox" value="1" name="auto_print" id="auto_print" class="ord-setting" {{ (isset($printer_settings['auto_print']) && $printer_settings['auto_print'] == 1) ? 'checked' : '' }}>
                                        <span class="slider round">
                                            <i class="fa-solid fa-circle-check check_icon"></i>
                                            <i class="fa-sharp fa-solid fa-circle-xmark uncheck_icon"></i>
                                        </span>
                                    </label>
                                    <label for="auto_print" class="form-label">{{ __('Auto Print') }}</label>
                                </div>
                                <div class="col-md-3 mb-3">
                                    <label class="switch me-2">
                                        <input type="checkbox" value="1" name="raw_printing" id="raw_printing" class="ord-setting" {{ (isset($printer_settings['raw_printing']) && $printer_settings['raw_printing'] == 1) ? 'checked' : '' }}>
                                        <span class="slider round">
                                            <i class="fa-solid fa-circle-check check_icon"></i>
                                            <i class="fa-sharp fa-solid fa-circle-xmark uncheck_icon"></i>
                                        </span>
                                    </label>
                                    <label for="raw_printing" class="form-label">{{ __('Raw Printing') }}</label>
                                </div>
                                <div class="col-md-3 mb-3">
                                    <label class="switch me-2">
                                        <input type="checkbox" value="1" name="greek_list" id="greek_list" class="ord-setting" {{ (isset($printer_settings['greek_list']) && $printer_settings['greek_list'] == 1) ? 'checked' : '' }}>
                                        <span class="slider round">
                                            <i class="fa-solid fa-circle-check check_icon"></i>
                                            <i class="fa-sharp fa-solid fa-circle-xmark uncheck_icon"></i>
                                        </span>
                                    </label>
                                    <label for="greek_list" class="form-label">{{ __('Greeklish') }}</label>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="printer_tray" class="form-label">{{ __('Printer Trays') }}</label>
                                    <select name="printer_tray" id="printer_tray" class="form-select">
                                        <option value="">not found</option>
                                    </select>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="printer_paper" class="form-label">{{ __('Printer Papers') }}</label>
                                    <select name="printer_paper" id="printer_paper" class="form-select">
                                        <option value="">not found</option>
                                    </select>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="default_printer" class="form-label">{{ __('Default Printer') }}</label>
                                    <select name="default_printer" id="default_printer" class="form-select">
                                        <option value="">not found</option>
                                    </select>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="receipt_intro" class="form-label">{{ __('Receipt Intro') }}</label>
                                    <input type="text" name="receipt_intro" id="receipt_intro" class="form-control" value="{{ (isset($printer_settings['receipt_intro'])) ? $printer_settings['receipt_intro'] : '' }}">
                                </div>
                                <div class="col-md-6 mb-2">
                                    <label for="print_font_size" class="form-label">{{ __('Print Font Size') }}</label>
                                    <input type="number" name="print_font_size" id="print_font_size" value="{{ (isset($printer_settings['print_font_size'])) ? $printer_settings['print_font_size'] : '' }}" class="form-control">
                                </div>
                                <div class="col-md-6 mb-2">
                                    <label for="default_code_page" class="form-label">{{ __('Code Page') }}</label>
                                    <select name="default_code_page" id="default_code_page" class="form-select">
                                        <option value="">Select CodePage</option>
                                        @if(count($code_pages) > 0)
                                            @foreach ($code_pages as $code_page)
                                                <option value="{{ $code_page['id'] }}" {{ (isset($printer_settings['default_code_page']) && $printer_settings['default_code_page'] == $code_page['id']) ? 'selected' : '' }}>{{ $code_page['name'] }}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                </div>
                            </div>
                            <div class="row mt-3">
                                <div class="col-md-12">
                                    <button id="update-btn" class="btn btn-success"><i class="bi bi-save"></i> Update</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

        </div>
    </section>

@endsection


{{-- Custom Script --}}
@section('page-js')

    <script src="{{ asset('public/admin/assets/js/jsprintmanager-v7.js') }}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bluebird/3.3.5/bluebird.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>

    <script type="text/javascript">

        var clientPrinters = null;
        var _this = this;
        var enablePrint = "{{ $enable_print }}";

        if(enablePrint == 1)
        {
            //WebSocket settings
            JSPM.JSPrintManager.license_url = "{{ route('jspm') }}";
            JSPM.JSPrintManager.auto_reconnect = true;
            JSPM.JSPrintManager.start();
            JSPM.JSPrintManager.WS.onStatusChanged = function () {
                if (jspmWSStatus()) {
                    //get client installed printers
                    JSPM.JSPrintManager.getPrintersInfo().then(function (myPrinters) {
                        clientPrinters = myPrinters;
                        var options = '';
                        for (var i = 0; i < clientPrinters.length; i++) {
                            options += '<option value="'+clientPrinters[i].name+'">' + clientPrinters[i].name + '</option>';
                        }
                        $('#default_printer').html(options);

                        // Set Default Printer
                        var def_printer = "{{ (isset($printer_settings['default_printer'])) ? $printer_settings['default_printer'] : '' }}";
                        $("#default_printer option[value='"+def_printer+"']").attr("selected", "selected");

                        _this.showSelectedPrinterInfo();
                    });
                }
            };
        }


        function showSelectedPrinterInfo()
        {
            // get selected printer index
            var idx = $("#default_printer")[0].selectedIndex;

            // get supported trays
            var options = '';
            for (var i = 0; i < clientPrinters[idx].trays.length; i++) {
                options += '<option value="'+clientPrinters[idx].trays[i]+'">' + clientPrinters[idx].trays[i] + '</option>';
            }
            $('#printer_tray').html(options);

            // get supported papers
            options = '';
            for (var i = 0; i < clientPrinters[idx].papers.length; i++) {
                options += '<option value="'+clientPrinters[idx].papers[i]+'">' + clientPrinters[idx].papers[i] + '</option>';
            }
            $('#printer_paper').html(options);

            // Set Default Paper
            var def_paper = "{{ (isset($printer_settings['printer_paper'])) ? $printer_settings['printer_paper'] : '' }}";
            $("#printer_paper option[value='"+def_paper+"']").attr("selected", "selected");

            // Set Default Tray
            var def_tray = "{{ (isset($printer_settings['printer_tray'])) ? $printer_settings['printer_tray'] : '' }}";
            $("#printer_tray option[value='"+def_tray+"']").attr("selected", "selected");
        }


        //Check JSPM WebSocket status
        function jspmWSStatus()
        {
            if (JSPM.JSPrintManager.websocket_status == JSPM.WSStatus.Open)
                return true;
            else if (JSPM.JSPrintManager.websocket_status == JSPM.WSStatus.Closed) {
                alert('JSPrintManager (JSPM) is not installed or not running! Download JSPM Client App from https://neodynamic.com/downloads/jspm');
                return false;
            }
            else if (JSPM.JSPrintManager.websocket_status == JSPM.WSStatus.Blocked) {
                alert('JSPM has blocked this website!');
                return false;
            }
        }


        @if (Session::has('success'))
            toastr.success('{{ Session::get('success') }}')
        @endif


    </script>
@endsection
