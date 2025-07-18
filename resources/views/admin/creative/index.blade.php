@extends("layouts.admin.layout")

@section("styles")

    <style>
        .coupon-card {
            transition: transform 0.3s ease;
            margin-bottom: 20px;
            overflow: hidden;
            border: none;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .coupon-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.15);
        }


        .coupon-value {
            font-size: 1.75rem;
            font-weight: bold;
        }

        .coupon-details {
            color: #6c757d;
            font-size: 0.9rem;
        }


        .ribbon {
            position: absolute;
            top: 2px;
            right: -30px;
            background-color: #dc3545;
            color: white;
            padding: 5px 35px;
            transform: rotate(45deg);
            font-size: 0.75rem;
            font-weight: bold;
        }

        .ribbon-limited {
            position: absolute;
            top: 10px;
            right: -30px;
            background-color: #dc3545;
            color: white;
            padding: 5px 30px;
            transform: rotate(45deg);
            font-size: 0.75rem;
            font-weight: bold;
        }
    </style>
@endsection

@section('content')

    <!-- [ breadcrumb ] start -->
    <div class="page-header">
        <div class="page-block">
            <div class="row align-items-center">
                <div class="col-12">
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}"><i
                                    class="ri-home-5-line text-primary"></i></a></li>
                        <li class="breadcrumb-item"><a href="javascript:void(0)">Creative</a></li>
                        <li class="breadcrumb-item"><a href="{{ route("admin.creatives.index") }}">Coupons</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    <!-- [ breadcrumb ] end -->

    <!-- [ breadcrumb ] end -->
    <div class="row" id="coupon-container">
        <!-- AJAX-loaded coupon cards will be inserted here -->
    </div>

    <div class="pagination-wrapper mt-4 d-flex justify-content-center">
        <ul id="pagination" class="pagination">
            <!-- AJAX pagination buttons go here -->
        </ul>
    </div>

@endsection

@section("scripts")
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script type="text/javascript">
        let couponContainer = $('#coupon-container');
        let paginationContainer = $('#pagination');
        // Helper function
        function formatDate(dateStr) {
            const options = { year: 'numeric', month: 'short', day: 'numeric' };
            return new Date(dateStr).toLocaleDateString('en-US', options);
        }

        function renderPagination(current, total) {
            paginationContainer.empty();

            if (total <= 1) return;

            const maxVisible = 7;

            // Previous
            if (current > 1) {
                paginationContainer.append(`<li class="page-item"><a class="page-link" href="#" data-page="${current - 1}">Previous</a></li>`);
            }

            // Always show first page
            paginationContainer.append(`
                        <li class="page-item ${current === 1 ? 'active' : ''}">
                            <a class="page-link" href="#" data-page="1">1</a>
                        </li>
                    `);

            // Show 2 to (maxVisible - 2) if within first few pages
            let start = 2;
            let end = total - 1;

            if (current > maxVisible - 2) {
                paginationContainer.append(`<li class="page-item disabled"><span class="page-link">...</span></li>`);
                start = current - 2;
            }

            end = Math.min(current + 2, total - 1);

            for (let i = start; i <= end; i++) {
                if (i === 1 || i === total) continue; // Already handled
                paginationContainer.append(`
                            <li class="page-item ${i === current ? 'active' : ''}">
                                <a class="page-link" href="#" data-page="${i}">${i}</a>
                            </li>
                        `);
            }

            // Ellipsis before last page
            if (current < total - 3) {
                paginationContainer.append(`<li class="page-item disabled"><span class="page-link">...</span></li>`);
            }

            // Last page
            if (total > 1) {
                paginationContainer.append(`
                            <li class="page-item ${current === total ? 'active' : ''}">
                                <a class="page-link" href="#" data-page="${total}">${total}</a>
                            </li>
                        `);
            }

            // Next
            if (current < total) {
                paginationContainer.append(`<li class="page-item"><a class="page-link" href="#" data-page="${current + 1}">Next</a></li>`);
            }
        }


        function loadCoupons(page = 1) {
            $.ajax({
                url: "{{ route('admin.creatives.creativeajax') }}",
                type: 'GET',
                data: { page: page },
                dataType: 'json',
                success: function (response) {
                    couponContainer.empty(); // Clear existing cards

                    // Render cards
                    response.data.forEach(function (item) {
                        let cardHtml = `
                                            <div class="col-md-6 col-lg-4 mb-4">
                                                <div class="card coupon-card">
                                                    <div class="bg-primary p-3">
                                                        <div class="ribbon-limited">${item.label || 'LIMITED'}</div>
                                                        <h5 class="coupon-code mb-1">${item.code}</h5>
                                                        <p class="coupon-desc text-white mb-0">${item.title}</p>
                                                    </div>
                                                    <div class="card-body">
                                                        <p class="coupon-details">${item.description}</p>
                                                        <div class="d-flex">
                                                            <p class="text-success text-sm me-3">
                                                                <i class="fas fa-clock me-1"></i> Starts: ${formatDate(item.start_date)}
                                                            </p>
                                                            <p class="text-danger text-sm">
                                                                <i class="fas fa-clock me-1"></i> Expires: ${formatDate(item.end_date)}
                                                            </p>
                                                        </div>
                                                        <div class="d-flex justify-content-between mt-3">
                                                            <button class="btn btn-primary btn-sm copy-btn" data-code="${item.code}">
                                                                <i class="fas fa-copy me-1"></i> Copy Code
                                                            </button>
                                                        </div>
                                                        <div class="d-flex justify-content-end align-items-center">
                                                            <p class="text-xs">Created By:</p>
                                                            <h6 class="mb-1">${item.advertiser_name}</h6>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        `;
                        couponContainer.append(cardHtml);
                    });

                    // Render pagination buttons
                    renderPagination(response.current_page, response.last_page);
                },
                error: function (xhr) {
                    console.error("Error fetching data:", xhr);
                }
            });
        }

        // Initial load
        loadCoupons();

        // Event delegation for pagination clicks
        $(document).on('click', '#pagination a', function (e) {
            e.preventDefault();
            let page = $(this).data('page');
            if (page) loadCoupons(page);
        });

        // Copy code to clipboard and show success state
        $(document).on('click', '.copy-btn', function () {
            let button = $(this);
            let code = button.data('code');

            copyToClipboard(code).then(() => {
                button.removeClass('btn-primary').addClass('btn-success');
                button.html('<i class="fas fa-check me-1"></i> Copied!');

                setTimeout(() => {
                    button.removeClass('btn-success').addClass('btn-primary');
                    button.html('<i class="fas fa-copy me-1"></i> Copy Code');
                }, 2000);
            }).catch(err => {
                console.error('Failed to copy code:', err);
            });
        });


        function copyToClipboard(text) {
            if (navigator.clipboard) {
                return navigator.clipboard.writeText(text);
            } else {
                let textarea = document.createElement("textarea");
                textarea.value = text;
                textarea.style.position = "fixed";  // Avoid scrolling
                document.body.appendChild(textarea);
                textarea.focus();
                textarea.select();
                try {
                    document.execCommand('copy');
                } catch (err) {
                    console.error('Fallback: Copy command failed', err);
                }
                document.body.removeChild(textarea);
                return Promise.resolve(); // for consistent handling
            }
        }

    </script>

@endsection
