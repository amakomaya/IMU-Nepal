@extends('backend.ward.form')

    @section('action',route('ward.store'))

    @section('methodField', "")

    @section ('ward_no',old('ward_no'))

    @section ('token',old('token'))

    @section ('phone',old('phone'))

    @php($province_id= old('province_id')) 

    @php($district_id= old('district_id')) 

    @php($municipality_id= old('municipality_id'))

    @section ('office_address',old('office_address'))

    @section ('office_longitude',old('office_longitude'))

    @section ('office_lattitude',old('office_lattitude'))

    @php($status= old('status')) 

    @section ('username',old('username'))

    @section ('email',old('email'))

    @section ('password',old('password'))

    @section ('re_password',old('re_password'))