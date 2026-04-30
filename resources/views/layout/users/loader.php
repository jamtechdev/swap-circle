<!-- modal loader start -->
<style>
    #mdl_loader .modal-content {
        background: transparent;
        border: none;
        box-shadow: none;
    }
    #mdl_loader .modal-dialog {
        transition: none !important; /* Prevent dialog from sliding */
    }
    #mdl_loader .modal-body {
        padding: 0;
    }
    /* override Bootstrap's default modal backdrop styling */
    /*.modal-backdrop {
        background-color: white !important; /* set background color to white 
        opacity: 1 !important; /* remove blur effect and ensure full opacity 
    }*/
</style>
<div class="modal fade modal-xl" id="mdl_loader" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-body p-5">
                <div class="row justify-content-center">
                        <div class="col-lg-12 d-flex justify-content-center align-items-center">
                            <img src="{{ asset('users/loader11.svg') }}" width="150px">
                        </div>
                    </div>              
                </div>
            </div>
        </div>
    </div>
</div>
<!-- modal loader end --> 