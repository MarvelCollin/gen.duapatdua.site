document.getElementById('image').addEventListener('change', function(event) {
    var fileName = event.target.files[0].name;
    var label = document.querySelector('.custom-file-label');
    label.textContent = fileName;
});

var form = document.getElementById('traineeForm');
form.addEventListener('submit', function(event) {
    var traineeNumberInput = document.getElementById('trainee_number');
    var traineeNumberValue = traineeNumberInput.value.trim();

    if (!traineeNumberValue.startsWith('T0')) {
        traineeNumberInput.classList.add('is-invalid');
        traineeNumberInput.nextElementSibling.textContent = 'Yaelah dah dibilang starts with T0, FOKUS KAKK!!!.';
        event.preventDefault();
        event.stopPropagation();
    } else {
        traineeNumberInput.classList.remove('is-invalid');
        traineeNumberInput.classList.add('is-valid');
        traineeNumberInput.nextElementSibling.textContent = '';
    }

    if (!form.checkValidity()) {
        event.preventDefault();
        event.stopPropagation();
    }
    form.classList.add('was-validated');
});

function showTraineeModal(element) {
    var traineeId = element.getAttribute('data-id');
    var traineeNumber = element.getAttribute('data-number');
    var traineeName = element.getAttribute('data-name');
    var traineeDegree = element.getAttribute('data-degree');
    var traineeBinusian = element.getAttribute('data-binusian');
    var traineeProfile = element.getAttribute('data-profile');
    var traineeStatus = element.getAttribute('data-status');

    document.getElementById('trainee_id').value = traineeId;
    document.getElementById('trainee_number').value = traineeNumber;
    document.getElementById('name').value = traineeName;
    document.getElementById('degree').value = traineeDegree;
    document.getElementById('binusian').value = traineeBinusian;
    document.getElementById('status').value = traineeStatus;

    document.querySelector('.custom-file-label').textContent = traineeProfile.split('/').pop();

    document.getElementById('newCaseSolveModalLabel').textContent = 'Edit Trainee';
    document.querySelector('button[type="submit"]').style.display = 'none';
    document.getElementById('editBtn').style.display = 'inline-block';
    document.getElementById('deleteBtn').style.display = 'inline-block';

    $('#newCaseSolveModal').modal('show');
}

document.getElementById('editBtn').addEventListener('click', function() {
    var form = document.getElementById('traineeForm');
    var traineeId = document.getElementById('trainee_id').value;
    form.action = form.dataset.updateUrl.replace(':id', traineeId);
    form.method = 'POST';

    var methodField = document.createElement('input');
    methodField.type = 'hidden';
    methodField.name = '_method';
    methodField.value = 'PUT';
    form.appendChild(methodField);

    form.submit();
});

document.getElementById('deleteBtn').addEventListener('click', function() {
    var form = document.getElementById('traineeForm');
    var traineeId = document.getElementById('trainee_id').value;
    form.action = form.dataset.deleteUrl.replace(':id', traineeId);
    form.method = 'POST';

    var methodField = document.createElement('input');
    methodField.type = 'hidden';
    methodField.name = '_method';
    methodField.value = 'DELETE';
    form.appendChild(methodField);

    form.submit();
});

document.getElementById('newCaseSolveModal').addEventListener('hidden.bs.modal', function() {
    form.reset();
    form.classList.remove('was-validated');
    document.getElementById('newCaseSolveModalLabel').textContent = 'Create New Trainee';
    document.querySelector('button[type="submit"]').style.display = 'inline-block';
    document.getElementById('editBtn').style.display = 'none';
    document.getElementById('deleteBtn').style.display = 'none';
    document.querySelector('.custom-file-label').textContent = 'Choose file...';

    var methodField = form.querySelector('input[name="_method"]');
    if (methodField) {
        form.removeChild(methodField);
    }
});
