<?php
function renderForm()
{
    ob_start();
?>
    <div id="insurance_form_wrapper">
        <div class="insurance_form_spinner">
        </div>
        <div class='insurance_form_steps_wrapper'>
            <div> 
                <div class="step_indicator indicator_selected"></div>
                <div class="step_indicator"></div>
                <div class="step_indicator"></div>
            </div>
        </div>
        <form id="insurance_form">

            <div class="form_step">
                <label>CONTACT NAME</label>
                <input name="contactName" type="text" />

                <label>PHONE</label>
                <input name="phone" type="phone" />

                <label>FAX (A/C, No)</label>
                <input name="fax" type="phone"/>
                <div class="insurance_btn_wrapper">
                    <button>
                        Next
                    </button>
                </div>
            </div>

            <div class="form_step">
                <label></label>
                <input type="text" />

                <label></label>
                <input type="text" />

                <label></label>
                <input type="text" />
                <div class="insurance_btn_wrapper">
                    <button>
                        Previous
                    </button>
                    <button>
                        Next
                    </button>
                </div>
            </div>

            <div class="form_step">
                <label></label>
                <input type="text" />

                <label></label>
                <input type="text" />

                <label></label>
                <input type="text" />

                <div class="insurance_btn_wrapper">
                    <button>
                        Previous
                    </button>
                    <button>
                        Send
                    </button>
                </div>
            </div>
        </form>
    </div>
<?php
    return ob_get_clean();
}
