@extends('index')
@section('title', 'العربة')
@section('content')
    <div class="container-fluid" ng-app="ngApp" ng-controller="ngCtrl">
        <div class="cart">
            <div class="card card-box">
                <div class="card-body">
                    <div class="d-flex">
                        <h5 class="card-title fw-semibold pt-1 me-auto mb-3 text-uppercase">
                            <span class="spinner-border-sm text-warning me-2" role="status"></span><span>العربة</span>
                        </h5>
                        <div>
                            <button class="btn btn-outline-primary btn-circle bi bi-cart3"></button>
                        </div>
                    </div>
                    <div class="alert alert-success mb-3" role="alert" style="display: none"></div>
                    <div data-ng-if="cart.length" class="table-responsive">
                        <table class="table table-hover" id="example">
                            <thead>
                                <tr>
                                    <th class="text-center">#</th>
                                    <th class="text-center">الصورة</th>
                                    <th class="text-center">اسم المنتج</th>
                                    <th class="text-center">الكمية</th>
                                    <th class="text-center">سعر</th>
                                    <th class="text-center">اجمالي</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr class="record-item" data-ng-repeat="c in cart track by $index">
                                    <td class="text-center" ng-bind="c.product_code"></td>
                                    <td class="text-center">
                                        <div>
                                            <img src="http://127.0.0.1:8001/media/product/<%c.product_id%>/<%c.media_url%>"
                                                class="card-img-top" style="width: 60px;" />
                                        </div>
                                    </td>
                                    <td class="text-center" ng-bind="c.product_name"></td>
                                    <td class="text-center">
                                        <a href="" class="bi bi-dash-circle" ng-click="qty($index, -1)"></a>
                                        <input type="text" step="1" min="0" ng-model="c.qty"
                                            ng-change="numClean($index, 'qty')" class=" font-monospace text-center "
                                            data-default="0" maxlength="c.prodcolor_maxqty" minlength="c.prodcolor_minqty"
                                            ng-class="c.product_code" name="qty" id="qty" style="width: 60px;">
                                        <a href="" class="bi bi-plus-circle" ng-click="qty($index, 1)"></a>
                                    </td>
                                    <td class="text-center" ng-bind="c.prodsize_sellprice"></td>
                                    </td>
                                    <td class="text-center" id="subTotal" ng-bind="toFixed(c.qty*c.prodsize_sellprice)">
                                    </td>
                                    <td></td>
                                </tr>
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td class="text-center"><span class="text-success" id="total"
                                            ng-class="c.prodsize_id" ng-bind="getTotal()"></td>
                                    <td></td>

                                </tr>
                            </tfoot>
                        </table>

                    </div>
                    <div class="d-flex">
                        <div class="" style="width: 90%;"></div>
                        <div class="p-2 flex-shrink-2" data-ng-if="user"><button
                                class="d-flex justify-content-end btn btn-outline-primary" type="submit" id="submit"
                                ng-click="create()">ارسال الطلب</button></div>
                        <div class="p-2 flex-shrink-2" data-ng-if="!user">
                            <p class="text-body-tertiary">لا تمتلك حساب الارجاء انشاء حساب <a
                                    href="\account\">ان
                                    نشاء حساب</a></p>

                        </div>

                    </div>
                </div>


                <div data-ng-if="!cart.length"
                                    class="text-center text-secondary py-5">
                                    <i class="bi bi-exclamation-circle display-3"></i>
                                    <h5 class="">لا توجد طلبات</h5>
                        </div>
                        <!-- </form> -->

                    </div>
                </div>

            </div>
        @endsection

        @section('js')
            <script>
                let carts = JSON.parse(localStorage.getItem('cart')) ?? [],
                    userdata = JSON.parse(localStorage.getItem('user'));
                var scope,
                    app = angular.module('ngApp', [], function($interpolateProvider) {
                        $interpolateProvider.startSymbol('<%');
                        $interpolateProvider.endSymbol('%>');
                    });
                app.controller('ngCtrl', function($scope) {
                    $scope.user = userdata;
                    $scope.cart = carts;
                    $scope.cartCount = carts.length;
                    $scope.total = 0;
                    $scope.req = {
                        id: [],
                        qty: [],
                        price: [],
                        disc: [],
                        sub: [],
                    }

                    $scope.create = function() {
                        console.log($('#subTotal').text());
                        carts.map(e => {
                            $scope.req.id.push(e.prodsize_id);
                            $scope.req.qty.push(e.qty);
                            $scope.req.price.push(e.prodsize_sellprice);
                            $scope.req.disc.push(e.product_disc);
                        });
                        $.post('/create', {
                            _token: "{{ csrf_token() }}",
                            sizes: $scope.req.id.join(),
                            qty: $scope.req.qty.join(),
                            price: $scope.req.price.join(),
                            disc: $scope.req.disc.join(),
                            total: $('#total').text(),
                            customer: userdata.customer_id
                        }, function(response) {
                            if (response.status == true) {
                                localStorage.removeItem('cart');
                                $scope.cart = [];
                                toastr.success('تمت معالجة البيانات بنجاح');
                                setTimeout(location.replace("./"), 5000);

                            } else {
                                console.log(44)
                            }
                        }, 'Json')
                    }

                    // $('#submit').on('click', function(e) {

                    // });


                    $scope.toFixed = (num) => num.toFixed(2);

                    $scope.qty = function(index, op) {
                        var i = $scope.cart[index].qty + op;
                        $scope.cart[index].qty = i < 0 ? 0 : i;
                    }

                    $scope.getTotal = function() {
                        $scope.total = 0;
                        $scope.cart.map(c => {
                            $scope.total += +(c.qty * c.prodsize_sellprice).toFixed(2);
                        });
                        return $scope.total.toFixed(2);
                    }

                    $scope.numClean = function(index, k) {
                        $scope.cart[index][k] = ($scope.cart[index][k]).replace(/[a-z]+/i, '');
                    }

                    scope = $scope;
                    document.getElementById('cartCount').innerText = $scope.cartCount;
                });
            </script>
        @endsection
