<!DOCTYPE html>
<html lang="en">
	<head>
		<?php 
			$system_image=DB::table('system_settings')->select('description')->where('type', 'system_image')->get(); 
			$system_name=DB::table('system_settings')->select('description')->where('type', 'system_name')->get(); 
		?>
	    <meta charset="utf-8">
	    <meta http-equiv="X-UA-Compatible" content="IE=edge">
	    <meta name="viewport" content="width=device-width,initial-scale=1">
	    <title><?php echo $system_name[0]->description; ?> :: Admin Portal</title>
	    <!-- Favicon icon -->
		<link rel="icon" type="image" sizes="24x24" href="/public/uploads/system_image/favico.png">
	    <!-- Datatable -->
	    <link href="{{asset('vendor/datatables/css/jquery.dataTables.min.css')}}" rel="stylesheet">
	    <!-- Custom Stylesheet -->
	    <link href="{{asset('vendor/bootstrap-select/dist/css/bootstrap-select.min.css')}}" rel="stylesheet">
	    <link href="{{asset('css/style.css')}}" rel="stylesheet">
		<link rel="stylesheet" type="text/css" href="{{asset('/icons/flaticon/flaticon.css')}}">
		<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.2/css/all.min.css">
		<style>
			table.dataTable tbody tr.selected,
			table.dataTable tbody th.selected,
			table.dataTable tbody td.selected,
			table.dataTable.display tbody tr.odd.selected > .sorting_1,
			table.dataTable.display tbody tr.even.selected > .sorting_1 {
				background-color: #fff !important;
				color: inherit !important;
			}
		</style>
		@yield('style')
	</head>

	<body>

	    <!--*******************
	        Preloader start
	    ********************-->
	    <div id="preloader">
	        <div class="sk-three-bounce">
	            <div class="sk-child sk-bounce1"></div>
	            <div class="sk-child sk-bounce2"></div>
	            <div class="sk-child sk-bounce3"></div>
	        </div>
	    </div>
	    <!--*******************
	        Preloader end
	    ********************-->


	    <!--**********************************
	        Main wrapper start
	    ***********************************-->
	    <div id="main-wrapper">

	        <!--**********************************
	            Nav header start
	        ***********************************-->
	       <div class="nav-header">
				<div style="display: flex; justify-content: space-evenly; align-items: baseline; filter: brightness(1.2);">
					<a href="{{ url('/admin/dashboard') }}">
						<img 
							style="width: 100%; margin-top: 30px; cursor: pointer;" 
							src="/public/uploads/system_image/{{$system_image[0]->description}}" 
							alt="image"
						>
					</a>
				</div>
			</div>

	        <!--**********************************
	            Nav header end
	        ***********************************-->
			
			<!--**********************************
	            Header start
	        ***********************************-->
			@include('layout.admin.header');

	        <!--**********************************
	            Header end ti-comment-alt
	        ***********************************-->

	        <!--**********************************
	            Sidebar start
	        ***********************************-->
			@include('layout.admin.sidebar');
	       
	        <!--**********************************
	            Sidebar end
	        ***********************************-->
			
			<!--**********************************
	            Content body start
	        ***********************************-->

			@yield('content')
				
			<i class="flaticon-airplane49"></i>  <span class="flaticon-airplane49"></span>
	        <!--**********************************
	            Sidebar end
	        ***********************************-->

	        <!--**********************************
	            Content body end
	        ***********************************-->

	        <!--**********************************
	            Footer start
	        ***********************************-->
	        <div class="footer">
			    <div class="copyright">
			        <p>Copyright © <?php echo date('Y'); ?></p>
			    </div>
			</div>
	        <!--**********************************
	            Footer end
	        ***********************************-->

	        <!--**********************************
	           Support ticket button start
	        ***********************************-->

	        <!--**********************************
	           Support ticket button end
	        ***********************************-->
   	 	</div>
	    <!--**********************************
	        Main wrapper end
	    ***********************************-->

	    <!--**********************************
	        Scripts
	    ***********************************-->
	    <!-- Required vendors -->
	    <script src="{{asset('vendor/global/global.min.js')}}"></script>
	    <script src="{{asset('vendor/global/bootstrap.bundle.min.js')}}"></script>
	    <script src="{{asset('vendor/metismenu/js/metisMenu.min.js')}}"></script>
	    <script src="{{asset('vendor/perfect-scrollbar/js/perfect-scrollbar.min.js')}}"></script>
		<script src="{{asset('vendor/bootstrap-select/dist/js/bootstrap-select.min.js')}}"></script>
	    <script src="{{asset('js/settings.js')}}"></script>
	    <script src="{{asset('js/custom.min.js')}}"></script>
		<script src="{{asset('js/deznav-init.js')}}"></script>
		
	    <!-- Datatable -->
	    <script src="{{asset('vendor/datatables/js/jquery.dataTables.min.js')}}"></script>
	    <script src="{{asset('js/plugins-init/datatables.init.js')}}"></script>

	    <script>
	        $(document).ready(function () {
	    		$('#example').DataTable();

                var globalSyncBtn = document.getElementById('swap-global-insuretech-sync');
                if (globalSyncBtn) {
                    globalSyncBtn.addEventListener('click', function () {
                        globalSyncBtn.disabled = true;
                        var icon = globalSyncBtn.querySelector('i');
                        if (icon) {
                            icon.classList.add('fa-spin');
                        }

                        fetch('/api/insuretech/sync', {
                            method: 'POST',
                            headers: {
                                'Accept': 'application/json',
                                'Content-Type': 'application/json'
                            },
                            body: JSON.stringify({ limit: 200 })
                        })
                        .then(function (response) { return response.json(); })
                        .then(function (data) {
                            if (data && data.ok) {
                                var total = (data.success_count || 0) + (data.failed_count || 0);
                                var message = total === 0
                                    ? 'InsureTech sync completed. No mapped purchases to push.'
                                    : 'InsureTech sync completed. Success: ' + (data.success_count || 0) + ', Failed: ' + (data.failed_count || 0);
                                if (typeof toastr !== 'undefined') {
                                    toastr.success(message);
                                } else {
                                    alert(message);
                                }
                                return;
                            }

                            var failMessage = (data && data.message) ? data.message : 'InsureTech sync failed.';
                            if (typeof toastr !== 'undefined') {
                                toastr.error(failMessage);
                            } else {
                                alert(failMessage);
                            }
                        })
                        .catch(function () {
                            var networkMessage = 'InsureTech sync failed due to network or server error.';
                            if (typeof toastr !== 'undefined') {
                                toastr.error(networkMessage);
                            } else {
                                alert(networkMessage);
                            }
                        })
                        .finally(function () {
                            globalSyncBtn.disabled = false;
                            if (icon) {
                                icon.classList.remove('fa-spin');
                            }
                        });
                    });
                }
	    	});
	    </script>  

		<link href="{{asset('toasters/toastr.min.css')}}" rel="stylesheet" type="text/css" />	
	    <script src="{{asset('toasters/toastr.min.js')}}" type="text/javascript"></script>
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
	</body>
</html>