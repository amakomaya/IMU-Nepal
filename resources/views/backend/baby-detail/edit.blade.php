@extends('backend.baby-detail.form')

    @section('action',route('child.update', $data->id))

    @section('methodField',method_field('PUT'))

    @section ('token',!!old('token')? old('token') : $data->token)

    @section ('delivery_token',!!old('delivery_token')? old('delivery_token') : $data->delivery_token)

    @php($gender = !!old('gender')? old('gender') : $data->gender)

    @section ('weight',!!old('weight')? old('weight') : $data->weight)

    @php($premature_birth = !!old('premature_birth')? old('premature_birth') : $data->premature_birth)

    @php($baby_alive = !!old('baby_alive')? old('baby_alive') : $data->baby_alive)

    @php($baby_status = !!old('baby_status')? old('baby_status') : $data->baby_status)

    @section ('advice',!!old('advice')? old('advice') : $data->advice)

    @php($hp_code = !!old('hp_code')? old('hp_code') : $data->hp_code)

    @section ('date_of_birth_reg',!!old('date_of_birth_reg')? old('date_of_birth_reg') : $data->date_of_birth_reg)

    @section ('birth_certificate_reg_no',!!old('birth_certificate_reg_no')? old('birth_certificate_reg_no') : $data->birth_certificate_reg_no)

    @section ('family_record_form_no',!!old('family_record_form_no')? old('family_record_form_no') : $data->family_record_form_no)

    @section ('baby_name',!!old('baby_name')? old('baby_name') : $data->baby_name)

    @section ('child_information_by',!!old('child_information_by')? old('child_information_by') : $data->child_information_by)

    @section ('grand_father_name',!!old('grand_father_name')? old('grand_father_name') : $data->grand_father_name)

    @section ('grand_mother_name',!!old('grand_mother_name')? old('grand_mother_name') : $data->grand_mother_name)

    @section ('father_citizenship_no',!!old('father_citizenship_no')? old('father_citizenship_no') : $data->father_citizenship_no)

    @section ('mother_citizenship_no',!!old('mother_citizenship_no')? old('mother_citizenship_no') : $data->mother_citizenship_no)

    @section ('local_registrar_fullname',!!old('local_registrar_fullname')? old('local_registrar_fullname') : $data->local_registrar_fullname)

    @php($status = !!old('status')? old('status') : $data->status)