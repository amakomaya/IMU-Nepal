@extends('backend.fchv.form')

    @section('action',route('fchv.store'))

    @section('methodField', "")

    @section ('token',old('token'))

    @section ('name',old('name'))

    @section ('post',old('post'))

    @php($province_id= old('province_id')) 

    @php($district_id= old('district_id')) 

    @php($municipality_id= old('municipality_id')) 

    @php($ward= old('ward')) 

    @php($hp_code= old('hp_code'))  

    @section ('image',old('image'))

    @section ('phone',old('phone'))

    @section ('tole',old('tole'))

    @section ('registered_device',old('registered_device'))

    @section ('role',old('role'))

    @section ('longitude',old('longitude'))

    @section ('latitude',old('latitude'))

    @php($status= old('status')) 

    @section ('username',old('username'))

    @section ('email',old('email'))

    @section ('password',old('password'))

    @section ('re_password',old('re_password'))