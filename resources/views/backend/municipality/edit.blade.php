@extends('backend.municipality.form')

    @section('action',route('municipality.update', $data->id))

    @section('methodField',method_field('PUT'))

    @section ('token',!!old('token')? old('token') : $data->token)

    @section ('responsible_person',!!old('responsible_person')? old('responsible_person') : $data->responsible_person)

    @section ('phone',!!old('phone')? old('phone') : $data->phone)

    @php($province_id = !!old('province_id')? old('province_id') : $data->province_id)

    @php($district_id = !!old('district_id')? old('district_id') : $data->district_id)

    @php($municipality_id = !!old('municipality_id')? old('municipality_id') : $data->municipality_id)

    @section ('office_address',!!old('office_address')? old('office_address') : $data->office_address)

    @section ('office_longitude',!!old('office_longitude')? old('office_longitude') : $data->office_longitude)

    @section ('office_lattitude',!!old('office_lattitude')? old('office_lattitude') : $data->office_lattitude)

    @php($status = !!old('status')? old('status') : $data->status)

    @section ('email',!!old('email')? old('email') : $user->email)