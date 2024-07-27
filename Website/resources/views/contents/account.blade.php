@extends('index')
@section('title', 'حساب')
@section('content')
    <div class="container-fluid" ng-app="ngApp" ng-controller="ngCtrl">
        <div class="row">
            <div class="col-12 col-sm-4 col-lg-2">
                @include('contents.side_bar')
            </div>
            <div class="col">
                <div class="card card-box">
                    <div class="card-body">
                        <h5 class="card-title fw-bold text-uppercase mb-4">
                            <span class="me-2">حساب</span>
                            <span class="font-monospace text-secondary fw-light small">#<%user.customer_code%></span>
                        </h5>
                        <div class="row" ng-if="user">
                            <div class="col-12 col-sm-6">
                                <div class="mb-3">
                                    <strong class="text-secondary">الاسم الكامل</strong>
                                    <p class="fw-bold" ng-bind="user.customer_name"></p>
                                </div>
                                <div class="mb-3">
                                    <strong class="text-secondary">عنوان البريد الإلكتروني</strong>
                                    <p class="fw-bold" ng-bind="user.customer_email"></p>
                                </div>
                                <div class="mb-3">
                                    <strong class="text-secondary">اتصال \ رقم الهاتف</strong>
                                    <p class="fw-bold" ng-bind="user.customer_phone">0192282828</p>
                                </div>
                                <div class="mb-3">
                                    <strong class="text-secondary">عنوان</strong>
                                    <p class="fw-bold" ng-bind="user.customer_address">0192282828</p>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-12">
                                <div class="mb-4">
                                    <div class="card small">
                                        <div class="card-body">
                                            <h6 class="card-title small text-uppercase">
                                                <span ng-if="submitBillAddress"
                                                    class="spinner-border text-primary spinner-border-sm me-2"
                                                    role="status"></span>
                                                <span>انشاء \ تعديل</span>
                                            </h6>
                                            <div>
                                                <form method="POST" id="modalForm" action="/customers/submit">
                                                    @csrf
                                                    <div class="row">
                                                        <div class="col-6">
                                                            <div class="mb-3">
                                                                <label for="customerName">
                                                                    الاسم الكامل <b class="text-danger">&ast;</b></label>
                                                                <input type="text" class="form-control" name="name"
                                                                    required id="customerName" />
                                                            </div>
                                                        </div>

                                                        <div class="col-6">
                                                            <div class="mb-3">
                                                                <label for="emailE">
                                                                    عنوان البريد الإلكتروني<b
                                                                        class="text-danger">&ast;</b></label>
                                                                <input type="email" class="form-control" name="email"
                                                                    required id="emailE" />
                                                            </div>
                                                        </div>

                                                        <div class="col-6">
                                                            <div class="mb-3">
                                                                <label for="password">
                                                                    كلمة المرور <b class="text-danger">&ast;</b></label>
                                                                <input type="password" class="form-control" name="password"
                                                                    required id="password" />
                                                            </div>
                                                        </div>

                                                        <div class="col-6">
                                                            <div class="mb-3">
                                                                <label for="phone">
                                                                    رقم الهاتف <b class="text-danger">&ast;</b></label>
                                                                <input type="text" class="form-control" name="phone"
                                                                    required id="phone" />
                                                            </div>
                                                        </div>

                                                        <div class="col-12">
                                                            <div class="mb-3">
                                                                <label for="address">
                                                                    العنوان</label>
                                                                <input type="text" class="form-control" name="address"
                                                                    required id="address" />
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <button type="submit" form="modalForm"
                                                        class="btn btn-outline-primary">ارسال</button>

                                                </form>
                                                <script>
                                                    $(function() {
                                                        $('#modalForm').on('submit', e => e.preventDefault()).validate({
                                                            rules: {
                                                                customerName: {
                                                                    required: true
                                                                },
                                                                phone: {
                                                                    digits: true
                                                                },
                                                                emailE: {
                                                                    required: true
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
                                                                            localStorage.setItem('user', JSON.stringify(response
                                                                                .data))
                                                                            toastr.success('تمت معالجة البيانات بنجاح');
                                                                        } else toastr.error(response.message);
                                                                    });
                                                                }).fail(function(jqXHR, textStatus, errorThrown) {
                                                                    toastr.error(jqXHR.responseJSON.message);
                                                                });
                                                            }
                                                        });
                                                    });

                                                    function customerClsForm() {
                                                        $('#customer_id').val('');
                                                        $('#customerName').val('');
                                                        $('#email').val('');
                                                        $('#password').val('');
                                                        $('#phone').val('');
                                                        $('#address').val('');
                                                    }
                                                </script>
                                            </div>

                                        </div>
                                    </div>
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
        let carts = JSON.parse(localStorage.getItem('cart')) ?? [],
            userdata = JSON.parse(localStorage.getItem('user'));
        var scope,
            app = angular.module('ngApp', [], function($interpolateProvider) {
                $interpolateProvider.startSymbol('<%');
                $interpolateProvider.endSymbol('%>');
            });

        app.controller('ngCtrl', function($scope) {
            $scope.cartCount = carts.length;
            $scope.user = userdata;
            $scope.jsonParse = (str) => JSON.parse(str);
            document.getElementById('cartCount').innerText = $scope.cartCount;

            scope = $scope;
        });
    </script>
@endsection
