<div class="admin-settings-nav mb-4">
<a class="btn btn-sm <?php if($page_name == 'account_settings') { echo 'btn-primary'; }  else { echo "btn-info"; }?>" href="{{ url('/admin/account_settings') }}">Account Settings</a>

<a class="btn btn-sm <?php if($page_name == 'system_settings') { echo 'btn-primary'; }  else { echo "btn-info"; }?>" href="{{ url('/admin/system_settings') }}">System Settings</a>

<a class="btn btn-sm <?php if($page_name == 'system_about_us') { echo 'btn-primary'; }  else { echo "btn-info"; }?>" href="{{ url('/admin/system_about_us') }}">About Us</a>

<a class="btn btn-sm <?php if($page_name == 'system_terms') { echo 'btn-primary'; }  else { echo "btn-info"; }?>" href="{{ url('/admin/system_terms') }}">Terms and Conditions</a>

<a class="btn btn-sm <?php if($page_name == 'system_privacy') { echo 'btn-primary'; }  else { echo "btn-info"; }?>" href="{{ url('/admin/system_privacy') }}">Privacy Policy</a>

<a class="btn btn-sm <?php if($page_name == 'system_cookies') { echo 'btn-primary'; }  else { echo "btn-info"; }?>" href="{{ url('/admin/system_cookies') }}">Cookie Policy</a>

<a class="btn btn-sm <?php if($page_name == 'system_gdpr') { echo 'btn-primary'; }  else { echo "btn-info"; }?>" href="{{ url('/admin/system_gdpr') }}">GDPR Narration</a>
</div>