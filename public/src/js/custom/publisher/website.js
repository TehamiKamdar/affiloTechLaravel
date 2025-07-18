'use strict';

// Bind the copy event once to avoid multiple bindings
$("#copyHTMLTag").off('click').on('click', () => {
    copyHtmlTag();
});

// Function to handle copying the HTML tag using the modern Clipboard API
async function copyHtmlTag() {
    const copyText = $("#htmlTag").val();
    try {
        await navigator.clipboard.writeText(copyText);
        toastMixin.fire({
            animation: true,
            title: 'HTML Tag successfully copied.'
        });
    } catch (error) {
        console.error('Failed to copy text:', error);
        toastMixin.fire({
            title: 'Failed to copy the HTML Tag.',
            icon: 'error'
        });
    }
}

// Open the verify modal and handle the verification process
function openVerifyModal(id, url) {
    $("#htmlTag").val(`<meta name="theaffiloverifycode" content="${id}" />`);

    $("#websiteVerify").off('click').on('click', () => {
        $("#verifyForm").addClass("disableDiv");

        $.ajax({
            url: '/publisher/profile/website/verification',
            type: 'POST',
            headers: { 'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content') },
            data: { url },
            success: (response) => handleVerificationResponse(response, id),
            error: (error) => {
                console.error('Verification error:', error);
                toastMixin.fire({
                    title: 'An error occurred during verification.',
                    icon: 'error'
                });
            },
            complete: () => {
                $("#verifyForm").removeClass("disableDiv");
            }
        });
    });
}

// Handle verification response
function handleVerificationResponse(response, id) {
    if (response.success) {
        $(`#verify-break-${id}`).hide();
        $(`#verify-btn-${id}`).hide();
        $(`#status-${id}`).html("<div class='badge badge-light-success'>Active</div>");
        toastMixin.fire({
            animation: true,
            title: response.message
        });
        $("#closeVerifyModal").trigger("click");
    } else {
        toastMixin.fire({
            title: response.message,
            icon: 'error'
        });
    }
}

// Open the website modal and initialize fields
function openWebsiteModal(type = null, id = null) {
    resetWebsiteForm();
    const title = type ? "Edit Website" : "Add Website";
    $("#websiteHeading").html(title);
    initializeSelect2Fields();

    if (id) {
        fetchWebsiteData(id);
    }
}

// Reset the website form fields
function resetWebsiteForm() {
    $("#edit-website")[0].reset();
    $('#website_id, #website_type, #categories, #website_country').val('').trigger('change');
    $('.form-group .is-invalid').removeClass('is-invalid');
}

// Initialize Select2 fields for partner types and categories
function initializeSelect2Fields() {
    $(".select2-field").select2({
        placeholder: "Please Select",
        dropdownCssClass: "tag",
        allowClear: true,
        maximumSelectionLength: 4
    });
}

// Fetch website data and populate the form for editing
function fetchWebsiteData(id) {
    $.ajax({
        url: `/publisher/profile/website/${id}`,
        type: 'GET',
        success: (response) => populateWebsiteForm(response.data, id),
        error: (error) => {
            console.error('Error fetching website data:', error);
        }
    });
}

// Populate the form with the data of the website to be edited
function populateWebsiteForm(data, id) {
    $("#website_id").val(id);
    $("#website_name").val(data.name);
    $("#website_url").val(data.url);
    $("#monthly_traffic").val(data.monthly_traffic);
    $("#monthly_page_views").val(data.monthly_page_views);
    $("#website_intro").val(data.website_intro);
    $("#website_country").val(data.country).trigger("change");
    $("#website_type").val(data.partner_types_ids).trigger("change");
    $("#categories").val(data.categories_ids).trigger("change");
}

// Form validation rules and AJAX submission logic
$(document).ready(() => {
    initializeFormValidation();
});

// Form validation rules and AJAX submission logic
function initializeFormValidation() {
    $('#edit-website').validate({
        rules: {
            'website_name': { required: true },
            'website_url': { required: true, url: true },
            'monthly_traffic': { required: true, number: true },
            'monthly_page_views': { required: true, number: true },
            'website_country': { required: true },
            'website_intro': { required: true },
            "website_type[]": { required: true },
            "categories[]": { required: true },
        },
        errorPlacement: (error, element) => {
            error.addClass('jquery-validation-error small form-text invalid-feedback');
            element.closest('.form-group').append(error);
        },
        highlight: (element) => {
            $(element).addClass('is-invalid');
        },
        unhighlight: (element) => {
            $(element).removeClass('is-invalid');
        },
        submitHandler: handleFormSubmit
    });
}

// Handle form submission and send data to the server via AJAX
function handleFormSubmit() {
    const data = $("#edit-website").serialize();
    const url = "/publisher/profile/websites";
    const method = $("#website_id").val() ? 'PATCH' : 'POST';

    $.ajax({
        url: url,
        type: method,
        headers: { 'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content') },
        data: data,
        success: (response) => {
            console.log(response)
            window.location.reload();
            updateWebsiteList(response.data)
            
            
        },
        error: (error) => {
            console.error('Error submitting form:', error);
        }
    });
}

// Update the website list with the new or edited website information
function updateWebsiteList(data) {
    const content = generateWebsiteRowContent(data);
    if ($("#website_id").val()) {
        $(`#website-row-${data.id}`).html(content);
    } else {
        $("#websiteContent").append(`<tr id="website-row-${data.id}">${content}</tr>`);
    }
    $("#closeEditModal").trigger("click");
}

// Generate the HTML content for a website row
function generateWebsiteRowContent(data) {
    return `
        <td>
            <a href="${data.url}" target="_blank" class="text-gray-900 fw-bold text-hover-primary fs-6">${data.name}</a>
        </td>
        <td>
            <p class="text-gray-900 fw-bold d-block mb-1 fs-6" title="${data.partner_types}">${data.trim_partner_types}</p>
        </td>
        <td>
            <p class="text-gray-900 fw-bold d-block mb-1 fs-6" title="${data.categories}">${data.trim_categories}</p>
        </td>
        <td class="text-center">
            <p class="text-gray-900 fw-bold d-block mb-1 fs-6">${data.updated_at}</p>
        </td>
        <td class="text-center" id="status-${data.id}">
            <div class='badge ${data.class}'>${data.status}</div>
        </td>
        <td>${generateActionLinks(data)}</td>`;
}

// Generate action links for a website row
function generateActionLinks(data) {
    let links = '';
    if (data.status === "pending") {
        links += `<a href="javascript:void(0)" id="verify-btn-${data.id}" data-bs-toggle="modal" data-bs-target="#verify-modal" class="text-gray-900 fw-bold text-hover-primary mb-1 fs-6" onclick="openVerifyModal('${data.id}', '${data.url}')">Verify</a> <span id="verify-break-${data.id}">|</span> `;
    }
    links += `<a href='javascript:void(0)' data-bs-toggle='modal' data-bs-target='#website-modal' onclick='openWebsiteModal(1, "${data.id}")' class="text-gray-900 fw-bold text-hover-primary mb-1 fs-6">Edit</a>`;
    return links;
}
