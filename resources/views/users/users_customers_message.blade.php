@extends('layout.users.master')
@section('content')
    <div class="page-content-wrapper">
        <div class="page-content-tab">
            <div class="container-fluid px-4 pb-4">
                <div class="row d-none" id="no_chat" style="min-height: 10px;">
                    <div class="col-lg-12">
                        <div class="card border-0 rounded-4 overflow-hidden">
                            <div class="card-body p-3 text-center">
                                <h4>No Chat Found!!</h4>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="messages-wraper d-none" id="chat">
                    <div class="row gap-4 gap-lg-0">
                        <div class="col-lg-4">
                            <div class="card border-0 rounded-4 overflow-hidden">
                                <div class="card-body p-0"> 
                                    <input type="hidden" id="selected_user_id" value="{{ $user_id }}" readonly>
                                    <form>
                                        <div class="form-group position-relative p-3 bg-white">
                                            <span class="input-icon">
                                                <svg width="18" height="18" viewBox="0 0 18 18" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                    <path fill-rule="evenodd" clip-rule="evenodd" d="M8.0625 2.125C4.78331 2.125 2.125 4.78331 2.125 8.0625C2.125 11.3417 4.78331 14 8.0625 14C11.3417 14 14 11.3417 14 8.0625C14 4.78331 11.3417 2.125 8.0625 2.125ZM0.875 8.0625C0.875 4.09295 4.09295 0.875 8.0625 0.875C12.032 0.875 15.25 4.09295 15.25 8.0625C15.25 12.032 12.032 15.25 8.0625 15.25C4.09295 15.25 0.875 12.032 0.875 8.0625Z" fill="#969D9F"/>
                                                    <path fill-rule="evenodd" clip-rule="evenodd" d="M12.2611 12.2612C12.5051 12.0171 12.9009 12.0171 13.1449 12.2612L16.9418 16.0581C17.1859 16.3022 17.1859 16.6979 16.9418 16.942C16.6977 17.1861 16.302 17.1861 16.0579 16.942L12.2611 13.1451C12.017 12.901 12.017 12.5053 12.2611 12.2612Z" fill="#969D9F"/>
                                                </svg>
                                            </span>
                                            <input type="text" class="form-control search" placeholder="Search" name="search" id="search">
                                        </div>
                                    </form> 
                                    <!-- all chats start -->
                                    <ul class="list-unstyled msg-tabs" id="all_chats"></ul>  
                                    <!-- all chats end -->                                        
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-8">
                            <div class="card border-0 rounded-4">
                                <div class="card-body p-4 position-relative">
                                    <h5 class="text-center" id="no_message">No Chat Selected</h5>
                                    <!-- all messages start -->
                                    <ul class="list-unstyled chat px-2" id="messages" style="scroll-behavior: smooth;"></ul>
                                    <!-- all messages end -->

                                    <!-- send message -->
                                    <form class="d-none" id="send_message">
                                        <div class="form-group position-relative">
                                            <input type="hidden" id="msg_receiver_id" value="" readonly>
                                            <input type="text" class="form-control msg-write" placeholder="Type message here..." id="entered_message">
                                            <span class="input-icon right" onclick="send_message()">
                                                <svg width="20" height="17" viewBox="0 0 20 17" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                    <path d="M0.14158 16.0631L0.0991079 10.0632L8.08475 8.00665L0.0707928 6.06333L0.0283203 0.0634766L19.0845 7.92878L0.14158 16.0631Z" fill="#4BD16F"/>
                                                </svg>                                                            
                                            </span>
                                        </div>
                                    </form>
                                    <!-- send message -->
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div> 
    </div>
@endsection 
@section('script') 
    <script>
        $(document).ready(function() { 
            get_all_chats();

            $selected_user_id = $('#selected_user_id').val();
            if ($selected_user_id != '') {
                get_messages($selected_user_id);
            }
        });
    </script>
@endsection        