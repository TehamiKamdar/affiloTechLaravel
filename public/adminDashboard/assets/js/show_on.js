$(document).ready(() => {

    let url = new URL(document.URL);
    let urlParams = url.searchParams;
    if(urlParams.has('page') && urlParams.has('page'))
    {
        getAdvertiserList(`${url.href}?page=${urlParams.get('page')}`, urlParams.get('search_by_network'), urlParams.get('search_by_country'));
        getCountryByNetworks(GET_COUNTRIES_BY_NETWORK_URL);
    }

    $("#SearchByNetwork").change(() => {
        let url = new URL(document.URL);
        let urlParams = url.searchParams;
        urlParams.delete(`page`);

        urlParams.append('page', 1);
        history.pushState({}, null, url.href);

        $("#SearchByInput").removeAttr('disabled');
        $("#clearFilter").removeClass('hide');

        filterAdvertiser("search_by_network", "SearchByNetwork");
        getAdvertiserList(url.href);
        getCountryByNetworks(GET_COUNTRIES_BY_NETWORK_URL);
    });

    $("#SearchByCountry").change(() => {
        let url = new URL(document.URL);
        let urlParams = url.searchParams;
        urlParams.delete(`page`);

        urlParams.append('page', 1);
        history.pushState({}, null, url.href);

        $("#SearchByInput").removeAttr('disabled');
        $("#clearFilter").removeClass('hide');

        filterAdvertiser("search_by_country", "SearchByCountry");

        getAdvertiserList(url.href);
        getCountryByNetworks(GET_COUNTRIES_BY_NETWORK_URL);
    });

    $("#SearchByInput").change(() => {
        let url = new URL(document.URL);
        let urlParams = url.searchParams;
        urlParams.delete(`page`);

        urlParams.append('page', 1);
        history.pushState({}, null, url.href);

        filterAdvertiser("search_by_input", "SearchByInput");
        getAdvertiserList(url.href);
        getCountryByNetworks(GET_COUNTRIES_BY_NETWORK_URL);
    });

    $("#selectAll").change(function() {
        $('.checkbox').prop('checked', $(this).prop('checked'));
    });
});
function filterAdvertiser(field, id)
{
    let data = $(`#${id}`).val();
    let dataObj = {[`${field}`]: data.toString()};

    $('#advertiserContent').html('');

    let url = new URL(document.URL);
    let urlParams = url.searchParams;

    if(urlParams.has(`${field}`)) {
        urlParams.delete(`${field}`);
    }

    urlParams.append(field, data);

    history.pushState({}, null, url.href);

}
function getAdvertiserList(url, network, country) {
    $("#selectAll").prop("checked", false).addClass("disabled").attr("disabled", "disabled");
    $("#updateBttn").addClass("disabled").attr("disabled", "disabled");

    $.ajax({
        url: url,
        type: 'GET',
        headers: {'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content')},
        data: {"search_by_network": network ? network : $("#SearchByNetwork").val(), "search_by_country": country ? country : $("#SearchByCountry").val(), "search_by_input": $("#SearchByInput").val()},
        success: function (response) {
            $("#advertiserContent").html(response.html);
            $("#selectAll").removeClass("disabled").removeAttr("disabled");
            $("#updateBttn").removeClass("disabled").removeAttr("disabled");
        },
        error: function (response) {
            // Handle error
        }
    });
}

function getCountryByNetworks(url) {
    $.ajax({
        url: url,
        type: 'POST',
        headers: {'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content')},
        data: {"search_by_network": $("#SearchByNetwork").val()},
        success: function (response) {
            $("#SearchByCountry").empty();

            let url = new URL(document.URL);
            let urlParams = url.searchParams;
            let selectedCountry = null;
            if(urlParams.has('search_by_country'))
            {
                selectedCountry = urlParams.get('search_by_country')
            }

            if (response.length) {
                $("#SearchByCountry").append('<option disabled selected="selected">Please Select</option>');
                $.each(response, function(key, value) {
                    $('#SearchByCountry').append(`<option value="${value.iso2}" ${selectedCountry == value.iso2 ? "selected" : ""}>${value.name}</option>`);
                });
            } else {
                $("#SearchByCountry").append('<option disabled selected="selected">No Data Found</option>');
            }
        },
        error: function (response) {
            // Handle error
        }
    });
}
