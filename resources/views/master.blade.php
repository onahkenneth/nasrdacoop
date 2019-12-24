<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width,initial-scale=1,user-scalable=0,minimal-ui">
    <title>NASRDA COOPERATIVE SOCIETY</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta content="Admin Dashboard" name="description">
    <meta content="ThemeDesign" name="author">
    <meta http-equiv="X-UA-Compatible" content="IE=edge"><!-- App Icons -->
    <link rel="shortcut icon" href="{{ asset('assets/images/favicon.ico') }}"><!-- morris css -->
    <link rel="stylesheet" href="https://themesdesign.in/zinzer_1/plugins/morris/morris.css"><!-- App css -->
    <link href="{{ asset('assets/css/bootstrap.min.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('assets/css/icons.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('assets/css/style.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('handsontable/handsontable.full.min.css') }}" rel="stylesheet" type="text/css">
    <link rel="stylesheet" href="http://cdn.bootcss.com/toastr.js/latest/css/toastr.min.css">
    <link href="{{ asset('css/awesomplete.css') }}" rel="stylesheet" />
    <link href="{{asset('DataTables/datatables.min.css')}}" rel="stylesheet" type="text/css">
</head>

<body>
    <!-- Loader -->
    <!-- <div id="preloader">
        <div id="status">
            <div class="spinner">
                <div class="rect1"></div>
                <div class="rect2"></div>
                <div class="rect3"></div>
                <div class="rect4"></div>
                <div class="rect5"></div>
            </div>
        </div>
    </div> -->
    <div class="header-bg">
        <!-- Navigation Bar-->
        @include('includes/header')
        <!-- End Navigation Bar-->
    </div><!-- header-bg -->
    <div id="app" class="wrapper">
        <div class="container-fluid">
            @yield('body')
        </div><!-- end container-fluid -->
    </div><!-- end wrapper -->
    <!-- Footer -->
    <footer class="footer">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">© {{date('Y')}}</div>
            </div>
        </div>
    </footer><!-- End Footer -->
    <!-- jQuery  -->
    <script src="{{ asset('js/app.js') }}"></script>
    <script src="{{ asset('assets/js/jquery.min.js') }}"></script>
    <script src="{{ asset('assets/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('assets/js/modernizr.min.js') }}"></script>
    <script src="{{ asset('assets/js/waves.js') }}"></script>
    <script src="{{ asset('assets/js/jquery.slimscroll.js') }}"></script>
    <!--Morris Chart-->
    <script src="https://themesdesign.in/zinzer_1/plugins/morris/morris.min.js"></script>
    <script src="https://themesdesign.in/zinzer_1/plugins/raphael/raphael.min.js"></script><!-- App js -->
    <script src="{{ asset('assets/js/app.js') }}"></script>
    <script src="{{ asset('handsontable/handsontable.full.min.js') }}"></script>
    <script src="http://cdn.bootcss.com/toastr.js/latest/js/toastr.min.js"></script>
    <script src="{{ asset('DataTables/datatables.min.js') }}"></script>

    <!--  Awesomplete Plugin    -->
    <script src="{{ asset('js/awesomplete.js') }}"></script>

    {!! Toastr::message() !!} 
    <script>
        $(document).ready(function () {
            $('body').on('click', '[data-toggle="modal"]', function () {
                url = $(this).data("remote")
                console.log(url)
                $($(this).data("target") + ' .modal-body').load(url);
            });

            $('#confirmationModal').on('show.bs.modal', function (e) {
                $(this).find('.confirm').attr('href', $(e.relatedTarget).data('href'));
            });
        });

        // AWESOMEPLETE
        var search = document.getElementById("search");
        var awesomplete_search = new Awesomplete(search, {
            minChars: 1,
            autoFirst: true
        });

        $("input[name=search]").on("keyup", function(){

            $.ajax({
                url: "{{ url('members/awesomplete') }}",
                headers: {'X-CSRF-TOKEN': $('input[name=_token]').val()},
                type: 'POST',
                data: {q:this.value},
                dataType: 'json',
                success: function(data) {
                    var list = [];
                    $.each(data, function(key, value) {
                        console.log(value)
                        list.push(value);
                    });
                    awesomplete_search.list = list;
                }
            })
        });

        $('.datatable').DataTable( {
            fixedHeader: true,
            paging: false,
            // dom: 'Bfrtip',
            // buttons: [
            //     'copy', 'csv', 'excel', 'pdf', 'print'
            // ]
        } );

    </script>

    @yield('js')

</body>

</html>





<div class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title mt-0" id="myModalLabel">New Ledger Entry</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                        aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body">
                loading...
            </div>
            <div class="modal-footer"><button type="button" class="btn btn-secondary waves-effect"
                    data-dismiss="modal">Close</button> <button type="button"
                    class="btn btn-primary waves-effect waves-light">Save
                    changes</button></div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div>


<div id="myModal" class="modal fade bs-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <!-- <h5 class="modal-title mt-0" id="myLargeModalLabel">Large modal</h5> -->
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                        aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body">loading...
            </div>
            <div class="modal-footer"><button type="button" class="btn btn-secondary waves-effect"
                    data-dismiss="modal">Close</button></div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div>
