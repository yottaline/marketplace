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
                                        <th class="text-center">Size Cost</th>
                                        <th class="text-center">WSP</th>
                                        <th class="text-center">RRP</th>
                                        <th class="text-center">Size Qty </th>
                                        <th class="text-center">AVAILABLE QUANTITY</th>
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
                                        <td style="width:100px">
                                            <input type="text" class="font-monospace text-center w-100"
                                                ng-model="si.prodsize_wsp" ng-blur="updatePrice(si)">
                                        </td>
                                        <td class="text-center" ng-bind="si.prodsize_rrp"></td>
                                        <td class="text-center" ng-bind="si.prodsize_qty"></td>
                                        <td class="text-center" ng-bind="si.prodsize_stock"></td>
                                        <td class="text-center">
                                            <span
                                                class="badge bg-<%statusObject.color[si.prodsize_visible]%> rounded-pill font-monospace p-2"
                                                data-ng-model="si.prodsize_visible" data-ng-click="toggle(si)"
                                                style="cursor: pointer">
                                                <%statusObject.name[si.prodsize_visible]%>
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
                                                    <input type="text" class="form-control"
                                                        ng-value="siezs[updateSize].prodcolor_name" name="name"
                                                        id="colorName">
                                                </div>
                                            </div>


                                            <div class="col-12 col-sm-6">
                                                <div class="mb-3">
                                                    <label for="Wholesale">Wholesale <b
                                                            class="text-danger">&ast;</b></label>
                                                    <input type="text" class="form-control" ng-model="wsp"
                                                        ng-value="siezs[updateSize].prodsize_wsp" name="wholesale"
                                                        id="Wholesale">
                                                </div>
                                            </div>

                                            <div class="col-12 col-sm-6">
                                                <div class="mb-3">
                                                    <label for="Recommanded">RRP <b class="text-danger">&ast;</b></label>
                                                    <input type="text" class="form-control" ng-value="to(wsp , 2.4)"
                                                        name="rrp" id="Recommanded" readonly>
                                                </div>
                                            </div>


                                            <input type="hidden" name="ps_size"
                                                ng-value="siezs[updateSize].prodsize_size">
                                            <div class="col-12 col-sm-12">
                                                <label for="size">Sizes<b class="text-danger">&ast;</b></label>
                                                <div class="form-check form-switch mb-5" style="display: inline-block"
                                                    ng-repeat="s in allsizes">
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
                                    wholesale: {
                                        digits: true,
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
                            $('#Wholesale').val('');
                            $('#Recommanded').val('0');
                        }
                    </script>
                    {{-- end  add  size model --}}

                    {{-- start update size model --}}
                    <div class="modal fade" id="editSizeModal" tabindex="-1" role="dialog">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-body">
                                    <form method="post" id="sizeFormedit" action="/product_sizes/submit">
                                        @csrf
                                        <div class="row">
                                            <input type="hidden" name="p_id" ng-value="product.product_id">
                                            <input ng-if="updateSize !== false" type="hidden" name="_method"
                                                value="put">
                                            <input type="hidden" name="id" id="prodsizeId"
                                                ng-value="siezs[updateSize].prodsize_id">
                                            <input type="hidden" name="color_id" id="prodsizeId"
                                                ng-value="siezs[updateSize].prodcolor_id">

                                            {{-- <div class="col-12 col-sm-12">
                                                <div class="mb-3">
                                                    <label for="colorName">Color Name<b
                                                            class="text-danger">&ast;</b></label>
                                                    <input type="text" class="form-control"
                                                        ng-value="siezs[updateSize].prodcolor_name" name="name"
                                                        id="colorName">
                                                </div>
                                            </div> --}}

                                            <div class="col-6">
                                                <div class="mb-3">
                                                    <label for="minQtyForColor">
                                                        Mini order quantity per-order <b
                                                            class="text-danger">&ast;</b></label>
                                                    <input type="text" class="form-control" name="mincolorqty"
                                                        ng-value="siezs[updateSize].prodcolor_mincolorqty"
                                                        id="minQtyForColor" />
                                                </div>
                                            </div>

                                            <div class="col-6">
                                                <div class="mb-3">
                                                    <label for="productMinQty">
                                                        Product Min Qty <b class="text-danger">&ast;</b></label>
                                                    <input type="text" class="form-control" name="minqty"
                                                        ng-value="siezs[updateSize].prodcolor_minqty"
                                                        id="productMinQty" />
                                                </div>
                                            </div>

                                            <div class="col-6">
                                                <div class="mb-3">
                                                    <label for="productMaxQty">
                                                        Product Max Qty <b class="text-danger">&ast;</b></label>
                                                    <input type="text" class="form-control" name="maxqty"
                                                        ng-value="siezs[updateSize].prodcolor_maxqty"
                                                        id="productMaxQty" />
                                                </div>
                                            </div>

                                            <div class="col-6">
                                                <div class="mb-3">
                                                    <label for="productMinOrder">
                                                        Product Min Order </label>
                                                    <input type="text" class="form-control" name="minorder"
                                                        ng-value="siezs[updateSize].prodcolor_minorder"
                                                        id="productMinOrder" />
                                                </div>
                                            </div>

                                            <div class="col-6">
                                                <div class="mb-3">
                                                    <label for="productDiscount">
                                                        Product Discount <b class="text-danger">&ast;</b></label>
                                                    <input type="text" class="form-control" name="discount"
                                                        ng-value="siezs[updateSize].prodcolor_discount"
                                                        id="productDiscount" />
                                                </div>
                                            </div>

                                            <div class="col-12 col-sm-6">
                                                <div class="mb-3">
                                                    <label for="Qty">in-stock <b
                                                            class="text-danger">&ast;</b></label>
                                                    <input type="text" class="form-control"
                                                        ng-value="siezs[updateSize].prodsize_qty" name="qty"
                                                        id="Qty">
                                                </div>
                                            </div>


                                            <div class="col-6">
                                                <div class="form-check form-switch mb-3">
                                                    <input class="form-check-input" type="checkbox" role="switch"
                                                        name="freeshipping" value="1"
                                                        ng-checked="+siezs[updateSize].prodcolor_freeshipping">
                                                    <label class="form-check-label">Free Shipping </label>
                                                </div>
                                            </div>

                                            <div class="col-6">
                                                <div class="form-check form-switch mb-3">
                                                    <input class="form-check-input" type="checkbox" role="switch"
                                                        name="color_status" value="1"
                                                        ng-checked="+siezs[updateSize].prodcolor_published">
                                                    <label class="form-check-label">Color product status </label>
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
                                    name: {
                                        required: true
                                    },
                                    code: {
                                        required: true,
                                    },
                                    cost: {
                                        digits: true,
                                        required: true,
                                    },
                                    mincolorqty: {
                                        digits: true,
                                        required: true,
                                    },
                                    minqty: {
                                        digits: true,
                                        required: true
                                    },
                                    maxqty: {
                                        digits: true,
                                        required: true
                                    },
                                    minorder: {
                                        digits: true,
                                        required: true
                                    },
                                    discount: {
                                        digits: true
                                    },
                                    order: {
                                        required: true
                                    },
                                    size: {
                                        required: true
                                    },
                                    wholesale: {
                                        digits: true,
                                    },
                                    qty: {
                                        digits: true,
                                    },
                                    stock: {
                                        digits: true,
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
                            <form action="/product_medias/image_default" method="post">
                                @csrf
                                {{-- <input type="hidden" name="_token" value="{{ csrf_token() }}"> --}}
                                <div class="mb-3 text-center image-container">
                                    <img src="{{ asset('media/product/') }}/<%m.media_product%>/<%m.media_file%>"
                                        class="card-img-top beautiful-image">
                                    <div class="card-body" style="display:ruby-text">
                                        <input type="hidden" name="c_id" ng-value="m.prodcolor_id">
                                        <input type="hidden" name="m_id" ng-value="m.media_id">
                                        <input type="hidden" name="s" ng-value="m.prodcolor_media">
                                        <h6 class="card-title" ng-bind="m.prodcolor_name"></h6>
                                        <button class="btn" style="padding-top:1px"
                                            ng-if="m.prodcolor_media == null || m.prodcolor_media !== m.media_id"><i
                                                class="bi bi-bookmark"></i></button>
                                        <button class="btn" style="padding-top:1px"
                                            ng-if="+m.prodcolor_media == m.media_id"><i
                                                class="bi bi-bookmark-star-fill"></i></button>
                                    </div>
                                </div>
                            </form>
                            <script>
                                $('#sortable form').on('submit', function(e) {
                                    e.preventDefault();
                                    var form = $(this),
                                        formData = new FormData(this),
                                        action = form.attr('action'),
                                        method = form.attr('method'),
                                        controls = form.find('button, input');
                                    $.ajax({
                                        url: action,
                                        type: method,
                                        data: formData,
                                        processData: false,
                                        contentType: false,
                                    }).done(function(data, textStatus, jqXHR) {
                                        var response = JSON.parse(data);
                                        if (response.status) {
                                            toastr.success('Actived successfully');
                                            scope.loadProductMedia(true)
                                        }
                                    }).fail(function(jqXHR, textStatus, errorThrown) {
                                        // toastr.error("error");
                                    }).always(function() {});

                                })
                            </script>
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
                        <form method="post" id="mediaForm" action="/product_medias/submit">
                            @csrf
                            <input type="hidden" name="product_id" ng-value="product.product_id">
                            <div class="row">
                                <div class="col-12 col-sm-12">

                                    <div class="mb-3">
                                        <label for="category">
                                            Color <b class="text-danger">&ast;</b></label>
                                        <select name="color" id="color" class="form-select" required>
                                            <option ng-repeat="color in colors" ng-value="color.prodcolor_ref">
                                                <%color.prodcolor_name%>
                                            </option>
                                        </select>
                                    </div>
                                    {{-- <div class="mb-3">
                                        <label for="color">Color</label>
                                        <input type="text" class="form-control font-monospace" name="color"
                                            id="color">
                                    </div> --}}
                                </div>
                                {{--
                                <div class="col-12 col-sm-6">
                                    <div class="mb-3">
                                        <label for="order">Order<b class="text-danger">&ast;</b></label>
                                        <input type="text" class="form-control" name="order" id="order">
                                    </div>
                                </div> --}}

                                <div class="col-12 col-sm-12">
                                    <div class="mb-3">
                                        <label for="media">Media<b class="text-danger">&ast;</b></label>
                                        <input type="file" class="form-control dropify" name="media[]" multiple
                                            id="media">
                                    </div>
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
            $scope.colors = [];
            $scope.medails = [];
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
                $.post("/product_medias/load", request, function(data) {
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

            $scope.loadcolor = function(reload = false) {
                var request = {
                    ref: $scope.product.product_ref,
                    _token: '{{ csrf_token() }}'
                };
                $.post("/product_medias/get_color", request, function(data) {
                    $scope.$apply(() => {
                        $scope.colors = data;
                    });
                }, 'json');
            }

            $scope.to = function(wsp, rrp) {
                if (wsp == null) return 0;
                return (wsp * rrp).toFixed(2);
            };

            $scope.updatePrice = function(item) {
                item.prodsize_wsp = parseFloat(item.prodsize_wsp);

                var data = {
                    id: item.prodsize_id,
                    newPrice: item.prodsize_wsp,
                    pr_id: $scope.product.product_id,
                    _token: '{{ csrf_token() }}'
                };

                $.post('/product_sizes/update', data)
                    .then(function(response) {
                        var data = JSON.parse(response);
                        scope.$apply(function() {
                            scope.submitting = false;
                            if (data.status) {
                                toastr.success('Data processed successfully');
                                $scope.data = data.data
                                // console.log()
                                scope.load()
                            } else toastr.error(data.message);
                        });
                    })
                    .catch(function(error) {
                        console.error('Error occurred:', error);
                    });
            };

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

            $scope.load();
            $scope.loadProductMedia();
            $scope.loadcolor();
            scope = $scope;
        });

        $('#nvSearch').on('submit', function(e) {
            e.preventDefault();
            scope.$apply(() => scope.q = $(this).find('input').val());
            scope.load(true);
        });

        $(function() {
            setTimeout(() => {
                // $("#sortable").sortable();
                $("#sortable").disableSelection();
                $('#sortable').sortable({
                    connectWith: '#sortable',
                    update: function(event, ui) {
                        var orders = [];
                        $('#sortable').children().each(function(index, element) {
                            orders.push($(element).data('id'));
                        });
                        $.post('/product_medias/order', {
                            orders: orders,
                            _token: '{{ csrf_token() }}'
                        }, function(data) {
                            var response = JSON.parse(data);
                            if (response.status) {
                                toastr.success('Media Ordered successfully');

                            } else toastr.error(response.message);
                        });
                    }
                });
            }, 5000);
        });
    </script>
@endsection
