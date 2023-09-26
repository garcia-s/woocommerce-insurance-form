
(() => {
    const form = document.getElementById("insurance_form");
    const inputs = form.querySelectorAll("input")
    const formData = {}


    for (let i = 0; i < inputs.length; i++) {
        inputs[i].onchange = (e) => {
            formData[e.target.name] = e.target.value;
        }
    }

    var radios = document.querySelectorAll('input[type=radio][name="insurance_type"]');

    function insuranceTypeHandler(event) {
        const sections = document.getElementsByClassName("insurance_options");
        for (let i = 0; i < sections.length; i++) {
            sections[i].style.display = "none";
        }
        const section = document.getElementById(this.value);
        section.style.display = "flex";
    }

    Array.prototype.forEach.call(radios, function(radio) {
        radio.addEventListener('change', insuranceTypeHandler);
    });

    form.onsubmit = async (e) => {
        e.preventDefault();
        var baseUrl = window.location.protocol + "//" + window.location.hostname + (window.location.port ? ":" + window.location.port : "");
        const response = await fetch(baseUrl + '/wp-json/insurance/v1/submit', {
            method: "POST",
            headers: { "Content-Type": "application/json" },
            body: JSON.stringify(formData)
        });
        const json = await response.json();
        console.log(json)
    }
})()
