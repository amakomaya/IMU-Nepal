@extends('backend.healthpost.form')

    @section('action',route('healthpost.update', $data->id))

    @section('methodField',method_field('PUT'))

    @section ('name',!!old('name')? old('name') : $data->name)

    @section ('token',!!old('token')? old('token') : $data->token)

    @php($province_id = !!old('province_id')? old('province_id') : $data->province_id)

    @php($district_id = !!old('district_id')? old('district_id') : $data->district_id)

    @php($municipality_id = !!old('municipality_id')? old('municipality_id') : $data->municipality_id)

    @php($ward_no = !!old('ward_no')? old('ward_no') : $data->ward_no)

    @section ('phone',!!old('phone')? old('phone') : $data->phone)

    @section ('address',!!old('address')? old('address') : $data->address)

    @section ('hmis_uid',!!old('hmis_uid')? old('hmis_uid') : $data->hmis_uid)

    @section ('longitude',!!old('longitude')? old('longitude') : $data->longitude)

    @section ('lattitude',!!old('lattitude')? old('lattitude') : $data->lattitude)

    @section ('no_of_beds',!!old('no_of_beds')? old('no_of_beds') : $data->no_of_beds)
    @section ('no_of_ventilators',!!old('no_of_ventilators')? old('no_of_ventilators') : $data->no_of_ventilators)
    @section ('no_of_icu',!!old('no_of_icu')? old('no_of_icu') : $data->no_of_icu)
    @section ('lattitude',!!old('lattitude')? old('lattitude') : $data->lattitude)

    @php($hospital_type = !!old('hospital_type')? old('hospital_type') : $data->hospital_type)

    @php($status = !!old('status')? old('status') : $data->status)

    @section ('email',!!old('email')? old('email') : $user->email)