<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Trainee Quiz</title>
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
            <a href="{{ route('trainee.index') }}" class="btn btn-danger mt-4">Back</a>
        </div>
        <div class="row">
            <div class="col-md-4">
                <img id="profileImage" src="" alt="Profile Image" style="height: 40vh; margin: 2vw">
            </div>
            <div class="col-md-4 quiz-form">
                <h2 class="mt-4 mb-4">Trainee Quiz</h2>
                <form id="quizForm">
                    <input type="hidden" id="correctTraineeNumber">
                    <input type="hidden" id="correctDegree">
                    <input type="hidden" id="correctBinusian">
                    <input type="text" id="traineeNumber" class="form-control mb-3" placeholder="Trainee Number">
                    <input type="text" id="traineeName" class="form-control mb-3" placeholder="Name">
                    <div class="form-group">
                        <select id="degree" class="form-control">
                            <option value="">Select Degree</option>
                            <option value="CS">Computer Science</option>
                            <option value="CSMT">Computer Science (Master Track)</option>
                            <option value="CSMATH">Computer Science (Mathematics)</option>
                            <option value="DS">Data Science</option>
                            <option value="GAT">Game Application & Technology</option>
                            <option value="CSEC">Cyber Security</option>
                            <option value="IS">Information System</option>
                            <option value="ISMT">Information System (Master Track)</option>
                            <option value="BA">Business Analytics</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <select id="binusian" class="form-control">
                            <option value="">Select Binusian</option>
                            <option value="B24">B24</option>
                            <option value="B25">B25</option>
                            <option value="B26">B26</option>
                            <option value="B27">B27</option>
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
                                <p><span id="correctTraineeNumberDisplay"></span></p>
                                <p><span id="correctName"></span></p>
                                <p><span id="correctDegreeDisplay"></span></p>
                                <p><span id="correctBinusianDisplay"></span></p>
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
        var trainees = {!! json_encode($trainee->where('status', 'active')) !!};

        var currentTraineeIndex = 0;

        function displayNextQuiz() {
            if (currentTraineeIndex < trainees.length) {
                var trainee = trainees[currentTraineeIndex];
                document.getElementById("profileImage").src = "storage/" + trainee.profile;
                document.getElementById("correctTraineeNumber").value = trainee.trainee_number;
                document.getElementById("correctDegree").value = trainee.degree;
                document.getElementById("correctBinusian").value = trainee.binusian;
                document.getElementById("correctName").value = trainee.name;
                currentTraineeIndex++;
            } else {
                document.getElementById("showAnswersButton").style.display = "block";
            }
        }

        function submitForm() {
            var userInputTraineeNumber = document.getElementById("traineeNumber").value.trim();
            var userInputTraineeName = document.getElementById("traineeName").value.trim();
            var userInputDegree = document.getElementById("degree").value.trim();
            var userInputBinusian = document.getElementById("binusian").value.trim();
            var correctTraineeNumber = document.getElementById("correctTraineeNumber").value;
            var correctDegree = document.getElementById("correctDegree").value;
            var correctBinusian = document.getElementById("correctBinusian").value;

            if (!userInputTraineeNumber || !userInputTraineeName || !userInputDegree || !userInputBinusian) {
                document.getElementById("resultMessage").innerHTML = "Please fill all fields.";
                return;
            }

            if (userInputTraineeNumber === correctTraineeNumber && userInputTraineeName === trainees[currentTraineeIndex -
                    1].name && userInputDegree === correctDegree && userInputBinusian === correctBinusian) {
                document.getElementById("resultMessage").innerHTML = "Correct!";
                setTimeout(function() {
                    document.getElementById("resultMessage").innerHTML = "";
                    displayNextQuiz();
                    document.getElementById("traineeNumber").value = "";
                    document.getElementById("traineeName").value = "";
                    document.getElementById("degree").value = "";
                    document.getElementById("binusian").value = "";
                }, 2000); 
            } else {
                document.getElementById("resultMessage").innerHTML = "Incorrect! Please try again.";
                setTimeout(function() {
                    document.getElementById("resultMessage").innerHTML = "";
                }, 2000); 
            }
        }

        function showAnswers() {
            $('#answersModal').modal('show');

            document.getElementById("correctTraineeNumberDisplay").innerText = document.getElementById(
                "correctTraineeNumber").value;
            document.getElementById("correctDegreeDisplay").innerText = document.getElementById("correctDegree").value;
            document.getElementById("correctName").innerText = document.getElementById("correctName").value;
            document.getElementById("correctBinusianDisplay").innerText = document.getElementById("correctBinusian").value;
        }

        trainees.sort(() => Math.random() - 0.5);

        displayNextQuiz();
    </script>
</body>

</html>
