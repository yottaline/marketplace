@extends('index')
@section('title', 'الامنتجات')
{{-- @section('search')
    <form id="nvSearch" role="search">
        <input type="search" name="q" class="form-control my-3 my-md-0 rounded-pill" placeholder="Search...">
    </form>
@endsection --}}
@section('style')
    <style>
        .filter-options label {
            display: block;
            margin-bottom: 5px;
        }

        form #input-wrap {
            margin: 0px;
            padding: 0px;
        }

        input#number {
            text-align: center;
            border: none;
            border-top: 1px solid #ddd;
            border-bottom: 1px solid #ddd;
            margin: 0px;
            width: 40px;
            height: 40px;
        }

        input[type=number]::-webkit-inner-spin-button,
        input[type=number]::-webkit-outer-spin-button {
            -webkit-appearance: none;
            margin: 0;
        }
    </style>
@endsection

@section('content')
    <div class="container-fluid" ng-app="ngApp" ng-controller="ngCtrl">
        <div class="row">
            <div class="col-12 col-sm-4 col-lg-2 h-100" style="border-left: 2px solid#000">
                <div>
                    <label class="mb-3">الفئات الفرعية</label>
                    <ul data-ng-repeat="sub in subcategories">
                        <div class="filter-options">
                            <label><input type="checkbox" value="sub.subcategory_id"
                                    style="margin-left: 12px"><%sub.subcategory_name%>
                            </label>
                        </div>
                    </ul>
                </div>
            </div>

            <div class="col-12 col-sm-8 col-lg-10">
                <div class="row" ng-if="products.length">
                    <div class="col-lg-2 col-dm-6" data-ng-repeat="product in products">
                        <div class="card">
                            <div class="card-footer position-relative bg-transparent" ng-if="!product.prodsize_qty">
                                <button class="position-absolute top-0 start-0 btn text-warning">نفدت
                                    الكمية في المخزن</button>
                            </div>
                            <img class="m-3"
                                src="http://127.0.0.1:8001/media/product/<%product.product_id%>/<%product.media_url%>">
                            <div class="card-body">
                                <h5 class="card-title text-center" ng-bind="product.product_name"></h5>
                                <p class="card-text" ng-bind="product.product_desc"></p>
                                <p><%product.prodsize_sellprice%> <span ng-bind="product.prodsize_price"></span>
                                </p>

                            </div>
                            <div class="card-footer position-relative mb-5 bg-transparent flex-row">
                                <button class="position-absolute top-15 start-10 btn btn-outline-dark btn-circle bi bi-eye"
                                    ng-click="viewProdcut(product)"></button>
                                <button class="position-absolute top-15 btn btn-outline-success btn-circle bi bi-cart4"
                                    style="left:15px" ng-click="addToCart(product)"></button>
                            </div>
                        </div>
                    </div>
                </div>
                <div>
                    @include('layouts.loader')
                </div>
            </div>
        </div>

        <div class="modal fade" id="viewProdcut" tabindex="-1" role="dialog">
            <div class="modal-dialog modal-lg modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-body">
                        <div class="row">

                            <div class="col-6">
                                <h3 ng-bind="product.product_name" class="mt-3"></h3>
                                <p class="p-2 text-slate-600">Lorem ipsum dolor sit amet consectetur adipisicing elit.
                                    Quidem qui voluptates eos repellendus quam rerum est tempora ullam rem veniam a ratione
                                    in ab perferendis suscipit possimus corporis, explicabo deserunt.</p>
                                <h5 class="price"><%product.prodsize_sellprice%>RS<s style="font-size:15px"
                                        class="me-2"><%product.prodsize_price%>RS</s></h5>

                                <div class="mt-5 clearfix">
                                    <div class="float-start">
                                        <button class="btn" onclick="decreaseValue()">-</button>
                                        <input type="number" id="number" value="0" />
                                        <button class="btn"onclick="increaseValue()">+</button>
                                    </div>

                                    <button type="button" ng-click="addToCart(product)"
                                        class="btn btn-outline-success me-2 float-end bi bi-cart4">اضافة
                                        الي
                                        العربة</button>
                                </div>

                            </div>

                            <div class="col-6">
                                <div id="carouselExample" class="carousel slide">
                                    <div class="carousel-inner">
                                        <div class="carousel-item active" data-ng-repeat="m in media">
                                            <img src="http://127.0.0.1:8001/media/product/<%product.product_id%>/<%m.media_url%>"
                                                class="d-block w-100" alt="...">
                                        </div>
                                    </div>
                                    <button class="carousel-control-prev" type="button" data-bs-target="#carouselExample"
                                        data-bs-slide="prev">
                                        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                        <span class="visually-hidden">Previous</span>
                                    </button>
                                    <button class="carousel-control-next" type="button" data-bs-target="#carouselExample"
                                        data-bs-slide="next">
                                        <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                        <span class="visually-hidden">Next</span>
                                    </button>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
@endsection

@section('js')
    <script>
        let carts = JSON.parse(localStorage.getItem('cart')) ?? [];
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

            $scope.noMore = false;
            $scope.loading = false;
            $scope.q = '';
            $scope.productupdate = false;
            $scope.product = false;
            $scope.products = [];
            $scope.media = [];
            $scope.cartProducts = carts.map((e, i) => e.product_id);
            $scope.cartCount = carts.length;
            $scope.last_id = 0;

            $scope.jsonParse = (str) => JSON.parse(str);
            $scope.subcategories = <?= json_encode($subcategories) ?>;
            $scope.load = function(reload = false) {
                if (reload) {
                    $scope.products = [];
                    $scope.last_id = 0;
                    $scope.noMore = false;
                }

                if ($scope.noMore) return;
                $scope.loading = true;

                var request = {
                    q: $scope.q,
                    last_id: $scope.last_id,
                    sub: $scope.subcategories.map((e, i) => e.subcategory_id),
                    limit: limit,
                    code: $('#code-filter').val(),
                    _token: '{{ csrf_token() }}'
                };

                $.post("/products/fetch", request, function(data) {

                    var ln = data.length;
                    $scope.$apply(() => {
                        $scope.loading = false;
                        $scope.noMore = ln < limit;
                        if (ln) {
                            $scope.products.push(...data);
                            $scope.last_id = data[ln - 1].prodsize_id;
                        }
                    });
                }, 'json');
            }

            $scope.viewProdcut = (p) => {
                $.get('/get_medias/' + p.product_id, function(response) {
                    $scope.$apply(function() {
                        if (response) {
                            $scope.media = response;
                            $scope.product = p;
                        } else {
                            console.log(response.message);
                        }
                    });
                }, 'json');
                $('#viewProdcut').modal('show');
            };


            $scope.addToCart = function(product) {
                console.log(product)
                product.qty = $('#number').val() != 0 ? parseInt($('#number').val(), 10) : 1;
                carts.push(product);
                $scope.cartProducts.push(product.product_id);
                localStorage.setItem('cart', JSON.stringify(carts))
                scope.cartCount = carts.length;
                document.getElementById('cartCount').innerText = carts.length;
                toastr.success('تم اضافة المنتج بنجاح');
            }

            document.getElementById('cartCount').innerText = $scope.cartCount;

            $scope.load();
            scope = $scope;
        });

        $('#nvSearch').on('submit', function(e) {
            e.preventDefault();
            scope.$apply(() => scope.q = $(this).find('input').val());
            scope.load(true);
        });

        function increaseValue() {
            var value = parseInt(document.getElementById('number').value, 10);
            value = isNaN(value) ? 0 : value;
            value++;
            document.getElementById('number').value = value;
        }

        function decreaseValue() {
            var value = parseInt(document.getElementById('number').value, 10);
            value = isNaN(value) ? 0 : value;
            value < 1 ? value = 1 : '';
            value--;
            document.getElementById('number').value = value;
        }
    </script>
@endsection
