// Mobile menu toggle
document.addEventListener('DOMContentLoaded', function() {
    // Auto-calculate percentage in add result form
    const marksInput = document.getElementById('marks_obtained');
    const totalMarksInput = document.getElementById('total_marks');
    
    if(marksInput && totalMarksInput) {
        function calculatePercentage() {
            const marks = parseFloat(marksInput.value) || 0;
            const total = parseFloat(totalMarksInput.value) || 100;
            const percentage = (marks / total * 100).toFixed(2);
            
            const percentageSpan = document.getElementById('percentage_display');
            if(percentageSpan) {
                percentageSpan.textContent = percentage + '%';
            }
        }
        
        marksInput.addEventListener('input', calculatePercentage);
        totalMarksInput.addEventListener('input', calculatePercentage);
    }
    
    // Form validation
    const forms = document.querySelectorAll('form');
    forms.forEach(form => {
        form.addEventListener('submit', function(e) {
            const requiredFields = this.querySelectorAll('[required]');
            let valid = true;
            
            requiredFields.forEach(field => {
                if(!field.value.trim()) {
                    valid = false;
                    field.style.borderColor = '#e74c3c';
                } else {
                    field.style.borderColor = '#ddd';
                }
            });
            
            if(!valid) {
                e.preventDefault();
                alert('Please fill in all required fields!');
            }
        });
    });
    
    // Confirm before delete
    const deleteLinks = document.querySelectorAll('.btn-delete');
    deleteLinks.forEach(link => {
        link.addEventListener('click', function(e) {
            if(!confirm('Are you sure you want to delete this item?')) {
                e.preventDefault();
            }
        });
    });
});