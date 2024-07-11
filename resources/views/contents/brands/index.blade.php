@extends('index')
@section('title', 'Brands')
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
                        <div class="mb-3">
                            <label for="statusFilter">Brand Code</label>
                            <input type="text" id="code-filter" class="form-control">
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-12 col-sm-8 col-lg-9">
                <div class="card card-box">
                    <div class="card-body">
                        <div class="d-flex">
                            <h5 class="card-title fw-semibold pt-1 me-auto mb-3 text-uppercase">BRANDS</h5>
                            <div>
                                <button type="button" class="btn btn-outline-primary btn-circle bi bi-plus-lg"
                                    ng-click="setBrand(false)"></button>
                                <button type="button" class="btn btn-outline-dark btn-circle bi bi-arrow-repeat"
                                    ng-click="load(true)"></button>
                            </div>
                        </div>

                        <div ng-if="list.length" class="table-responsive">
                            <table class="table table-hover" id="example">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Name</th>
                                        <th class="text-center">Status</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr ng-repeat="brand in list track by $index">
                                        <td ng-bind="brand.brand_code" class="small font-monospace text-uppercase">
                                        </td>
                                        <td ng-bind="brand.brand_name">
                                        </td>
                                        <td class="text-center">
                                            <span
                                                class="badge bg-<%statusObject.color[brand.brand_status]%> rounded-pill font-monospace p-2"><%statusObject.name[brand.brand_status]%></span>

                                        </td>
                                        <td class="col-fit">
                                            <button class="btn btn-outline-primary btn-circle bi bi-pencil-square"
                                                ng-click="setBrand($index)"></button>
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

        <div class="modal fade" id="brandModal" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false"
            role="dialog">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-body">
                        <form id="brandForm" method="post" action="/brands/submit">
                            @csrf
                            <input ng-if="updatebrand !== false" type="hidden" name="_method" value="put">
                            <input type="hidden" name="id" ng-value="list[updatebrand].brand_id">
                            <div class="row">
                                <div class="col-12 col-md-12">
                                    <div class="mb-3">
                                        <label for="fullName">brand Name<b class="text-danger">&ast;</b></label>
                                        <input id="fullName" name="name" class="form-control" maxlength="120"
                                            ng-value="list[updatebrand].brand_name" required>
                                    </div>
                                </div>


                                <div class="col-6">
                                    <div class="form-check form-switch mb-3">
                                        <input class="form-check-input" type="checkbox" role="switch" name="status"
                                            value="1" ng-checked="+list[updatebrand].brand_status" id="brandStatus">
                                        <label class="form-check-label" for="brandStatus">Status</label>
                                    </div>
                                </div>
                            </div>
                        </form>
                        <div class="modal-footer d-flex">
                            <button type="button" class="btn btn-outline-secondary me-auto"
                                data-bs-dismiss="modal">Close</button>
                            <button type="submit" form="brandForm" class="btn btn-outline-primary"
                                ng-disabled="submitting">Submit</button>
                            <span class="spinner-border spinner-border-sm text-warning ms-2" role="status"
                                ng-if="submitting"></span>
                        </div>
                    </div>
                </div>

                <script>
                    $(function() {
                        $('#brandForm').on('submit', e => e.preventDefault()).validate({
                            rules: {
                                password: {
                                    password: true
                                },
                                password_confirm: {
                                    equalTo: "#password"
                                }
                            },
                            submitHandler: function(form) {
                                var formData = new FormData(form),
                                    action = $(form).attr('action'),
                                    method = $(form).attr('method');

                                scope.$apply(() => scope.submitting = true);

                                $.ajax({
                                    url: action,
                                    type: method,
                                    data: formData,
                                    processData: false,
                                    contentType: false,
                                }).done(function(data, textStatus, jqXHR) {
                                    var response = JSON.parse(data);
                                    scope.$apply(() => {
                                        scope.submitting = false;
                                        if (response.status) {
                                            if (scope.updatebrand === false) scope.list.unshift(
                                                response.data);
                                            else scope.list[scope.updatebrand] = response.data;
                                            toastr.success('Data processed successfully');
                                            $('#brandModal').modal('hide');
                                        } else toastr.error(response.message);
                                    });
                                }).fail(function(jqXHR, textStatus, errorThrown) {
                                    toastr.error(jqXHR.responseJSON.message);
                                });
                            }
                        });
                    });
                </script>
            </div>
        </div>

    </div>
@endsection

@section('js')
    <script>
        var scope,
            app = angular.module('ngApp', [], function($interpolateProvider) {
                $interpolateProvider.startSymbol('<%');
                $interpolateProvider.endSymbol('%>');
            });

        app.controller('ngCtrl', function($scope) {
            $scope.statusObject = {
                name: ['Un Available', 'Available'],
                color: ['danger', 'success']
            };

            $scope.submitting = false;
            $scope.noMore = false;
            $scope.loading = false;
            $scope.q = '';
            $scope.updatebrand = false;
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
                    code: $('#code-filter').val(),
                    _token: '{{ csrf_token() }}'
                };

                $.post("/brands/load", request, function(data) {

                    var ln = data.length;
                    $scope.$apply(() => {
                        $scope.loading = false;
                        $scope.noMore = ln < limit;
                        if (ln) {
                            $scope.list.push(...data);
                            $scope.last_id = data[ln - 1].brand_id;
                        }
                    });
                }, 'json');
            }

            $scope.setBrand = (indx) => {
                $scope.updatebrand = indx;
                $('#brandModal').modal('show');
            };

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
