// Improved vendor import functionality
$(document).ready(function() {
    $('#kt_export_form').on('submit', function(e) {
        e.preventDefault();
        
        var formData = new FormData(this);
        formData.append('_token', csrf_token);
        
        // Show loading indicator on button
        const submitButton = $(this).find('button[type="submit"]');
        submitButton.attr('data-kt-indicator', 'on');
        submitButton.prop('disabled', true);
        
        $.ajax({
            url: baseUrl + "/vendor/import",
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                // Reset button state
                submitButton.removeAttr('data-kt-indicator');
                submitButton.prop('disabled', false);
                
                if (response.status === 200) {
                    Swal.fire({
                        text: response.message || "Data imported successfully",
                        icon: "success",
                        buttonsStyling: false,
                        confirmButtonText: "Ok!",
                        customClass: {
                            confirmButton: "btn btn-primary"
                        }
                    }).then(function() {
                        $('#kt_modal_import').modal('hide');
                        $('#kt_export_form')[0].reset();
                        KTDatatablesServerSide.refresh();
                    });
                } else {
                    Swal.fire({
                        text: response.message || "Something went wrong",
                        icon: "error",
                        buttonsStyling: false,
                        confirmButtonText: "Ok!",
                        customClass: {
                            confirmButton: "btn btn-danger"
                        }
                    });
                }
            },
            error: function(xhr) {
                // Reset button state
                submitButton.removeAttr('data-kt-indicator');
                submitButton.prop('disabled', false);
                
                if (xhr.responseJSON?.errors) {
                    let errorMessage = "Import failed with the following errors:<br><ul>";
                    if (Array.isArray(xhr.responseJSON.errors)) {
                        xhr.responseJSON.errors.forEach(error => {
                            errorMessage += `<li>${error}</li>`;
                        });
                    } else {
                        Object.keys(xhr.responseJSON.errors).forEach(key => {
                            errorMessage += `<li>${xhr.responseJSON.errors[key]}</li>`;
                        });
                    }
                    errorMessage += "</ul>";
                    
                    Swal.fire({
                        title: "Import Failed",
                        html: errorMessage,
                        icon: "error",
                        buttonsStyling: false,
                        confirmButtonText: "Ok!",
                        customClass: {
                            confirmButton: "btn btn-danger"
                        }
                    });
                } else {
                    Swal.fire({
                        text: "An unknown error occurred during import",
                        icon: "error",
                        buttonsStyling: false,
                        confirmButtonText: "Ok!",
                        customClass: {
                            confirmButton: "btn btn-danger"
                        }
                    });
                }
            }
        });
    });
});
