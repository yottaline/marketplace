@extends('index')
@section('title', 'Dashboard')
@section('content')
    <div class="container-fluid">
        @hasrole('admin')
            <div class="row">
                <div class="col-sm-3">
                    <div class="card ">
                        <div class="card-body">
                            <h5 class="card-title"><i class="bi bi-person-lines-fill text-secondary me-2"></i><b
                                    class="fw-semibold pt-1 me-auto mb-3 text-uppercase">Retailers</b>

                            </h5>
                            <p class="card-text fs-3 fw-bold">{{ DB::table('retailers')->count() }}</p>

                        </div>
                    </div>
                </div>
                <div class="col-sm-3">
                    <div class="card ">
                        <div class="card-body">
                            <h5 class="card-title"><i class="bi bi-slack text-secondary me-2"></i><b
                                    class="fw-semibold pt-1 me-auto mb-3 text-uppercase">Brands</b>
                            </h5>
                            <p class="card-text fs-3 fw-bold">{{ DB::table('brands')->count() }}</p>
                        </div>
                    </div>
                </div>
            </div>
        @endhasrole

        @hasrole('retailer')
            <div class="row">
                <div class="col-sm-3">
                    <div class="card ">
                        <div class="card-body">
                            <h5 class="card-title"><i class="bi bi-box-seam text-secondary me-2"></i><b
                                    class="fw-semibold pt-1 me-auto mb-3 text-uppercase">PRODUCTS</b>

                            </h5>
                            <p class="card-text fs-3 fw-bold">
                                {{ DB::table('products')->where('product_created_by', auth()->user()->id)->count() }}</p>

                        </div>
                    </div>
                </div>
                <div class="col-sm-3">
                    <div class="card ">
                        <div class="card-body">
                            <h5 class="card-title"><i class="bi bi-cart4 text-secondary me-2"></i><b
                                    class="fw-semibold pt-1 me-auto mb-3 text-uppercase">ORDERS</b>
                            </h5>
                            <p class="card-text fs-3 fw-bold">
                                {{ DB::table('orders')->where('order_create_by', auth()->user()->id)->count() }}</p>
                        </div>
                    </div>
                </div>
            </div>
        @endhasrole
    </div>


@endsection
