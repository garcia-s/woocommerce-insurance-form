
(() => {
    let loader = document.getElementById("insurance_loader");
    let form = document.getElementById("insurance_form");
    let previewAmount = document.getElementById("insurance_preview_amount")
    let preview = document.getElementById("insurance_preview_wrapper")
    let baseUrl = window.location.protocol + "//" + window.location.hostname + (window.location.port ? ":" + window.location.port : "");
    const send = document.getElementById("send_insurance");
    const back = document.getElementById("go_back");
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
    }, 400)


    form.onsubmit = async (e) => {
        e.preventDefault();
        //TODO: VALIDATE LOCALLY;
        loader.classList.add("show");
        form.classList.remove("show")

        const inputs = form.elements

        const formData = {}

        for (let i = 0; i < inputs.length; i++) {
            if ((inputs[i].type == "radio" && inputs[i].checked === false) || inputs[i].name === "")
                continue;
            formData[inputs[i].name] = inputs[i].value;
        }

        const response = await fetch(baseUrl + '/wp-json/insurance/v1/get-premium', {
            method: "POST",
            headers: { "Content-Type": "application/json" },
            body: JSON.stringify(formData)
        });

        json = await response.json();
        if (response.status != 200) {
            Object.keys(json).forEach((key) => { 
                document.getElementById(key).innerHTML = json[key];
            })
            setTimeout(() => {
                form.classList.add("show");
                loader.classList.remove("show")
            }, 200)
            return
        }
        // Add the premium to the table and show it  
        back.onclick = () => {
            preview.classList.remove("show");
            form.classList.add("show");
            back.onclick = null
            send.onclick = null
        }

        send.onclick = async () => {
            preview.classList.remove("show");
            loader.classList.add("show")
            const response = await fetch(baseUrl + "/wp-json/insurance/v1/submit", {
                method: "POST",
                headers: { "Content-Type": "application/json" },
                body: JSON.stringify(formData)
            })
            if (response.status === 200) {
                // SHOW THE SUCCESS SCREEN  
                setTimeout(() => {
                    document.getElementById("insurance_completed").classList.add("show");
                    loader.classList.remove("show")
                }, 200)

            }
        }

        previewAmount.innerHTML = "$" + json.premium 

        setTimeout(() => {
            preview.classList.add("show");
            loader.classList.remove("show");
        }, 200)
    }
})()
