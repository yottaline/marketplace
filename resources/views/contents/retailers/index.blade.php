@extends('index')
@section('title', 'Reatilers')
@section('search')
    <form id="nvSearch" role="search">
        <input type="search" name="q" class="form-control my-3 my-md-0 rounded-pill" placeholder="Search...">
    </form>
@endsection
@section('content')
    <div class="container-fluid" ng-app="ngApp" ng-controller="ngCtrl">
        <div class="row">
            <div class="col-12 col-sm-4 col-lg-3">
                <div class="card card-box">
                    <div class="card-body">
                        <div class="d-flex">
                            <h5 class="card-title fw-semibold pt-1 me-auto mb-3 text-uppercase">
                                <span class="text-warning" role="status"></span><span>FILTERS</span>
                            </h5>
                            <div>
                                <button type="button" class="btn btn-outline-dark btn-circle bi bi-funnel"></button>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="statusFilter">Retailer status</label>
                            <select class="form-select" id="status-filter">
                                <option value="0">-- SELECT STATUS --</option>
                                <option value="1">Not Approved</option>
                                <option value="2">Approved</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-12 col-sm-8 col-lg-9">
                <div class="card card-box">
                    <div class="card-body">
                        <div class="d-flex">
                            <h5 class="card-title fw-semibold pt-1 me-auto mb-3 text-uppercase">RETAILERS</h5>
                            <div>
                                <button type="button" class="btn btn-outline-primary btn-circle bi bi-plus-lg"
                                    ng-click="setRetailer(false)"></button>
                                <button type="button" class="btn btn-outline-dark btn-circle bi bi-arrow-repeat"
                                    ng-click="load(true)"></button>
                            </div>
                        </div>

                        <div ng-if="list.length" class="table-responsive">
                            <table class="table table-hover" id="example">
                                <thead>
                                    <tr>
                                        <th class="text-center">#</th>
                                        <th>Full Name</th>
                                        <th class="text-center">Stroe</th>
                                        <th class="text-center">Address</th>
                                        <th class="text-center">Apperoved Date</th>
                                        <th class="text-center">Status</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr ng-repeat="retailer in list track by $index">
                                        <td ng-bind="retailer.user_code"
                                            class="text-center small font-monospace text-uppercase"></td>
                                        <td>
                                            <span ng-bind="retailer.user_name" class="fw-bold"></span><br>
                                            <small ng-if="+retailer.retailer_phone"
                                                class="me-1 db-inline-block dir-ltr font-monospace badge bg-primary">
                                                <i class="bi bi-phone me-1"></i>
                                                <span ng-bind="retailer.retailer_phone" class="fw-normal"></span>
                                            </small>
                                            <small ng-if="retailer.user_email"
                                                class="db-inline-block dir-ltr font-monospace badge bg-primary">
                                                <i class="bi bi-envelope-at me-1"></i>
                                                <span ng-bind="retailer.user_email" class="fw-normal"></span>
                                            </small>
                                        </td>
                                        <td class="text-center" ng-bind="retailer.retailer_store"></td>
                                        <td class="text-center" ng-bind="retailer.retailer_address"></td>
                                        <td class="text-center">
                                            <span ng-if="retailer.retailer_approved == null">Not Approved</span>
                                            <span ng-if="retailer.retailer_approved != null"
                                                ng-bind="retailer.retailer_approved"></span>
                                        </td>

                                        <td class="text-center">
                                            <span
                                                class="badge bg-<%statusObject.color[retailer.retailer_status]%> rounded-pill font-monospace p-2"><%statusObject.name[retailer.retailer_status]%></span>

                                        </td>
                                        <td class="col-fit">

                                            <button class="btn btn-outline-primary btn-circle bi bi-pencil-square"
                                                ng-click="setRetailer($index)"></button>
                                            <button ng-if="retailer.retailer_approved == null"
                                                class="btn btn-outline-dark btn-circle bi bi-shield-fill-check"
                                                ng-click="editApproved($index)"></button>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        @include('layouts.loader')

                    </div>
                </div>
            </div>
        </div>

        {{-- start add and update model --}}
        <div class="modal fade" id="retailerForm" tabindex="-1" role="dialog" data-bs-backdrop="static"
            data-bs-keyboard="false" aria-labelledby="retailerFormLabel">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-body">
                        <form method="POST" id="modalForm" action="/retailers/submit">
                            @csrf
                            <input ng-if="updateRetailer !== false" type="hidden" name="_method" value="put">
                            <input type="hidden" name="retailer_id" id="retailer_id"
                                ng-value="list[updateRetailer].retailer_id">
                            <input type="hidden" name="id" id="id" ng-value="list[updateRetailer].id">
                            <div class="row">

                                <div class="col-6">
                                    <div class="mb-3">
                                        <label for="retailerName">
                                            Full Name <b class="text-danger">&ast;</b></label>
                                        <input type="text" class="form-control" name="name" required
                                            ng-value="list[updateRetailer].user_name" id="retailerName" />
                                    </div>
                                </div>

                                <div class="col-6">
                                    <div class="mb-3">
                                        <label for="email">
                                            Email <b class="text-danger">&ast;</b></label>
                                        <input type="email" class="form-control" name="email" required
                                            ng-value="list[updateRetailer].user_email" id="email" />
                                    </div>
                                </div>

                                <div class="col-6">
                                    <div class="mb-3">
                                        <label for="password">
                                            Password <b class="text-danger">&ast;</b></label>
                                        <input type="password" class="form-control" name="password" id="password" />
                                    </div>
                                </div>

                                <div class="col-6">
                                    <div class="mb-3">
                                        <label for="phone">
                                            Phone <b class="text-danger">&ast;</b></label>
                                        <input type="text" class="form-control" name="phone"
                                            ng-value="list[updateRetailer].retailer_phone" id="phone" />
                                    </div>
                                </div>

                                <div class="col-6">
                                    <div class="mb-3">
                                        <label for="storeName">
                                            Store Name <b class="text-danger">&ast;</b></label>
                                        <input type="text" class="form-control" name="store_name"
                                            ng-value="list[updateRetailer].retailer_store" id="storeName" />
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="mb-3">
                                        <label for="storeMobile">
                                            Store Mobile</label>
                                        <input type="text" class="form-control" name="store_mobile"
                                            ng-value="list[updateRetailer].retailer_mobile" id="storeMobile" />
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="mb-3">
                                        <label for="logo">
                                            Logo</label>
                                        <input type="file" class="form-control" name="logo" id="logo" />
                                    </div>
                                </div>

                                <div class="col-12">
                                    <div class="mb-3">
                                        <label for="address">
                                            Address <b class="text-danger">&ast;</b></label>
                                        <input type="text" class="form-control" name="address"
                                            ng-value="list[updateRetailer].retailer_address" id="address" />
                                    </div>
                                </div>

                                <div class="col-12">
                                    <div class="form-check form-switch mb-3">
                                        <input class="form-check-input" type="checkbox" role="switch" name="vat"
                                            value="1" ng-checked="+list[updateRetailer].retailer_vat"
                                            id="retailerVat">
                                        <label class="form-check-label" for="retailerVat">Has VAT</label>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer d-flex">
                        <button type="button" class="btn btn-outline-secondary me-auto"
                            data-bs-dismiss="modal">Close</button>
                        <button type="submit" form="modalForm" class="btn btn-outline-primary"
                            ng-disabled="submitting">Submit</button>
                        <span class="spinner-border spinner-border-sm text-warning ms-2" role="status"
                            ng-if="submitting"></span>
                    </div>
                </div>
                <script>
                    $(function() {
                        $('#retailerForm form').on('submit', function(e) {
                            e.preventDefault();
                            var form = $(this),
                                formData = new FormData(this),
                                action = form.attr('action'),
                                method = form.attr('method');

                            scope.$apply(() => scope.submitting = true);
                            $.ajax({
                                url: action,
                                type: method,
                                data: formData,
                                processData: false,
                                contentType: false,
                            }).done(function(data, textStatus, jqXHR) {
                                var response = JSON.parse(data);
                                if (response.status) {
                                    toastr.success('Data processed successfully');
                                    $('#retailerForm').modal('hide');
                                    scope.$apply(() => {
                                        scope.submitting = false;
                                        if (scope.updateRetailer === false) {
                                            scope.list.unshift(response
                                                .data);
                                            scope.load(true);
                                            retailerClsForm()
                                        } else {
                                            scope.list[scope
                                                .updateRetailer] = response.data;
                                        }
                                    });
                                } else toastr.error(response.message);
                            }).fail(function(jqXHR, textStatus, errorThrown) {
                                // error msg
                            })
                        })
                    });

                    function retailerClsForm() {
                        $('#retailer_id').val('');
                        $('#retailerName').val('');
                        $('#email').val('');
                        $('#password').val('');
                        $('#phone').val('');
                        $('#storeName').val('');
                        $('#storeMobile').val('');
                        $('#logo').val('');
                        $('#address').val('');
                    }
                </script>
            </div>
        </div>
        {{-- end add and update model --}}
    </div>
@endsection

@section('js')
    <script>
        var scope, timeout,
            app = angular.module('ngApp', [], function($interpolateProvider) {
                $interpolateProvider.startSymbol('<%');
                $interpolateProvider.endSymbol('%>');
            });

        app.controller('ngCtrl', function($scope, $timeout) {
            $scope.statusObject = {
                name: ['Available', 'Blacked'],
                color: ['success', 'danger']
            };

            $scope.submitting = false;
            $scope.noMore = false;
            $scope.loading = false;
            $scope.q = '';
            $scope.updateRetailer = false;
            $scope.list = [];
            $scope.last_id = 0;

            $scope.jsonParse = (str) => JSON.parse(str);
            $scope.load = function(reload = false) {
                if (reload) {
                    $scope.list = [];
                    $scope.last_id = 0;
                    $scope.noMore = false;
                }

                if ($scope.noMore) return;
                $scope.loading = true;

                var request = {
                    q: $scope.q,
                    last_id: $scope.last_id,
                    limit: limit,
                    status: $('#status-filter').val(),
                    _token: '{{ csrf_token() }}'
                };

                $.post("/retailers/load", request, function(data) {
                    var ln = data.length;
                    $scope.$apply(() => {
                        $scope.loading = false;
                        $scope.noMore = ln < limit;
                        if (ln) {
                            $scope.list.push(...data);
                            $scope.last_id = data[ln - 1].retailer_id;
                        }
                    });
                }, 'json');
            }

            $scope.setRetailer = (indx) => {
                $scope.updateRetailer = indx;
                $('#retailerForm').modal('show');
            };

            $scope.editApproved = (index) => {
                $scope.updateRetailer = index;
                $('#editApproved').modal('show');
            }
            $scope.load();
            scope = $scope;
        });

        $('#nvSearch').on('submit', function(e) {
            e.preventDefault();
            scope.$apply(() => scope.q = $(this).find('input').val());
            scope.load(true);
        });
    </script>
@endsection
