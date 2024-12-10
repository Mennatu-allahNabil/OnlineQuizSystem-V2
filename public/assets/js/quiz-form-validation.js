// document.addEventListener('DOMContentLoaded', function() {
//     const form = document.querySelector('form');
//     const createQuizBtn = document.getElementById('create_quiz');
//
//     // Validation functions
//     const validationRules = {
//         required: (value) => value !== null && value !== undefined && value.trim() !== '',
//         minLength: (value, min) => value.trim().length >= min,
//         maxLength: (value, max) => value.trim().length <= max,
//         numeric: (value) => !isNaN(value) && value !== '',
//         positiveNumber: (value) => !isNaN(value) && parseFloat(value) > 0,
//         image: (file) => {
//             if (!file) return true; // Optional file is valid if not provided
//             const validTypes = ['image/jpeg', 'image/png', 'image/jpg', 'image/gif'];
//             return file && validTypes.includes(file.type);
//         }
//     };
//
//     // Error display helper
//     function displayError(element, message) {
//         // Remove any existing error elements
//         const existingError = element.parentNode.querySelector('.validation-error');
//         if (existingError) {
//             existingError.remove();
//         }
//
//         // Create and append error message
//         const errorElement = document.createElement('div');
//         errorElement.className = 'validation-error text-danger small mt-1';
//         errorElement.textContent = message;
//         element.parentNode.appendChild(errorElement);
//         element.classList.add('is-invalid');
//     }
//
//     // Clear error helper
//     function clearError(element) {
//         const existingError = element.parentNode.querySelector('.validation-error');
//         if (existingError) {
//             existingError.remove();
//         }
//         element.classList.remove('is-invalid');
//     }
//
//     // Validate individual field
//     function validateField(element) {
//         const value = element.value;
//         const name = element.name;
//         let isValid = true;
//         let errorMessage = '';
//
//         // Clear previous errors
//         clearError(element);
//
//         // Specific validation rules
//         switch(name) {
//             case 'title':
//                 if (!validationRules.required(value)) {
//                     errorMessage = 'Quiz title is required';
//                     isValid = false;
//                 } else if (!validationRules.minLength(value, 3)) {
//                     errorMessage = 'Title must be at least 3 characters long';
//                     isValid = false;
//                 }
//                 break;
//
//             case 'description':
//                 if (!validationRules.required(value)) {
//                     errorMessage = 'Description is required';
//                     isValid = false;
//                 } else if (!validationRules.minLength(value, 10)) {
//                     errorMessage = 'Description must be at least 10 characters long';
//                     isValid = false;
//                 }
//                 break;
//
//             case 'image':
//                 if (element.files && element.files[0]) {
//                     if (!validationRules.image(element.files[0])) {
//                         errorMessage = 'Invalid image type. Only JPEG, PNG, JPG, and GIF are allowed.';
//                         isValid = false;
//                     }
//                 }
//                 break;
//
//             case 'quiz_type':
//                 const checkedRadio = document.querySelector('input[name="quiz_type"]:checked');
//                 if (!checkedRadio) {
//                     errorMessage = 'Please select a quiz type';
//                     isValid = false;
//                 }
//                 break;
//
//             case 'time_limit':
//                 if (!validationRules.required(value)) {
//                     errorMessage = 'Time limit is required';
//                     isValid = false;
//                 } else if (!validationRules.positiveNumber(value)) {
//                     errorMessage = 'Time limit must be a positive number';
//                     isValid = false;
//                 }
//                 break;
//
//             case 'topic_id':
//                 if (!validationRules.required(value)) {
//                     errorMessage = 'Please select a topic';
//                     isValid = false;
//                 }
//                 break;
//         }
//
//         // Handle questions validation
//         if (name.includes('questions[')) {
//             // Validate question text
//             if (name.includes('[text]')) {
//                 if (!validationRules.required(value)) {
//                     errorMessage = 'Question text is required';
//                     isValid = false;
//                 }
//             }
//
//             // Validate question image (optional)
//             if (name.includes('[image]')) {
//                 if (element.files && element.files[0]) {
//                     if (!validationRules.image(element.files[0])) {
//                         errorMessage = 'Invalid image type for question. Only JPEG, PNG, JPG, and GIF are allowed.';
//                         isValid = false;
//                     }
//                 }
//             }
//
//             // Validate question options
//             if (name.includes('[options][]')) {
//                 if (!validationRules.required(value)) {
//                     errorMessage = 'Option text is required';
//                     isValid = false;
//                 }
//             }
//
//             // Validate correct option
//             if (name.includes('[is_correct_number]')) {
//                 if (!validationRules.required(value)) {
//                     errorMessage = 'Please select the correct option';
//                     isValid = false;
//                 }
//             }
//         }
//
//         // Display error if validation fails
//         if (!isValid) {
//             displayError(element, errorMessage);
//         }
//
//         return isValid;
//     }
//
//     // Validate all fields
//     function validateForm() {
//         const fields = form.querySelectorAll('input, textarea, select');
//         let isFormValid = true;
//
//         // Validate each field
//         fields.forEach(field => {
//             if (!validateField(field)) {
//                 isFormValid = false;
//             }
//         });
//
//         // Validate questions
//         const questionsContainer = document.getElementById('questions-container');
//         if (!questionsContainer || questionsContainer.children.length === 0) {
//             displayError(createQuizBtn, 'At least one question is required');
//             isFormValid = false;
//         }
//
//         return isFormValid;
//     }
//
//     // Add event listeners for real-time validation
//     form.addEventListener('input', function(event) {
//         validateField(event.target);
//     });
//
//     // Prevent form submission and validate
//     form.addEventListener('submit', function(event) {
//         // Prevent default submission
//         event.preventDefault();
//
//         // Validate the entire form
//         if (validateForm()) {
//             // If validation passes, submit the form
//             form.submit();
//         }
//     });
//
//     // Add validation to the create quiz button
//     createQuizBtn.addEventListener('click', function(event) {
//         // Prevent default button click behavior
//         event.preventDefault();
//
//         // Validate the form
//         if (validateForm()) {
//             form.submit();
//         }
//     });
// });
//
