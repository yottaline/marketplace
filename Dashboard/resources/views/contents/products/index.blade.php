@extends('index')
@section('title', 'Products')

@section('search')
    <form id="nvSearch" role="search">
        <input type="search" name="q" class="form-control my-3 my-md-0 rounded-pill" placeholder="Search...">
    </form>
@endsection

@section('content')
    <div class="container-fluid" ng-app="ngApp" ng-controller="ngCtrl">
        <div class="row">
            <div class="col-12 col-sm-4 col-lg-3 col-xl-2">
                <div class="card card-box">
                    <div class="card-body">
                        <div class="mb-3">
                            <label for="category-filter">{{ __('Category') }}<b class="text-danger">&ast;</b></label>
                            <select name="category" id="category-filter" class="form-select" required>
                                <option value="default">-- {{ __('SELECT CATEGORY NAME') }} --</option>
                                <option ng-repeat="c in categories" ng-bind="c.category_name" ng-value="c.category_id">
                                </option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="product-filter">{{ __('Product Name') }}</label>
                            <input type="text" class="form-control" id="product-filter">
                        </div>
                    </div>
                </div>
            </div>

            <div class="col">
                <div class="card card-box">
                    <div class="card-body">
                        <div class="d-flex">
                            <h5 class="card-title fw-semibold pt-1 me-auto mb-3 text-uppercase">{{ __('PRODUCTS') }}</h5>
                            <div>
                                <button type="button" class="btn btn-outline-primary btn-circle bi bi-plus"
                                    data-bs-toggle="modal" data-bs-target="#formModal"></button>
                                <button type="button" class="btn btn-outline-dark btn-circle bi bi-arrow-repeat"
                                    ng-click="load(true)"></button>
                            </div>
                        </div>

                        <div ng-if="list.length" class="row">
                            <div ng-repeat="p in list" class="col-6 col-sm-4 col-md-3 col-xl-2" id="swapDemo">
                                <div class="mb-3 text-center" id="items">
                                    <a href="/products/view/<% p.product_code %>" class="card">
                                        <img ng-if="p.media_id == null" src="/assets/img/default_product_image.png"
                                            alt="" class="card-img-top">
                                        <img ng-if="p.media_id"
                                            src="{{ asset('media/product/') }}/<% p.product_id %>/<% p.media_url %>"
                                            alt="" class="card-img-top">
                                        <div class="card-body">
                                            <h6 class="card-title" ng-bind="p.product_name"></h6>
                                            <h6 class="small font-monospace" ng-bind="p.product_code"></h6>
                                            <h6 class="small font-monospace" ng-bind="p.prodcolor_name"></h6>
                                        </div>
                                    </a>
                                </div>
                            </div>
                        </div>

                        @include('layouts.loader')
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="formModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
            role="dialog">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-body">
                        <form method="post" id="modalForm" action="/products/submit">
                            @csrf
                            <div class="row">
                                <div class="col-12 col-sm-6">
                                    <div class="mb-3">
                                        <label for="productCode">{{ __('Code') }}<b class="text-danger">&ast;</b></label>
                                        <input type="text" class="form-control font-monospace" name="code"
                                            id="productCode">
                                    </div>
                                </div>
                                <div class="col-12 col-sm-6">
                                    <div class="mb-3">
                                        <label for="productName">{{ __('Name') }}<b class="text-danger">&ast;</b></label>
                                        <input type="text" class="form-control" name="name" id="productName">
                                    </div>
                                </div>
                                <div class="col-12 col-sm-6">
                                    <div class="mb-3">
                                        <label for="category">{{ __('Category') }}<b class="text-danger">&ast;</b></label>
                                        <select name="category" id="category" class="form-select" required>
                                            <option value="default">-- {{ __('SELECT CATEGORY NAME') }} --</option>
                                            <option ng-repeat="c in categories" ng-bind="c.category_name"
                                                ng-value="c.category_id"></option>
                                        </select>
                                    </div>
                                </div>

                                <div class="col-12 col-sm-6">
                                    <div class="mb-3">
                                        <label for="subcategory">{{ __('Subcategory') }}<b
                                                class="text-danger">&ast;</b></label>
                                        <select name="subcategory" id="subcategory" class="form-select" required>

                                        </select>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer d-flex">
                        <div class="me-auto">
                            <button type="submit" form="modalForm" class="btn btn-outline-primary btn-sm"
                                ng-disabled="submitting">{{ __('Submit') }}</button>
                            <span class="spinner-border spinner-border-sm text-warning ms-2" role="status"
                                ng-if="submitting"></span>
                        </div>
                        <button type="button" class="btn btn-outline-secondary btn-sm" data-bs-dismiss="modal"
                            ng-disabled="submitting">{{ __('Close') }}</button>
                    </div>
                </div>
            </div>
            <script>
                $(function() {
                    $('#modalForm').on('submit', e => e.preventDefault()).validate({
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
                                scope.$apply(function() {
                                    scope.submitting = false;
                                    if (response.status) {
                                        toastr.success('Data processed successfully');
                                        scope.list.unshift(response.data);
                                        productClsForm();
                                        $('#formModal').modal('hide');
                                    } else toastr.error(response.message);
                                });
                            }).fail((jqXHR, textStatus, errorThrown) => toastr.error("Request failed!"));
                        }
                    });
                });


                function productClsForm() {
                    $('#productName').val('');
                    $('#productCode').val('');
                }
            </script>
        </div>
    </div>
@endsection

@section('js')
    <script>
        var scope,
            ngApp = angular.module('ngApp', [], function($interpolateProvider) {
                $interpolateProvider.startSymbol('<%');
                $interpolateProvider.endSymbol('%>');
            });

        ngApp.controller('ngCtrl', function($scope) {
            $scope.noMore = false;
            $scope.loading = false;
            $scope.q = '';
            $scope.submitting = false;
            $scope.list = [];
            $scope.offset = 0;

            $scope.jsonParse = (str) => JSON.parse(str);
            $scope.categories = <?= json_encode($categories) ?>;
            $scope.load = function(reload = false) {
                if (reload) {
                    $scope.list = [];
                    $scope.offset = 0;
                    $scope.noMore = false;
                }

                if ($scope.noMore) return;
                $scope.loading = true;

                var request = {
                    q: $scope.q,
                    offset: $scope.offset,
                    limit: limit,
                    categoy: $('#categoy-filter').val(),
                    p_name: $('#product-filter').val(),
                    _token: '{{ csrf_token() }}'
                };

                $.post("/products/load", request, function(data) {
                    var ln = data.length;
                    $scope.$apply(() => {
                        $scope.loading = false;
                        $scope.noMore = ln < limit;
                        if (ln) {
                            $scope.list.push(...data);
                            $scope.offset += ln;
                        }
                    });
                }, 'json');
            }

            scope = $scope;
            $scope.load();
        });

        $('#nvSearch').on('submit', function(e) {
            e.preventDefault();
            scope.$apply(() => scope.q = $(this).find('input').val());
            scope.load(true);
        });

        $('#category').on('change', function() {
            var idState = this.value;
            $('#subcategory').html('');
            $.ajax({
                url: '/products/subcategory/' + idState,
                type: 'GET',
                dataType: 'json',
                success: function(res) {
                    $.each(res, function(key, value) {
                        $('#subcategory').append('<option id="class" value="' + value
                            .subcategory_id +
                            '">' + value.subcategory_name + '</option>');
                    });
                }
            });
        });
    </script>
@endsection
