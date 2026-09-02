@extends('layout.users.master')

@section('page_title', 'Messages')
@section('page_subtitle', 'Chat with community members')

@section('content')
    <div class="page-content-wrapper">
        <div class="page-content-tab">
            <div class="container-fluid px-4 pb-4">
                <div class="portal-messages-panel">
                    <div class="portal-messages-empty d-none" id="no_chat">
                        <div class="portal-messages-empty__icon" aria-hidden="true">
                            <svg viewBox="0 0 24 24" fill="none">
                                <path d="M21 11.5a8.38 8.38 0 0 1-.9 3.8 8.5 8.5 0 0 1-7.6 4.7 8.38 8.38 0 0 1-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 0 1-.9-3.8 8.5 8.5 0 0 1 4.7-7.6 8.38 8.38 0 0 1 3.8-.9h.5a8.48 8.48 0 0 1 8 8v.5Z" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                        </div>
                        <h2 class="portal-messages-empty__title">No conversations yet</h2>
                        <p class="portal-messages-empty__text">When you message other members from offers or swap requests, your chats will appear here.</p>
                        <a href="{{ url('/users/offers') }}" class="portal-messages-empty__cta">Browse offers</a>
                    </div>

                    <div class="portal-messages-layout d-none" id="chat">
                        <input type="hidden" id="selected_user_id" value="{{ $user_id ?? '' }}" readonly>

                        <aside class="portal-messages-sidebar">
                            <div class="portal-messages-sidebar__head">
                                <h2 class="portal-messages-sidebar__title">Conversations</h2>
                                <div class="portal-messages-search">
                                    <span class="portal-messages-search__icon" aria-hidden="true">
                                        <svg viewBox="0 0 18 18" fill="none">
                                            <path fill-rule="evenodd" clip-rule="evenodd" d="M8.0625 2.125C4.78331 2.125 2.125 4.78331 2.125 8.0625C2.125 11.3417 4.78331 14 8.0625 14C11.3417 14 14 11.3417 14 8.0625C14 4.78331 11.3417 2.125 8.0625 2.125ZM0.875 8.0625C0.875 4.09295 4.09295 0.875 8.0625 0.875C12.032 0.875 15.25 4.09295 15.25 8.0625C15.25 12.032 12.032 15.25 8.0625 15.25C4.09295 15.25 0.875 12.032 0.875 8.0625Z" fill="currentColor"/>
                                            <path fill-rule="evenodd" clip-rule="evenodd" d="M12.2611 12.2612C12.5051 12.0171 12.9009 12.0171 13.1449 12.2612L16.9418 16.0581C17.1859 16.3022 17.1859 16.6979 16.9418 16.942C16.6977 17.1861 16.302 17.1861 16.0579 16.942L12.2611 13.1451C12.017 12.901 12.017 12.5053 12.2611 12.2612Z" fill="currentColor"/>
                                        </svg>
                                    </span>
                                    <input type="search" class="portal-messages-search__input" placeholder="Search conversations" name="search" id="search" autocomplete="off">
                                </div>
                            </div>
                            <ul class="portal-messages-list" id="all_chats"></ul>
                        </aside>

                        <section class="portal-messages-main">
                            <div class="portal-messages-main__placeholder" id="no_message">
                                <div class="portal-messages-main__placeholder-icon" aria-hidden="true">
                                    <svg viewBox="0 0 24 24" fill="none">
                                        <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round"/>
                                    </svg>
                                </div>
                                <h3>Select a conversation</h3>
                                <p>Choose someone from the list to view messages</p>
                            </div>

                            <ul class="portal-messages-thread list-unstyled" id="messages"></ul>

                            <form class="portal-messages-compose d-none" id="send_message" onsubmit="return false;">
                                <input type="hidden" id="msg_receiver_id" value="" readonly>
                                <div class="portal-messages-compose__field">
                                    <input type="text" class="portal-messages-compose__input" placeholder="Type a message…" id="entered_message" autocomplete="off">
                                    <button type="button" class="portal-messages-compose__send" onclick="send_message()" aria-label="Send message">
                                        <svg viewBox="0 0 20 17" fill="none" aria-hidden="true">
                                            <path d="M0.14158 16.0631L0.0991079 10.0632L8.08475 8.00665L0.0707928 6.06333L0.0283203 0.0634766L19.0845 7.92878L0.14158 16.0631Z" fill="currentColor"/>
                                        </svg>
                                    </button>
                                </div>
                            </form>
                        </section>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script>
        $(document).ready(function () {
            get_all_chats();

            var selectedUserId = $('#selected_user_id').val();
            if (selectedUserId !== '') {
                get_messages(selectedUserId);
            }

            $('#search').on('input', function () {
                var query = $(this).val().toLowerCase().trim();
                $('#all_chats .portal-messages-item').each(function () {
                    var name = $(this).find('.portal-messages-item__name').text().toLowerCase();
                    $(this).toggle(query === '' || name.indexOf(query) !== -1);
                });
            });

            $('#entered_message').on('keydown', function (event) {
                if (event.key === 'Enter' && !event.shiftKey) {
                    event.preventDefault();
                    send_message();
                }
            });
        });
    </script>
@endsection
