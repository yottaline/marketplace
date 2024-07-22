@extends('index')
@section('title', __('Orders'))
@section('search')
    <form id="nvSearch" role="search">
        <input type="search" name="q" class="form-control my-3 my-md-0 rounded-pill" placeholder="Search...">
    </form>
@endsection
@section('style')
    <style>
        .table>:not(:first-child) {
            border-top: 1px solid #ccc !important;
        }

        tbody input,
        tfoot input {
            padding: 5px;
            border: 1px dashed #ccc !important;
            outline: none !important;
        }

        #inv-item-input {
            padding-left: 35px
        }

        #items-selector {
            position: absolute;
            width: 100%;
            max-height: 200px;
            overflow-y: auto;
            background-color: #fff;
            border: 1px solid #ccc;
            border-top: 0;
            box-shadow: 2px 2px 2px #eee;
        }

        #items-selector>.items-list>a {
            border-right: 5px solid transparent;
            text-decoration: none;
            display: block;
            color: #2d2d2d;
            padding: 5px 10px;
        }

        #items-selector>.items-list>a:focus {
            border-right-color: #2d2d2d;
            background-color: #f8f8f8;
        }
    </style>
@endsection
@section('content')
    <div class="container-fluid" data-ng-app="myApp" data-ng-controller="myCtrl">
        <div class="row">
            <div class="col-12 col-sm-4 col-lg-3">
                <div class="card card-box">
                    <div class="card-body">
                        <div class="mb-3">
                            <label for="roleFilter">{{ __('Customer Name') }}</label>
                            <input type="text" class="form-control" id="filter-name">
                        </div>

                        <div class="mb-3">
                            <label for="roleFilter">{{ __('Order Date') }}</label>
                            <input type="text" id="filterOrderDate" class="form-control text-center text-monospace">
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-12 col-sm-8 col-lg-9">
                <div class="card card-box">
                    <div class="card-body">
                        <div class="d-flex">
                            <h5 class="card-title fw-semibold pt-1 me-auto  text-uppercase">
                                <span class="loading-spinner spinner-border spinner-border-sm text-warning me-2"
                                    role="status"></span><span>{{ __('ORDERS') }}</span>
                            </h5>
                            {{-- @csrf --}}
                            <div>
                                <a href="/orders/create" class="btn btn-outline-primary btn-circle bi bi-plus-lg"></a>
                                {{-- <button type="button" id="exportData"
                                    class="btn btn-outline-success btn-circle bi bi-filetype-xlsx"></button> --}}
                                @csrf
                                <button type="button" class="btn btn-outline-dark btn-circle bi bi-arrow-repeat"
                                    data-ng-click="load(true)"></button>
                            </div>
                        </div>
                        <div data-ng-if="list.length" class="table-responsive">
                            <table class="table table-hover" id="example">
                                <thead>
                                    <tr>
                                        {{-- <th></th> --}}
                                        <th class="text-center">{{ __('Code') }}</th>
                                        <th class="text-center">{{ __('Customer Name') }}</th>
                                        <th class="text-center">{{ __('Placed') }}</th>
                                        <th class="text-center">{{ __('Total') }}</th>
                                        <th class="text-center">{{ __('Status') }}</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr data-ng-repeat="order in list" ng-dblclick="view(order)">
                                        {{-- <td class="text-center">
                                            <input class="form-check-input order-checkbox" type="checkbox"
                                                ng-value="order.order_id">
                                        </td> --}}
                                        <td data-ng-bind="order.order_code"
                                            class="text-center small font-monospace text-uppercase">
                                        </td>
                                        <td class="text-center" data-ng-bind="order.customer_name">
                                        </td>
                                        <td class="text-center">
                                            <span ng-if="!order.order_approved"> --</span>
                                            <span ng-if="order.order_approved" data-ng-bind="order.order_approved"></span>
                                        </td>
                                        <td data-ng-bind="order.order_subtotal" class="text-center font-monospace"></td>
                                        <td class="text-center">
                                            <span class="rounded-pill"><%statusObject.name[order.order_status]%></span>
                                        </td>
                                        <td class="col-fit">
                                            <a href="/orders/view/<%order.order_id%>" target="_blank"
                                                class="btn btn-outline-dark btn-circle bi bi-eye"></a>
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
    </div>
@endsection
@section('js')
    <script>
        var scope,
            app = angular.module('myApp', [], function($interpolateProvider) {
                $interpolateProvider.startSymbol('<%');
                $interpolateProvider.endSymbol('%>');
            });

        app.controller('myCtrl', function($scope) {
            $scope.statusObject = {
                name: ['', 'Draft', 'Canceled', 'Placed',
                    'APPROVED', 'DELIVERED'
                ],
            };
            $('.loading-spinner').hide();
            $scope.noMore = false;
            $scope.loading = false;
            $scope.q = '';
            $scope.updateOrders = false;
            $scope.list = [];
            $scope.products = [];
            $scope.orderDisc = 0;
            $scope.orderDetails = [];
            $scope.orDe = [];
            $scope.last_id = 0;
            $scope.jsonParse = (str) => JSON.parse(str);
            $scope.customers = <?= json_encode($customers) ?>;
            $scope.load = function(reload = false) {
                if (reload) {
                    $scope.list = [];
                    $scope.last_id = 0;
                    $scope.noMore = false;
                }

                if ($scope.noMore) return;
                $scope.loading = true;

                $('.loading-spinner').show();
                var request = {
                    date: $('#filterOrderDate').val(),
                    r_name: $('#filter-name').val(),
                    q: $scope.q,
                    last_id: $scope.last_id,
                    limit: limit,
                    _token: '{{ csrf_token() }}'
                };

                $.post("/orders/load", request, function(data) {
                    $('.loading-spinner').hide();
                    var ln = data.length;
                    $scope.$apply(() => {
                        $scope.loading = false;
                        $scope.noMore = ln < limit;
                        if (ln) {
                            $scope.list.push(...data);
                            $scope.last_id = data[ln - 1].order_id;
                        }
                    });
                }, 'json');
            }

            $scope.setOrder = (indx) => {
                $scope.updateOrders = indx;
                $('#orderModal').modal('show');
            };

            $scope.view = function(order) {
                window.open('/orders/view/' + order.order_id);
            }

            $scope.delProduct = (index) => $scope.products.splice(index, 1);

            $scope.clTotal = function() {
                var total = 0;
                $scope.products.map(p => total += p.prodcolor_mincolorqty * p.prodsize_wsp);
                var totals = total - (total * $scope.orderDisc / 100);
                return totals.toFixed();
            }

            $scope.to = function(prodcolor_price, pecAmount) {
                return (pecAmount * prodcolor_price).toFixed();
            };

            $scope.load();
            scope = $scope;
        });

        $(function() {
            $('#nvSearch').on('submit', function(e) {
                e.preventDefault();
                scope.$apply(() => scope.q = $(this).find('input').val());
                scope.load(true);
            });
        });
    </script>
@endsection
