@extends('baby-detail.form')

    @section('action',route('baby-detail.store'))

    @section('methodField', "")

    @section ('token',old('token'))

    @section ('delivery_token',old('delivery_token'))

    @php($gender= old('gender')) 

    @section ('weight',old('weight'))

    @php($premature_birth= old('premature_birth')) 

    @php($baby_alive= old('baby_alive')) 

    @php($baby_status= old('baby_status')) 

    @section ('advice',old('advice'))

    @php($hp_code= old('hp_code')) 

    @section ('birth_certificate_reg_no',old('birth_certificate_reg_no'))

    @section ('family_record_form_no',old('family_record_form_no'))

    @section ('baby_name',old('baby_name'))

    @section ('child_information_by',old('child_information_by'))

    @section ('grand_father_name',old('grand_father_name'))

    @section ('grand_mother_name',old('grand_mother_name'))

    @section ('father_citizenship_no',old('father_citizenship_no'))

    @section ('mother_citizenship_no',old('mother_citizenship_no'))

    @section ('local_registrar_fullname',old('local_registrar_fullname'))

    @php($status= old('status')) 