@extends('index')
@section('title', 'View order')

@section('style')
    <style>
        .product-img {
            width: 90px;
            height: 90px;
            background-size: contain;
            background-position: center top;
            background-repeat: no-repeat;
            margin: 10px;
        }

        .qty-input {
            width: 50px;
            text-align: center;
        }
    </style>
@endsection

@section('content')
    <div class="container-fluid container" ng-app="ngApp" ng-controller="ngCtrl">
        <div class="row">
            <div class="col-12 col-lg-8">
                <div class="card card-box mb-3" ng-repeat="(pk, p) in parsedProducts">
                    <div class="card-body d-sm-flex">
                        {{-- <div class="product-img rounded mb-2"
                            style="background-image:url(/assets/img/default_product_image.png)">
                        </div> --}}

                        <div class="flex-fill">
                            <div class="d-flex">
                                <h6 class="fw-semibold pt-1 text-uppercase small me-auto">
                                    <span class="me-1"><%p.info.product_name%></span>
                                    <span class="text-secondary">#<%p.info.product_code%></span>
                                </h6>
                            </div>
                            <div class="table-responsive">
                                <table class="table table-hover sizes-table">
                                    <thead>
                                        <tr class="text-center small">
                                            <th class="text-start">Color</th>
                                            <th>Size</th>
                                            <th>Price</th>
                                            <th>Qty</th>
                                            <th>Total</th>
                                            <th></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr class="small text-center" ng-repeat="(sk, s) in p.sizes">
                                            <td ng-bind="s.prodcolor_name" class="text-uppercase text-start"></td>
                                            <td width="70" ng-bind="s.size_name">
                                            <td width="70" class="font-monospace" ng-bind="s.orderItem_productPrice">
                                            </td>
                                            <td class="col-fit">
                                                <input class="qty-input" ng-readonly="s.order_status > 2" type="text"
                                                    ng-model="s.orderItem_qty"
                                                    ng-blur="updateQty(s.orderItem_id, s.orderItem_qty, 0)">
                                            </td>
                                            <td width="80"
                                                ng-bind="fn.toFixed(s.orderItem_qty * s.orderItem_productPrice, 2)"
                                                class="text-center font-monospace"></td>
                                            <td class="col-fit">
                                                <a href="" class="link-danger bi bi-x"
                                                    ng-click="delSize(s.orderItem_id)"></a>
                                            </td>
                                        </tr>
                                    </tbody>
                                    <tfoot>
                                        <tr class="text-center font-monospace small">
                                            <td colspan="3"></td>
                                            <td ng-bind="p.qty">qty</td>
                                            <td ng-if="s.order_status > 2">qty</td>
                                            <td ng-bind="fn.sepNumber(p.total)">total</td>
                                            <td></td>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col order-lg-first">
                <div class="card card-box">
                    <div class="card-body product-block">
                        <h6 class="fw-semibold text-uppercase">
                            <span>ORDER</span> <span><%order.order_code%></span>
                        </h6>
                        <span class="text-primary"><%statusObject.name[order.order_status]%></span>
                        <p class="text-secondary mb-0 small">created: <span class="font-monospace"
                                ng-bind="fn.slice(order.order_created, 0, 16)"></span></p>
                        <p class="text-secondary mb-0 small" ng-if="order.order_placed">placed: <span class="font-monospace"
                                ng-bind="fn.slice(order.order_placed, 0, 16)"></span></p>
                        <hr>
                        <h6 class="fw-semibold text-uppercase">customer info</h6>
                        <p class="mb-0 small font-monospace text-seconday"><% customer.customer_code %></p>
                        <p class="mb-0 small"><% customer.customer_name %></p>
                        <p class="mb-0 small"><% customer.customer_email %></p>
                        <hr>
                        <table class="table small">
                            <tbody>
                                <tr ng-if="+order.order_discount">
                                    <td class="col-fit">Subtotal</td>
                                    <td class="text-end font-monospace">
                                        <span ng-bind="fn.sepNumber(order.order_subtotal)"></span>
                                    </td>
                                </tr>
                                <tr ng-if="+order.order_discount">
                                    <td class="col-fit">Discount</td>
                                    <td class="text-end font-monospace">
                                        <span ng-bind="fn.sepNumber(order.order_discount)"></span>
                                    </td>
                                </tr>
                                <tr class="fw-bold">
                                    <td class="col-fit">Total</td>
                                    <td class="text-end font-monospace">
                                        <span ng-bind="fn.sepNumber(order.order_total)"></span>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        <h6 class="font-monospace small">Qty: <span ng-bind="orderQty"></span></h6>
                        <div class="mt-4">
                            <form action="/orders/change_status" id="statusForm" method="post">
                                <input type="hidden" name="id" ng-value="order.order_id">
                                @csrf
                                <label for="orderStatus">Status</label>
                                <div class="input-group mb-3">
                                    <select id="orderStatus" name="status" class="form-select"
                                        ng-value="order.order_status">
                                        <option value="1">DRAFT</option>
                                        <option value="2">CANCELLED</option>
                                        <option value="3">PLACED</option>
                                        <option value="4">APPROVED</option>
                                        <option value="5">DELIVERED</option>
                                    </select>
                                    <button type="submit" class="btn btn-outline-dark bi bi-arrow-right"></button>
                                </div>
                            </form>
                            <script>
                                $(function() {
                                    $('#statusForm').on('submit', e => e.preventDefault()).validate({
                                        submitHandler: function(form) {
                                            var formData = new FormData(form),
                                                action = $(form).attr('action'),
                                                method = $(form).attr('method');

                                            scope.$apply(() => scope.statusSubmit = true);
                                            $(form).find('button').prop('disabled', true);
                                            $.ajax({
                                                url: action,
                                                type: method,
                                                data: formData,
                                                processData: false,
                                                contentType: false,
                                            }).done(function(data, textStatus, jqXHR) {
                                                var response = JSON.parse(data);
                                                scope.$apply(() => {
                                                    scope.statusSubmit = false;
                                                    if (response.status) {
                                                        toastr.success('Status changed successfully');
                                                        scope.order = response.data;
                                                    } else toastr.error(response.message);
                                                });
                                            }).fail(function(jqXHR, textStatus, errorThrown) {
                                                console.error("Error");
                                            });
                                        }
                                    });
                                });
                            </script>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('js')
    <script>
        var scope,
            app = angular.module('ngApp', ['ngSanitize'], function($interpolateProvider) {
                $interpolateProvider.startSymbol('<%');
                $interpolateProvider.endSymbol('%>');
            });

        app.controller('ngCtrl', function($scope) {
            $scope.fn = NgFunctions;
            $scope.statusObject = {
                name: ['', 'Draft', 'Canceled', 'Placed',
                    'APPROVED', 'DELIVERED'
                ],
            };

            $scope.statusSubmit = false;
            $scope.qtyUpdate = false;
            $scope.focusedQty = 0;
            $scope.orderDisc = 0;
            $scope.orderQty = 0;
            $scope.customer = <?= json_encode($customer) ?>;
            $scope.order = <?= json_encode($order) ?>;
            $scope.products = <?= json_encode($products) ?>;
            $scope.parsedProducts = {};
            $scope.parseProducts = function() {
                $scope.parsedProducts = {};
                $scope.orderQty = 0;
                $.map($scope.products, function(p) {
                    if (typeof $scope.parsedProducts[p.prodcolor_slug] == 'undefined')
                        $scope.parsedProducts[p.prodcolor_slug] = {
                            info: p,
                            sizes: [],
                            qty: 0,
                            total: 0
                        };
                    $scope.parsedProducts[p.prodcolor_slug].sizes.push(p);
                    $scope.parsedProducts[p.prodcolor_slug].qty += +p.orderItem_qty;
                    $scope.parsedProducts[p.prodcolor_slug].total += +p.orderItem_qty * +p
                        .orderItem_productPrice;
                    $scope.orderQty += +p.orderItem_qty;
                });
            }

            $scope.delSize = function(id) {
                if (!confirm('Are you sure to delete this item?')) return;
                $.post('/orders/del_size', {
                    order: $scope.order.order_id,
                    size: id,
                    _token: '{{ csrf_token() }}',
                }, function(response) {
                    $scope.$apply(function() {
                        if (response.status) {
                            $scope.order = response.order;
                            $scope.products = response.products;
                            $scope.parseProducts();
                        } else {
                            toastr.error('Error deleting please reload the page');
                            console.log(response.message);
                        }
                    });
                }, 'json');
            };

            $scope.updateQty = function(id, qty, target) {
                $scope.qtyUpdate = true;
                $.post('/orders/update_qty', {
                    order: $scope.order.order_id,
                    product: id,
                    qty: qty,
                    target: target,
                    _token: '{{ csrf_token() }}',
                }, function(response) {
                    $scope.$apply(function() {
                        $scope.qtyUpdate = false;
                        if (response.status) {
                            $scope.order = response.order;
                            $scope.products = response.products;
                            $scope.parseProducts();
                        } else {
                            toastr.error('Error updating qty please reload the page');
                            console.log(response.message);
                        }
                    });
                }, 'json');
            }
            $scope.parseProducts();
            scope = $scope;
        });
    </script>
@endsection
