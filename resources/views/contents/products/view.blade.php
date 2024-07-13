@extends('index')
@section('title')
    Product #{{ $product->product_code }}
@endsection
@section('search')
    <form id="nvSearch" role="search">
        <input type="search" name="q" class="form-control my-3 my-md-0 rounded-pill" placeholder="Search...">
    </form>
@endsection
<style>
    .image-container {
        position: relative;
        overflow: hidden;
        border-radius: 4%;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        transition: transform 0.3s, box-shadow 0.3s;
    }

    .image-container:hover {
        transform: scale(1);
        box-shadow: 0 8px 16px rgba(0, 0, 0, 0.3);
    }

    .beautiful-image {
        width: 100%;
        height: 50%;
        object-fit: cover;
        transition: transform 0.3s;
    }

    .image-container:hover .beautiful-image {
        transform: scale(1.1);
    }

    .linak {
        margin-left: 230px;
        padding: 10px;
        border-radius: 50%;
    }

    @media only screen and (max-width: 600px) {
        .linak {
            margin-left: 0px;
            padding: 0px;
            border-radius: none%;
        }
    }
</style>
@section('content')
    <div class="container-fluid" ng-app="ngApp" ng-controller="ngCtrl">
        <div class="row">
            <div class="col-12 col-sm-8 col-lg-12">
                <div class="card card-box">
                    <div class="card-body">
                        {{-- ref name --}}
                        <h3 class="text-body-tertiary">#<%product.product_code%></h3>
                        <hr>
                        {{-- start form product --}}
                        <form method="POST" id="wProductF" action="/products/submit">
                            @csrf
                            <input type="hidden" name="_method" value="put">
                            <input type="hidden" name="id" id="product_id" ng-value="product.product_id">
                            <div class="row">
                                <div class="col-3">
                                    <div class="mb-3">
                                        <label for="productName">
                                            Product Name <b class="text-danger">&ast;</b></label>
                                        <input type="text" class="form-control" name="name"
                                            ng-value="product.product_name" id="productName" />
                                    </div>
                                </div>

                                <div class="col-3">
                                    <div class="mb-3">
                                        <label for="productCode">
                                            Product Code <b class="text-danger">&ast;</b></label>
                                        <input type="text" class="form-control" name="code"
                                            ng-value="product.product_code" id="productCode" />
                                    </div>
                                </div>

                                <div class="col-3">
                                    <div class="mb-3">
                                        <label for="category">
                                            Category <b class="text-danger">&ast;</b></label>
                                        <select name="category" id="category" class="form-select" required>
                                            <option ng-value="product.category_id" ng-bind="product.category_name">
                                            </option>
                                            <option ng-repeat="category in categories" ng-value="category.category_id"
                                                ng-bind="category.category_name">
                                            </option>
                                        </select>
                                    </div>
                                </div>

                                <div class="col-3">
                                    <div class="mb-3">
                                        <label for="subcategory">
                                            Subcategory <b class="text-danger">&ast;</b></label>
                                        <select name="subcategory" id="subcategory" class="form-select" required>
                                            <option ng-value="product.subcategory_id" ng-bind="product.subcategory_name">
                                            </option>

                                        </select>
                                    </div>
                                </div>

                                <div class="col-12">
                                    <div class="mb-3">
                                        <label for="desc">
                                            Product Description </label>
                                        <textarea class="form-control" name="description" id="desc" cols="30" rows="7"><%product.product_desc%></textarea>
                                    </div>
                                </div>

                                <div class="d-flex mt-2">
                                    <div class="me-auto">
                                    </div>
                                    <button type="submit" class="btn btn-outline-primary">Update</button>
                                </div>
                        </form>
                        <script>
                            $('#wProductF').on('submit', e => e.preventDefault()).validate({
                                rules: {
                                    name: {
                                        required: true
                                    },
                                    reference: {
                                        required: true
                                    },
                                    season: {
                                        required: true
                                    },
                                    category: {
                                        required: true
                                    },
                                    order_type: {
                                        required: true
                                    }
                                },
                                submitHandler: function(form) {
                                    console.log(form);
                                    var formData = new FormData(form),
                                        action = $(form).attr('action'),
                                        method = $(form).attr('method');

                                    $(form).find('button').prop('disabled', true);
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
                                            scope.$apply(() => {
                                                if (scope.updateWProduct === false) {
                                                    scope.siezs.unshift(response
                                                        .data);
                                                } else {
                                                    scope.siezs[scope
                                                        .updateWProduct] = response.data;
                                                }
                                            });
                                        } else toastr.error(response.message);
                                    }).fail(function(jqXHR, textStatus, errorThrown) {
                                        console.log(textStatus)
                                        toastr.error("error");
                                    }).always(function() {
                                        $(form).find('button').prop('disabled', false);
                                    });
                                }
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
                        <hr class="mt-4 text-body-tertiary">

                    </div>
                </div>

            </div>

            {{-- start siezs section --}}
            <div class="mt-4">
                <div class="card card-box">
                    <div class="card-body">
                        <div class="d-flex">
                            <h5 class="card-title fw-semibold pt-1 me-auto mb-3 text-uppercase">
                                <span class="loading-spinner spinner-border spinner-border-sm text-warning me-2"
                                    role="status"></span><span>SIZES</span>
                            </h5>
                            <div>
                                <button type="button" class="btn btn-outline-primary btn-circle bi bi-plus-lg"
                                    ng-click="setSiez(false)"></button>
                                <button type="button" class="btn btn-outline-dark btn-circle bi bi-arrow-repeat"
                                    ng-click="load(true)"></button>
                            </div>
                        </div>

                        <div ng-if="siezs.length" class="table-responsive">
                            <table class="table table-hover" id="example">
                                <thead>
                                    <tr>
                                        <th class="text-center">#</th>
                                        <th class="text-center">Size Name</th>
                                        <th class="text-center">Color Name</th>
                                        <th class="text-center">Cost</th>
                                        <th class="text-center">Mai</th>
                                        <th class="text-center">Max</th>
                                        <th class="text-center">Sell price</th>
                                        <th class="text-center">Price</th>
                                        <th class="text-center">Qty </th>
                                        <th class="text-center">Status</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr ng-repeat="si in siezs track by $index">
                                        <input type="hidden" ng-id="id" ng-value="si.size_id">
                                        <td ng-bind="si.prodcolor_ref"
                                            class="text-center small font-monospace text-uppercase"></td>
                                        <td class="text-center" ng-bind="si.size_name"></td>
                                        <td class="text-center" ng-bind="si.prodcolor_name"></td>
                                        <td class="text-center" ng-bind="si.prodsize_cost"></td>
                                        <td class="text-center" ng-bind="si.prodcolor_minqty"></td>
                                        <td class="text-center" ng-bind="si.prodcolor_maxqty"></td>
                                        <td class="text-center" ng-bind="si.prodsize_price"></td>
                                        <td class="text-center" ng-bind="si.prodsize_sellprice"></td>
                                        <td class="text-center" ng-bind="si.prodsize_qty"></td>
                                        <td class="text-center">
                                            <span
                                                class="badge bg-<%statusObject.color[si.prodsize_status]%> rounded-pill font-monospace p-2"
                                                data-ng-model="si.prodsize_status" data-ng-click="toggle(si)"
                                                style="cursor: pointer">
                                                <%statusObject.name[si.prodsize_status]%>
                                            </span>
                                        </td>
                                        <td class="col-fit">
                                            <button class="btn btn-outline-primary btn-circle bi bi-pencil-square"
                                                ng-click="editSize($index)"></button>
                                        </td>
                                    </tr>
                                </tbody>

                            </table>
                        </div>
                        <div ng-if="!siezs.length" class="py-5 text-center text-secondary">
                            <i class="bi bi-exclamation-circle display-3"></i>
                            <h5>No Data</h5>
                        </div>
                    </div>

                    {{-- start add size model --}}
                    <div class="modal fade" id="sizeModal" tabindex="-1" role="dialog">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-body">
                                    <form method="post" id="sizeForm" action="/product_sizes/submit">
                                        @csrf
                                        <div class="row">
                                            <input type="hidden" name="p_id" ng-value="product.product_id">
                                            <input ng-if="updateSize !== false" type="hidden" name="_method"
                                                value="put">
                                            <input type="hidden" name="id" id="prodsizeId"
                                                ng-value="siezs[updateSize].prodsize_id">

                                            <div class="col-12 col-sm-12">
                                                <div class="mb-3">
                                                    <label for="colorName">Color Name<b
                                                            class="text-danger">&ast;</b></label>
                                                    <input type="text" class="form-control" name="name"
                                                        id="colorName">
                                                </div>
                                            </div>

                                            <div class="col-12 col-sm-6">
                                                <div class="mb-3">
                                                    <label for="cost">Cost <b class="text-danger">&ast;</b></label>
                                                    <input type="text" class="form-control" name="cost"
                                                        id="cost">
                                                </div>
                                            </div>

                                            <div class="col-12 col-sm-6">
                                                <div class="mb-3">
                                                    <label for="Qty">QTY <b class="text-danger">&ast;</b></label>
                                                    <input type="text" class="form-control" name="qty"
                                                        id="Qty">
                                                </div>
                                            </div>

                                            <div class="col-12 col-sm-6">
                                                <div class="mb-3">
                                                    <label for="price">Priec <b class="text-danger">&ast;</b></label>
                                                    <input type="text" class="form-control" name="price"
                                                        id="price">
                                                </div>
                                            </div>

                                            <div class="col-12 col-sm-6">
                                                <div class="mb-3">
                                                    <label for="sell">Sell Price <b
                                                            class="text-danger">&ast;</b></label>
                                                    <input type="text" class="form-control" name="sell"
                                                        id="sell">
                                                </div>
                                            </div>


                                            <div class="col-12 col-sm-6">
                                                <div class="mb-3">
                                                    <label for="min"> Min Order QTY</label>
                                                    <input type="text" class="form-control" name="min"
                                                        id="min">
                                                </div>
                                            </div>

                                            <div class="col-12 col-sm-6">
                                                <div class="mb-3">
                                                    <label for="max">Max Order QTY</label>
                                                    <input type="text" class="form-control" name="max"
                                                        id="max">
                                                </div>
                                            </div>

                                            <div class="col-12 col-sm-12">
                                                <div class="mb-3">
                                                    <label for="discount">Discount</label>
                                                    <input type="text" class="form-control" name="discount"
                                                        id="discount">
                                                </div>
                                            </div>


                                            <div class="col-12 col-md-6">
                                                <div class="mb-3">
                                                    <label>Discount Start</label>
                                                    <input id="subStart" type="text" class="form-control text-center"
                                                        name="start" />
                                                </div>
                                            </div>
                                            <div class="col-12 col-md-6">
                                                <div class="mb-3">
                                                    <label>Discount End</label>
                                                    <input id="subEnd" type="text" class="form-control text-center"
                                                        name="end">
                                                </div>
                                            </div>

                                            <div class="col-12 col-sm-12">
                                                <label for="size">Sizes<b class="text-danger">&ast;</b></label>
                                                <div class="form-check form-switch mb-5" style="display: inline-block"
                                                    ng-repeat="s in subcatesizes">
                                                    <input type="checkbox" name="size[]" ng-value="s.size_id">
                                                    <label for="size" ng-bind="s.size_name">Size<b
                                                            class="text-danger">&ast;</b></label>
                                                </div>
                                            </div>

                                        </div>

                                </div>
                                </form>
                                <div class="modal-footer d-flex">
                                    <button type="button" class="btn btn-outline-secondary me-auto"
                                        data-bs-dismiss="modal">Close</button>
                                    <button type="submit" form="sizeForm" class="btn btn-outline-primary"
                                        ng-disabled="submitting">Submit</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <script>
                        $(function() {
                            $('#sizeForm').on('submit', e => e.preventDefault()).validate({
                                rules: {
                                    name: {
                                        required: true
                                    },
                                    code: {
                                        required: true,
                                    },
                                    cost: {
                                        digits: true,
                                    },
                                    sell: {
                                        digits: true,
                                    },
                                    price: {
                                        digits: true,
                                    },
                                    min: {
                                        digits: true,
                                    },
                                    max: {
                                        digits: true,
                                    },
                                    discount: {
                                        digits: true,
                                    },
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
                                        console.log(response);
                                        scope.$apply(function() {
                                            scope.submitting = false;
                                            if (response.status) {
                                                toastr.success('Data processed successfully');
                                                $('#sizeModal').modal('hide');
                                                scope.load(true);
                                                sClsForm();
                                                scope.loadcolor(true);
                                                $('#sizeModal').modal('hide');
                                            } else toastr.error(response.message);
                                        });
                                    }).fail((jqXHR, textStatus, errorThrown) => toastr.error("Request failed!"));
                                }
                            });
                        });

                        function sClsForm() {
                            $('#prodsizeId').val('');
                            $('#colorName').val('');
                            $('#cost').val('');
                            $('#Qty').val('0');
                            $('#price').val('');
                            $('#sell').val('');
                            $('#max').val('');
                            $('#min').val('');
                        }
                        $("#subStart").datetimepicker($.extend({}, dtp_opt, {
                            showTodayButton: false,
                            format: "YYYY-MM-DD",
                        }));

                        $("#subEnd").datetimepicker($.extend({}, dtp_opt, {
                            showTodayButton: false,
                            format: "YYYY-MM-DD",
                        }));
                    </script>
                    {{-- end  add  size model --}}

                    {{-- start update size model --}}
                    <div class="modal fade" id="editSizeModal" tabindex="-1" role="dialog">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-body">
                                    <form method="post" id="sizeFormedit" action="/product_sizes/update">
                                        @csrf
                                        <div class="row">
                                            <input type="hidden" name="p_id" ng-value="product.product_id">
                                            <input ng-if="updateSize !== false" type="hidden" name="_method"
                                                value="put">
                                            <input type="hidden" name="id" id="prodsizeId"
                                                ng-value="siezs[updateSize].prodsize_id">

                                            <div class="col-12 col-sm-6">
                                                <div class="mb-3">
                                                    <label for="cost">Cost <b class="text-danger">&ast;</b></label>
                                                    <input type="text" class="form-control" name="cost"
                                                        ng-value="siezs[updateSize].prodsize_cost" id="cost">
                                                </div>
                                            </div>

                                            <div class="col-12 col-sm-6">
                                                <div class="mb-3">
                                                    <label for="Qty">QTY <b class="text-danger">&ast;</b></label>
                                                    <input type="text" class="form-control" name="qty"
                                                        ng-value="siezs[updateSize].prodsize_qty" id="Qty">
                                                </div>
                                            </div>

                                            <div class="col-12 col-sm-6">
                                                <div class="mb-3">
                                                    <label for="price">Priec <b class="text-danger">&ast;</b></label>
                                                    <input type="text" class="form-control" name="price"
                                                        ng-value="siezs[updateSize].prodsize_price" id="price">
                                                </div>
                                            </div>

                                            <div class="col-12 col-sm-6">
                                                <div class="mb-3">
                                                    <label for="sell">Sell Price <b
                                                            class="text-danger">&ast;</b></label>
                                                    <input type="text" class="form-control" name="sell"
                                                        id="sell" ng-value="siezs[updateSize].prodsize_sellprice">
                                                </div>
                                            </div>

                                            <div class="col-12 col-sm-12">
                                                <div class="mb-3">
                                                    <label for="discount">Discount</label>
                                                    <input type="text" class="form-control" name="discount"
                                                        id="discount" ng-value="siezs[updateSize].prodsize_discount">
                                                </div>
                                            </div>


                                            <div class="col-12 col-md-6">
                                                <div class="mb-3">
                                                    <label>Discount Start</label>
                                                    <input id="subStart" type="text" class="form-control text-center"
                                                        name="start"
                                                        ng-value="siezs[updateSize].prodsize_discount_start" />
                                                </div>
                                            </div>
                                            <div class="col-12 col-md-6">
                                                <div class="mb-3">
                                                    <label>Discount End</label>
                                                    <input id="subEnd" type="text" class="form-control text-center"
                                                        name="end" ng-value="siezs[updateSize].prodsize_discount_end">
                                                </div>
                                            </div>

                                            <div class="col-6">
                                                <div class="form-check form-switch mb-3">
                                                    <input class="form-check-input" type="checkbox" role="switch"
                                                        name="status" value="1"
                                                        ng-checked="+siezs[updateSize].prodsize_status">
                                                    <label class="form-check-label">product status </label>
                                                </div>
                                            </div>
                                        </div>

                                </div>
                                </form>
                                <div class="modal-footer d-flex">
                                    <button type="button" class="btn btn-outline-secondary me-auto"
                                        data-bs-dismiss="modal">Close</button>
                                    <button type="submit" form="sizeFormedit" class="btn btn-outline-primary"
                                        ng-disabled="submitting">Submit</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <script>
                        $(function() {
                            $('#sizeFormedit').on('submit', e => e.preventDefault()).validate({
                                rules: {
                                    cost: {
                                        digits: true,
                                    },
                                    sell: {
                                        digits: true,
                                    },
                                    price: {
                                        digits: true,
                                    },
                                    min: {
                                        digits: true,
                                    },
                                    max: {
                                        digits: true,
                                    },
                                    discount: {
                                        digits: true,
                                    },
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
                                        console.log(response);
                                        scope.$apply(function() {
                                            scope.submitting = false;
                                            if (response.status) {
                                                toastr.success('Data processed successfully');
                                                $('#editSizeModal').modal('hide');
                                                scope.load(true);
                                                $('#editSizeModal').modal('hide');
                                            } else toastr.error(response.message);
                                        });
                                    }).fail((jqXHR, textStatus, errorThrown) => toastr.error("Request failed!"));
                                }
                            });
                        });
                    </script>
                    {{-- end  update  size model --}}
                </div>
            </div>
        </div>
        {{-- end siezs section --}}

        {{-- start media section --}}
        <div class="mt-5">
            <div class="card card-box">
                <div class="card-body">
                    <div class="d-flex">
                        <h5 class="card-title fw-semibold pt-1 me-auto mb-3 text-uppercase">MEDIAS</h5>
                        <div>
                            <button type="button" class="btn btn-outline-primary btn-circle bi bi-plus"
                                data-bs-toggle="modal" data-bs-target="#mediaModal"></button>
                            <button type="button" class="btn btn-outline-dark btn-circle bi bi-arrow-repeat"
                                ng-click="loadProductMedia(true)"></button>
                        </div>
                    </div>

                    <div ng-if="medails.length" class="row" id="sortable">
                        <div ng-repeat="m in medails" class="col-6 col-sm-4 col-md-3 col-xl-2" data-id="<%m.media_id%>">
                            <div class="mb-3 text-center image-container">
                                <a data-ng-click="delete(m)" class="btn btn-dark linak p-2" style="border-radius: 50%"><i
                                        class="bi bi-x"></i></a>
                                <img src="{{ asset('media/product/') }}/<%m.media_product%>/<%m.media_url%>"
                                    class="card-img-top">
                                <div class="card-body" style="display:ruby-text">
                                    <input type="hidden" name="c_id" ng-value="m.prodcolor_id">
                                    <input type="hidden" name="m_id" ng-value="m.media_id">
                                    <input type="hidden" name="s" ng-value="m.prodcolor_media">
                                    <h6 class="card-title" ng-bind="m.prodcolor_name"></h6>

                                </div>
                            </div>
                        </div>
                    </div>
                    <div ng-if="!medails.length" class="py-5 text-center text-secondary">
                        <i class="bi bi-exclamation-circle display-3"></i>
                        <h5>No Data</h5>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="mediaModal" tabindex="-1" role="dialog">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-body">
                        <form method="post" id="mediaForm" action="/medias/submit">
                            @csrf
                            <input type="hidden" name="product_id" ng-value="product.product_id">

                            <div class="col-12 col-sm-12">
                                <div class="mb-3">
                                    <label for="media">Media<b class="text-danger">&ast;</b></label>
                                    <input type="file" class="form-control dropify" name="media[]" multiple
                                        id="media">
                                </div>
                            </div>

                        </form>
                        <div class="modal-footer d-flex">
                            <button type="button" class="btn btn-outline-secondary me-auto"
                                data-bs-dismiss="modal">Close</button>
                            <button type="submit" form="mediaForm" class="btn btn-outline-primary"
                                ng-disabled="submitting">Submit</button>
                        </div>
                    </div>
                </div>
            </div>
            <script>
                $('#mediaForm').on('submit', e => e.preventDefault()).validate({
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
                                    $('#mediaModal').modal('hide');
                                    scope.loadProductMedia(true);
                                } else toastr.error(response.message);
                            });
                        }).fail((jqXHR, textStatus, errorThrown) => toastr.error("Request failed!"));
                    }
                });
                $('.dropify').dropify();
            </script>
        </div>
        {{-- end media section --}}
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
                name: ['Un visible', 'Visible'],
                color: ['danger', 'success']
            };
            $('.loading-spinner').hide();
            $scope.q = '';
            $scope.updateSize = false;
            $scope.siezs = [];
            $scope.medails = [];
            $scope.subcatesizes = [];
            $scope.updateMedails = false;

            $scope.jsonParse = (str) => JSON.parse(str);
            $scope.product = <?= json_encode($product) ?>;
            console.log($scope.data);
            $scope.load = function(reload = false) {
                $('.loading-spinner').show();
                var request = {
                    q: $scope.q,
                    product_id: $scope.product.product_id,
                    _token: '{{ csrf_token() }}'
                };
                var sub = {
                    q: $scope.q,
                    subcategory: $scope.product.product_subcategory,
                    _token: '{{ csrf_token() }}'
                };


                $.post("/product_sizes/load", request, function(data) {
                    $('.loading-spinner').hide();
                    var ln = data.length;
                    $scope.$apply(() => {
                        $scope.loading = false;
                        if (ln) {
                            $scope.siezs = data;
                            console.log(data)
                        }
                    });
                }, 'json');


                $.post("/subcategories/get_sizes", sub, function(data) {
                    $('.loading-spinner').hide();
                    var ln = data.length;
                    $scope.$apply(() => {
                        $scope.loading = false;
                        if (ln) {
                            $scope.subcatesizes = data;

                        }
                    });
                }, 'json');
            }

            $scope.setSiez = (indx) => {
                $scope.updateSize = indx;
                $('#sizeModal').modal('show');
            };

            $scope.editStatus = (index) => {
                $scope.updateSize = index;
                $('#editStatus').modal('show');
            }

            $scope.editSize = (index) => {
                $scope.updateSize = index;
                $('#editSizeModal').modal('show');
            }

            $scope.loadProductMedia = function(reload = false) {
                $('.loading-spinner').show();
                var request = {
                    product_id: $scope.product.product_id,
                    _token: '{{ csrf_token() }}'
                };

                $.post("/medias/load", request, function(data) {
                    $('.loading-spinner').hide();
                    var ln = data.length;
                    $scope.$apply(() => {
                        if (ln) {
                            $scope.medails = data;
                            console.log(data)
                        }
                    });
                }, 'json');
            }




            $scope.toggle = function(item) {
                var data = {
                    id: item.prodsize_id,
                    status: item.prodsize_visible,
                    pr_id: $scope.product.product_id,
                    _token: '{{ csrf_token() }}'
                };

                $.post('/product_sizes/edit_status', data)
                    .then(function(response) {
                        var data = JSON.parse(response);
                        scope.$apply(function() {
                            scope.submitting = false;
                            if (data.status) {
                                toastr.success('Data processed successfully');
                                $scope.data = data.data
                                $scope.load()
                            } else toastr.error(data.message);
                        });
                    })
                    .catch(function(error) {
                        console.error('Error occurred:', error);
                    });
            };

            $scope.delete = function(media) {
                var request = {
                    id: media.media_id,
                    product_id: $scope.product.product_id,
                    _token: '{{ csrf_token() }}'
                };
                $.post('/medias/delete', request)
                    .then(function(response) {
                        var data = JSON.parse(response);
                        scope.$apply(function() {
                            if (response) {
                                toastr.success('Delete Media successfully');
                                scope.loadProductMedia(true);
                            } else toastr.error(response.message);
                        });
                    })
                    .catch(function(error) {
                        console.error('Error occurred:', error);
                    });
            }

            $scope.load();
            $scope.loadProductMedia();
            scope = $scope;
        });

        $('#nvSearch').on('submit', function(e) {
            e.preventDefault();
            scope.$apply(() => scope.q = $(this).find('input').val());
            scope.load(true);
        });
    </script>
@endsection
