<div class="d-flex flex-column flex-center text-center mt-3">
    <!--begin::Wrapper-->
    <div class="card card-flush w-md-650px py-5">
        <div class="card-body py-15 py-lg-20">
            <!--begin::Logo-->
            <div class="mb-7">
                <a href="{{ route("admin.dashboard") }}" class="">
                    <img alt="Logo" src="https://app.linkscircle.com/img/folders/1.svg">
                </a>
            </div>
            <!--end::Logo-->
            <!--begin::Title-->
            <h1 class="fw-bolder text-gray-900 mb-5">{{ $title }}</h1>
            <!--end::Title-->
            <!--begin::Link-->
            <div class="mb-0">
                <a href="{{ route("admin.dashboard") }}" class="btn btn-sm btn-primary">Go To Dashboard</a>
            </div>
            <!--end::Link-->
        </div>
    </div>
    <!--end::Wrapper-->
</div>
