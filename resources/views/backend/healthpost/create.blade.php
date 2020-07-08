@extends('backend.healthpost.form')

    @section('action',route('healthpost.store'))

    @section('methodField', "")

    @section ('name',old('name'))

    @section ('token',old('token'))

    @php($province_id= old('province_id')) 

    @php($district_id= old('district_id')) 

    @php($municipality_id= old('municipality_id')) 

    @php($ward_no= old('ward_no')) 

    @section ('hp_code',old('hp_code'))

    @section ('phone',old('phone'))

    @section ('address',old('address'))

    @section ('hmis_uid',old('hmis_uid'))

    @section ('longitude',old('longitude'))

    @section ('lattitude',old('lattitude'))

    @php($status= old('status')) 

    @section ('username',old('username'))

    @section ('email',old('email'))

    @section ('password',old('password'))

    @section ('re_password',old('re_password'))