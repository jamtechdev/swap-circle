<?php 
	$users_system = DB::table('users_system')->where('users_system_id', session('admin_id'))->get()->first();
	$permissions = DB::table('users_system_roles')->where('users_system_roles_id', $users_system->users_system_roles_id)->get()->first();
?>
<style>
	li.mm-active > a i{
		color: #8000FF;
	}
</style>
 
<div class="deznav">
	<div class="deznav-scroll">
		<ul class="metismenu" id="menu">
			<?php if($permissions->dashboard == 'Yes'){ ?>
			<li>
				<a href="{{ url('admin/dashboard')  }}" aria-expanded="true">	
					<i class="fa fa-th-large" aria-hidden="true"></i>
					<span class="nav-text"> Dashboard</span>
				</a>
			</li>
			<?php } ?>

			<?php if($permissions->users_customers == 'Yes'){ ?>
			<li>
				<a href="{{ url('admin/users_customers') }}"  aria-expanded="true">
					<i class="fa fa-users"></i>
					<span class="nav-text"> Users</span>
				</a>
			</li>	
			<?php } ?>


			<?php if($permissions->swap_offers == 'Yes'){ ?>			
			<li>
				<a href="{{ url('admin/swap_offers') }}"  aria-expanded="true">
					<i class="fa fa-exchange" aria-hidden="true"></i>
					<span class="nav-text">Swap Offers</span>
				</a>
			</li>
			<?php } ?>

			<?php if($permissions->products == 'Yes'){ ?>			
			<li>
				<a href="{{ url('admin/manage_products') }}" aria-expanded="true">
					<i class="fa fa-exchange" aria-hidden="true"></i>
					<span class="nav-text">Products</span>
				</a>
			</li>
			<?php } ?>
			
			<?php if($permissions->users_customers_trxns == 'Yes'){ ?>
				<li>
				<a class="has-arrow ai-icon" aria-expanded="false" href="javascript:void()">
					<i class="fa fa-gears"></i>
					<span class="nav-text">Transactions</span>
				</a>
				<ul aria-expanded="false">
			        <li><a href="{{ url('admin/users_customers_trxns') }}">FX Transactions</a></li>	
			        <li><a href="{{ url('admin/transactions') }}">Product Transactions</a></li>	
				</ul>
			</li>
			<!-- <li>
				<a href="{{ url('admin/users_customers_trxns') }}"  aria-expanded="true">
					<i class="fa fa-money" aria-hidden="true"></i>
					<span class="nav-text">Transactions</span>
				</a>
			</li> -->
			<?php } ?>

			<?php if($permissions->products_claims == 'Yes'){ ?>
			<li>
				<a href="{{ url('admin/products_claims') }}"  aria-expanded="true">
				<i class="fa fa-list" aria-hidden="true"></i>
					<span class="nav-text">Products Claims</span>
				</a>
			</li>	
			<?php } ?>

			<?php if($permissions->tasks_types == 'Yes' || $permissions->occupations == 'Yes' || $permissions->relationships == 'Yes'){ ?>
				<li>
					<a class="has-arrow ai-icon" aria-expanded="false" href="javascript:void()">
						<i class="fa fa-th-large"></i>
						<span class="nav-text">App Resources</span>
					</a>
					<ul aria-expanded="false">
						<?php if($permissions->tasks_types == 'Yes'){ ?>
							<li><a href="{{ url('admin/tasks_types') }}">Tasks Types</a></li>	
						<?php } ?>
						<?php if($permissions->occupations == 'Yes'){ ?>
							<li><a href="{{ url('admin/occupations') }}">Occupations</a></li>	
						<?php } ?>
						<?php if($permissions->relationships == 'Yes'){ ?>
							<li><a href="{{ url('admin/relationships') }}">Relationships</a></li>
						<?php } ?>
					</ul>
				</li>
			<?php } ?>

			<?php if($permissions->tasks_types == 'Yes'){ ?>
			<!-- <li>
				<a href="{{ url('admin/tasks_types') }}"  aria-expanded="true">
					<i class="fa fa-th-large" aria-hidden="true"></i>
					<span class="nav-text">Tasks Types</span>
				</a>
			</li>	 -->
			<?php } ?>
			
			<?php if($permissions->admin_rate == 'Yes'){ ?>
			<li>
				<a href="{{ url('admin/admin_rate') }}"  aria-expanded="true">
					<i class="fa fa-database" aria-hidden="true"></i>
					<span class="nav-text">Admin Rate</span>
				</a>
			</li>
			<?php } ?>
			
			<?php if($permissions->rate_api == 'Yes'){ ?>
			<li>
				<a href="{{ url('admin/rate_api') }}"  aria-expanded="true">
					<i class="fa fa-database" aria-hidden="true"></i>
					<span class="nav-text">Rate Api</span>
				</a>
			</li>
			<?php } ?>
			
			<?php if($permissions->currency_rate == 'Yes'){ ?>
			<li>
				<a href="{{ url('admin/currency_rate') }}"  aria-expanded="true">
					<i class="fa fa-university" aria-hidden="true"></i>
					<span class="nav-text">Currency Rate</span>
				</a>
			</li>
			<?php } ?>

			
			<?php if($permissions->fund_wallet_requests == 'Yes'){ ?>
			<li>
				<a href="{{ url('admin/fund_wallet_requests') }}"  aria-expanded="true">
					<i class="fa fa-money" aria-hidden="true"></i>
					<span class="nav-text">Fund Wallet Request</span>
				</a>
			</li>
			<?php } ?>
			<?php if($permissions->withdraw_wallets_requests == 'Yes'){ ?>
			<li>
				<a href="{{ url('admin/withdraw_wallets_requests') }}"  aria-expanded="true">
					<i class="fa fa-money" aria-hidden="true"></i>
					<span class="nav-text">Withdraw Wallet Request</span>
				</a>
			</li>
			<?php } ?>
			<!-- <li>
				<a href="{{ url('admin/payment_methods') }}"  aria-expanded="true">
					<i class="fa fa-credit-card" aria-hidden="true"></i>
					<span class="nav-text">Payment Methods</span>
				</a>
			</li>	 -->
			
			<?php if($permissions->connect_categories == 'Yes'){ ?>
			<li>
				<a href="{{ url('admin/connect_categories') }}"  aria-expanded="true">
				<i class="fa fa-list" aria-hidden="true"></i>
					<span class="nav-text">Connect Categories</span>
				</a>
			</li>	
			<?php } ?>
			
			<?php if($permissions->connect_articles == 'Yes'){ ?>
			<li>
				<a href="{{ url('admin/connect_articles') }}"  aria-expanded="true">
				<i class="fa fa-newspaper" aria-hidden="true"></i>
					<span class="nav-text">Connect Articles</span>
				</a>
			</li>	
			<?php } ?>

			<?php if($permissions->users_customers_faqs == 'Yes'){ ?>
			<li>
				<a href="{{ url('admin/users_customers_faqs') }}"  aria-expanded="true">
				<i class="fa fa-question-circle-o" aria-hidden="true"></i>
					<span class="nav-text">FAQ's</span>
				</a>
			</li>	
			<?php } ?>

			<?php if($permissions->account_settings == 'Yes'){ ?>
			<li>
				<a href="{{ url('admin/account_settings')  }}" aria-expanded="true">	
					<i class="fa fa-wrench"></i>
					<span class="nav-text"> General Settings</span>
				</a>
			</li>
			<?php } ?>

			<?php if($permissions->users_system == 'Yes' || $permissions->users_system_roles == 'Yes' || $permissions->system_settings == 'Yes'){ ?>
			<li>
				<a class="has-arrow ai-icon" aria-expanded="false" href="javascript:void()">
					<i class="fa fa-gears"></i>
					<span class="nav-text"> Roles</span>
				</a>

				<ul aria-expanded="false">
					<?php if($permissions->users_system == 'Yes'){ ?>
			        <li><a href=" {{ url('admin/users_system') }}">Users System</a></li>	
			        <?php } ?>

					<?php if($permissions->users_system_roles == 'Yes'){ ?>
			        <li><a href=" {{ url('admin/users_system_roles') }}">Users System Roles</a></li>	
			        <?php } ?>

					<!--
					<?php if($permissions->system_settings == 'Yes'){ ?>
			        <li><a href=" {{ url('admin/system_settings') }}">System Settings</a></li>	
			    	<?php } ?>
			    	-->
				</ul>
			</li>
			<?php } ?>
		</ul>
    </div>
</div>