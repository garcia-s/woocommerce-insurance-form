const form = document.getElementById("insurance_form");
const slides = document.getElementsByClassName("form_step");
const indicators = document.getElementsByClassName("step_indicator");
let currentStep = 0;

form.onsubmit = (e) => {
  e.preventDefault();
  changeStep(1);
};

function changeStep(direction) {
  console.log("Here");
  newStep = currentStep + direction;
  console.log(slides);
  if (newStep < 0 || newStep > slides.length - 1) return;
  currentStep = newStep;

  for (let i = 0; i < slides.length; i++) {
    let position = (
      ((currentStep + slides.length) % (slides.length + i)) *
      100
    ).toString();
    console.log(position);
    /*     slides[i].style.left =  position + '%'; */
  }
}

// 0   0  1  2
// 1  -1  0  1
// 2  -2 -1  1
//
// length + current % length -i)
