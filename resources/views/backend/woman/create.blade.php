@extends('backend.woman.form')

    @section('action',route('woman.store'))

    @section('methodField', "")

    @section ('name',old('name'))

    @section ('mool_darta_no',old('mool_darta_no'))

    @section ('sewa_darta_no',old('sewa_darta_no'))

    @section ('orc_darta_no',old('orc_darta_no'))

    @section ('phone',old('phone'))

    @section ('height',old('height'))

    @section ('age',old('age'))

    @section ('lmp_date_en',old('lmp_date_en'))

    @section ('blood_group',old('blood_group'))

    @php($province_id= old('province_id')) 

    @php($district_id= old('district_id')) 

    @php($municipality_id= old('municipality_id')) 

    @section ('tole',old('tole'))

    @section ('ward',old('ward'))

    @php($caste= old('caste')) 

    @section ('husband_name',old('husband_name'))

    @php($anc_status= old('anc_status')) 

    @php($delivery_status= old('delivery_status')) 

    @php($pnc_status= old('pnc_status')) 

    @php($labtest_status= old('labtest_status')) 

    @php($registered_device= old('registered_device')) 

    @section ('created_by',old('created_by'))

    @section ('longitude',old('longitude'))

    @section ('latitude',old('latitude'))

    @php($status= old('status'))

    @section ('username',old('username'))

    @section ('email',old('email'))

    @section ('password',old('password'))

    @section ('re_password',old('re_password'))