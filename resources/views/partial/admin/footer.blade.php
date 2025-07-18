
    
    <script src="{{ asset('testAssets/assets/vendor/libs/jquery/jquery.js') }}"></script>
<script src="{{ asset('testAssets/assets/vendor/libs/popper/popper.js') }}"></script>
<script src="{{ asset('testAssets/assets/vendor/js/bootstrap.js') }}"></script>
<script src="{{ asset('testAssets/assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.js') }}"></script>
<script src="{{ \App\Helper\Methods::staticAsset('panel/assets/plugins/data-tables/js/datatables.min.js') }}"></script>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script src="//cdn.datatables.net/buttons/1.2.4/js/dataTables.buttons.min.js"></script>
<script src="//cdn.datatables.net/buttons/1.2.4/js/buttons.flash.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.2.4/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.2.4/js/buttons.print.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.2.4/js/buttons.colVis.min.js"></script>
<script src="https://cdn.datatables.net/select/1.7.0/js/dataTables.select.min.js"></script>
<script src="{{ asset('testAssets/assets/vendor/js/menu.js') }}"></script>
<!-- endbuild -->

<!-- Vendors JS -->
<script src="{{ asset('testAssets/assets/vendor/libs/apex-charts/apexcharts.js') }}"></script>

<!-- Main JS -->
<script src="{{ asset('testAssets/assets/js/main.js') }}"></script>

<!-- Page JS -->
<script src="{{ asset('testAssets/assets/js/dashboards-analytics.js') }}"></script>

<!-- Place this tag in your head or just before your close body tag. -->
<script async defer src="https://buttons.github.io/buttons.js"></script> 
@yield("scripts")
