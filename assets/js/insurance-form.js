
(() => {
    let loader = document.getElementById("insurance_loader");
    let form = document.getElementById("insurance_form");
    let previewIframe = document.getElementById("insurance_preview_frame")
    let preview = document.getElementById("insurance_preview_wrapper")
    let contentWrapper = document.getElementById("insurance_form_wrapper")

    let baseUrl = window.location.protocol + "//" + window.location.hostname + (window.location.port ? ":" + window.location.port : "");
    const inputs = form.elements
    const formData = {}


    for (let i = 0; i < inputs.length; i++) {
        inputs[i].onchange = (e) => {
            formData[e.target.name] = e.target.value;
        }
    }

    var radios = document.querySelectorAll('input[type=radio][name="insurance_type"]');

    const optionsContainer = document.getElementById("insurance_section_wrapper")
    const elements = [...optionsContainer.children]

    optionsContainer.innerHTML = "";

    for (let i = 0; i < radios.length; i++) {
        radios[i].addEventListener('change', () => {
            optionsContainer.innerHTML = "";
            elements[i].style.display = "flex"
            optionsContainer.appendChild(elements[i])
        });
    }

    setTimeout(() => {
        loader.classList.remove("show");
        form.classList.add("show");
        console.log("Running")
    }, 400)


    form.onsubmit = async (e) => {
        e.preventDefault();
        //TODO: VALIDATE LOCALLY;
        loader.classList.add("show");
        form.classList.remove("show")
        const response = await fetch(baseUrl + '/wp-json/insurance/v1/get-pdf-preview', {
            method: "POST",
            headers: { "Content-Type": "application/json" },
            body: JSON.stringify(formData)
        });
        if (response.status != 200) {
            setTimeout(() => {
                form.classList.add("show");
                loader.classList.remove("show")
            }, 200)
            return
        }

        const blob = await response.blob()
        previewIframe.src = URL.createObjectURL(blob);
        setTimeout(() => {
            preview.classList.add("show");
            loader.classList.remove("show")
        }, 200)
    }
})()
