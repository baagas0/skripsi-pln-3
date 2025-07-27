function updateEmployeeCounter() {
    const checkedCount = $('.employee-checkbox:checked').length;
    $('.employee-counter').text(checkedCount + ' dipilih');
}

function updateEmployeeVisualState() {
    $('.employee-checkbox').each(function() {
        if($(this).is(':checked')) {
            $(this).closest('.form-check').addClass('bg-light-success rounded');
            $(this).siblings('label').find('.symbol-label').removeClass('bg-light-primary text-primary').addClass('bg-success text-white');
        } else {
            $(this).closest('.form-check').removeClass('bg-light-success rounded');
            $(this).siblings('label').find('.symbol-label').removeClass('bg-success text-white').addClass('bg-light-primary text-primary');
        }
    });
}

function updateSelectedEmployeesDisplay() {
    let selectedCount = $('.employee-checkbox:checked').length;
    
    if($('.selected-employees-display').length > 0) {
        if(selectedCount > 0) {
            $('.selected-employees-display').removeClass('d-none');
            
            $('.selected-employees-list').empty();
            
            $('.employee-checkbox:checked').each(function() {
                let employeeId = $(this).val();
                let employeeName = $(this).siblings('label').find('.fw-bold').text();
                let employeeNip = $(this).siblings('label').find('.text-muted').text();
                let firstLetter = employeeName.charAt(0);
                
                let employeeElement = `
                <div class="d-flex align-items-center bg-light-primary rounded p-2 mb-2 employee-item" data-id="${employeeId}">
                    <div class="symbol symbol-25px me-2">
                        <div class="symbol-label bg-primary text-white fw-bold">
                            ${firstLetter}
                        </div>
                    </div>
                    <div class="flex-grow-1">
                        <span class="fw-bold">${employeeName}</span>
                        <small class="d-block text-muted">${employeeNip}</small>
                    </div>
                    <button type="button" class="btn btn-sm btn-icon btn-light-danger remove-employee" data-id="${employeeId}">
                        <i class="ki-duotone ki-cross fs-2">
                            <span class="path1"></span>
                            <span class="path2"></span>
                        </i>
                    </button>
                </div>`;
                
                $('.selected-employees-list').append(employeeElement);
            });
        } else {
            $('.selected-employees-display').addClass('d-none');
        }
    }
}

$(document).ready(function() {
    updateEmployeeCounter();
    updateEmployeeVisualState();
    updateSelectedEmployeesDisplay();
    
    $('.employee-checkbox').on('change', function() {
        updateEmployeeCounter();
        updateEmployeeVisualState();
        updateSelectedEmployeesDisplay();
    });
    
    $('.select-all-employees').on('click', function(e) {
        e.preventDefault();
        $('.employee-checkbox:not(:disabled)').prop('checked', true);
        updateEmployeeCounter();
        updateEmployeeVisualState();
        updateSelectedEmployeesDisplay();
    });
    
    $('.deselect-all-employees').on('click', function(e) {
        e.preventDefault();
        $('.employee-checkbox:not(:disabled)').prop('checked', false);
        updateEmployeeCounter();
        updateEmployeeVisualState();
        updateSelectedEmployeesDisplay();
    });
    
    $(document).on('click', '.remove-employee', function(e) {
        e.preventDefault();
        let employeeId = $(this).data('id');
        
        $('#employee_' + employeeId).prop('checked', false);
        
        updateEmployeeCounter();
        updateEmployeeVisualState();
        updateSelectedEmployeesDisplay();
    });
    
    $('#form_scoring_lv4').on('submit', function(e) {
        let selectedEmployees = [];
        $('.employee-checkbox:checked').each(function() {
            selectedEmployees.push($(this).val());
        });
        
        console.log('Selected employees on submit:', selectedEmployees);
    });
});
