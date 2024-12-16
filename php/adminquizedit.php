<?php

$host = 'localhost';
$db   = 'vocabquiz';
$user = 'root';
$pass = '';


$conn = new mysqli($host, $user, $pass, $db);


if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}


$quiz_id = intval($_GET['quiz_id']);


$quiz_sql = "SELECT quiz_title FROM quizzes WHERE quiz_id = ?";
$stmt = $conn->prepare($quiz_sql);
$stmt->bind_param("i", $quiz_id);
$stmt->execute();
$quiz_result = $stmt->get_result();
$quiz = $quiz_result->fetch_assoc();
$stmt->close();

if (!$quiz) {
    die("Quiz not found.");
}


$question_sql = "
    SELECT q.question_id, q.question_text, q.correct_option_id
    FROM questions q
    WHERE q.quiz_id = ?
    ORDER BY q.question_id ASC
";
$stmt = $conn->prepare($question_sql);
$stmt->bind_param("i", $quiz_id);
$stmt->execute();
$question_result = $stmt->get_result();

$questions = [];
while ($row = $question_result->fetch_assoc()) {
    $questions[] = $row;
}
$stmt->close();

$total_questions = count($questions);


$question_ids = array_column($questions, 'question_id');
$options = [];
if (!empty($question_ids)) {
    $placeholders = rtrim(str_repeat('?,', count($question_ids)), ',');
    $option_sql = "SELECT option_id, question_id, option_text FROM options WHERE question_id IN ($placeholders) ORDER BY option_id ASC";
    $stmt = $conn->prepare($option_sql);

   
    $types = str_repeat('i', count($question_ids));
    $stmt->bind_param($types, ...$question_ids);

    $stmt->execute();
    $option_result = $stmt->get_result();

    while ($opt = $option_result->fetch_assoc()) {
        $options[$opt['question_id']][] = $opt;
    }
    $stmt->close();
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Admin Quiz Review - DB Driven</title>
    <style>
     
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #000;
            color: #fff;
        }

        .gray-layer {
            background-color: #333;
            padding: 30px 0;
            min-height: 100vh;
        }

        .header {
            width: 100%;
            background-color: #222;
            color: #2196F3;
            padding: 10px 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 2px solid #2196F3;
            position: fixed;
            top: 0;
            z-index: 1000;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.5);
        }

        .header h1 {
            margin: 1;
            font-size: 20px;
            text-align: center;
            flex: 1;
        }

        .container {
            max-width: 800px;
            margin: 80px auto 0;
            padding: 20px;
            background-color: #222;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.5);
        }

        .quiz-info {
            background-color: #444;
            margin-top: 20px;
            padding: 15px;
            border-radius: 5px;
            box-shadow: 0 2px 4px rgba(255, 255, 255, 0.1);
        }

        .quiz-info h2 {
            text-align: center;
            color: #2196F3;
            margin: 0 0 10px;
        }

        .quiz-info p {
            text-align: center;
            margin: 0;
        }

        .questions-container {
            margin-top: 20px;
        }

        .question-item {
            background-color: #333;
            margin-bottom: 25px;
            padding: 45px;
            border-radius: 5px;
            box-shadow: 0 2px 4px rgba(255, 255, 255, 0.1);
            color: #fff;
            position: relative;
        }

        .question-item p {
            margin: 0 0 10px;
            font-weight: bold;
        }

        .question-item ul {
            list-style-type: none;
            padding: 0;
        }

        .question-item li {
            display: flex;
            align-items: center;
            margin: 10px 0;
            cursor: not-allowed;
        }

        .question-item li::before {
            content: '●';
            color: #555;
            font-size: 18px;
            margin-right: 10px;
        }

        .question-item li.preselected::before {
            color: #2196F3;
        }

        .question-item li.disabled::before {
            color: #555;
        }

        .question-item li .option-box {
            background-color: #fff;
            color: #333;
            padding: 10px 15px;
            border-radius: 5px;
            border: 1px solid #555;
            width: 100%;
            display: inline-block;
        }

        .question-item li.preselected .option-box {
            border-color: #2196F3;
        }

        .question-item li.disabled .option-box {
            background-color: #f0f0f0;
            color: #999;
            pointer-events: none;
            cursor: not-allowed;
        }

        .edit-btn {
            background: #2196F3;
            color: #fff;
            border: none;
            border-radius: 5px;
            padding: 5px 10px;
            cursor: pointer;
            font-weight: bold;
            position: absolute;
            bottom: 15px;
            right: 15px;
        }

        .edit-btn:hover {
            background: #42a5f5;
        }

        .done-btn {
            display: block;
            width: 100%;
            background-color: #2196F3;
            color: #fff;
            border: none;
            padding: 15px;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
            margin-top: 20px;
            font-weight: bold;
        }

        .done-btn:hover {
            background: #42a5f5;
        }

        .add-question-btn {
            display: block;
            width: 100%;
            background-color: #4caf50;
            color: #fff;
            border: none;
            padding: 15px;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
            font-weight: bold;
            margin-top: 20px;
        }

        .add-question-btn:hover {
            background-color: #45a049;
        }
        
    </style>
</head>

<body>
    <div class="gray-layer">
        <header class="header">
            <h1>Quiz Review</h1>
        </header>

        <div class="container">
            <div class="quiz-info">
                <h2 id="quiz-title">
                    <span id="title-text"><?php echo htmlspecialchars($quiz['quiz_title'], ENT_QUOTES, 'UTF-8'); ?></span>
                    <button id="edit-title-btn" class="edit-title-btn">✏️</button>
                </h2>
                <p>Total questions (<?php echo $total_questions; ?>)</p>
            </div>

            <div class="questions-container" id="questions-container">
                <?php if ($total_questions > 0): ?>
                    <?php foreach ($questions as $index => $q): ?>
                        <div class="question-item" id="question-<?php echo $q['question_id']; ?>">
                            <p><strong><?php echo $index + 1; ?>.</strong> <?php echo htmlspecialchars($q['question_text'], ENT_QUOTES, 'UTF-8'); ?></p>
                            <ul>
                                <?php
                                $q_options = isset($options[$q['question_id']]) ? $options[$q['question_id']] : [];
                                foreach ($q_options as $opt) {
                                    $is_correct = ($opt['option_id'] == $q['correct_option_id']);
                                    $li_class = $is_correct ? 'preselected' : 'disabled';
                                ?>
                                    <li class="<?php echo $li_class; ?>">
                                        <span class="option-box"><?php echo htmlspecialchars($opt['option_text'], ENT_QUOTES, 'UTF-8'); ?></span>
                                    </li>
                                <?php } ?>
                            </ul>
                            <button class="edit-btn" onclick="toggleEditDone(this)">Edit</button>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p>No questions found for this quiz.</p>
                <?php endif; ?>
            </div>

            <button class="add-question-btn" id="add-question-btn">Add More Question</button>
            <button class="done-btn">Done</button>
        </div>
    </div>

    <script>
        const quizId = <?php echo (int)$quiz_id; ?>;
        const editTitleBtn = document.getElementById('edit-title-btn');
        const titleTextElement = document.getElementById('title-text');
        let originalTitle = titleTextElement.textContent;

        editTitleBtn.addEventListener('click', enterTitleEditMode);

        function enterTitleEditMode() {
            const titleContainer = document.getElementById('quiz-title');
            titleContainer.innerHTML = '';

            
            const form = document.createElement('form');
            form.action = 'updatequiztitle.php';
            form.method = 'POST';

            
            const quizIdInput = document.createElement('input');
            quizIdInput.type = 'hidden';
            quizIdInput.name = 'quiz_id';
            quizIdInput.value = quizId;
            form.appendChild(quizIdInput);

           
            const input = document.createElement('input');
            input.type = 'text';
            input.name = 'new_title';
            input.value = originalTitle;
            input.className = 'title-edit-input';
            input.id = 'title-input';
            form.appendChild(input);

            
            input.focus();

            
            const saveBtn = document.createElement('button');
            saveBtn.type = 'submit';
            saveBtn.className = 'edit-title-btn';
            saveBtn.textContent = '✔️';
            form.appendChild(saveBtn);

            titleContainer.appendChild(form);
        }

        function enableQuestionEdit(questionId) {
            const questionItem = document.getElementById('question-' + questionId);

            
            const questionTextElem = questionItem.querySelector('p');
            const originalQuestionText = questionTextElem.textContent.replace(/^\d+\.\s*/, '').trim();

            const optionItems = questionItem.querySelectorAll('li');
            const optionsData = [];
            let correctIndex = 0;

            optionItems.forEach((li, idx) => {
                const text = li.querySelector('.option-box').textContent.trim();
                const isPreselected = li.classList.contains('preselected');
                optionsData.push(text);
                if (isPreselected) correctIndex = idx;
            });

            
            questionItem.innerHTML = '';

           
            const form = document.createElement('form');
            form.action = 'update_question.php'; 
            form.method = 'POST';

           
            const qIdInput = document.createElement('input');
            qIdInput.type = 'hidden';
            qIdInput.name = 'question_id';
            qIdInput.value = questionId;
            form.appendChild(qIdInput);

           
            const qInput = document.createElement('input');
            qInput.type = 'text';
            qInput.name = 'question_text';
            qInput.value = originalQuestionText;
            qInput.className = 'title-edit-input';
            form.appendChild(qInput);

           
            for (let i = 0; i < optionsData.length; i++) {
                const optDiv = document.createElement('div');
                optDiv.className = 'option-input';

                const radio = document.createElement('input');
                radio.type = 'radio';
                radio.name = 'correct_option';
                radio.value = i; 
                if (i === correctIndex) radio.checked = true;
                optDiv.appendChild(radio);

                const optInput = document.createElement('input');
                optInput.type = 'text';
                optInput.name = 'options[]';
                optInput.value = optionsData[i];
                optDiv.appendChild(optInput);

                form.appendChild(optDiv);
            }

           
            const removeBtn = document.createElement('button');
            removeBtn.type = 'button';
            removeBtn.textContent = 'Remove Question';
            removeBtn.className = 'remove-question-btn';
            removeBtn.addEventListener('click', () => {
                if (confirm('Are you sure you want to delete this question?')) {
                    window.location.href = 'delete_question.php?question_id=' + questionId;
                }
            });
            form.appendChild(removeBtn);

           
            const doneBtn = document.createElement('button');
            doneBtn.type = 'submit';
            doneBtn.textContent = 'Done';
            doneBtn.className = 'done-question-btn';
            form.appendChild(doneBtn);

            questionItem.appendChild(form);
        }

        
        const addQuestionBtn = document.getElementById('add-question-btn');
        addQuestionBtn.addEventListener('click', addNewQuestionForm);

        function addNewQuestionForm() {
            
            const existingAddForm = document.querySelector('.question-item form[action="add_question.php"]');
            if (existingAddForm) {
                alert('You are already adding a new question. Please finish adding it before adding another one.');
                return;
            }

            
            const editingQuestion = document.querySelector('.question-item input.edit-question-text, .question-item input.edit-option-input[name="options[]"]');
            if (editingQuestion) {
                alert('You are currently editing a question. Please finish editing it before adding a new question.');
                return;
            }

            const container = document.getElementById('questions-container');

            const questionItem = document.createElement('div');
            questionItem.className = 'question-item';

            const form = document.createElement('form');
            form.action = 'add_question.php';
            form.method = 'POST';
            form.style.position = 'relative';

            const quizIdInput = document.createElement('input');
            quizIdInput.type = 'hidden';
            quizIdInput.name = 'quiz_id';
            quizIdInput.value = quizId;
            form.appendChild(quizIdInput);

            const questionTextElem = document.createElement('p');
            questionTextElem.style.marginBottom = '10px';
            const qInput = document.createElement('input');
            qInput.type = 'text';
            qInput.name = 'question_text';
            qInput.className = 'edit-question-text title-edit-input';
            qInput.placeholder = 'Enter question title';
            questionTextElem.appendChild(qInput);
            form.appendChild(questionTextElem);

            const ul = document.createElement('ul');
            ul.style.listStyleType = 'none';
            ul.style.padding = '0';

            for (let i = 1; i <= 4; i++) {
                const li = document.createElement('li');
                li.style.cursor = 'default';
                li.style.margin = '10px 0';

                const radio = document.createElement('input');
                radio.type = 'radio';
                radio.name = 'correct_option';
                radio.value = i;
                li.appendChild(radio);

                const optInput = document.createElement('input');
                optInput.type = 'text';
                optInput.name = 'options[]';
                optInput.className = 'edit-option-input';
                optInput.style.marginLeft = '10px';
                optInput.placeholder = `Option ${i}`;
                li.appendChild(optInput);

                ul.appendChild(li);
            }
            form.appendChild(ul);

            const removeBtn = document.createElement('button');
            removeBtn.type = 'button';
            removeBtn.textContent = 'Remove';
            removeBtn.style.position = 'absolute';
            removeBtn.style.top = '15px';
            removeBtn.style.right = '15px';
            removeBtn.style.background = 'red';
            removeBtn.style.color = '#fff';
            removeBtn.style.border = 'none';
            removeBtn.style.borderRadius = '5px';
            removeBtn.style.padding = '5px 10px';
            removeBtn.style.cursor = 'pointer';
            removeBtn.style.fontWeight = 'bold';
            removeBtn.addEventListener('click', () => {
                questionItem.remove();
            });
            form.appendChild(removeBtn);

            const doneBtn = document.createElement('button');
            doneBtn.type = 'submit';
            doneBtn.textContent = 'Done';
            doneBtn.className = 'done-question-btn';
            doneBtn.style.marginTop = '20px';
            form.appendChild(doneBtn);

            form.addEventListener('submit', function(e) {
                const questionTitle = qInput.value.trim();
                if (!questionTitle) {
                    alert('Please fill in the question title.');
                    e.preventDefault();
                    return;
                }

                const optionInputs = form.querySelectorAll('input[name="options[]"]');
                for (let opt of optionInputs) {
                    if (!opt.value.trim()) {
                        alert('Please fill in all options.');
                        e.preventDefault();
                        return;
                    }
                }

                const correctSelected = form.querySelector('input[name="correct_option"]:checked');
                if (!correctSelected) {
                    alert('Please select a correct option.');
                    e.preventDefault();
                    return;
                }
                
            });

            questionItem.appendChild(form);
            container.appendChild(questionItem);
        }

        
        function toggleEditDone(button) {
            const questionItem = button.closest('.question-item');
            const isEditing = (button.textContent === 'Done');
            const questionId = questionItem.getAttribute('id') ? questionItem.getAttribute('id').replace('question-', '') : 0;

            if (isEditing) {
                const questionTextInput = questionItem.querySelector('.edit-question-text');
                const questionText = questionTextInput ? questionTextInput.value.trim() : '';

                const optionInputs = questionItem.querySelectorAll('.edit-option-input');
                const options = [];
                optionInputs.forEach(opt => options.push(opt.value.trim()));

                const correctRadio = questionItem.querySelector('input[name="edit-correct-option"]:checked');
                const correctIndex = correctRadio ? parseInt(correctRadio.value, 10) : -1;

                if (!questionText || options.some(o => !o) || correctIndex < 0) {
                    alert('Please fill in all fields and select the correct option.');
                    return;
                }

                const form = document.createElement('form');
                form.action = 'update_question.php';
                form.method = 'POST';

                const qIdInput = document.createElement('input');
                qIdInput.type = 'hidden';
                qIdInput.name = 'question_id';
                qIdInput.value = questionId;
                form.appendChild(qIdInput);

                const qTextInput = document.createElement('input');
                qTextInput.type = 'hidden';
                qTextInput.name = 'question_text';
                qTextInput.value = questionText;
                form.appendChild(qTextInput);

                options.forEach(o => {
                    const optHidden = document.createElement('input');
                    optHidden.type = 'hidden';
                    optHidden.name = 'options[]';
                    optHidden.value = o;
                    form.appendChild(optHidden);
                });

                const correctHidden = document.createElement('input');
                correctHidden.type = 'hidden';
                correctHidden.name = 'correct_option';
                correctHidden.value = correctIndex;
                form.appendChild(correctHidden);

                document.body.appendChild(form);
                form.submit();

            } else {
                

              
                const addingQuestionForm = document.querySelector('.question-item form[action="add_question.php"]');
                if (addingQuestionForm) {
                    alert('You are currently adding a new question. Please finish adding it before editing another question.');
                    return; 
                }

               
                const editingButtons = document.querySelectorAll('.question-item .edit-btn');
                for (let btn of editingButtons) {
                    if (btn !== button && btn.textContent === 'Done') {
                        alert('You are currently editing another question. Please finish that one first before editing this question.');
                        return; 
                    }
                }

               
                button.textContent = 'Done';

                const questionTextElem = questionItem.querySelector('p');
                const originalQuestionText = questionTextElem.textContent.replace(/^\d+\.\s*/, '').trim();

                const ul = questionItem.querySelector('ul');
                const optionLis = ul.querySelectorAll('li');
                const optionsData = [];
                let correctIndex = 0;
                optionLis.forEach((li, idx) => {
                    const text = li.querySelector('.option-box').textContent.trim();
                    optionsData.push(text);
                    if (li.classList.contains('preselected')) correctIndex = idx;
                });

                questionTextElem.innerHTML = '';
                const questionTextInput = document.createElement('input');
                questionTextInput.type = 'text';
                questionTextInput.value = originalQuestionText;
                questionTextInput.className = 'edit-question-text';
                questionTextElem.appendChild(questionTextInput);

                ul.innerHTML = '';
                for (let i = 0; i < optionsData.length; i++) {
                    const li = document.createElement('li');
                    li.style.cursor = 'default';

                    const radio = document.createElement('input');
                    radio.type = 'radio';
                    radio.name = 'edit-correct-option';
                    radio.value = i;
                    if (i === correctIndex) radio.checked = true;

                    const optInput = document.createElement('input');
                    optInput.type = 'text';
                    optInput.value = optionsData[i];
                    optInput.className = 'edit-option-input';
                    optInput.style.marginLeft = '10px';

                    li.appendChild(radio);
                    li.appendChild(optInput);
                    ul.appendChild(li);
                }

               
                const removeBtn = document.createElement('button');
                removeBtn.type = 'button';
                removeBtn.textContent = 'Remove';
                removeBtn.style.position = 'absolute';
                removeBtn.style.top = '15px';
                removeBtn.style.right = '15px';
                removeBtn.style.background = 'red';
                removeBtn.style.color = '#fff';
                removeBtn.style.border = 'none';
                removeBtn.style.borderRadius = '5px';
                removeBtn.style.padding = '5px 10px';
                removeBtn.style.cursor = 'pointer';
                removeBtn.style.fontWeight = 'bold';

                removeBtn.addEventListener('click', () => {
                  
                    const totalQuestionsCount = document.querySelectorAll('.question-item').length;
                    if (totalQuestionsCount <= 1) {
                        alert('At least one question must remain in the quiz.');
                        return;
                    }

                    if (confirm('Are you sure you want to delete this question?')) {
                        window.location.href = 'delete_question.php?question_id=' + questionId;
                    }
                });

                questionItem.appendChild(removeBtn);
            }
        }

        const globalDoneButton = document.querySelector('.done-btn');
        globalDoneButton.addEventListener('click', handleGlobalDone);

        function handleGlobalDone() {
            const titleInput = document.getElementById('title-input');
            const anyQuestionEditing = document.querySelector('.question-item input.edit-question-text, .question-item input.edit-option-input');
            const addingQuestionForm = document.querySelector('.question-item form[action="add_question.php"]'); 

           
            if (titleInput || anyQuestionEditing || addingQuestionForm) {
                const confirmed = confirm('Some questions or the quiz title are still being edited. Do you want to save changes and exit edit mode?');
                if (!confirmed) return; 

              
                if (addingQuestionForm) {
                    
                    const qInput = addingQuestionForm.querySelector('input[name="question_text"]');
                    const questionTitle = qInput ? qInput.value.trim() : '';
                    if (!questionTitle) {
                        alert('Please fill in the question title before finalizing.');
                        return;
                    }

                    const optionInputs = addingQuestionForm.querySelectorAll('input[name="options[]"]');
                    for (let opt of optionInputs) {
                        if (!opt.value.trim()) {
                            alert('Please fill in all options before finalizing.');
                            return; 
                        }
                    }

                    const correctSelected = addingQuestionForm.querySelector('input[name="correct_option"]:checked');
                    if (!correctSelected) {
                        alert('Please select a correct option before finalizing.');
                        return; 
                    }

                 
                    addingQuestionForm.submit();
                    return; 
                }

               
                if (titleInput) {
                    const form = titleInput.closest('form');
                    if (form) {
                        form.submit();
                        return;
                    }
                }

                
                const editingButtons = document.querySelectorAll('.question-item .edit-btn');
                let saveNeeded = false;
                editingButtons.forEach(btn => {
                    if (btn.textContent === 'Done') {
                        saveNeeded = true;
                        btn.click(); 
                    }
                });

                if (!saveNeeded) {
                   
                    alert('All changes saved and edit mode exited.');
                }

            } else {
                
                alert('No edits are in progress. All changes are already saved.');
            }
            window.location.href = 'admindashboard.php';
        }
    </script>
</body>

</html>

<?php
$conn->close();
?>
