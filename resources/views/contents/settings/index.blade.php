@extends('index')
@section('title', 'Settings')
@section('content')
    <div class="container-fluid" data-ng-app="ngApp" data-ng-controller="ngCtrl">

        {{-- start brand and category section  --}}
        <div class="card card-box">
            <div class="card-body">
                <div class="row">
                    <div id="brandBox" class="col-12 col-sm-6 col-lg-6">
                        <div class="list-box border p-3">
                            <div class="d-flex">
                                <h5 class="card-title fw-semibold pt-1 me-auto mb-3">
                                    <span class="loading-spinner spinner-border spinner-border-sm text-warning me-2"
                                        role="status"></span><span>BRANDS</span>
                                </h5>
                                <div>
                                    <button type="button" class="btn btn-outline-primary btn-circle bi bi-plus-lg"
                                        data-ng-click="setBrand(false)"></button>
                                    <button type="button" class="btn btn-outline-dark btn-circle bi bi-arrow-repeat"
                                        data-ng-click="loadBrandsData(true)"></button>
                                </div>

                            </div>

                            <div data-ng-if="brands.length" class="table-responsive">
                                <table class="table table-hover" id="brand_table">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Name</th>
                                            <th class="text-center">Status</th>
                                            <th></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr data-ng-repeat="brand in brands track by $index">
                                            <td data-ng-bind="brand.brand_id"></td>
                                            <td data-ng-bind="brand.brand_name"></td>
                                            <td class="text-center">
                                                <span
                                                    class="badge bg-<%statusObject.color[brand.brand_status]%> rounded-pill font-monospace p-2"><%statusObject.name[brand.brand_status]%></span>

                                            </td>
                                            <td class="col-fit">
                                                <div>
                                                    <button class="btn btn-outline-primary btn-circle bi bi-pencil-square"
                                                        data-ng-click="setBrand($index)"></button>
                                                </div>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>

                            <div data-ng-if="!brands.length" class="text-center py-5 text-secondary">
                                <i class="bi bi-exclamation-circle  display-4"></i>
                                <h5>No records</h5>
                            </div>
                        </div>
                    </div>
                    {{--  --}}
                    <div class="modal fade" id="brandModal" tabindex="-1" data-bs-backdrop="static"
                        data-bs-keyboard="false" role="dialog">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-body">
                                    <form id="brandForm" method="post" action="/brands/submit">
                                        @csrf
                                        <input ng-if="updatebrand !== false" type="hidden" name="_method" value="put">
                                        <input type="hidden" id="id" name="id"
                                            ng-value="brands[updatebrand].brand_id">
                                        <div class="row">
                                            <div class="col-12 col-md-12">
                                                <div class="mb-3">
                                                    <label for="fullName">brand Name<b class="text-danger">&ast;</b></label>
                                                    <input id="fullName" name="name" class="form-control"
                                                        maxlength="120" ng-value="brands[updatebrand].brand_name" required>
                                                </div>
                                            </div>


                                            <div class="col-6">
                                                <div class="form-check form-switch mb-3">
                                                    <input class="form-check-input" type="checkbox" role="switch"
                                                        name="status" value="1"
                                                        ng-checked="+brands[updatebrand].brand_status" id="brandStatus">
                                                    <label class="form-check-label" for="brandStatus">Status</label>
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                                <div class="modal-footer d-flex">
                                    <button type="button" class="btn btn-outline-secondary me-auto"
                                        data-bs-dismiss="modal">Close</button>
                                    <button type="submit" form="brandForm" class="btn btn-outline-primary"
                                        ng-disabled="submitting">Submit</button>
                                    <span class="spinner-border spinner-border-sm text-warning ms-2" role="status"
                                        ng-if="submitting"></span>
                                </div>
                            </div>

                            <script>
                                $(function() {
                                    $('#brandForm').on('submit', e => e.preventDefault()).validate({
                                        rules: {
                                            fullName: {
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
                                                        if (scope.updatebrand === false) {
                                                            scope.brands.unshift(
                                                                response.data);
                                                            clsForm();
                                                        } else scope.brands[scope.updatebrand] = response.data;
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

                                clsForm = function() {
                                    $('#id').val('');
                                    $('#fullName').val('');
                                };
                            </script>
                        </div>
                    </div>
                    {{--  --}}

                    <div id="categoryBox" class="col-12 col-sm-6 col-lg-6">
                        <div class="brands-box border p-3">
                            <div class="d-flex">
                                <h5 class="card-title fw-semibold pt-1 me-auto mb-3">
                                    <span class="loading-spinner spinner-border spinner-border-sm text-warning me-2"
                                        role="status"></span><span>CATEGORIES</span>
                                </h5>
                                <div>
                                    <button type="button" class="btn btn-outline-primary btn-circle bi bi-plus-lg"
                                        data-ng-click="setCategory(false)"></button>
                                    <button type="button" class="btn btn-outline-dark btn-circle bi bi-arrow-repeat"
                                        data-ng-click="fetchCategories(true)"></button>
                                </div>

                            </div>
                            <div data-ng-if="categories.length" class="table-responsive">
                                <table class="table table-hover" id="example">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th class="text-center">Category Name</th>
                                            <th class="text-center">Brand Name</th>
                                            <th class="text-center">Status</th>
                                            <th></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr ng-repeat="category in categories track by $index">
                                            <td ng-bind="category.category_code"
                                                class="small font-monospace text-uppercase">
                                            </td>
                                            <td ng-bind="category.category_name" class="text-center">
                                            </td>
                                            <td ng-bind="category.brand_name" class="text-center">
                                            </td>
                                            <td class="text-center">
                                                <span
                                                    class="badge bg-<%statusObject.color[category.category_status]%> rounded-pill font-monospace p-2"><%statusObject.name[category.category_status]%></span>

                                            </td>
                                            <td class="col-fit">
                                                <button class="btn btn-outline-primary btn-circle bi bi-pencil-square"
                                                    ng-click="setCategory($index)"></button>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>

                            <div data-ng-if="!categories.length" class="text-center py-5 text-secondary">
                                <i class="bi bi-exclamation-circle  display-4"></i>
                                <h5>No records</h5>
                            </div>
                        </div>
                        {{--  --}}
                        <div class="modal fade" id="categoryModal" tabindex="-1" data-bs-backdrop="static"
                            data-bs-keyboard="false" role="dialog">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-body">
                                        <form id="categoryForm" method="post" action="/categories/submit">
                                            @csrf
                                            <input ng-if="updatecategory !== false" type="hidden" name="_method"
                                                value="put">
                                            <input type="hidden" name="id"
                                                ng-value="categories[updatecategory].category_id">
                                            <div class="row">
                                                <div class="col-12 col-md-12">
                                                    <div class="mb-3">
                                                        <label for="fullName">category Name<b
                                                                class="text-danger">&ast;</b></label>
                                                        <input id="fullName" name="name" class="form-control"
                                                            maxlength="120"
                                                            ng-value="categories[updatecategory].category_name" required>
                                                    </div>
                                                </div>

                                                <div class="col-12">
                                                    <div class="mb-3">
                                                        <label for="brand">
                                                            Brand <b class="text-danger">&ast;</b></label>
                                                        <select name="brand" id="brand" class="form-select"
                                                            required>
                                                            <option value="default">--
                                                                SELECT BRAND NAME --</option>
                                                            <option ng-repeat="b in brands" ng-value="b.brand_id"
                                                                ng-bind="b.brand_name">
                                                            </option>
                                                        </select>
                                                    </div>
                                                </div>


                                                <div class="col-6">
                                                    <div class="form-check form-switch mb-3">
                                                        <input class="form-check-input" type="checkbox" role="switch"
                                                            name="status" value="1"
                                                            ng-checked="+categories[updatecategory].category_status"
                                                            id="categoryStatus">
                                                        <label class="form-check-label"
                                                            for="categoryStatus">Status</label>
                                                    </div>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                    <div class="modal-footer d-flex">
                                        <button type="button" class="btn btn-outline-secondary me-auto"
                                            data-bs-dismiss="modal">Close</button>
                                        <button type="submit" form="categoryForm" class="btn btn-outline-primary"
                                            ng-disabled="submitting">Submit</button>
                                        <span class="spinner-border spinner-border-sm text-warning ms-2" role="status"
                                            ng-if="submitting"></span>
                                    </div>
                                </div>

                                <script>
                                    $(function() {
                                        $('#categoryForm').on('submit', e => e.preventDefault()).validate({
                                            rules: {
                                                fullName: {
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
                                                            if (scope.updatecategory === false) {
                                                                scope.categories.unshift(
                                                                    response.data);
                                                                clsForm();
                                                            } else scope.categories[scope.updatecategory] = response
                                                                .data;
                                                            toastr.success('Data processed successfully');
                                                            $('#categoryModal').modal('hide');
                                                        } else toastr.error(response.message);
                                                    });
                                                }).fail(function(jqXHR, textStatus, errorThrown) {
                                                    toastr.error(jqXHR.responseJSON.message);
                                                });
                                            }
                                        });
                                    });

                                    clsForm = function() {
                                        $('#fullName').val('');
                                    };
                                </script>
                            </div>
                        </div>
                        {{--  --}}
                    </div>
                </div>
            </div>


        </div>
        {{-- end brand and category section  --}}


        {{-- start subcategoires and sizes section  --}}
        <div class="card card-box mt-5">
            <div class="card-body">
                <div class="row">
                    <div id="subcategorieBox" class="col-6">
                        <div class="brands-box border p-3">
                            <div class="d-flex">
                                <h5 class="card-title fw-semibold pt-1 me-auto mb-3">
                                    <span class="loading-spinner spinner-border spinner-border-sm text-warning me-2"
                                        role="status"></span><span>SUBCATEGORIES</span>
                                </h5>
                                <div>
                                    <button type="button" class="btn btn-outline-primary btn-circle bi bi-plus-lg"
                                        data-ng-click="setSubsubcategory(false)"></button>
                                    <button type="button" class="btn btn-outline-dark btn-circle bi bi-arrow-repeat"
                                        data-ng-click="fetchSubsubcategory(true)"></button>
                                </div>

                            </div>
                            <div data-ng-if="subcategoires.length" class="table-responsive">
                                <table class="table table-hover" id="example">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th class="text-center">Subcategory Name</th>
                                            <th class="text-center">Category Name</th>
                                            <th class="text-center">Status</th>
                                            <th></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr ng-repeat="subcategory in subcategoires track by $index">
                                            <td ng-bind="subcategory.subcategory_code"
                                                class="small font-monospace text-uppercase">
                                            </td>
                                            <td ng-bind="subcategory.subcategory_name" class="text-center">
                                            </td>
                                            <td ng-bind="subcategory.category_name" class="text-center">
                                            </td>
                                            <td class="text-center">
                                                <span
                                                    class="badge bg-<%statusObject.color[subcategory.subcategory_status]%> rounded-pill font-monospace p-2"><%statusObject.name[subcategory.subcategory_status]%></span>

                                            </td>
                                            <td class="col-fit">
                                                <button class="btn btn-outline-primary btn-circle bi bi-pencil-square"
                                                    ng-click="setSubsubcategory($index)"></button>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>

                            <div data-ng-if="!subcategoires.length" class="text-center py-5 text-secondary">
                                <i class="bi bi-exclamation-circle display-4"></i>
                                <h5>No records</h5>
                            </div>
                        </div>
                    </div>
                    {{--  --}}
                    <div class="modal fade" id="subcategoryModal" tabindex="-1" data-bs-backdrop="static"
                        data-bs-keyboard="false" role="dialog">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-body">
                                    <form id="subcategoryForm" method="post" action="/subcategories/submit">
                                        @csrf
                                        <input ng-if="updatesubcategory !== false" type="hidden" name="_method"
                                            value="put">
                                        <input type="hidden" name="id"
                                            ng-value="subcategoires[updatesubcategory].subcategory_id">
                                        <div class="row">
                                            <div class="col-12 col-md-12">
                                                <div class="mb-3">
                                                    <label for="fullName">Subcategory Name<b
                                                            class="text-danger">&ast;</b></label>
                                                    <input id="fullName" name="name" class="form-control"
                                                        maxlength="120"
                                                        ng-value="subcategoires[updatesubcategory].subcategory_name"
                                                        required>
                                                </div>
                                            </div>

                                            <div class="col-12">
                                                <div class="mb-3">
                                                    <label for="category">
                                                        Category <b class="text-danger">&ast;</b></label>
                                                    <select name="category" id="category" class="form-select" required>
                                                        <option value="default">--
                                                            SELECT CATEGORY NAME --</option>
                                                        <option ng-repeat="c in categories" ng-value="c.category_id"
                                                            ng-bind="c.category_name">
                                                        </option>
                                                    </select>
                                                </div>
                                            </div>


                                            <div class="col-6">
                                                <div class="form-check form-switch mb-3">
                                                    <input class="form-check-input" type="checkbox" role="switch"
                                                        name="status" value="1"
                                                        ng-checked="+subcategoires[updatesubcategory].subcategory_status"
                                                        id="subcategoryStatus">
                                                    <label class="form-check-label" for="subcategoryStatus">Status</label>
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                                <div class="modal-footer d-flex">
                                    <button type="button" class="btn btn-outline-secondary me-auto"
                                        data-bs-dismiss="modal">Close</button>
                                    <button type="submit" form="subcategoryForm" class="btn btn-outline-primary"
                                        ng-disabled="submitting">Submit</button>
                                    <span class="spinner-border spinner-border-sm text-warning ms-2" role="status"
                                        ng-if="submitting"></span>
                                </div>
                            </div>

                            <script>
                                $(function() {
                                    $('#subcategoryForm').on('submit', e => e.preventDefault()).validate({
                                        rules: {
                                            fullName: {
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
                                                        if (scope.updatesubcategory === false) {
                                                            scope.subcategoires
                                                                .unshift(
                                                                    response.data);
                                                            clsForm();
                                                        } else scope.subcategoires[scope.updatesubcategory] =
                                                            response
                                                            .data;
                                                        toastr.success('Data processed successfully');
                                                        $('#subcategoryModal').modal('hide');
                                                    } else toastr.error(response.message);
                                                });
                                            }).fail(function(jqXHR, textStatus, errorThrown) {
                                                toastr.error(jqXHR.responseJSON.message);
                                            });
                                        }
                                    });
                                });

                                clsForm = function() {
                                    $('#fullName').val('');
                                };
                            </script>
                        </div>
                    </div>
                    {{--  --}}

                    <div id="sizeBox" class="col-6">
                        <div class="list-box border p-3">
                            <div class="d-flex">
                                <h5 class="card-title fw-semibold pt-1 me-auto mb-3">
                                    <span class="loading-spinner spinner-border spinner-border-sm text-warning me-2"
                                        role="status"></span><span>SIZES</span>
                                </h5>
                                <div>
                                    <button type="button" class="btn btn-outline-primary btn-circle bi bi-plus-lg"
                                        data-ng-click="setSize(false)"></button>
                                    <button type="button" class="btn btn-outline-dark btn-circle bi bi-arrow-repeat"
                                        data-ng-click="fetchSizes(true)"></button>
                                </div>

                            </div>
                            <div data-ng-if="sizes.length" class="table-responsive">
                                <table class="table table-hover" id="example">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th class="text-center">Size Name</th>
                                            <th class="text-center">Subcategory Name</th>
                                            <th class="text-center">Status</th>
                                            <th></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr ng-repeat="size in sizes track by $index">
                                            <td ng-bind="size.size_code" class="small font-monospace text-uppercase">
                                            </td>
                                            <td ng-bind="size.size_name" class="text-center">
                                            </td>
                                            <td ng-bind="size.subcategory_name" class="text-center">
                                            </td>
                                            <td class="text-center">
                                                <span
                                                    class="badge bg-<%statusObject.color[size.size_status]%> rounded-pill font-monospace p-2"><%statusObject.name[size.size_status]%></span>

                                            </td>
                                            <td class="col-fit">
                                                <button class="btn btn-outline-primary btn-circle bi bi-pencil-square"
                                                    ng-click="setSize($index)"></button>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>

                            <div data-ng-if="!sizes.length" class="text-center py-5 text-secondary">
                                <i class="bi bi-exclamation-circle display-4"></i>
                                <h5>No records</h5>
                            </div>
                        </div>
                    </div>
                </div>
                {{--  --}}
                <div class="modal fade" id="sizeModal" tabindex="-1" data-bs-backdrop="static"
                    data-bs-keyboard="false" role="dialog">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-body">
                                <form id="sizeForm" method="post" action="/sizes/submit">
                                    @csrf
                                    <input ng-if="updatesize !== false" type="hidden" name="_method" value="put">
                                    <input type="hidden" name="id" ng-value="sizes[updatesize].size_id">
                                    <div class="row">
                                        <div class="col-12 col-md-6">
                                            <div class="mb-3">
                                                <label for="name">Size Name<b class="text-danger">&ast;</b></label>
                                                <input id="name" name="name" class="form-control"
                                                    maxlength="120" ng-value="sizes[updatesize].size_name" required>
                                            </div>
                                        </div>

                                        <div class="col-12 col-md-6">
                                            <div class="mb-3">
                                                <label for="sign">Size sign </label>
                                                <input id="sign" name="sign" class="form-control"
                                                    ng-value="sizes[updatesize].size_sign">
                                            </div>
                                        </div>

                                        <div class="col-12">
                                            <div class="mb-3">
                                                <label for="subcategory">
                                                    Subcategory <b class="text-danger">&ast;</b></label>
                                                <select name="subcategory" id="subcategory" class="form-select" required>
                                                    <option value="default">--
                                                        SELECT SUBCATEGORY NAME --</option>
                                                    <option ng-repeat="c in subcategoires" ng-value="c.subcategory_id"
                                                        ng-bind="c.subcategory_name">
                                                    </option>
                                                </select>
                                            </div>
                                        </div>


                                        <div class="col-6">
                                            <div class="form-check form-switch mb-3">
                                                <input class="form-check-input" type="checkbox" role="switch"
                                                    name="status" value="1"
                                                    ng-checked="+sizes[updatesize].size_status" id="sizeStatus">
                                                <label class="form-check-label" for="sizeStatus">Status</label>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                            <div class="modal-footer d-flex">
                                <button type="button" class="btn btn-outline-secondary me-auto"
                                    data-bs-dismiss="modal">Close</button>
                                <button type="submit" form="sizeForm" class="btn btn-outline-primary"
                                    ng-disabled="submitting">Submit</button>
                                <span class="spinner-border spinner-border-sm text-warning ms-2" role="status"
                                    ng-if="submitting"></span>
                            </div>
                        </div>

                        <script>
                            $(function() {
                                $('#sizeForm').on('submit', e => e.preventDefault()).validate({
                                    rules: {
                                        name: {
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
                                                    if (scope.updatesize === false) {
                                                        scope.sizes
                                                            .unshift(
                                                                response.data);
                                                        clsForm()
                                                    } else scope.sizes[scope.updatesize] = response
                                                        .data;
                                                    toastr.success('Data processed successfully');
                                                    $('#sizeModal').modal('hide');
                                                } else toastr.error(response.message);
                                            });
                                        }).fail(function(jqXHR, textStatus, errorThrown) {
                                            toastr.error(jqXHR.responseJSON.message);
                                        });
                                    }
                                });
                            });

                            clsForm = function() {
                                $('#name').val('');
                                $('#sign').val('');
                            };
                        </script>
                    </div>
                </div>
                {{--  --}}

            </div>

        </div>
        {{-- end subcategories and sizes section  --}}


    </div>
@endsection
@section('js')
    <script>
        var scope, ngApp = angular.module("ngApp", ['ngSanitize'], function($interpolateProvider) {
            $interpolateProvider.startSymbol('<%');
            $interpolateProvider.endSymbol('%>');
        });
        ngApp.controller("ngCtrl", function($scope) {
            $scope.statusObject = {
                name: ['Un Available', 'Available'],
                color: ['danger', 'success']
            };
            $('.loading-spinner').hide();

            // brands
            $scope.updatebrand = false;
            $scope.brands = [];
            $scope.page = 1;

            // categories
            $scope.updatecategory = false;
            $scope.categories = [];
            $scope.page = 1;

            // subcategoires
            $scope.subcategoires = [];
            $scope.updatesubcategory = [];
            $scope.page = 1;

            // sizes
            $scope.sizes = [];
            $scope.updatesize = [];
            $scope.page = 1;

            $scope.jsonParse = str => JSON.parse(str);



            $scope.loadBrandsData = function(reload = false) {
                $('.loading-spinner').show();
                if (reload) {
                    $scope.page = 1;
                }
                $.post("/brands/load/", {
                    limit: 24,
                    _token: '{{ csrf_token() }}'
                }, function(data) {
                    $('.loading-spinner').hide();
                    $scope.$apply(() => {
                        $scope.brands = data;
                    });
                }, 'json');
            }

            $scope.setBrand = (indx) => {
                $scope.updatebrand = indx;
                $('#brandModal').modal('show');
            };

            $scope.fetchCategories = function(reload = false) {
                $('.loading-spinner').show();
                if (reload) {
                    $scope.page = 1;
                }
                $.post("/categories/load/", {
                    page: $scope.page,
                    _token: '{{ csrf_token() }}'
                }, function(data) {
                    $('.loading-spinner').hide();
                    $scope.$apply(() => {
                        $scope.categories = data;
                        $scope.page++;
                    });
                }, 'json');
            }

            $scope.setCategory = (indx) => {
                $scope.updatecategory = indx;
                $('#categoryModal').modal('show');
            };

            $scope.fetchSubsubcategory = function(reload = false) {
                $('.loading-spinner').show();
                if (reload) {
                    $scope.page = 1;
                }
                $.post("/subcategories/load/", {
                    limit: 24,
                    _token: '{{ csrf_token() }}'
                }, function(data) {
                    $('.loading-spinner').hide();
                    $scope.$apply(() => {
                        $scope.subcategoires = data;
                        $scope.page++;
                    });
                }, 'json');
            }

            $scope.fetchSizes = function(reload = false) {
                $('.loading-spinner').show();
                if (reload) {
                    $scope.page = 1;
                }
                $.post("/sizes/load/", {
                    _token: '{{ csrf_token() }}'
                }, function(data) {
                    $('.loading-spinner').hide();
                    $scope.$apply(() => {
                        $scope.sizes = data;
                        // $scope.page++;
                    });
                }, 'json');
            }

            $scope.setSubsubcategory = (indx) => {
                $scope.updatesubcategory = indx;
                $('#subcategoryModal').modal('show');
            };

            $scope.setSize = (index) => {
                $scope.updatesize = index;
                $('#sizeModal').modal('show');
            }

            $scope.loadBrandsData();

            $scope.fetchCategories();

            $scope.fetchSizes();

            $scope.fetchSubsubcategory()

            scope = $scope;
        });
    </script>
@endsection
