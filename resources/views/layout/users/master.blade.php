<!DOCTYPE html>
<html lang="en">
	<head>
	    <?php 
			$system_image   = DB::table('system_settings')->select('description')->where('type', 'system_image')->get(); 
			$system_name    = DB::table('system_settings')->select('description')->where('type', 'system_name')->get(); 
		?>
	    <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
	    <title>{{ $system_name[0]->description }} :: Users Customers Portal</title>
		<link href="{{ asset('uploads/system_image/favico.png') }}" rel="icon" type="image" sizes="24x24">

	    <link href="{{ asset('users/assets/css/bootstrap.min.css') }}" rel="stylesheet" type="text/css">
	    <link href="{{ asset('users/assets/css/navbar.css') }}" rel="stylesheet" type="text/css">
	    <link href="{{ asset('users/assets/plugin/splide/splide.min.css') }}" rel="stylesheet" type="text/css">
	    <link href="{{ asset('users/assets/css/jquery.ui.min.css') }}" rel="stylesheet" type="text/css">
	    <link href="{{ asset('users/assets/css/style.css') }}" rel="stylesheet" type="text/css">
	    <link href="{{ asset('users/assets/css/custom.css') }}" rel="stylesheet" type="text/css">
		@vite(['resources/sass/app.scss', 'resources/js/app.js'])
		<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
		<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
	</head>
	<body>
	    <div class="d-flex" id="dashboard-wrapper">
	    	<!-- HEADER START -->
	    	@include('layout.users.header')
	    	<!-- HEADER END -->

	    	<!-- MENU START -->
	    	@include('layout.users.menu')
	    	<!-- MENU END -->

	    	<!-- MAIN CONTENT START -->
	    	@yield('content')
	    	<!-- MAIN CONTENT END -->

			<!-- MODALS START -->
	    	@include('layout.users.modals')
	    	<!-- MODALS END -->
		</div>   
	</body>

	<!-- SCRIPTS START -->
	@include('layout.users.scripts')
	@yield('script')
	<!-- SCRIPTS ENDS-->
</html>