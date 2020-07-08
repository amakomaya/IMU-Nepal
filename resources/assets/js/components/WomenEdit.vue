<template>

    <div class="panel panel-default">
        <div class="">
            <div class="panel-heading fullname">
                Woman ( {{ data.name }} )
            </div>
            <div class="panel-heading pull-right">
                ANC Visit Status :
                <ul v-if="data.anc_visits.length > 0">
                    <status-indicator status="positive" v-if="data.anc_visits.includes(1)"
                                      title="First ANC"/>
                    <status-indicator status="negative" pulse v-else title="First ANC"/>
                    <status-indicator status="positive" v-if="data.anc_visits.includes(2)"
                                      title="Second ANC"/>
                    <status-indicator status="negative" pulse v-else title="Second ANC"/>
                    <status-indicator status="positive" v-if="data.anc_visits.includes(3)"
                                      title="Third ANC"/>
                    <status-indicator status="negative" pulse v-else title="Third ANC"/>
                    <status-indicator status="positive" v-if="data.anc_visits.includes(4)"
                                      title="Forth ANC"/>
                    <status-indicator status="negative" pulse v-else title="Forth ANC"/>
                </ul>
                <ul v-else>
                    <status-indicator status="negative" pulse title="First ANC"/>
                    <status-indicator status="negative" pulse title="Second ANC"/>
                    <status-indicator status="negative" pulse title="Third ANC"/>
                    <status-indicator status="negative" pulse title="Forth ANC"/>
                </ul>
            </div>
        </div>

        <div id="exTab1">
            <ul class="nav nav-pills">
                <li class="active">
                    <a href="#register" data-toggle="tab">General Information</a>
                </li>
                <li>
                    <a href="#anc" data-toggle="tab">ANC</a>
                </li>
                <li>
                    <a href="#delivery" data-toggle="tab">Delivery</a>
                </li>
                <li>
                    <a href="#pnc" data-toggle="tab">PNC</a>
                </li>
                <li>
                    <a href="#labtest" data-toggle="tab">Lab test</a>
                </li>
                <li>
                    <a href="#mediation" data-toggle="tab">Medication</a>
                </li>
                <li>
                    <a href="#hiv_syphillis_test" data-toggle="tab">HIV/Syphillis Test</a>
                </li>
            </ul>
            <hr>

            <div class="tab-content clearfix">
                <!-- General Information -->
                <div class="tab-pane active" id="register">
                    <div class="panel">
                        <form>
                            <div class="panel-body">
                                <table class="table">
                                    <tbody>
                                    <tr>
                                        <td class="col-md-6 form-horizontal" colspan="1">
                                            <div class="form-group">
                                                <label class="col-md-4 control-label">Full Name</label>
                                                <div class="col-md-8 inputGroupContainer">
                                                    <div class="input-group"><span class="input-group-addon"><i
                                                            class="fa fa-female"></i></span>
                                                        <input id="full_name" name="" placeholder="Fullname"
                                                               class="form-control" required="true" v-model="data.name"
                                                               type="text">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="col-md-4 control-label">Height</label>
                                                <div class="col-md-8 inputGroupContainer">
                                                    <div class="input-group"><span class="input-group-addon"><img
                                                            src="images/backend/icons/height.png"
                                                            alt="" height="19" width="14"></span>
                                                        <input id="height" name="" placeholder="Height"
                                                               class="form-control" v-model="data.height" value=""
                                                               type="text">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="col-md-4 control-label">LMP Date</label>
                                                <div class="col-md-8 inputGroupContainer">
                                                    <div class="input-group"><span class="input-group-addon"><i
                                                            class="glyphicon glyphicon-calendar"></i></span>
                                                        <input id="lmp_date" name="LMP" placeholder="LMP DATE"
                                                               class="form-control" v-model="data.lmp_date_en"
                                                               required="true" value="" type="text">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="col-md-4 control-label">District</label>
                                                <div class="col-md-8 inputGroupContainer">
                                                    <div class="input-group">
                                                    <span class="input-group-addon" style="max-width: 100%;"><i
                                                            class="glyphicon glyphicon-list"></i></span>
                                                        <select class="form-control" :required="true"
                                                                v-model="data.district_id">
                                                            <option
                                                                    v-for="option in districts"
                                                                    v-bind:value="option.id"
                                                                    :selected="option.id == data.district_id"
                                                            >{{ option.district_name }}
                                                            </option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="col-md-4 control-label">Ward no.</label>
                                                <div class="col-md-8 inputGroupContainer">
                                                    <div class="input-group"><span class="input-group-addon"><img
                                                            src="images/backend/icons/ward.png" alt="" height="15"
                                                            width="15"></span>
                                                        <input id="ward" name="ward" placeholder="Ward no."
                                                               class="form-control" required="true" v-model="data.ward"
                                                               value=""
                                                               type="text">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="col-md-4 control-label">Sewa Darta No.</label>
                                                <div class="col-md-8 inputGroupContainer">
                                                    <div class="input-group"><span class="input-group-addon"><i
                                                            class="fa fa-calculator" aria-hidden="true"></i></span>
                                                        <input id="" name="" placeholder="Sewa Darta No."
                                                               class="form-control" required="true" value=""
                                                               type="text" v-model="data.sewa_darta_no">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="col-md-4 control-label">Blood Group</label>
                                                <div class="col-md-8 inputGroupContainer">
                                                    <div class="input-group">
                                                    <span class="input-group-addon" style="max-width: 100%;"><img
                                                            src="images/backend/icons/checkup.png" alt="" height="16"
                                                            width="14"></span>
                                                        <select class="form-control" :required="true"
                                                                v-model="data.blood_group">
                                                            <option
                                                                    v-for="(option, index) in bloodGroupOptions"
                                                                    v-bind:value="index"
                                                                    :selected="index == data.blood_group"
                                                            >{{ option }}
                                                            </option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="col-md-6 form-horizontal" colspan="1">
                                            <fieldset>
                                                <div class="form-group">
                                                    <label class="col-md-4 control-label">Phone</label>
                                                    <div class="col-md-8 inputGroupContainer">
                                                        <div class="input-group"><span class="input-group-addon"><i
                                                                class="glyphicon glyphicon-earphone"></i></span>
                                                            <input id="phone" name="Phone" placeholder="Phone Number"
                                                                   class="form-control" required="true"
                                                                   v-model="data.phone" value=""
                                                                   type="text">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label class="col-md-4 control-label">Age</label>
                                                    <div class="col-md-8 inputGroupContainer">
                                                        <div class="input-group"><span class="input-group-addon"><i
                                                                class="glyphicon glyphicon-font"></i></span>
                                                            <input id="age" name="age" placeholder="Age"
                                                                   class="form-control" required="true" value=""
                                                                   type="text" v-model="data.age">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label class="col-md-4 control-label">Caste</label>
                                                    <div class="col-md-8 inputGroupContainer">
                                                        <div class="input-group">
                                                        <span class="input-group-addon" style="max-width: 100%;"><i
                                                                class="glyphicon glyphicon-list"></i></span>
                                                            <select class="selectpicker form-control"
                                                                    v-model="data.caste">
                                                                <option
                                                                        v-for="(option, index) in casteOptions"
                                                                        v-bind:value="index+1"
                                                                        :selected="index+1 == data.caste"
                                                                >{{ option }}
                                                                </option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label class="col-md-4 control-label">Municipality</label>
                                                    <div class="col-md-8 inputGroupContainer">
                                                        <div class="input-group">
                                                        <span class="input-group-addon" style="max-width: 100%;"><i
                                                                class="glyphicon glyphicon-list"></i></span>
                                                            <select class="form-control" :required="true"
                                                                    v-model="data.municipality_id">
                                                                <option
                                                                        v-for="option in municipalities"
                                                                        v-if="data.district_id == option.district_id"
                                                                        v-bind:value="option.id"
                                                                        :selected="option.id == data.municipality_id"
                                                                >{{ option.municipality_name }}
                                                                </option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label class="col-md-4 control-label">Tole Name</label>
                                                    <div class="col-md-8 inputGroupContainer">
                                                        <div class="input-group"><span class="input-group-addon"><i
                                                                class="fa fa-map-marker"></i></span>
                                                            <input id="tole" name="tole" placeholder="Tole Name"
                                                                   class="form-control" value=""
                                                                   type="text" v-model="data.tole">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label class="col-md-4 control-label">Mul/ORC Darta</label>
                                                    <div class="col-md-4 inputGroupContainer">
                                                        <input id="mool_darta" name="" placeholder="Mul Darta"
                                                               class="form-control" value=""
                                                               type="text" v-model="data.mool_darta_no">
                                                    </div>
                                                    <div class="col-md-4 inputGroupContainer">

                                                        <input id="orc_darta" name="" placeholder="ORC Darta"
                                                               class="form-control" value="" v-model="data.orc_darta_no"
                                                               type="text">
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label class="col-md-4 control-label">Husband Name</label>
                                                    <div class="col-md-8 inputGroupContainer">
                                                        <div class="input-group"><span class="input-group-addon"><i
                                                                class="glyphicon glyphicon-user"></i></span>
                                                            <input id="husband_name" name="husband_name"
                                                                   placeholder="Husband_name" class="form-control"
                                                                   value="" v-model="data.husband_name" type="text">
                                                        </div>
                                                    </div>
                                                </div>
                                            </fieldset>
                                        </td>
                                    </tr>
                                    </tbody>
                                </table>

                                <div class="pull-right">
                                    <button class="btn btn-primary"
                                            @click.prevent="updateGeneralInformationFormValues(data)">
                                        Update
                                    </button>
                                    <button class="btn btn-danger" @click.prevent="deleteWoman(data)">Delete</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                <!-- End General Information -->
                <!-- ANC -->
                <div class="tab-pane" id="anc">
                    <div v-if="data.ancs && data.ancs.length > 0" v-for="anc in data.ancs" class="panel">
                        <form>
                            <div class="panel-body">
                                <div class="card card-body">
                                    <table class="table">
                                        <td class="form-horizontal">
                                            <div class="form-group">
                                                <label class="col-md-4 control-label">ANC Service Visit</label>
                                                <div class=" inputGroupContainer">
                                                    <div class="input-group col-md-6">
                                                        <select class="selectpicker form-control"
                                                                v-model="anc.service_for">
                                                            <option
                                                                    v-for="(option, index) in serviceForOptions"
                                                                    v-bind:value="index"
                                                                    :selected="index == anc.service_for"
                                                            >{{ option }}
                                                            </option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                    </table>
                                    <table class="table">
                                        <td class="col-md-6 form-horizontal" colspan="1">
                                            <fieldset>
                                                <div class="form-group">
                                                    <label class="col-md-4 control-label">Visit Date</label>
                                                    <div class="col-md-8 inputGroupContainer">
                                                        <div class="input-group"><span class="input-group-addon"><i
                                                                class="glyphicon glyphicon-calendar"></i></span>
                                                            <input id="visit_date" name="" placeholder=""
                                                                   class="form-control"
                                                                   required="true" v-model="anc.visit_date" value=""
                                                                   type="text">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label class="col-md-4 control-label">Blood Pressure</label>
                                                    <div class="col-md-8 inputGroupContainer">
                                                        <div class="input-group"><span class="input-group-addon"><img
                                                                src="images/backend/icons/right_heart.png"
                                                                alt="" height="13" width="13"></span>
                                                            <input id="blood_pressure" name="" placeholder="120"
                                                                   class="form-control"
                                                                   required="true" v-model="anc.blood_pressure" value=""
                                                                   type="text">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label class="col-md-4 control-label">Baby Presentation</label>
                                                    <div class="col-md-8 inputGroupContainer">
                                                        <div class="input-group">
                                                            <span class="input-group-addon" style="max-width: 100%;"><i
                                                                    class="glyphicon glyphicon-list"></i></span>
                                                            <select class="selectpicker form-control"
                                                                    v-model="anc.baby_presentation">
                                                                <option
                                                                        v-for="option in babyPresentationOptions"
                                                                        v-bind:value="option"
                                                                        :selected="option == anc.baby_presentation"
                                                                >{{ option }}
                                                                </option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label class="col-md-4 control-label">Womb Height(C.M)</label>
                                                    <div class="col-md-8 inputGroupContainer">
                                                        <div class="input-group"><span class="input-group-addon"><img
                                                                src="images/backend/icons/measure_height.png"
                                                                alt="" height="19" width="14"></span>
                                                            <input id="Womb_height" name="Womb_height"
                                                                   placeholder="Womb height" class="form-control"
                                                                   required="true" value="" v-model="anc.uterus_height"
                                                                   type="text">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label class="col-md-4 control-label">Next Visit</label>
                                                    <div class="col-md-8 inputGroupContainer">
                                                        <div class="input-group"><span class="input-group-addon"><i
                                                                class="glyphicon glyphicon-calendar"></i></span>
                                                            <input id="next_visit" name="" placeholder=""
                                                                   class="form-control"
                                                                   required="true" value=""
                                                                   v-model="anc.next_visit_date" type="text">
                                                        </div>
                                                    </div>
                                                </div>
                                            </fieldset>
                                        </td>
                                        <td class="col-md-6 form-horizontal" colspan="1">
                                            <fieldset>
                                                <div class="form-group">
                                                    <label class="col-md-4 control-label">Weight</label>
                                                    <div class="col-md-8 inputGroupContainer">
                                                        <div class="input-group"><span class="input-group-addon"><img
                                                                src="images/backend/icons/weight.png" alt=""
                                                                height="15" width="14"></span>
                                                            <input id="Weight" name="Weight" placeholder="Weight"
                                                                   class="form-control" required="true"
                                                                   v-model="anc.weight" value=""
                                                                   type="text">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label class="col-md-4 control-label">Anemia</label>
                                                    <div class="col-md-8 inputGroupContainer">
                                                        <div class="input-group">
                                                        <span class="input-group-addon" style="max-width: 100%;"><i
                                                                class="glyphicon glyphicon-list"></i></span>
                                                            <select class="selectpicker form-control"
                                                                    v-model="anc.anemia">
                                                                <option
                                                                        v-for="option in ['Yes', 'No']"
                                                                        v-bind:value="option"
                                                                        :selected="option == anc.anemia"
                                                                >{{ option }}
                                                                </option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label class="col-md-4 control-label">Swelling</label>
                                                    <div class="col-md-8 inputGroupContainer">
                                                        <div class="input-group">
                                                        <span class="input-group-addon" style="max-width: 100%;"><i
                                                                class="glyphicon glyphicon-list"></i></span>
                                                            <select class="selectpicker form-control"
                                                                    v-model="anc.swelling">
                                                                <option
                                                                        v-for="option in swellingOptions"
                                                                        v-bind:value="option"
                                                                        :selected="option == anc.swelling"
                                                                >{{ option }}
                                                                </option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label class="col-md-4 control-label">Fetal Heartsound</label>
                                                    <div class="col-md-8 inputGroupContainer">
                                                        <div class="input-group"><span class="input-group-addon"><i
                                                                class="fa fa-stethoscope" aria-hidden="true"></i></span>
                                                            <input id="fetal_heartsound" name="" placeholder="140"
                                                                   class="form-control"
                                                                   required="true" value=""
                                                                   v-model="anc.baby_heart_beat" type="text">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label class="col-md-4 control-label">Other Problem</label>
                                                    <div class="col-md-8 inputGroupContainer">
                                                        <div class="input-group"><span class="input-group-addon"><img
                                                                src="images/backend/icons/problem.png" alt=""
                                                                height="14" width="13"></span>
                                                            <input id="other_problems" name="" placeholder="140"
                                                                   class="form-control"
                                                                   required="true" value="" v-model="anc.other"
                                                                   type="text">
                                                        </div>
                                                    </div>
                                                </div>
                                            </fieldset>
                                        </td>
                                    </table>
                                </div>
                                <div class="pull-right">
                                    <button class="btn btn-primary"
                                            @click.prevent="updateAnc(anc)">
                                        Update
                                    </button>
                                    <button class="btn btn-danger" @click.prevent="deleteAnc(anc)">Delete</button>
                                </div>
                            </div>
                        </form>
                        <hr class="anc-break">
                    </div>

                    <div v-if="!data.ancs.length">
                        <p class="text-center">No Record Found</p>
                    </div>
                </div>
                <!-- End ANC -->
                <!-- Delivery -->
                <div class="tab-pane" id="delivery">
                    <div v-if="data.delivery !== null" class="panel">
                        <form>
                            <div class="panel-body">
                                <div class="card card-body">
                                    <table class="table">
                                        <td class="col-md-6 form-horizontal" colspan="1">
                                            <fieldset>
                                                <div class="form-group">
                                                    <label class="col-md-4 control-label">Delivery Date</label>
                                                    <div class="col-md-8 inputGroupContainer">
                                                        <div class="input-group"><span class="input-group-addon"><i
                                                                class="glyphicon glyphicon-calendar"></i></span>
                                                            <input id="delivery_date" name="" placeholder=""
                                                                   class="form-control"
                                                                   required="true" v-model="data.delivery.delivery_date"
                                                                   value="" type="text">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label class="col-md-4 control-label">Delivery Place</label>
                                                    <div class="col-md-8 inputGroupContainer">
                                                        <div class="input-group">
                                                            <span class="input-group-addon" style="max-width: 100%;"><i
                                                                    class="glyphicon glyphicon-list"></i></span>
                                                            <select class="selectpicker form-control"
                                                                    v-model="data.delivery.delivery_place">
                                                                <option
                                                                        v-for="option in deliveryPlaceOptions"
                                                                        v-bind:value="option"
                                                                        :selected="option == data.delivery.delivery_place"
                                                                >{{ option }}
                                                                </option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label class="col-md-4 control-label">Presentation</label>
                                                    <div class="col-md-8 inputGroupContainer">
                                                        <div class="input-group">
                                                            <span class="input-group-addon" style="max-width: 100%;"><i
                                                                    class="glyphicon glyphicon-list"></i></span>
                                                            <select class="selectpicker form-control"
                                                                    v-model="data.delivery.presentation">
                                                                <option
                                                                        v-for="option in babyPresentationOptions"
                                                                        v-bind:value="option"
                                                                        :selected="option == data.delivery.presentation"
                                                                >{{ option }}
                                                                </option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label class="col-md-4 control-label">Other Problem</label>
                                                    <div class="col-md-8 inputGroupContainer">
                                                        <div class="input-group"><span class="input-group-addon"><img
                                                                src="images/backend/icons/problem.png" alt=""
                                                                height="14" width="13"></span>
                                                            <textarea id="other_proble" class="form-control"
                                                                      v-model="data.delivery.other_problem"></textarea>

                                                        </div>
                                                    </div>
                                                </div>
                                            </fieldset>
                                        </td>
                                        <td class="col-md-6 form-horizontal" colspan="1">
                                            <fieldset>
                                                <div class="form-group">
                                                    <label class="col-md-4 control-label">Delivery Time</label>
                                                    <div class="col-md-8 inputGroupContainer">
                                                        <div class="input-group"><span class="input-group-addon"><i
                                                                class="glyphicon glyphicon-calendar"></i></span>
                                                            <input id="delivery_time" name="" placeholder=""
                                                                   class="form-control"
                                                                   required="true" v-model="data.delivery.delivery_time"
                                                                   value="" type="time">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label class="col-md-4 control-label">Delivery Type</label>
                                                    <div class="col-md-8 inputGroupContainer">
                                                        <div class="input-group">
                                                        <span class="input-group-addon" style="max-width: 100%;"><i
                                                                class="glyphicon glyphicon-list"></i></span>
                                                            <select class="selectpicker form-control"
                                                                    v-model="data.delivery.delivery_type">
                                                                <option
                                                                        v-for="option in deliveryTypeOptions"
                                                                        v-bind:value="option"
                                                                        :selected="option === data.delivery.delivery_type"
                                                                >{{ option }}
                                                                </option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label class="col-md-4 control-label">Complications</label>
                                                    <div class="col-md-8 inputGroupContainer">
                                                        <div class="input-group">
                                                            <input type="checkbox"
                                                                   v-model="deliveryComplicationChecked" value="true_1"
                                                            > Excess BloodFlow
                                                            <input type="checkbox" v-model="deliveryComplicationChecked"
                                                                   value="true_2"> > 12 Hrs Labor pain
                                                            <input type="checkbox" v-model="deliveryComplicationChecked"
                                                                   value="true_3"> Placental Retaintaion
                                                            <!--                                                            <span>Checked names: {{ deliveryComplicationChecked }}</span>-->
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label class="col-md-4 control-label">Advice</label>
                                                    <div class="col-md-8 inputGroupContainer">
                                                        <div class="input-group"><span class="input-group-addon"><i
                                                                class="fa fa-stethoscope" aria-hidden="true"></i></span>
                                                            <textarea id="advice" class="form-control"
                                                                      v-model="data.delivery.advice"></textarea>
                                                        </div>
                                                    </div>
                                                </div>
                                            </fieldset>
                                        </td>
                                    </table>
                                </div>
                                <div class="pull-right">
                                    <button class="btn btn-primary"
                                            @click.prevent="updateDelivery(data.delivery)">
                                        Update
                                    </button>
                                    <button class="btn btn-danger" @click.prevent="deleteDelivery(data.delivery)">Delete
                                    </button>
                                </div>
                            </div>
                        </form>
                        <hr class="anc-break">
                    </div>

                    <div v-else>
                        <p class="text-center">No Record Found</p>
                    </div>

                    <!-- Baby Details-->
                    <h4 class="text-center" v-if="data.delivery !== null"><u>Baby Details</u></h4>
                    <div v-if="data.baby_details && data.baby_details.length > 0" v-for="baby in data.baby_details"
                         class="panel">
                        <form>
                            <div class="panel-body">
                                <div class="card card-body">
                                    <table class="table">
                                        <td class="col-md-6 form-horizontal" colspan="1">
                                            <fieldset>
                                                <div class="form-group">
                                                    <label class="col-md-4 control-label">Weight</label>
                                                    <div class="col-md-8 inputGroupContainer">
                                                        <div class="input-group"><span class="input-group-addon"><img
                                                                src="images/backend/icons/weight.png"
                                                                alt="" height="19" width="14"></span>
                                                            <input id="baby_weight" name="Womb_height"
                                                                   placeholder="Womb height" class="form-control"
                                                                   required="true" value="" v-model="baby.weight"
                                                                   type="text">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label class="col-md-4 control-label">Premature Birth</label>
                                                    <div class="col-md-8 inputGroupContainer">
                                                        <div class="input-group"><span class="input-group-addon"><i
                                                                class="glyphicon glyphicon-list"></i></span>
                                                            <select class="selectpicker form-control"
                                                                    v-model="baby.premature_birth">
                                                                <option
                                                                        v-for="option in ['Yes', 'No']"
                                                                        v-bind:value="option"
                                                                        :selected="option == baby.premature_birth"
                                                                >{{ option }}
                                                                </option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label class="col-md-4 control-label">Advice</label>
                                                    <div class="col-md-8 inputGroupContainer">
                                                        <div class="input-group"><span class="input-group-addon"><i
                                                                class="fa fa-stethoscope" aria-hidden="true"></i></span>
                                                            <textarea id="baby_advice" class="form-control"
                                                                      v-model="baby.advice"></textarea>
                                                        </div>
                                                    </div>
                                                </div>
                                            </fieldset>
                                        </td>
                                        <td class="col-md-6 form-horizontal" colspan="1">
                                            <fieldset>
                                                <div class="form-group">
                                                    <label class="col-md-4 control-label">Gender</label>
                                                    <div class="col-md-8 inputGroupContainer">
                                                        <div class="input-group"><span class="input-group-addon"><i
                                                                class="glyphicon glyphicon-list"></i></span>
                                                            <select class="selectpicker form-control"
                                                                    v-model="baby.gender">
                                                                <option
                                                                        v-for="option in ['Male', 'Female']"
                                                                        v-bind:value="option"
                                                                        :selected="option == baby.gender"
                                                                >{{ option }}
                                                                </option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label class="col-md-4 control-label">Alive / Dead Birth</label>
                                                    <div class="col-md-8 inputGroupContainer">
                                                        <div class="input-group"><span class="input-group-addon"><i
                                                                class="glyphicon glyphicon-list"></i></span>
                                                            <select class="selectpicker form-control"
                                                                    v-model="baby.baby_alive">
                                                                <option
                                                                        v-for="option in ['Alive', 'Death']"
                                                                        v-bind:value="option"
                                                                        :selected="option == baby.baby_alive"
                                                                >{{ option }}
                                                                </option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label class="col-md-4 control-label">Baby's Status</label>
                                                    <div class="col-md-8 inputGroupContainer">
                                                        <div class="input-group">
                                                        <span class="input-group-addon" style="max-width: 100%;"><i
                                                                class="glyphicon glyphicon-list"></i></span>

                                                        </div>
                                                    </div>
                                                </div>
                                            </fieldset>
                                        </td>
                                    </table>
                                </div>
                                <div class="pull-right">
                                    <button class="btn btn-primary"
                                            @click.prevent="updateBabyDetail(baby)">
                                        Update
                                    </button>
                                    <button class="btn btn-danger" @click.prevent="deleteBabyDetail(baby)">Delete
                                    </button>
                                </div>
                            </div>

                        </form>
                        <hr class="anc-break">
                    </div>
                    <!--  End Delivery-->
                </div>
                <!-- End Delivery -->
                <!-- Pncs -->
                <div class="tab-pane" id="pnc">
                    <div v-if="data.pncs && data.pncs.length > 0" v-for="pnc in data.pncs" class="panel">
                        <form>
                            <div class="panel-body">
                                <div class="card card-body">
                                    <table class="table">
                                        <td class="col-md-6 form-horizontal" colspan="1">
                                            <fieldset>
                                                <div class="form-group">
                                                    <label class="col-md-4 control-label">Visit Date</label>
                                                    <div class="col-md-8 inputGroupContainer">
                                                        <div class="input-group"><span class="input-group-addon"><i
                                                                class="glyphicon glyphicon-calendar"></i></span>
                                                            <input id="date_of_visit" name="" placeholder=""
                                                                   class="form-control"
                                                                   required="true" v-model="pnc.date_of_visit" value=""
                                                                   type="text">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label class="col-md-4 control-label">Mother Status</label>
                                                    <div class="col-md-8 inputGroupContainer">
                                                        <div class="input-group"><span class="input-group-addon"><img
                                                                src="images/backend/icons/problem.png" alt=""
                                                                height="14" width="13"></span>
                                                            <textarea id="mother_status" class="form-control"
                                                                      v-model="pnc.mother_status"></textarea>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label class="col-md-4 control-label">Family Plan</label>
                                                    <div class="col-md-8 inputGroupContainer">
                                                        <div class="input-group"><span class="input-group-addon"><img
                                                                src="images/backend/icons/problem.png" alt=""
                                                                height="14" width="13"></span>
                                                            <textarea id="family_plan" class="form-control"
                                                                      v-model="pnc.family_plan"></textarea>

                                                        </div>
                                                    </div>
                                                </div>
                                            </fieldset>
                                        </td>
                                        <td class="col-md-6 form-horizontal" colspan="1">
                                            <fieldset>
                                                <div class="form-group">
                                                    <label class="col-md-4 control-label">Time</label>
                                                    <div class="col-md-8 inputGroupContainer">
                                                        <div class="input-group"><span class="input-group-addon"><i
                                                                class="glyphicon glyphicon-calendar"></i></span>
                                                            <input id="pnc_time" name="" placeholder=""
                                                                   class="form-control"
                                                                   required="true" v-model="pnc.delivery_time" value=""
                                                                   type="time">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label class="col-md-4 control-label">Baby's Status</label>
                                                    <div class="col-md-8 inputGroupContainer">
                                                        <div class="input-group"><span class="input-group-addon"><img
                                                                src="images/backend/icons/problem.png" alt=""
                                                                height="14" width="13"></span>
                                                            <textarea id="pnc_baby_status" class="form-control"
                                                                      v-model="pnc.baby_status"></textarea>

                                                        </div>
                                                    </div>
                                                </div>
                                            </fieldset>
                                        </td>
                                    </table>
                                </div>
                                <div class="pull-right">
                                    <button class="btn btn-primary"
                                            @click.prevent="updateAnc(anc)">
                                        Update
                                    </button>
                                    <button class="btn btn-danger" @click.prevent="deleteAnc(anc)">Delete</button>
                                </div>
                            </div>
                        </form>
                        <hr class="anc-break">
                    </div>

                    <div v-if="data.pncs.length == 0">
                        <p class="text-center">No Record Found</p>
                    </div>
                </div>
                <!-- End PNCS -->
                <!-- Lab-Test -->
                <div class="tab-pane" id="labtest">
                    <div v-if="data.lab_tests && data.lab_tests.length > 0" v-for="lab_test in data.lab_tests"
                         class="panel">
                        <form>
                            <div class="panel-body">
                                <div class="card card-body">
                                    <table class="table">
                                        <td class="col-md-6 form-horizontal" colspan="1">
                                            <fieldset>
                                                <div class="form-group">
                                                    <label class="col-md-4 control-label">Test Visit Date</label>
                                                    <div class="col-md-8 inputGroupContainer">
                                                        <div class="input-group"><span class="input-group-addon"><i
                                                                class="glyphicon glyphicon-calendar"></i></span>
                                                            <input id="test_date" name="" placeholder=""
                                                                   class="form-control"
                                                                   required="true" v-model="lab_test.test_date" value=""
                                                                   type="text">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label class="col-md-4 control-label">Haemoglobin</label>
                                                    <div class="col-md-8 inputGroupContainer">
                                                        <div class="input-group"><span class="input-group-addon"><img
                                                                src="images/backend/icons/problem.png" alt=""
                                                                height="14" width="13"></span>
                                                            <textarea id="test_hb" class="form-control"
                                                                      v-model="lab_test.hb"></textarea>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label class="col-md-4 control-label">Urine Protein</label>
                                                    <div class="col-md-8 inputGroupContainer">
                                                        <div class="input-group"><span class="input-group-addon"><img
                                                                src="images/backend/icons/problem.png" alt=""
                                                                height="14" width="13"></span>
                                                            <textarea id="urine_protein" class="form-control"
                                                                      v-model="lab_test.urine_protein"></textarea>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label class="col-md-4 control-label">Blood Sugar</label>
                                                    <div class="col-md-8 inputGroupContainer">
                                                        <div class="input-group"><span class="input-group-addon"><img
                                                                src="images/backend/icons/problem.png" alt=""
                                                                height="14" width="13"></span>
                                                            <textarea id="test_blood_sugar" class="form-control"
                                                                      v-model="lab_test.blood_sugar"></textarea>

                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label class="col-md-4 control-label">VDRL</label>
                                                    <div class="col-md-8 inputGroupContainer">
                                                        <div class="input-group"><span class="input-group-addon"><img
                                                                src="images/backend/icons/problem.png" alt=""
                                                                height="14" width="13"></span>
                                                            <textarea id="test_vdrl" class="form-control"
                                                                      v-model="lab_test.vdrl"></textarea>

                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label class="col-md-4 control-label">Others</label>
                                                    <div class="col-md-8 inputGroupContainer">
                                                        <div class="input-group"><span class="input-group-addon"><img
                                                                src="images/backend/icons/problem.png" alt=""
                                                                height="14" width="13"></span>
                                                            <textarea id="test_others" class="form-control"
                                                                      v-model="lab_test.other"></textarea>
                                                        </div>
                                                    </div>
                                                </div>
                                            </fieldset>
                                        </td>
                                        <td class="col-md-6 form-horizontal" colspan="1">
                                            <fieldset>
                                                <div class="form-group">
                                                    <label class="col-md-4 control-label">Albumin</label>
                                                    <div class="col-md-8 inputGroupContainer">
                                                        <div class="input-group"><span class="input-group-addon"><img
                                                                src="images/backend/icons/problem.png" alt=""
                                                                height="14" width="13"></span>
                                                            <textarea id="test_albumin" class="form-control"
                                                                      v-model="lab_test.albumin"></textarea>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label class="col-md-4 control-label">Urine Sugar</label>
                                                    <div class="col-md-8 inputGroupContainer">
                                                        <div class="input-group"><span class="input-group-addon"><img
                                                                src="images/backend/icons/problem.png" alt=""
                                                                height="14" width="13"></span>
                                                            <textarea id="test_sugar" class="form-control"
                                                                      v-model="lab_test.urine_sugar"></textarea>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label class="col-md-4 control-label">HBsAg</label>
                                                    <div class="col-md-8 inputGroupContainer">
                                                        <div class="input-group"><span class="input-group-addon"><img
                                                                src="images/backend/icons/problem.png" alt=""
                                                                height="14" width="13"></span>
                                                            <textarea id="test_hbsag" class="form-control"
                                                                      v-model="lab_test.hbsag"></textarea>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label class="col-md-4 control-label">Retro Virus</label>
                                                    <div class="col-md-8 inputGroupContainer">
                                                        <div class="input-group"><span class="input-group-addon"><img
                                                                src="images/backend/icons/problem.png" alt=""
                                                                height="14" width="13"></span>
                                                            <textarea id="test_retro_virus" class="form-control"
                                                                      v-model="lab_test.retro_virus"></textarea>
                                                        </div>
                                                    </div>
                                                </div>
                                            </fieldset>
                                        </td>
                                    </table>
                                </div>
                                <div class="pull-right">
                                    <button class="btn btn-primary"
                                            @click.prevent="updateLabTest(lab_test)">
                                        Update
                                    </button>
                                    <button class="btn btn-danger" @click.prevent="deleteLabTest(lab_test)">Delete</button>
                                </div>
                            </div>
                        </form>
                        <hr class="anc-break">
                    </div>

                    <div v-if="data.lab_tests.length == 0">
                        <p class="text-center">No Record Found</p>
                    </div>
                </div>
                <!-- End Lab Tests -->
                <!-- Vaccinations -->
                <div class="tab-pane" id="mediation">
                    <div class="panel">
                        <div class="panel-body">
                            <div class="card card-body">
                                <table class="table">
                                    <thead>
                                    <tr>
                                        <th class="col-md-0" scope="col">S.N.</th>
                                        <th class="col-md-2" scope="col">Vaccination Name</th>
                                        <th class="col-md-3" scope="col">No of. Pills / Reg. No.</th>
                                        <th scope="col-md-3">Vaccinated Date NP</th>
                                        <th scope="col-md-2">Action</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <tr v-if="Object.keys(data.vaccinations).length > 0" v-for="(vaccination, key) in data.vaccinations">
                                        <th scope="row">{{ key +1 }}</th>
                                        <td><input name="" v-model="vaccination.vaccine_name" placeholder="Vaccine Name" class="form-control" readonly value="" type="text"></td>
                                        <td v-if="vaccination.vaccine_type === 3">
                                            <input name="" v-model="vaccination.vaccine_reg_no" placeholder="Vaccination Reg. No." class="form-control" value="" type="text">
                                        </td>
                                        <td v-else>
                                            <input name="" v-model="vaccination.no_of_pills" placeholder="No. of. Pills" class="form-control" value="" type="text">
                                        </td>
                                        <td><input name="" v-model="vaccination.vaccinated_date_np" placeholder="Vaccinated Date NP" class="form-control" value="" type="text"></td>
                                        <td>
                                            <button class="btn btn-primary btn-sm" @click.prevent="updateVaccination(vaccination)">Update</button>
                                            <button class="btn btn-danger btn-sm" @click.prevent="deleteVaccination(vaccination)">Delete</button>
                                        </td>
                                    </tr>
                                    <tr v-if="Object.keys(data.vaccinations).length === 0">
                                        <br>
                                        || No Record Found ||
                                    </tr>
                                    </tbody>
                                </table>

                                <!-- /.row -->

                            </div>
                        </div>
                    </div>
                </div>
                <!-- End Vaccinations -->
                <!-- HIV/Syphillis Test -->
                <div class="tab-pane" id="hiv_syphillis_test">
                    <div class="panel" v-if="data.h_i_v_syphillis_test !== null">
                        <form>
                            <div class="panel-body">
                                <div class="card card-body">
                                    <table class="table">
                                        <td class="form-horizontal">
                                            <div class="form-group">
                                                <label class="col-md-2 control-label">Counselling Date</label>
                                                <div class="col-md-8 inputGroupContainer">
                                                    <div class="input-group"><span class="input-group-addon"><i
                                                            class="glyphicon glyphicon-calendar"></i></span>
                                                        <input id="delivery_date" name="" placeholder=""
                                                                class="form-control"
                                                                required="true" v-model="data.h_i_v_syphillis_test.counselling_date"
                                                                value="" type="text">
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                    </table>
                                    <table class="table">
                                        <td class="col-md-6 form-horizontal" colspan="1">
                                            <fieldset>
                                                <div class="form-group">
                                                    <label class="col-md-4 control-label">HIV Test Date</label>
                                                    <div class="col-md-8 inputGroupContainer">
                                                        <div class="input-group"><span class="input-group-addon"><i
                                                                class="glyphicon glyphicon-calendar"></i></span>
                                                            <input id="delivery_date" name="" placeholder=""
                                                                   class="form-control"
                                                                   required="true" v-model="data.h_i_v_syphillis_test.hiv_test_date"
                                                                   value="" type="text">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label class="col-md-4 control-label">HIV Status</label>
                                                    <div class="col-md-8 inputGroupContainer">
                                                        <div class="input-group">
                                                        <span class="input-group-addon" style="max-width: 100%;"><i
                                                                class="glyphicon glyphicon-list"></i></span>
                                                            <select class="selectpicker form-control"
                                                                    v-model="data.h_i_v_syphillis_test.hiv_status">
                                                                <option
                                                                        v-for="(option, index) in positiveNegatveOptions"
                                                                        v-bind:value="index"
                                                                        :selected="index === data.h_i_v_syphillis_test.hiv_status"
                                                                >{{ option }}
                                                                </option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label class="col-md-4 control-label">Partner HIV status</label>
                                                    <div class="col-md-8 inputGroupContainer">
                                                        <div class="input-group">
                                                        <span class="input-group-addon" style="max-width: 100%;"><i
                                                                class="glyphicon glyphicon-list"></i></span>
                                                            <select class="selectpicker form-control"
                                                                    v-model="data.h_i_v_syphillis_test.partner_hiv_status">
                                                                <option
                                                                        v-for="(option, index) in positiveNegatveOptions"
                                                                        v-bind:value="index"
                                                                        :selected="index === data.h_i_v_syphillis_test.partner_hiv_status"
                                                                >{{ option }}
                                                                </option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                            </fieldset>
                                        </td>
                                        <td class="col-md-6 form-horizontal" colspan="1">
                                            <fieldset>
                                                <div class="form-group">
                                                    <label class="col-md-4 control-label">Result Recieved</label>
                                                    <div class="col-md-8 inputGroupContainer">
                                                        <div class="input-group">
                                                        <span class="input-group-addon" style="max-width: 100%;"><i
                                                                class="glyphicon glyphicon-list"></i></span>
                                                            <select class="selectpicker form-control"
                                                                    v-model="data.h_i_v_syphillis_test.result_recieved">
                                                                <option
                                                                        v-for="(option, index) in noYesOptions"
                                                                        v-bind:value="index"
                                                                        :selected="index === data.h_i_v_syphillis_test.result_recieved"
                                                                >{{ option }}
                                                                </option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label class="col-md-4 control-label">Partner Referred</label>
                                                    <div class="col-md-8 inputGroupContainer">
                                                        <div class="input-group">
                                                        <span class="input-group-addon" style="max-width: 100%;"><i
                                                                class="glyphicon glyphicon-list"></i></span>
                                                            <select class="selectpicker form-control"
                                                                    v-model="data.h_i_v_syphillis_test.partner_referred">
                                                                <option
                                                                        v-for="(option, index) in noYesOptions"
                                                                        v-bind:value="index"
                                                                        :selected="index === data.h_i_v_syphillis_test.partner_referred"
                                                                >{{ option }}
                                                                </option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                            </fieldset>
                                        </td>
                                    </table>
                                    <table class="table">
                                        <td class="col-md-6 form-horizontal" colspan="1">
                                            <fieldset>
                                                <div class="form-group">
                                                    <label class="col-md-4 control-label">Syphillis Tested</label>
                                                    <div class="col-md-8 inputGroupContainer">
                                                        <div class="input-group">
                                                        <span class="input-group-addon" style="max-width: 100%;"><i
                                                                class="glyphicon glyphicon-list"></i></span>
                                                            <select class="selectpicker form-control"
                                                                    v-model="data.h_i_v_syphillis_test.syphillis_tested">
                                                                <option
                                                                        v-for="(option, index) in positiveNegatveOptions"
                                                                        v-bind:value="index"
                                                                        :selected="index === data.h_i_v_syphillis_test.syphillis_tested"
                                                                >{{ option }}
                                                                </option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label class="col-md-4 control-label">Syphillis Status</label>
                                                    <div class="col-md-8 inputGroupContainer">
                                                        <div class="input-group">
                                                        <span class="input-group-addon" style="max-width: 100%;"><i
                                                                class="glyphicon glyphicon-list"></i></span>
                                                            <select class="selectpicker form-control"
                                                                    v-model="data.h_i_v_syphillis_test.syphillis_status">
                                                                <option
                                                                        v-for="(option, index) in noYesOptions"
                                                                        v-bind:value="index"
                                                                        :selected="index === data.h_i_v_syphillis_test.syphillis_status"
                                                                >{{ option }}
                                                                </option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                            </fieldset>
                                        </td>
                                        <td class="col-md-6 form-horizontal" colspan="1">
                                            <fieldset>
                                                <div class="form-group">
                                                    <label class="col-md-4 control-label">Syphillis Treated</label>
                                                    <div class="col-md-8 inputGroupContainer">
                                                        <div class="input-group">
                                                        <span class="input-group-addon" style="max-width: 100%;"><i
                                                                class="glyphicon glyphicon-list"></i></span>
                                                            <select class="selectpicker form-control"
                                                                    v-model="data.h_i_v_syphillis_test.syphillis_treated">
                                                                <option
                                                                        v-for="(option, index) in noYesOptions"
                                                                        v-bind:value="index"
                                                                        :selected="index === data.h_i_v_syphillis_test.syphillis_treated"
                                                                >{{ option }}
                                                                </option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                            </fieldset>
                                        </td>
                                    </table> 
                                    <table class="table">
                                        <td class="form-horizontal">
                                            <div class="form-group">
                                                <label class="col-md-2 control-label">ART Started Date</label>
                                                <div class="col-md-8 inputGroupContainer">
                                                    <div class="input-group"><span class="input-group-addon"><i
                                                            class="glyphicon glyphicon-calendar"></i></span>
                                                        <input id="delivery_date" name="" placeholder=""
                                                                class="form-control"
                                                                required="true" v-model="data.h_i_v_syphillis_test.art_started_date"
                                                                value="" type="text">
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                    </table>
                                </div>
                                <div class="pull-right">
                                    <button class="btn btn-primary"
                                            @click.prevent="updateDelivery(data.delivery)">
                                        Update
                                    </button>
                                    <button class="btn btn-danger" @click.prevent="deleteDelivery(data.delivery)">Delete
                                    </button>
                                </div>
                            </div>
                        </form>
                        <hr class="anc-break">
                    </div>

                    <div v-else>
                        <p class="text-center">No Record Found</p>
                    </div>               
                </div>
                 <!-- End HIV/Syphillis Test -->
            </div>
        </div>
    </div>

</template>

<script>
    import axios from 'axios'

    export default {
        data() {
            return {
                data: [],
                bloodGroupOptions: [
                    'Select Blood Group', 'A +ve', 'A -ve', 'B +ve', 'B -ve', 'O +ve', 'O -ve', 'AB +ve', 'AB -ve'
                ],
                casteOptions: [
                    'दलित', 'जनजाति', 'मधेसी', 'मुस्लिम', 'ब्राह्मण / क्षेत्री', 'अन्य'
                ],
                babyPresentationOptions: [
                    'None', 'Cephalic', 'Breech', 'Shoulder'
                ],
                swellingOptions: [
                    'None', 'Legs', 'Face', 'Hand', 'Both', 'Face and Legs', 'Hand and Legs', 'All', 'Hand and Face'
                ],
                serviceForOptions: [
                    'Regular ANC', 'First ANC', 'Second ANC', 'Third ANC', 'Forth ANC'
                ],
                deliveryPlaceOptions: [
                    'Home', 'Health-Organization', 'Transit'
                ],
                deliveryTypeOptions: [
                    'Vacuum/Forcep', 'Normal', 'C/S'
                ],
                noYesOptions: [
                    'No', 'Yes'
                ],
                positiveNegatveOptions : [
                    'Negative', 'Positive', 'Unknown'
                ],
                deliveryComplicationChecked: [],
                districts: [],
                municipalities: [],
            }
        },
        created() {
            this.fetch()
        },
        mounted() {
            this.fetchDistricts()
            this.fetchMunicipalities()
            this.updateRecords()
        },
        methods: {
            fetch() {
                let url = window.location.protocol + '/data/api/women/' + this.getUrlVars()['q'];
                axios.get(url)
                    .then((response) => {
                        this.data = response.data.record;
                    })
                    .catch((error) => {
                        console.error(error)
                    })
                    .finally(() => {
                    })
            },
            fetchDistricts() {
                let url = window.location.protocol + '/api/district';
                axios.get(url)
                    .then((response) => {
                        this.districts = response.data;
                    })
                    .catch((error) => {
                        console.error(error)
                    })
                    .finally(() => {
                    })
            },
            fetchMunicipalities() {
                let url = window.location.protocol + '/api/municipality';
                axios.get(url)
                    .then((response) => {
                        this.municipalities = response.data;
                    })
                    .catch((error) => {
                        console.error(error)
                    })
                    .finally(() => {
                    })
            },
            updateGeneralInformationFormValues(data) {
                axios.put('/data/api/women/' + data.token, data)
                    .then((response) => {
                        if (response.status === 200) {
                            this.$swal({
                                title: data.name + '\'s record updated successfully',
                                type: 'success',
                                toast: true,
                                position: 'top-end',
                                showConfirmButton: false,
                                timer: 3000
                            })
                        } else {
                            this.$swal({
                                title: 'Oops. Something went wrong. Please try again later.',
                                type: 'error',
                                toast: true,
                                position: 'top-end',
                                showConfirmButton: false,
                                timer: 3000
                            })
                        }
                    })
            },
            updateAnc(data) {
                axios.put('/data/api/women/anc/' + data.token, data)
                    .then((response) => {
                        if (response.status === 200) {
                            this.$swal({
                                title: 'ANC record updated successfully',
                                type: 'success',
                                toast: true,
                                position: 'top-end',
                                showConfirmButton: false,
                                timer: 3000
                            })
                        } else {
                            this.$swal({
                                title: 'Oops. Something went wrong. Please try again later.',
                                type: 'error',
                                toast: true,
                                position: 'top-end',
                                showConfirmButton: false,
                                timer: 3000
                            })
                        }
                    })
            },
            deleteWoman(data) {
                this.$swal({
                    title: 'Are you sure?',
                    text: "Delete " + data.name + "'s records. You won't be able to revert this!",
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, delete it!'
                }).then((willDelete) => {
                    if (willDelete.value) {
                        axios.delete('/woman/' + data.token)
                            .then(response => {
                                window.location = '/admin/woman';
                            })
                    }
                }).catch(() => {
                    this.$swal("Error!", "Failed to delete!", "error");
                })
            },
            deleteAnc(data) {
                this.$swal({
                    title: 'Are you sure?',
                    text: "Delete this records. You won't be able to revert this!",
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, delete it!'
                }).then((willDelete) => {
                    if (willDelete.value) {
                        axios.delete('data/api/women/anc/' + data.id)
                            .then(response => {
                                this.data.ancs.splice(this.data.ancs.indexOf(data), 1);
                            })
                    }
                }).catch(() => {
                    this.$swal("Error!", "Failed to delete!", "error");
                })
            },
            getUrlVars() {
                var vars = {};
                var parts = window.location.href.replace(/[?&]+([^=&]+)=([^&]*)/gi, function (m, key, value) {
                    vars[key] = value;
                });
                return vars;
            },
            updateRecords() {
                console.log(this.data);
            },
            updateDelivery(data) {
                axios.put('/data/api/women/delivery/' + data.token, data)
                    .then((response) => {
                        if (response.status === 200) {
                            this.$swal({
                                title: 'Delivery record updated successfully',
                                type: 'success',
                                toast: true,
                                position: 'top-end',
                                showConfirmButton: false,
                                timer: 3000
                            })
                        } else {
                            this.$swal({
                                title: 'Oops. Something went wrong. Please try again later.',
                                type: 'error',
                                toast: true,
                                position: 'top-end',
                                showConfirmButton: false,
                                timer: 3000
                            })
                        }
                    })
            },
            deleteDelivery(data) {
                    this.$swal({
                        title: 'Are you sure?',
                        text: "Delete this records. You won't be able to revert this!",
                        type: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Yes, delete it!'
                    }).then((willDelete) => {
                        if (willDelete.value) {
                            axios.delete('data/api/women/delivery/' + data.id)
                                .then(response => {
                                    // this.data.delivery.splice(this.data.delivery.indexOf(data), 1);
                                    delete this.data.delivery;
                                    delete this.data.baby_details;
                                })
                        }
                    }).catch(() => {
                        this.$swal("Error!", "Failed to delete!", "error");
                    })
            },
            updateBabyDetail(data) {
                console.log(data);
            },
            deleteBabyDetail(data) {
                console.log(data);
            },
            updateLabTest(data){
                console.log(data);
            },
            deleteLabTest(data){
                console.log(data);
            }
        }
    }
</script>

<style scoped>
    .fullname {
        text-transform: capitalize;
    }

    .anc-break {
        height: 2px;
        border: none;
        background-color: #e7e7e7;
    }
</style>