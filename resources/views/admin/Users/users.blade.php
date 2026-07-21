<?php
if (Auth('admin')->User()->dashboard_style == "light") {
    $text = "dark";
    $bg = "light";
} else {
    $bg = 'dark';
    $text = "light";
}
?>
@extends('layouts.app')
@section('content')
    @include('admin.topmenu')
    @include('admin.sidebar')
    <div class="main-panel">
        <div class="content bg-{{Auth('admin')->User()->dashboard_style}}">
            <div class="page-inner">
                <div class="mt-2 mb-4">
                    <h1 class="title1 text-{{$text}}">{{$settings->site_name}} users list</h1>
                </div>

                <x-danger-alert/>
                <x-success-alert/>
                <div class="row">
                    <div class="col-12">
                        <!-- Action buttons all on same line -->
                        <div class="d-flex flex-wrap justify-content-between align-items-center mb-3">
                            <div class="d-flex flex-wrap">
                                <a href="#" data-toggle="modal" data-target="#sendmailModal" class="btn btn-primary btn-sm mr-2 mb-2">Message all</a>
                                @if($settings->enable_kyc =="yes")
                                    <a href="{{ url('admin/dashboard/kyc') }}" class="btn btn-warning btn-sm mr-2 mb-2">KYC</a>
                                @endif
                                <a href="#" data-toggle="modal" data-target="#adduser" class="btn btn-success btn-sm mb-2"> <i class='fas fa-plus-circle'></i> Add User</a>
                            </div>
                            <!-- Filter toggle button -->
                            <button class="btn btn-outline-secondary btn-sm mb-2" type="button" data-toggle="collapse" data-target="#filterControls" aria-expanded="false" aria-controls="filterControls">
                                <i class="fas fa-filter"></i> Filters
                            </button>
                        </div>

                        <!-- Modal -->
                        <div class="modal fade" id="adduser" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header bg-{{$bg}}">
                                        <h3 class="mb-2 d-inline text-{{$text}}">Manually Add Users</h3>
                                        <button type="button" class="close text-{{$text}}" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body bg-{{$bg}}">
                                        <div>
                                            <form method="POST" action="{{ route('createuser')}}">
                                                @csrf
                                                <div class="form-row">
                                                    <div class="form-group col-md-12">
                                                        <h6 class="text-{{$text}}">Username</h6>
                                                        <input type="text" id="input1" class="form-control bg-{{$bg}} text-{{$text}}" name="username" required>
                                                    </div>
                                                    <div class="form-group col-md-12">
                                                        <h6 class="text-{{$text}}">Fullname</h6>
                                                        <input type="text" class="form-control bg-{{$bg}} text-{{$text}}" name="name" required>
                                                    </div>
                                                    <div class="form-group col-md-12">
                                                        <h6 class="text-{{$text}}">Email</h6>
                                                        <input type="email" class="form-control bg-{{$bg}} text-{{$text}}" name="email" required>
                                                    </div>
                                                    <div class="form-group col-md-12">
                                                        <h6 class="text-{{$text}}">Password</h6>
                                                        <input type="password" class="form-control bg-{{$bg}} text-{{$text}}" name="password" required>
                                                    </div>
                                                    <div class="form-group col-md-12">
                                                        <h6 class="text-{{$text}}">Confirm Password</h6>
                                                        <input type="password" class="form-control bg-{{$bg}} text-{{$text}}" name="password_confirmation" required>
                                                    </div>
                                                </div>
                                                <button type="submit" class="px-4 btn btn-primary">Add User</button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="mb-5 row">
                    <div class="col-md-12 shadow card p-4 bg-{{Auth('admin')->User()->dashboard_style}}">
                        <!-- Collapsible Filter Controls -->
                        <div class="collapse" id="filterControls">
                            <div class="card card-body mb-3 bg-{{$bg}}">
                                <form class="form-inline flex-column flex-sm-row">
                                    <div class="mb-2 mb-sm-0 mr-sm-2">
                                        <label class="text-{{$text}} mr-2">Show:</label>
                                        <select class="form-control form-control-sm bg-{{$bg}} text-{{$text}}" id="numofrecord">
                                            <option>10</option>
                                            <option>20</option>
                                            <option>30</option>
                                            <option>40</option>
                                            <option>50</option>
                                            <option>100</option>
                                            <option>200</option>
                                            <option>300</option>
                                            <option>400</option>
                                            <option>500</option>
                                            <option>600</option>
                                            <option>700</option>
                                            <option>800</option>
                                            <option>900</option>
                                            <option>1000</option>
                                        </select>
                                    </div>
                                    <div class="mb-2 mb-sm-0 mr-sm-2">
                                        <label class="text-{{$text}} mr-2">Sort:</label>
                                        <select class="form-control form-control-sm bg-{{$bg}} text-{{$text}}" id="order">
                                            <option value="desc">Newest First</option>
                                            <option value="asc">Oldest First</option>
                                        </select>
                                    </div>
                                    <div class="mb-2 mb-sm-0">
                                        <label class="text-{{$text}} mr-2">Search:</label>
                                        <input type="text" id="searchitem" placeholder="Search by name or email" class="form-control form-control-sm bg-{{$bg}} text-{{$text}}">
                                        <small id="errorsearch"></small>
                                    </div>
                                </form>
                            </div>
                        </div>

                        <div class="table-responsive" data-example-id="hoverable-table">
                            <table class="table table-hover text-{{$text}}">
                                <thead>
                                <tr>
                                    <th>Client Name</th>
                                    <th>Account Balance</th>
                                    <th>Email</th>
                                    <th>Phone</th>
                                    <th>Status</th>
                                    <th>Date registered</th>
                                    <th>Action</th>
                                </tr>
                                </thead>
                                <tbody id="userslisttbl">
                                <!-- Users data will be loaded here -->
                                </tbody>
                            </table>
                        </div>

                        <!-- Pagination Container -->
                        <div id="paginationContainer" class="d-flex justify-content-center mt-4">
                            <!-- Pagination links will be loaded here -->
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- CSS for pagination styling -->
        <style>
            #paginationContainer .pagination {
                margin-bottom: 0;
            }

            #paginationContainer .page-link {
                background-color: var(--bs-{{$bg}});
                border-color: var(--bs-gray-300);
                color: var(--bs-{{$text}});
            }

            #paginationContainer .page-item.active .page-link {
                background-color: var(--bs-primary);
                border-color: var(--bs-primary);
                color: white;
            }

            #paginationContainer .page-link:hover {
                background-color: var(--bs-gray-100);
                border-color: var(--bs-gray-300);
                color: var(--bs-{{$text}});
            }

            @media (max-width: 768px) {
                #paginationContainer .pagination {
                    font-size: 0.875rem;
                }

                #paginationContainer .page-link {
                    padding: 0.375rem 0.5rem;
                }
            }
        </style>

        <script>
            $('#input1').on('keypress', function(e) {
                return e.which !== 32;
            });

            let currentPage = 1;

            function getallusers(page = 1){
                let number = document.querySelector('#numofrecord').value;
                let searchvalue = document.querySelector('#searchitem').value;
                let ordervalue = document.querySelector('#order').value;
                let table = document.querySelector('#userslisttbl');
                let paginationContainer = document.querySelector('#paginationContainer');

                if (searchvalue == "") {
                    searchvalue = "query";
                } else {
                    searchvalue = searchvalue;
                }

                let url = "{{url('/admin/dashboard/getusers/')}}" + '/' + number + '/' + searchvalue + '/' + ordervalue + '?page=' + page;

                // Show loading state
                table.innerHTML = '<tr><td colspan="7" class="text-center"><i class="fas fa-spinner fa-spin"></i> Loading...</td></tr>';
                paginationContainer.innerHTML = '';

                fetch(url)
                    .then(function(res){
                        return res.json();
                    })
                    .then(function (response){
                        if(response.status === 200){
                            table.innerHTML = response.data;
                            paginationContainer.innerHTML = response.pagination || '';
                            document.querySelector('#searchitem').style.borderColor = '';
                            currentPage = page;

                            // Add click event listeners to pagination links
                            addPaginationEventListeners();
                        }
                        if(response.status === 201){
                            table.innerHTML = '<tr><td colspan="7" class="text-center text-muted">' + response.data + '</td></tr>';
                            paginationContainer.innerHTML = '';
                            document.querySelector('#searchitem').style.borderColor = 'red';
                        }
                    })
                    .catch(function(err){
                        console.log(err);
                        table.innerHTML = '<tr><td colspan="7" class="text-center text-danger">Error loading users. Please try again.</td></tr>';
                        paginationContainer.innerHTML = '';
                    });
            }

            function addPaginationEventListeners() {
                const paginationLinks = document.querySelectorAll('#paginationContainer .pagination .page-link');

                paginationLinks.forEach(link => {
                    link.addEventListener('click', function(e) {
                        e.preventDefault();

                        // Get the URL from the clicked link
                        const url = this.getAttribute('href');
                        if (!url || url === '#') return;

                        // Extract page number from URL
                        const urlParams = new URLSearchParams(url.split('?')[1]);
                        const page = urlParams.get('page');

                        if (page) {
                            getallusers(parseInt(page));
                        }
                    });
                });
            }

            // Event listeners for filters
            let numberopt = document.querySelector('#numofrecord');
            let searchbox = document.querySelector('#searchitem');
            let order = document.querySelector('#order');

            numberopt.addEventListener('change', function() {
                currentPage = 1;
                getallusers(1);
            });

            order.addEventListener('change', function() {
                currentPage = 1;
                getallusers(1);
            });

            searchbox.addEventListener('keyup', function() {
                currentPage = 1;
                getallusers(1);
            });

            // Initial load
            getallusers();

            function viewuser(id){
                let url = "{{url('/admin/dashboard/user-details/')}}" + '/' + id;
                window.location.href = url;
            }
        </script>

        <!-- send all users email -->
        <div id="sendmailModal" class="modal fade" role="dialog">
            <div class="modal-dialog">
                <!-- Modal content-->
                <div class="modal-content">
                    <div class="modal-header bg-{{Auth('admin')->User()->dashboard_style}}">
                        <h4 class="modal-title text-{{$text}}">This message will be sent to all your users.</h4>
                        <button type="button" class="close text-{{$text}}" data-dismiss="modal">&times;</button>
                    </div>
                    <div class="modal-body bg-{{Auth('admin')->User()->dashboard_style}}">
                        <form method="post" action="{{route('sendmailtoall')}}">
                            @csrf
                            <div class=" form-group">
                                <input type="text" name="subject" class="form-control bg-{{$bg}} text-{{$text}}" placeholder="Subject" required>
                            </div>
                            <div class=" form-group">
                                <textarea placeholder="Type your message here" class="form-control bg-{{$bg}} text-{{$text}}" name="message" row="8" placeholder="Type your message here" required></textarea>
                            </div>
                            <div class=" form-group">
                                <input type="submit" class="btn btn-{{$text}}" value="Send">
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <!-- /send all users email Modal -->
@endsection
