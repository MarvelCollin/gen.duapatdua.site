<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Trainer Quiz</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

    <style>
        body {
            background-color: #444b52;
            color: white;
        }

        img {
            border-radius: 5%;
        }

        .quiz-form {
            max-width: 500px;
            margin: 0 auto;
            text-align: center;
        }

        .profile-image {
            max-width: 200px;
            height: auto;
            margin-bottom: 20px;
            border-radius: 50%;
        }

        .result-message {
            font-weight: bold;
            margin-top: 10px;
            color: #007bff;
        }

        .modal-body,
        .modal-header {
            color: black;
        }

        .btn-submit {
            background-color: #007bff;
            border-color: #007bff;
        }

        .btn-submit:hover {
            background-color: #0056b3;
            border-color: #0056b3;
        }

        .btn-show-answers {
            background-color: #17a2b8;
            border-color: #17a2b8;
        }

        .btn-show-answers:hover {
            background-color: #138496;
            border-color: #138496;
        }
    </style>
</head>

<body>

    <div class="container">
        <div class="row">
            <a href="{{ route('trainer.index') }}" class="btn btn-danger mt-4">Back</a>
        </div>
        <div class="row">
            <div class="col-md-4">
                <img id="profileImage" src="" alt="Profile Image" style="height: 40vh; margin: 2vw">
            </div>
            <div class="col-md-4 quiz-form">
                <h2 class="mt-4 mb-4">Trainer Quiz</h2>
                <form id="quizForm">
                    <input type="hidden" id="correctTrainerCode">
                    <input type="hidden" id="correctGeneration">
                    <input type="hidden" id="correctPosition">
                    <input type="text" id="trainerCode" class="form-control mb-3" placeholder="Trainer Code">
                    <input type="text" id="trainerName" class="form-control mb-3" placeholder="Name">
                    <div class="form-group">
                        <select id="generation" class="form-control">
                            <option value="">Select Generation</option>
                            @foreach ($trainers->unique('generation') as $generation)
                                <option value="{{ $generation->generation }}">{{ $generation->generation }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <select id="position" class="form-control">
                            <option value="">Select Position</option>
                            @foreach ($trainers->unique('position') as $position)
                                <option value="{{ $position->position }}">{{ $position->position }}</option>
                            @endforeach
                        </select>
                    </div>
                    <button type="button" class="btn btn-primary btn-block btn-submit"
                        onclick="submitForm()">Submit</button>

                </form>

                <button type="button" class="btn btn-info mt-3 btn-show-answers" onclick="showAnswers()">Show
                    Answers</button>

                <div class="modal fade" id="answersModal" tabindex="-1" role="dialog"
                    aria-labelledby="answersModalLabel" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="answersModalLabel">Answers</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <p><span id="correctTrainerCodeDisplay"></span></p>
                                <p><span id="correctName"></span></p>
                                <p><span id="correctGenerationDisplay"></span></p>
                                <p><span id="correctPositionDisplay"></span></p>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            </div>
                        </div>
                    </div>
                </div>

                <div id="resultMessage" class="result-message mt-3"></div>
            </div>
        </div>
    </div>

    <script>
        var trainers = {!! json_encode($trainers) !!};

        var currentTrainerIndex = 0;

        function displayNextQuiz() {
            if (currentTrainerIndex < trainers.length) {
                var trainer = trainers[currentTrainerIndex];
                document.getElementById("profileImage").src = "storage/" + trainer.profile;
                document.getElementById("correctTrainerCode").value = trainer.code;
                document.getElementById("correctGeneration").value = trainer.generation;
                document.getElementById("correctPosition").value = trainer.position;
                document.getElementById("correctName").value = trainer.name;
                currentTrainerIndex++;
            } else {
                document.getElementById("showAnswersButton").style.display = "block";
            }
        }

        function submitForm() {
            var userInputTrainerCode = document.getElementById("trainerCode").value.trim();
            var userInputTrainerName = document.getElementById("trainerName").value.trim();
            var userInputGeneration = document.getElementById("generation").value.trim();
            var userInputPosition = document.getElementById("position").value.trim();
            var correctTrainerCode = document.getElementById("correctTrainerCode").value;
            var correctGeneration = document.getElementById("correctGeneration").value;
            var correctPosition = document.getElementById("correctPosition").value;

            if (!userInputTrainerCode || !userInputTrainerName || !userInputGeneration || !userInputPosition) {
                document.getElementById("resultMessage").innerHTML = "Please fill all fields.";
                return;
            }

            if (userInputTrainerCode === correctTrainerCode && userInputTrainerName === trainers[currentTrainerIndex - 1].name && userInputGeneration === correctGeneration && userInputPosition === correctPosition) {
                document.getElementById("resultMessage").innerHTML = "Correct!";
                setTimeout(function() {
                    document.getElementById("resultMessage").innerHTML = "";
                    displayNextQuiz();
                    document.getElementById("trainerCode").value = "";
                    document.getElementById("trainerName").value = "";
                    document.getElementById("generation").value = "";
                    document.getElementById("position").value = "";
                }, 2000); 
            } else {
                document.getElementById("resultMessage").innerHTML = "Incorrect! Please try again.";
                setTimeout(function() {
                    document.getElementById("resultMessage").innerHTML = "";
                }, 500); 
            }
        }

        function showAnswers() {
            $('#answersModal').modal('show');

            document.getElementById("correctTrainerCodeDisplay").innerText = document.getElementById("correctTrainerCode").value;
            document.getElementById("correctGenerationDisplay").innerText = document.getElementById("correctGeneration").value;
            document.getElementById("correctName").innerText = document.getElementById("correctName").value;
            document.getElementById("correctPositionDisplay").innerText = document.getElementById("correctPosition").value;
        }

        trainers.sort(() => Math.random() - 0.5);

        displayNextQuiz();
    </script>
</body>

</html>
