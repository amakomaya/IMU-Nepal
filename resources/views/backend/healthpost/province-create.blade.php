@extends('backend.healthpost.province-form')

    @section('action',route('healthpost.store'))

    @section('methodField', "")

    @section ('name',old('name'))

    @section ('token',old('token'))

    @php($province_id= old('province_id'))

    @php($district_id= old('district_id'))

    @php($municipality_id= old('municipality_id'))

    @php($ward_no= old('ward_no'))

    @section ('org_code',old('org_code'))

    @section ('phone',old('phone'))

    @section ('address',old('address'))

    @section ('hmis_uid',old('hmis_uid'))

    @section ('longitude',old('longitude'))

    @section ('lattitude',old('lattitude'))

    @section ('no_of_beds',old('no_of_beds'))
    @section ('no_of_ventilators',old('no_of_ventilators'))
    @section ('no_of_icu',old('no_of_icu'))

    @php($hospital_type= old('hospital_type'))
    @php($status= old('status'))

    @section ('username',old('username'))

    @section ('email',old('email'))

    @section ('password',old('password'))

    @section ('re_password',old('re_password'))