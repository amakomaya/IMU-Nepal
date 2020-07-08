@extends('backend.out-reach-clinic.form')

    @section('action',route('out-reach-clinic.update', $data->id))

    @section('methodField',method_field('PUT'))

    @section ('name',!!old('name')? old('name') : $data->name)

    @php($province_id = !!old('province_id')? old('province_id') : $data->province_id)

    @php($district_id = !!old('district_id')? old('district_id') : $data->district_id)

    @php($municipality_id = !!old('municipality_id')? old('municipality_id') : $data->municipality_id)

    @section ('ward_no',!!old('ward_no')? old('ward_no') : $data->ward_no)

    @section ('hp_code',!!old('hp_code')? old('hp_code') : $data->hp_code)

    @section ('address',!!old('address')? old('address') : $data->address)

    @section ('phone',!!old('phone')? old('phone') : $data->phone)

    @section ('longitude',!!old('longitude')? old('longitude') : $data->longitude)

    @section ('lattitude',!!old('lattitude')? old('lattitude') : $data->lattitude)

    @php($status = !!old('status')? old('status') : $data->status)