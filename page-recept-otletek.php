<?php get_header(); ?>

<main class="container my-5">
    <h1>Kérdés Ollama AI-hoz</h1>

    <form id="ollamaForm">
        <input type="text" id="question" placeholder="Írd be a kérdésed..." required style="width:70%;padding:5px;">
        <button type="submit" style="padding:5px 10px;">Küldés</button>
    </form>

    <div id="ollamaResult" style="margin-top:20px;font-weight:bold;"></div>
    <h3>Előző kérdések és válaszok:</h3>
    <div id="ollamaHistory" style="border:1px solid #ccc; padding:10px; max-height:300px; overflow-y:auto;"></div>
</main>


<script>
    document.getElementById('ollamaForm').addEventListener('submit', async function(e) {
        e.preventDefault();

        const questionInput = document.getElementById('question'); // Az új kérdés mező
        const resultDiv = document.getElementById('ollamaResult'); // Az aktuális válasz mező
        const historyDiv = document.getElementById('ollamaHistory'); // Az előző kérdések-válaszok

        const questionText = questionInput.value.trim();
        if (!questionText) {
            resultDiv.textContent = 'Kérlek, írj be egy kérdést!';
            return;
        }

        resultDiv.textContent = 'Dolgozom...';

        try {
            const formData = new FormData();
            formData.append('action', 'ollama_query');
            formData.append('question', questionText);

            const response = await fetch('<?php echo admin_url("admin-ajax.php"); ?>', {
                method: 'POST',
                body: formData
            });

            const data = await response.json();

            if (!data.success) {
                let errMsg = data.message || 'Ismeretlen hiba';
                if (data.raw_response) {
                    errMsg += '\nRészletek: ' + data.raw_response;
                }
                resultDiv.textContent = errMsg;
                return;
            }

            // A tiszta válasz kinyerése
            const answerText = data.answer || 'Nincs visszajelzés az Ollama szervertől.';
            resultDiv.textContent = answerText;

            // Az előzményekhez hozzáadjuk az aktuális kérdés-választ
            if (historyDiv) {
                const newEntry = document.createElement('div');
                newEntry.className = 'ollama-history-entry';
                newEntry.innerHTML = `
                <strong>Kérdés:</strong> ${questionText}<br>
                <strong>Válasz:</strong> ${answerText}<hr>
            `;
                historyDiv.prepend(newEntry); // legújabb felül jelenik meg
            }

            // Az input mező kiürítése az új kérdéshez
            questionInput.value = '';
            questionInput.focus();

        } catch (err) {
            resultDiv.textContent = 'Hiba: ' + err.message;
        }
    });
</script>



<?php get_footer(); ?>