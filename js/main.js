document.addEventListener("DOMContentLoaded", () => {

    restrictInputToNumbers('.restrict-number');

    const copyBtn = document.getElementById('copy');
    copyBtn.addEventListener('click', function () {
        copyEmails();
    });

    const textInput = document.getElementById('textInput');
    
    textInput.addEventListener('change', async function (event) {
        event.preventDefault();
        display_textarea_info(textInput);
    });

    function display_textarea_info(textInput){
        const valueTextInput = textInput.value;
        if(valueTextInput.length > 0) {
            const size = new Blob([valueTextInput]).size; 
            const charCount = valueTextInput.length;

            console.log(`Taille du texte: ${size} octets`);
            console.log(`Nombre de caractères: ${charCount}`);

            document.getElementById('text-size-to-extract').textContent = `Taille du texte: ${size} octets`;
            document.getElementById('char-count-to-extract').textContent = `Nombre de caractères: ${charCount}`;
            
        } else {
            document.getElementById('text-size-to-extract').textContent = 'Taille du texte: 0 octets';
            document.getElementById('char-count-to-extract').textContent = 'Nombre de caractères: 0';
        }
    }

    function restrictInputToNumbers(inputSelector) {
        const inputs = document.querySelectorAll(inputSelector);
      
        inputs.forEach(input => {
          input.addEventListener('keyup', function () {
            const numericValue = input.value.replace(/\D/g, '');
            input.value = numericValue;
          });
        });
    }

    function copyEmails() {
        const textarea = document.getElementById('result');
        if (textarea.value.trim() !== "") {
            textarea.select();
            textarea.setSelectionRange(0, 99999);
            document.execCommand('copy');
            Swal.fire({
                icon: "success",
                title: "Emails copiés dans le presse-papiers.",
                showConfirmButton: false,
                timer: 1500
            });
        } else {
            Swal.fire({
                icon: "error",
                title: "Aucun email à copier.",
                showConfirmButton: false,
                timer: 1500
            });
        }
    }



    function formatTime(ms) {
        let seconds = ms / 1000; 
        const minutes = parseInt(seconds / 60, 10);
        seconds = seconds % 60; 
    
        let formattedTime = "";
        if (minutes > 0) {
            formattedTime += `${minutes} min `;
        }
        formattedTime += `${seconds.toFixed(2)} s`;
    
        return formattedTime;
    }

    const generateTexteForm = document.querySelector('#generate_text_form');
    generateTexteForm.addEventListener('submit', async function (event) {
      event.preventDefault();
      const spinner = document.querySelector('.generate-loading-spinner');
      spinner.style.display = 'block';

      const token = document.querySelector('meta[name="token"]').getAttribute('content');
      const successMessageSpan = document.querySelector('#generate_text_form span.success-message');

      const url = window.location.origin + '/simple_email_extractor/api/generate-text';
      const startTime = performance.now();

      const formData = new FormData(generateTexteForm);
      formData.append('token', token); 

      successMessageSpan.textContent = "";
        try {
            const response = await fetch(url, {
                method: 'POST',
                body: formData
            });
            const data = await response.json(); 
            const endTime = performance.now();
            const requestTime = endTime - startTime;
            const formattedTime = formatTime(requestTime);

            console.log(data)       
            if (data.success && data.content !== "") {
                textInput.value = data.content;
                display_textarea_info(textInput);
                successMessageSpan.textContent = `Texte généré en ${formattedTime} !`;
            } else {
                Swal.fire({
                    title: 'Erreur',
                    text: data.error.msg,
                    icon: 'error',
                    showConfirmButton: true,
                    forceSwal2: true,
                })
            }
        } catch(error) {
            console.error(error);
           
        }finally {
            spinner.style.display = 'none';
        }
    });

    const emailExtractForm = document.querySelector('#email_extract_form');
    emailExtractForm.addEventListener('submit', async function (event) {
        event.preventDefault();
        const spinner = document.querySelector('.extract-loading-spinner');
        spinner.style.display = 'block';

        const token = document.querySelector('meta[name="token"]').getAttribute('content');
        const successMessageSpan = document.querySelector('.result.success-message');
  
        const url = window.location.origin + '/simple_email_extractor/api/extract-email';
        const startTime = performance.now();
        document.getElementById('result').value = "";
        const formData = new FormData(emailExtractForm);
        formData.append('token', token); 
  
        successMessageSpan.textContent = "";
          try {
              const response = await fetch(url, {
                method: 'POST',
                body: formData
              });
              const data = await response.json(); 
              const endTime = performance.now();
              const requestTime = endTime - startTime;
              const formattedTime = formatTime(requestTime);
  
              console.log(data)       
              if (data.success && data.emails !== "") {
                document.getElementById('result').value = data.emails;
                //display_textarea_info(textInput);
                successMessageSpan.textContent = `Extraction de ${data.count_emails} emails réalisé en ${formattedTime} !`;
              } else {
                Swal.fire({
                    title: 'Erreur',
                    text: data.error.msg,
                    icon: 'error',
                    showConfirmButton: true,
                    forceSwal2: true,
                })
              }
          } catch(error) {
              console.error(error);
             
          }finally {
              spinner.style.display = 'none';
          }
      });
});
  
  